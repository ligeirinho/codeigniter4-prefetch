<?php namespace CIModuleTests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestTables extends Migration
{
	public function up()
	{
		// Factories
		$fields = [
			'name'           => ['type' => 'varchar', 'constraint' => 31],
			'uid'            => ['type' => 'varchar', 'constraint' => 31],
			'class'          => ['type' => 'varchar', 'constraint' => 63],
			'icon'           => ['type' => 'varchar', 'constraint' => 31],
			'summary'        => ['type' => 'varchar', 'constraint' => 255],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('name');
		$this->forge->addKey('uid');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('factories');
		
		// Workers
		$fields = [
			'firstname'      => ['type' => 'varchar', 'constraint' => 31],
			'lastname'       => ['type' => 'varchar', 'constraint' => 31],
			'role'           => ['type' => 'varchar', 'constraint' => 63],
			'age'            => ['type' => 'int', 'null' => true, 'unsigned' => true],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('lastname');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('workers');

		// Factories-Workers
		$fields = [
			'factory_id'     => ['type' => 'INT', 'unsigned' => true],
			'worker_id'      => ['type' => 'INT', 'unsigned' => true],
			'created_at'     => ['type' => 'DATETIME', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addUniqueKey(['factory_id', 'worker_id']);
		$this->forge->addUniqueKey(['worker_id', 'factory_id']);
		
		$this->forge->createTable('factories_workers');
		
		// Machines
		$fields = [
			'type'           => ['type' => 'varchar', 'constraint' => 31],
			'serial'         => ['type' => 'varchar', 'constraint' => 31],
			'factory_id'     => ['type' => 'int', 'null' => true, 'unsigned' => true],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addUniqueKey('serial');
		$this->forge->addKey('factory_id');
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('machines');
		
		// Servicers
		$fields = [
			'company'        => ['type' => 'varchar', 'constraint' => 31],
			'created_at'     => ['type' => 'datetime', 'null' => true],
			'updated_at'     => ['type' => 'datetime', 'null' => true],
			'deleted_at'     => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('company');
		$this->forge->addKey(['deleted_at', 'id']);
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('servicers');

		// Machines-Servicers
		$fields = [
			'machine_id'     => ['type' => 'INT', 'unsigned' => true],
			'servicer_id'    => ['type' => 'INT', 'unsigned' => true],
			'created_at'     => ['type' => 'DATETIME', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addUniqueKey(['machine_id', 'servicer_id']);
		$this->forge->addUniqueKey(['servicer_id', 'machine_id']);
		
		$this->forge->createTable('machines_servicers');
	}

	public function down()
	{
		$this->forge->dropTable('factories');
		$this->forge->dropTable('workers');
		$this->forge->dropTable('factories_workers');
		
		$this->forge->dropTable('machines');
		$this->forge->dropTable('servicers');
		$this->forge->dropTable('machines_servicers');
	}
}
