<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTokenBlacklistTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'token' => [
                'type' => 'TEXT',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('token_blacklist');
    }

    public function down()
    {
        $this->forge->dropTable('token_blacklist');
    }
}
