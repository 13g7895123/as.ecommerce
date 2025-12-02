<?php

namespace Tests\Unit\Entities;

use App\Entities\Category;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Category Entity 單元測試
 * @internal
 */
final class CategoryEntityTest extends CIUnitTestCase
{
    /**
     * 測試建立 Category Entity
     */
    public function testCreateCategoryEntity(): void
    {
        $category = new Category([
            'id'          => 'cat_001',
            'name'        => '電子產品',
            'slug'        => 'electronics',
            'description' => '各種電子產品',
            'icon'        => 'laptop',
        ]);
        
        $this->assertEquals('cat_001', $category->id);
        $this->assertEquals('電子產品', $category->name);
        $this->assertEquals('electronics', $category->slug);
        $this->assertEquals('各種電子產品', $category->description);
        $this->assertEquals('laptop', $category->icon);
    }

    /**
     * 測試轉換為 API 回應格式
     */
    public function testToApiResponse(): void
    {
        $category = new Category([
            'id'          => 'cat_001',
            'name'        => '電子產品',
            'slug'        => 'electronics',
            'description' => '各種電子產品',
            'icon'        => 'laptop',
        ]);
        
        $response = $category->toApiResponse();
        
        $this->assertIsArray($response);
        $this->assertEquals('cat_001', $response['id']);
        $this->assertEquals('電子產品', $response['name']);
        $this->assertEquals('electronics', $response['slug']);
        $this->assertEquals('各種電子產品', $response['description']);
        $this->assertEquals('laptop', $response['icon']);
    }

    /**
     * 測試回應包含所有必要欄位
     */
    public function testApiResponseContainsAllFields(): void
    {
        $category = new Category([
            'id'   => 'cat_001',
            'name' => '測試分類',
        ]);
        
        $response = $category->toApiResponse();
        
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('icon', $response);
    }
}
