<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenBlacklistModel extends Model
{
    protected $table            = 'token_blacklist';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'token',
        'expires_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * 將 Token 加入黑名單
     */
    public function addToBlacklist(string $token, string $expiresAt): bool
    {
        return $this->insert([
            'token'      => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * 檢查 Token 是否在黑名單中
     */
    public function isBlacklisted(string $token): bool
    {
        return $this->where('token', $token)->countAllResults() > 0;
    }

    /**
     * 清理過期的 Token
     */
    public function cleanExpired(): int
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
