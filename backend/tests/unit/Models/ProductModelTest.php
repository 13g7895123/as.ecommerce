<?php

namespace Tests\Unit\Models;

use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * ProductModel 單元測試
 * @internal
 */
final class ProductModelTest extends CIUnitTestCase
{
    protected ProductModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new ProductModel();
    }

    /**
     * 測試產生唯一 ID 格式
     */
    public function testGenerateIdFormat(): void
    {
        $id = $this->model->generateId();
        
        $this->assertStringStartsWith('prod_', $id);
        $this->assertEquals(17, strlen($id)); // prod_ (5) + 12 hex chars
    }

    /**
     * 測試產生唯一 ID 不重複
     */
    public function testGenerateIdUniqueness(): void
    {
        $ids = [];
        for ($i = 0; $i < 100; $i++) {
            $ids[] = $this->model->generateId();
        }
        
        $uniqueIds = array_unique($ids);
        $this->assertCount(100, $uniqueIds);
    }

    /**
     * 測試 Model 允許的欄位
     */
    public function testAllowedFields(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $allowedFields = $property->getValue($this->model);
        
        $this->assertContains('id', $allowedFields);
        $this->assertContains('name', $allowedFields);
        $this->assertContains('description', $allowedFields);
        $this->assertContains('price', $allowedFields);
        $this->assertContains('stock', $allowedFields);
        $this->assertContains('images', $allowedFields);
        $this->assertContains('thumbnail', $allowedFields);
        $this->assertContains('category_id', $allowedFields);
    }

    /**
     * 測試 Model 使用時間戳記
     */
    public function testUsesTimestamps(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('useTimestamps');
        $property->setAccessible(true);
        
        $this->assertTrue($property->getValue($this->model));
    }

    /**
     * 測試驗證規則存在
     */
    public function testValidationRulesExist(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('validationRules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->model);
        
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('price', $rules);
        $this->assertArrayHasKey('stock', $rules);
    }

    /**
     * 測試 Model 表格名稱
     */
    public function testTableName(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('table');
        $property->setAccessible(true);
        
        $this->assertEquals('products', $property->getValue($this->model));
    }

    /**
     * 測試主鍵設定
     */
    public function testPrimaryKey(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('primaryKey');
        $property->setAccessible(true);
        
        $this->assertEquals('id', $property->getValue($this->model));
    }

    /**
     * 測試不使用自動遞增
     */
    public function testNoAutoIncrement(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('useAutoIncrement');
        $property->setAccessible(true);
        
        $this->assertFalse($property->getValue($this->model));
    }

    /**
     * 測試分頁偏移量計算
     */
    public function testPaginationOffsetCalculation(): void
    {
        $page = 3;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $this->assertEquals(40, $offset);
    }

    /**
     * 測試 hasMore 邏輯
     */
    public function testHasMoreLogic(): void
    {
        $offset = 40;
        $count = 20;
        $total = 100;
        
        $hasMore = ($offset + $count) < $total;
        
        $this->assertTrue($hasMore);
    }
}
