<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'id'         => 'string',
        'email'      => 'string',
        'name'       => 'string',
        'phone'      => 'string',
        'address'    => 'string',
    ];

    /**
     * 設定密碼（自動雜湊）
     */
    public function setPassword(string $password): self
    {
        $this->attributes['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * 驗證密碼
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['password_hash']);
    }

    /**
     * 轉換為 API 回應格式
     */
    public function toApiResponse(): array
    {
        return [
            'id'        => $this->id,
            'email'     => $this->email,
            'name'      => $this->name,
            'phone'     => $this->phone,
            'address'   => $this->address ?? null,
            'createdAt' => $this->created_at?->toIso8601String(),
            'updatedAt' => $this->updated_at?->toIso8601String(),
        ];
    }
}
