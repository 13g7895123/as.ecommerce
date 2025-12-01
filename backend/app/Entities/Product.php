<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Product extends Entity
{
    protected $datamap = [];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'id'                => 'string',
        'name'              => 'string',
        'description'       => 'string',
        'short_description' => 'string',
        'price'             => 'float',
        'original_price'    => '?float',
        'images'            => 'json-array',
        'thumbnail'         => 'string',
        'category_id'       => 'string',
        'stock'             => 'integer',
        'sku'               => 'string',
        'tags'              => 'json-array',
        'specifications'    => 'json-array',
        'featured'          => 'boolean',
    ];

    /**
     * 轉換為 API 列表回應格式
     */
    public function toApiResponse(): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'shortDescription' => $this->short_description,
            'price'            => (float) $this->price,
            'originalPrice'    => $this->original_price ? (float) $this->original_price : null,
            'images'           => $this->images ?? [],
            'thumbnail'        => $this->thumbnail,
            'categoryId'       => $this->category_id,
            'stock'            => (int) $this->stock,
            'sku'              => $this->sku,
            'tags'             => $this->tags ?? [],
            'featured'         => (bool) $this->featured,
            'createdAt'        => $this->created_at?->toIso8601String(),
            'updatedAt'        => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * 轉換為 API 詳情回應格式（含規格）
     */
    public function toDetailResponse(): array
    {
        $response = $this->toApiResponse();
        $response['specifications'] = $this->specifications ?? [];
        return $response;
    }
}
