<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 依序執行各個 Seeder
        $this->call('CategorySeeder');
        $this->call('ProductSeeder');
    }
}
