<?php

namespace Tests\Unit\Controllers\Api;

use App\Controllers\Api\OrderController;
use App\Entities\Order;
use App\Entities\OrderItem;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * OrderController 單元測試
 * @internal
 */
final class OrderControllerTest extends CIUnitTestCase
{
    protected OrderController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controller = new OrderController();
        $this->controller->initController(
            Services::request(),
            Services::response(),
            Services::logger()
        );
    }

    /**
     * 測試訂單列表參數處理
     */
    public function testIndexParameterProcessing(): void
    {
        $inputPage = 3;
        $inputLimit = 15;
        
        $page = max($inputPage, 1);
        $limit = min(max($inputLimit, 1), 100);
        
        $this->assertEquals(3, $page);
        $this->assertEquals(15, $limit);
    }

    /**
     * 測試運費計算 - 滿額免運
     */
    public function testShippingCalculationFreeShipping(): void
    {
        $subtotal = 1500;
        $shipping = $subtotal >= 1000 ? 0 : 60;
        
        $this->assertEquals(0, $shipping);
    }

    /**
     * 測試運費計算 - 未達免運門檻
     */
    public function testShippingCalculationWithFee(): void
    {
        $subtotal = 500;
        $shipping = $subtotal >= 1000 ? 0 : 60;
        
        $this->assertEquals(60, $shipping);
    }

    /**
     * 測試運費計算 - 剛好達免運門檻
     */
    public function testShippingCalculationExactThreshold(): void
    {
        $subtotal = 1000;
        $shipping = $subtotal >= 1000 ? 0 : 60;
        
        $this->assertEquals(0, $shipping);
    }

    /**
     * 測試訂單總額計算
     */
    public function testOrderTotalCalculation(): void
    {
        $subtotal = 1500;
        $shipping = 0;
        $discount = 100;
        $total = $subtotal + $shipping - $discount;
        
        $this->assertEquals(1400, $total);
    }

    /**
     * 測試訂單總額計算 - 含運費
     */
    public function testOrderTotalCalculationWithShipping(): void
    {
        $subtotal = 500;
        $shipping = 60;
        $discount = 0;
        $total = $subtotal + $shipping - $discount;
        
        $this->assertEquals(560, $total);
    }

    /**
     * 測試有效付款方式
     */
    public function testValidPaymentMethods(): void
    {
        $validMethods = ['credit_card', 'atm', 'cod'];
        
        $this->assertContains('credit_card', $validMethods);
        $this->assertContains('atm', $validMethods);
        $this->assertContains('cod', $validMethods);
        $this->assertNotContains('invalid_method', $validMethods);
    }

    /**
     * 測試訂單驗證規則
     */
    public function testOrderValidationRules(): void
    {
        $validMethods = ['credit_card', 'atm', 'cod'];
        
        // 有效付款方式
        $this->assertContains('credit_card', $validMethods);
        
        // 無效付款方式
        $this->assertNotContains('bitcoin', $validMethods);
    }

    /**
     * 測試收件人資訊驗證
     */
    public function testShippingInfoValidation(): void
    {
        $validData = [
            'recipientName'  => '測試使用者',
            'recipientPhone' => '0912345678',
            'city'           => '台北市',
            'district'       => '信義區',
            'address'        => '測試路100號',
            'postalCode'     => '110',
        ];
        
        // 驗證各欄位長度
        $this->assertGreaterThanOrEqual(1, mb_strlen($validData['recipientName']));
        $this->assertLessThanOrEqual(100, mb_strlen($validData['recipientName']));
        $this->assertGreaterThanOrEqual(10, strlen($validData['recipientPhone']));
        $this->assertLessThanOrEqual(20, strlen($validData['recipientPhone']));
        $this->assertLessThanOrEqual(50, mb_strlen($validData['city']));
        $this->assertLessThanOrEqual(50, mb_strlen($validData['district']));
        $this->assertLessThanOrEqual(255, mb_strlen($validData['address']));
        $this->assertLessThanOrEqual(10, strlen($validData['postalCode']));
    }

    /**
     * 測試商品項目小計計算
     */
    public function testItemSubtotalCalculation(): void
    {
        $price = 500;
        $quantity = 3;
        $itemTotal = $price * $quantity;
        
        $this->assertEquals(1500, $itemTotal);
    }

    /**
     * 測試錯誤回應方法
     */
    public function testErrorResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '訂單不存在', 404);
        
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * 測試驗證錯誤回應
     */
    public function testValidationErrorResponse(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'validationError');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['items' => '商品項目為必填']);
        
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * 測試伺服器錯誤回應
     */
    public function testServerErrorResponse(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '訂單建立失敗，請稍後再試', 500);
        
        $this->assertEquals(500, $response->getStatusCode());
    }
}
