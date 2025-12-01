<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Category extends Entity
{
    protected $datamap = [];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'id'          => 'string',
        'name'        => 'string',
        'slug'        => 'string',
        'description' => 'string',
        'icon'        => 'string',
    ];

    /**
     * 轉換為 API 回應格式
     */
    public function toApiResponse(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'icon'        => $this->icon,
        ];
    }
}
