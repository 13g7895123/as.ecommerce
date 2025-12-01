<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Order extends Entity
{
    protected $datamap = [];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'id'              => 'string',
        'user_id'         => 'string',
        'order_number'    => 'string',
        'subtotal'        => 'float',
        'shipping'        => 'float',
        'discount'        => 'float',
        'total'           => 'float',
        'status'          => 'string',
        'payment_method'  => 'string',
        'recipient_name'  => 'string',
        'recipient_phone' => 'string',
        'city'            => 'string',
        'district'        => 'string',
        'address'         => 'string',
        'postal_code'     => 'string',
        'tracking_number' => 'string',
    ];

    /**
     * 訂單商品項目（關聯資料）
     */
    protected array $orderItems = [];

    public function setOrderItems(array $items): self
    {
        $this->orderItems = $items;
        return $this;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * 取得運送資訊
     */
    public function getShippingInfo(): array
    {
        return [
            'recipientName'  => $this->recipient_name,
            'recipientPhone' => $this->recipient_phone,
            'city'           => $this->city,
            'district'       => $this->district,
            'address'        => $this->address,
            'postalCode'     => $this->postal_code,
        ];
    }

    /**
     * 轉換為 API 列表回應格式
     */
    public function toListResponse(): array
    {
        return [
            'id'          => $this->id,
            'orderNumber' => $this->order_number,
            'total'       => (float) $this->total,
            'status'      => $this->status,
            'createdAt'   => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * 轉換為 API 詳情回應格式
     */
    public function toDetailResponse(): array
    {
        return [
            'id'             => $this->id,
            'userId'         => $this->user_id,
            'orderNumber'    => $this->order_number,
            'items'          => array_map(fn($item) => $item->toApiResponse(), $this->orderItems),
            'subtotal'       => (float) $this->subtotal,
            'shipping'       => (float) $this->shipping,
            'discount'       => (float) $this->discount,
            'total'          => (float) $this->total,
            'status'         => $this->status,
            'paymentMethod'  => $this->payment_method,
            'shippingInfo'   => $this->getShippingInfo(),
            'trackingNumber' => $this->tracking_number,
            'createdAt'      => $this->created_at?->toIso8601String(),
            'updatedAt'      => $this->updated_at?->toIso8601String(),
        ];
    }
}
