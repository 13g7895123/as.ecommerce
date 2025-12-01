<?php

namespace App\Models;

use App\Entities\Order;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = Order::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'user_id',
        'order_number',
        'subtotal',
        'shipping',
        'discount',
        'total',
        'status',
        'payment_method',
        'recipient_name',
        'recipient_phone',
        'city',
        'district',
        'address',
        'postal_code',
        'tracking_number',
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
        'user_id'         => 'required',
        'payment_method'  => 'required|in_list[credit_card,atm,cod]',
        'recipient_name'  => 'required|min_length[1]|max_length[100]',
        'recipient_phone' => 'required|min_length[10]|max_length[20]',
        'city'            => 'required|max_length[50]',
        'district'        => 'required|max_length[50]',
        'address'         => 'required|max_length[255]',
        'postal_code'     => 'required|max_length[10]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => '使用者 ID 為必填',
        ],
        'payment_method' => [
            'required' => '付款方式為必填',
            'in_list'  => '付款方式無效',
        ],
        'recipient_name' => [
            'required'   => '收件人姓名為必填',
            'min_length' => '收件人姓名至少需要 1 個字元',
            'max_length' => '收件人姓名不可超過 100 個字元',
        ],
        'recipient_phone' => [
            'required'   => '收件人電話為必填',
            'min_length' => '收件人電話至少需要 10 個字元',
            'max_length' => '收件人電話不可超過 20 個字元',
        ],
        'city' => [
            'required'   => '城市為必填',
            'max_length' => '城市不可超過 50 個字元',
        ],
        'district' => [
            'required'   => '區域為必填',
            'max_length' => '區域不可超過 50 個字元',
        ],
        'address' => [
            'required'   => '地址為必填',
            'max_length' => '地址不可超過 255 個字元',
        ],
        'postal_code' => [
            'required'   => '郵遞區號為必填',
            'max_length' => '郵遞區號不可超過 10 個字元',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * 產生唯一 ID
     */
    public function generateId(): string
    {
        return 'ord_' . date('Ymd') . bin2hex(random_bytes(4));
    }

    /**
     * 產生訂單編號
     */
    public function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $count = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults() + 1;
        return sprintf('ORD-%s-%04d', $date, $count);
    }

    /**
     * 取得使用者訂單列表
     */
    public function getOrdersByUser(string $userId, array $params = []): array
    {
        $page   = (int) ($params['page'] ?? 1);
        $limit  = (int) ($params['limit'] ?? 10);
        $status = $params['status'] ?? null;

        $builder = $this->where('user_id', $userId);

        if ($status) {
            $builder->where('status', $status);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $limit;
        $orders = $builder->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();

        return [
            'orders' => $orders,
            'total'  => $total,
            'page'   => $page,
            'limit'  => $limit,
        ];
    }

    /**
     * 取得使用者特定訂單
     */
    public function getOrderByUser(string $orderId, string $userId): ?Order
    {
        return $this->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * 更新訂單狀態
     */
    public function updateStatus(string $orderId, string $status): bool
    {
        return $this->update($orderId, ['status' => $status]);
    }
}
