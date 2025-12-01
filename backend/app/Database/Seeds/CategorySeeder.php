<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'          => 'cat_elec',
                'name'        => '電子產品',
                'slug'        => 'electronics',
                'description' => '各類3C周邊',
                'icon'        => 'device-icon',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 'cat_cloth',
                'name'        => '服飾配件',
                'slug'        => 'clothing',
                'description' => '流行服飾',
                'icon'        => 'shirt-icon',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 'cat_home',
                'name'        => '居家生活',
                'slug'        => 'home',
                'description' => '居家用品',
                'icon'        => 'home-icon',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 'cat_food',
                'name'        => '食品飲料',
                'slug'        => 'food',
                'description' => '美食甜點',
                'icon'        => 'food-icon',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
