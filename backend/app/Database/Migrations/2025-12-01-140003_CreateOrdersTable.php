<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'shipping' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'shipped', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['credit_card', 'atm', 'cod'],
            ],
            'recipient_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'recipient_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'postal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'tracking_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey('order_number');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
