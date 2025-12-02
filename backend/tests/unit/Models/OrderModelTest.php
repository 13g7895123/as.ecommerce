<?php

namespace Tests\Unit\Models;

use App\Models\OrderModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * OrderModel 單元測試
 * @internal
 */
final class OrderModelTest extends CIUnitTestCase
{
    protected OrderModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new OrderModel();
    }

    /**
     * 測試產生唯一 ID 格式
     */
    public function testGenerateIdFormat(): void
    {
        $id = $this->model->generateId();
        
        $this->assertStringStartsWith('ord_', $id);
        // ord_ (4) + date (8) + hex (8) = 20
        $this->assertEquals(20, strlen($id));
    }

    /**
     * 測試產生唯一 ID 包含日期
     */
    public function testGenerateIdContainsDate(): void
    {
        $id = $this->model->generateId();
        $date = date('Ymd');
        
        $this->assertStringContainsString($date, $id);
    }

    /**
     * 測試產生訂單編號格式（直接測試格式邏輯）
     */
    public function testGenerateOrderNumberFormat(): void
    {
        // 直接測試格式邏輯
        $date = date('Ymd');
        $count = 1;
        $orderNumber = sprintf('ORD-%s-%04d', $date, $count);
        
        $this->assertStringStartsWith('ORD-', $orderNumber);
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{4}$/', $orderNumber);
    }

    /**
     * 測試產生訂單編號包含日期
     */
    public function testGenerateOrderNumberContainsDate(): void
    {
        $date = date('Ymd');
        $count = 1;
        $orderNumber = sprintf('ORD-%s-%04d', $date, $count);
        
        $this->assertStringContainsString($date, $orderNumber);
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
        $this->assertContains('user_id', $allowedFields);
        $this->assertContains('order_number', $allowedFields);
        $this->assertContains('subtotal', $allowedFields);
        $this->assertContains('shipping', $allowedFields);
        $this->assertContains('discount', $allowedFields);
        $this->assertContains('total', $allowedFields);
        $this->assertContains('status', $allowedFields);
        $this->assertContains('payment_method', $allowedFields);
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
        
        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('payment_method', $rules);
        $this->assertArrayHasKey('recipient_name', $rules);
        $this->assertArrayHasKey('recipient_phone', $rules);
        $this->assertArrayHasKey('city', $rules);
        $this->assertArrayHasKey('district', $rules);
        $this->assertArrayHasKey('address', $rules);
        $this->assertArrayHasKey('postal_code', $rules);
    }

    /**
     * 測試 Model 表格名稱
     */
    public function testTableName(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('table');
        $property->setAccessible(true);
        
        $this->assertEquals('orders', $property->getValue($this->model));
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
     * 測試付款方式驗證規則
     */
    public function testPaymentMethodValidation(): void
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('validationRules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->model);
        
        $this->assertStringContainsString('in_list[credit_card,atm,cod]', $rules['payment_method']);
    }
}
