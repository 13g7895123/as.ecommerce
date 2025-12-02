<?php

namespace Tests\Unit\Entities;

use App\Entities\Order;
use App\Entities\OrderItem;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * Order Entity 單元測試
 * @internal
 */
final class OrderEntityTest extends CIUnitTestCase
{
    /**
     * 測試建立 Order Entity
     */
    public function testCreateOrderEntity(): void
    {
        $order = new Order([
            'id'           => 'ord_20231201_abc123',
            'user_id'      => 'user_12345',
            'order_number' => 'ORD-20231201-0001',
            'subtotal'     => 1500,
            'shipping'     => 0,
            'discount'     => 100,
            'total'        => 1400,
            'status'       => 'pending',
        ]);
        
        $this->assertEquals('ord_20231201_abc123', $order->id);
        $this->assertEquals('user_12345', $order->user_id);
        $this->assertEquals('ORD-20231201-0001', $order->order_number);
        $this->assertEquals(1400, $order->total);
    }

    /**
     * 測試金額型別轉換
     */
    public function testAmountCasting(): void
    {
        $order = new Order([
            'subtotal' => '1500.50',
            'shipping' => '60.00',
            'discount' => '100.00',
            'total'    => '1460.50',
        ]);
        
        $this->assertIsFloat($order->subtotal);
        $this->assertIsFloat($order->shipping);
        $this->assertIsFloat($order->discount);
        $this->assertIsFloat($order->total);
    }

    /**
     * 測試設定訂單項目
     */
    public function testSetOrderItems(): void
    {
        $order = new Order();
        $items = [
            new OrderItem(['product_id' => 'prod_001', 'quantity' => 2]),
            new OrderItem(['product_id' => 'prod_002', 'quantity' => 1]),
        ];
        
        $order->setOrderItems($items);
        
        $this->assertCount(2, $order->getOrderItems());
    }

    /**
     * 測試取得運送資訊
     */
    public function testGetShippingInfo(): void
    {
        $order = new Order([
            'recipient_name'  => '收件人姓名',
            'recipient_phone' => '0912345678',
            'city'            => '台北市',
            'district'        => '信義區',
            'address'         => '測試路100號',
            'postal_code'     => '110',
        ]);
        
        $shippingInfo = $order->getShippingInfo();
        
        $this->assertIsArray($shippingInfo);
        $this->assertEquals('收件人姓名', $shippingInfo['recipientName']);
        $this->assertEquals('0912345678', $shippingInfo['recipientPhone']);
        $this->assertEquals('台北市', $shippingInfo['city']);
        $this->assertEquals('信義區', $shippingInfo['district']);
        $this->assertEquals('測試路100號', $shippingInfo['address']);
        $this->assertEquals('110', $shippingInfo['postalCode']);
    }

    /**
     * 測試轉換為列表回應格式
     */
    public function testToListResponse(): void
    {
        $order = new Order([
            'id'           => 'ord_123',
            'order_number' => 'ORD-20231201-0001',
            'total'        => 1400,
            'status'       => 'pending',
        ]);
        
        $response = $order->toListResponse();
        
        $this->assertIsArray($response);
        $this->assertEquals('ord_123', $response['id']);
        $this->assertEquals('ORD-20231201-0001', $response['orderNumber']);
        $this->assertEquals(1400.0, $response['total']);
        $this->assertEquals('pending', $response['status']);
    }

    /**
     * 測試轉換為詳情回應格式
     */
    public function testToDetailResponse(): void
    {
        $order = new Order([
            'id'              => 'ord_123',
            'user_id'         => 'user_12345',
            'order_number'    => 'ORD-20231201-0001',
            'subtotal'        => 1500,
            'shipping'        => 0,
            'discount'        => 100,
            'total'           => 1400,
            'status'          => 'pending',
            'payment_method'  => 'credit_card',
            'recipient_name'  => '測試使用者',
            'recipient_phone' => '0912345678',
            'city'            => '台北市',
            'district'        => '信義區',
            'address'         => '測試路100號',
            'postal_code'     => '110',
        ]);
        
        $order->setOrderItems([]);
        $response = $order->toDetailResponse();
        
        $this->assertIsArray($response);
        $this->assertArrayHasKey('items', $response);
        $this->assertArrayHasKey('shippingInfo', $response);
        $this->assertArrayHasKey('paymentMethod', $response);
        $this->assertEquals('credit_card', $response['paymentMethod']);
    }

    /**
     * 測試訂單狀態值
     */
    public function testOrderStatusValues(): void
    {
        $validStatuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        foreach ($validStatuses as $status) {
            $order = new Order(['status' => $status]);
            $this->assertEquals($status, $order->status);
        }
    }
}
