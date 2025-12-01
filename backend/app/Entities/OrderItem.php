<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OrderItem extends Entity
{
    protected $datamap = [];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'id'                => 'integer',
        'order_id'          => 'string',
        'product_id'        => 'string',
        'product_name'      => 'string',
        'product_thumbnail' => 'string',
        'price'             => 'float',
        'quantity'          => 'integer',
    ];

    /**
     * 轉換為 API 回應格式
     */
    public function toApiResponse(): array
    {
        return [
            'id'        => $this->product_id,
            'name'      => $this->product_name,
            'price'     => (float) $this->price,
            'quantity'  => (int) $this->quantity,
            'thumbnail' => $this->product_thumbnail,
        ];
    }
}
