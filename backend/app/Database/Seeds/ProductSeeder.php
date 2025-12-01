<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'                => 'prod_001',
                'name'              => '無線藍牙耳機',
                'description'       => '高音質無線藍牙耳機，支援主動降噪功能，續航力長達 20 小時。輕巧便攜設計，適合通勤和運動使用。',
                'short_description' => '輕巧便攜',
                'price'             => 1299,
                'original_price'    => 1599,
                'images'            => json_encode(['https://picsum.photos/400/400?random=1', 'https://picsum.photos/400/400?random=2']),
                'thumbnail'         => 'https://picsum.photos/200/200?random=1',
                'category_id'       => 'cat_elec',
                'stock'             => 100,
                'sku'               => 'HP-001',
                'tags'              => json_encode(['熱銷', '新品']),
                'specifications'    => json_encode(['藍牙版本' => '5.0', '續航力' => '20小時', '重量' => '250g']),
                'featured'          => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 'prod_002',
                'name'              => '智慧手錶',
                'description'       => '多功能智慧手錶，支援心率監測、睡眠追蹤、GPS 定位。防水設計，適合各種運動場景。',
                'short_description' => '運動健康夥伴',
                'price'             => 2499,
                'original_price'    => 2999,
                'images'            => json_encode(['https://picsum.photos/400/400?random=3', 'https://picsum.photos/400/400?random=4']),
                'thumbnail'         => 'https://picsum.photos/200/200?random=3',
                'category_id'       => 'cat_elec',
                'stock'             => 50,
                'sku'               => 'SW-001',
                'tags'              => json_encode(['熱銷']),
                'specifications'    => json_encode(['螢幕' => '1.4吋 AMOLED', '電池' => '300mAh', '防水等級' => 'IP68']),
                'featured'          => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 'prod_003',
                'name'              => '經典白T恤',
                'description'       => '100% 純棉材質，舒適透氣。經典百搭款式，適合各種場合穿著。',
                'short_description' => '百搭必備',
                'price'             => 399,
                'original_price'    => null,
                'images'            => json_encode(['https://picsum.photos/400/400?random=5']),
                'thumbnail'         => 'https://picsum.photos/200/200?random=5',
                'category_id'       => 'cat_cloth',
                'stock'             => 200,
                'sku'               => 'TS-001',
                'tags'              => json_encode(['新品']),
                'specifications'    => json_encode(['材質' => '100% 純棉', '尺寸' => 'S/M/L/XL']),
                'featured'          => 0,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 'prod_004',
                'name'              => '香氛蠟燭',
                'description'       => '天然大豆蠟製成，薰衣草香氣，燃燒時間約 40 小時。營造放鬆舒適的居家氛圍。',
                'short_description' => '居家香氛',
                'price'             => 299,
                'original_price'    => 350,
                'images'            => json_encode(['https://picsum.photos/400/400?random=6']),
                'thumbnail'         => 'https://picsum.photos/200/200?random=6',
                'category_id'       => 'cat_home',
                'stock'             => 80,
                'sku'               => 'CD-001',
                'tags'              => json_encode([]),
                'specifications'    => json_encode(['重量' => '200g', '燃燒時間' => '40小時']),
                'featured'          => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'id'                => 'prod_005',
                'name'              => '有機綠茶',
                'description'       => '台灣高山有機綠茶，清香回甘。每包含 20 個茶包，方便沖泡。',
                'short_description' => '健康茶飲',
                'price'             => 199,
                'original_price'    => null,
                'images'            => json_encode(['https://picsum.photos/400/400?random=7']),
                'thumbnail'         => 'https://picsum.photos/200/200?random=7',
                'category_id'       => 'cat_food',
                'stock'             => 150,
                'sku'               => 'GT-001',
                'tags'              => json_encode(['有機']),
                'specifications'    => json_encode(['產地' => '台灣', '包裝' => '20入']),
                'featured'          => 0,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
