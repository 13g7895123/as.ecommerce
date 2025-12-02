<?php

namespace Tests\Unit\Entities;

use App\Entities\Product;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Product Entity 單元測試
 * @internal
 */
final class ProductEntityTest extends CIUnitTestCase
{
    /**
     * 測試建立 Product Entity
     */
    public function testCreateProductEntity(): void
    {
        $product = new Product([
            'id'          => 'prod_123456',
            'name'        => '測試商品',
            'description' => '這是測試商品的描述',
            'price'       => 1000,
            'stock'       => 50,
        ]);
        
        $this->assertEquals('prod_123456', $product->id);
        $this->assertEquals('測試商品', $product->name);
        $this->assertEquals(1000, $product->price);
        $this->assertEquals(50, $product->stock);
    }

    /**
     * 測試價格型別轉換
     */
    public function testPriceCasting(): void
    {
        $product = new Product([
            'price' => '999.99',
        ]);
        
        $this->assertIsFloat($product->price);
        $this->assertEquals(999.99, $product->price);
    }

    /**
     * 測試庫存型別轉換
     */
    public function testStockCasting(): void
    {
        $product = new Product([
            'stock' => '100',
        ]);
        
        $this->assertIsInt($product->stock);
        $this->assertEquals(100, $product->stock);
    }

    /**
     * 測試精選標記型別轉換
     */
    public function testFeaturedCasting(): void
    {
        $product = new Product([
            'featured' => 1,
        ]);
        
        $this->assertIsBool($product->featured);
        $this->assertTrue($product->featured);
    }

    /**
     * 測試圖片陣列型別轉換
     */
    public function testImagesCasting(): void
    {
        $images = ['image1.jpg', 'image2.jpg'];
        $product = new Product();
        $product->images = $images;
        
        $this->assertIsArray($product->images);
        $this->assertCount(2, $product->images);
    }

    /**
     * 測試標籤陣列型別轉換
     */
    public function testTagsCasting(): void
    {
        $tags = ['新品', '熱賣'];
        $product = new Product();
        $product->tags = $tags;
        
        $this->assertIsArray($product->tags);
        $this->assertCount(2, $product->tags);
    }

    /**
     * 測試規格陣列型別轉換
     */
    public function testSpecificationsCasting(): void
    {
        $specs = [
            ['name' => '尺寸', 'value' => 'L'],
            ['name' => '顏色', 'value' => '黑'],
        ];
        $product = new Product();
        $product->specifications = $specs;
        
        $this->assertIsArray($product->specifications);
        $this->assertCount(2, $product->specifications);
    }

    /**
     * 測試轉換為 API 回應格式
     */
    public function testToApiResponse(): void
    {
        $product = new Product([
            'id'                => 'prod_123456',
            'name'              => '測試商品',
            'description'       => '詳細描述',
            'short_description' => '簡短描述',
            'price'             => 1000,
            'original_price'    => 1200,
            'images'            => json_encode(['img1.jpg']),
            'thumbnail'         => 'thumb.jpg',
            'category_id'       => 'cat_001',
            'stock'             => 50,
            'sku'               => 'SKU001',
            'tags'              => json_encode(['新品']),
            'featured'          => 1,
        ]);
        
        $response = $product->toApiResponse();
        
        $this->assertIsArray($response);
        $this->assertEquals('prod_123456', $response['id']);
        $this->assertEquals('測試商品', $response['name']);
        $this->assertEquals(1000.0, $response['price']);
        $this->assertEquals(1200.0, $response['originalPrice']);
        $this->assertEquals(50, $response['stock']);
        $this->assertTrue($response['featured']);
    }

    /**
     * 測試詳細回應格式包含規格
     */
    public function testToDetailResponse(): void
    {
        $specs = [
            ['name' => '尺寸', 'value' => 'L'],
        ];
        $product = new Product([
            'id'    => 'prod_123456',
            'name'  => '測試商品',
            'price' => 1000,
        ]);
        $product->specifications = $specs;
        
        $response = $product->toDetailResponse();
        
        $this->assertArrayHasKey('specifications', $response);
        $this->assertIsArray($response['specifications']);
    }

    /**
     * 測試原價為 null 的情況
     */
    public function testNullOriginalPrice(): void
    {
        $product = new Product([
            'price'          => 1000,
            'original_price' => null,
        ]);
        
        $response = $product->toApiResponse();
        
        $this->assertNull($response['originalPrice']);
    }

    /**
     * 測試空陣列預設值
     */
    public function testEmptyArrayDefaults(): void
    {
        $product = new Product([
            'price' => 1000,
        ]);
        
        $response = $product->toApiResponse();
        
        $this->assertEquals([], $response['images']);
        $this->assertEquals([], $response['tags']);
    }
}
