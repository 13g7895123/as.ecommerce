<?php

namespace Tests\Unit\Entities;

use App\Entities\OrderItem;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * OrderItem Entity 單元測試
 * @internal
 */
final class OrderItemEntityTest extends CIUnitTestCase
{
    /**
     * 測試建立 OrderItem Entity
     */
    public function testCreateOrderItemEntity(): void
    {
        $item = new OrderItem([
            'id'                => 1,
            'order_id'          => 'ord_123',
            'product_id'        => 'prod_001',
            'product_name'      => '測試商品',
            'product_thumbnail' => 'thumb.jpg',
            'price'             => 500,
            'quantity'          => 2,
        ]);
        
        $this->assertEquals(1, $item->id);
        $this->assertEquals('ord_123', $item->order_id);
        $this->assertEquals('prod_001', $item->product_id);
        $this->assertEquals('測試商品', $item->product_name);
        $this->assertEquals(500, $item->price);
        $this->assertEquals(2, $item->quantity);
    }

    /**
     * 測試價格型別轉換
     */
    public function testPriceCasting(): void
    {
        $item = new OrderItem([
            'price' => '500.50',
        ]);
        
        $this->assertIsFloat($item->price);
        $this->assertEquals(500.50, $item->price);
    }

    /**
     * 測試數量型別轉換
     */
    public function testQuantityCasting(): void
    {
        $item = new OrderItem([
            'quantity' => '3',
        ]);
        
        $this->assertIsInt($item->quantity);
        $this->assertEquals(3, $item->quantity);
    }

    /**
     * 測試轉換為 API 回應格式
     */
    public function testToApiResponse(): void
    {
        $item = new OrderItem([
            'product_id'        => 'prod_001',
            'product_name'      => '測試商品',
            'product_thumbnail' => 'thumb.jpg',
            'price'             => 500,
            'quantity'          => 2,
        ]);
        
        $response = $item->toApiResponse();
        
        $this->assertIsArray($response);
        $this->assertEquals('prod_001', $response['id']);
        $this->assertEquals('測試商品', $response['name']);
        $this->assertEquals(500.0, $response['price']);
        $this->assertEquals(2, $response['quantity']);
        $this->assertEquals('thumb.jpg', $response['thumbnail']);
    }

    /**
     * 測試計算項目小計
     */
    public function testCalculateItemTotal(): void
    {
        $item = new OrderItem([
            'price'    => 500,
            'quantity' => 3,
        ]);
        
        $total = $item->price * $item->quantity;
        
        $this->assertEquals(1500, $total);
    }
}
