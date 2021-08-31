<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdminEquipment extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'adminequip_id' => [
				'type' => 'INT',
				'constraint' => '5',
				'unsigned' => true,
				'auto_increment' => true,
			],
			'company' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
			],
			'area' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
			],
			'unit' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
			],
			'equipment' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
			],
		]);

		$this->forge->addKey('adminequip_id', true);
		$this->forge->createTable('tblm_adminEquip');
	}

	public function down()
	{
		//
	}
}
