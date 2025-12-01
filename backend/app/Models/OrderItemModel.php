<?php

namespace App\Models;

use App\Entities\OrderItem;
use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = OrderItem::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_id',
        'product_name',
        'product_thumbnail',
        'price',
        'quantity',
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
        'order_id'     => 'required',
        'product_id'   => 'required',
        'product_name' => 'required',
        'price'        => 'required|numeric|greater_than_equal_to[0]',
        'quantity'     => 'required|integer|greater_than[0]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * 取得訂單的所有商品項目
     */
    public function getByOrderId(string $orderId): array
    {
        return $this->where('order_id', $orderId)->findAll();
    }

    /**
     * 批次建立訂單項目
     */
    public function createBatch(array $items): bool
    {
        return $this->insertBatch($items);
    }
}
