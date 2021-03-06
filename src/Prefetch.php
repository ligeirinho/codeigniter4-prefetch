<?php namespace Tatter\Prefetch;

use CodeIgniter\Config\BaseConfig;

class Prefetch
{
	/**
	 * The configuration instance.
	 *
	 * @var \Tatter\Prefetch\Config\Prefetch
	 */
	protected $config;
	
	/**
	 * Warehouse of prefetched items
	 *
	 * @var array
	 */
	protected $store = [];
	
	/**
	 * Current size in bytes of the store
	 *
	 * @var array
	 */
	protected $size = 0;
	
	/**
	 * Array of error messages assigned on failure
	 *
	 * @var array
	 */
	protected $errors = [];
	
	// Initiate library
	public function __construct(BaseConfig $config)
	{		
		// Save the configuration
		$this->config = $config;
	}
	
	// Reset this instance
	public function reset()
	{
		$this->store  = [];
		$this->errors = [];
		$this->size   = 0;
		
		return $this;
	}
	
	// Return any error messages
	public function getErrors(): array
	{
		return $this->errors;
	}
	
	// Return current store size
	public function getSize(): int
	{
		return $this->size;
	}
	
	// Set heuristics flag on-the-fly
	public function setHeuristics(bool $bool)
	{
		$this->config->heuristics = $bool;
		return $this;
	}
	
	// Set training flag on-the-fly
	public function setTraining(bool $bool)
	{
		$this->config->training = $bool;
		return $this;
	}
	
	/**
	 * Checks the store for items from the requested table and keys
	 *
	 * @param string  $table The table the items are a part of
	 * @param array   $keys  The primary IDs for the items
	 * @param &array  $items A container for located items
	 *
	 * @return array  The remaining keys to check that were not found in the store
	 */
	public function fetch(string $table, array $keys, array &$items): array
	{
		if (empty($this->store[$table]))
			return $keys;

		// Check each key for an item or confirmed absence
		$remainder = [];
		foreach ($keys as $key)
		{
			if (! isset($this->store[$table][$key]))
			{
				$remainder[] = $key;
			}
			
			// Check for an item since it can be boolean false
			if ($this->store[$table][$key])
			{
				$items[] = $this->store[$table][$key];
			}
		}
		
		return $remainder;
	}
	
	/**
	 * Checks for and loads items related to a table
	 *
	 * @param string  $table   The table of reference
	 * @param array   $models  The models to query for relatives
	 *
	 */
	public function fetchRelatives(string $table, array $models)
	{
		if (empty($this->store[$table]))
			return;
		if (empty($models))
			return;

		// Get stored keys for this table
		$keys = array_keys($this->store[$table]);
		
		// Handle models one at a time
		foreach ($models as $model)
		{
			// Convert class strings to their models
			if (is_string($model))
			{
				$model = new $model();
			}
			
			// Check for the join table
			// WIP - table is protected, need new approach. goodnight
			$tables = [$table, $model->table];
		}
	}
	
	/**
	 * Collects items coming back from the database and adds them to the store
	 *
	 * @param string  $table      The table the items come from
	 * @param string  $primaryKey The primary key for this table
	 * @param array   $items      Raw array of items back from the database
	 * @param array   $ids        Array of primary keys that were requested (usually from find())
	 */
	public function collect(string $table, string $primaryKey, array $items, $ids = [])
	{
		$before = memory_get_usage(); // bytes
	    
	    if (! isset($this->store[$table]))
	    	$this->store[$table] = [];

		// Reindex items by their primary key
	    foreach ($items as $item)
	    {
	    	$this->store[$table][$item->{$primaryKey}] = $item;
	    }
	    
	    // If we know which keys were requested then set any not returned to false
	    foreach ($ids as $id)
	    {
	    	if (! isset($this->store[$table][$id]))
	    	{
	    		$this->store[$table][$id] = false;
	    	}
	    }
	    
	    // Update memory usage
    	$after = memory_get_usage();
	    $this->size += $after - $before;
	}
}
