<?php

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = User::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'email',
        'password_hash',
        'name',
        'phone',
        'address',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'name'     => 'required|min_length[1]|max_length[100]',
        'phone'    => 'required|min_length[10]|max_length[20]',
    ];

    protected $validationMessages = [
        'email' => [
            'required'    => '電子郵件為必填欄位',
            'valid_email' => '請輸入有效的電子郵件格式',
            'is_unique'   => '此電子郵件已被註冊',
        ],
        'name' => [
            'required'   => '姓名為必填欄位',
            'min_length' => '姓名至少需要 1 個字元',
            'max_length' => '姓名不可超過 100 個字元',
        ],
        'phone' => [
            'required'   => '電話為必填欄位',
            'min_length' => '電話至少需要 10 個字元',
            'max_length' => '電話不可超過 20 個字元',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * 產生唯一 ID
     */
    public function generateId(): string
    {
        return 'user_' . bin2hex(random_bytes(8));
    }

    /**
     * 透過 Email 查詢使用者
     */
    public function findByEmail(string $email): ?User
    {
        return $this->where('email', $email)->first();
    }
}
