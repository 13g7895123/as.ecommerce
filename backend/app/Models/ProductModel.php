<?php

namespace App\Models;

use App\Entities\Product;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = Product::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'name',
        'description',
        'short_description',
        'price',
        'original_price',
        'images',
        'thumbnail',
        'category_id',
        'stock',
        'sku',
        'tags',
        'specifications',
        'featured',
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
        'name'  => 'required|min_length[1]|max_length[255]',
        'price' => 'required|numeric|greater_than[0]',
        'stock' => 'permit_empty|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => '商品名稱為必填欄位',
            'min_length' => '商品名稱至少需要 1 個字元',
            'max_length' => '商品名稱不可超過 255 個字元',
        ],
        'price' => [
            'required'     => '商品價格為必填欄位',
            'numeric'      => '商品價格必須為數字',
            'greater_than' => '商品價格必須大於 0',
        ],
        'stock' => [
            'integer'               => '庫存必須為整數',
            'greater_than_equal_to' => '庫存不可為負數',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * 產生唯一 ID
     */
    public function generateId(): string
    {
        return 'prod_' . bin2hex(random_bytes(6));
    }

    /**
     * 取得商品列表（含分頁、搜尋、篩選、排序）
     */
    public function getProducts(array $params = []): array
    {
        $page       = (int) ($params['page'] ?? 1);
        $limit      = (int) ($params['limit'] ?? 20);
        $categoryId = $params['categoryId'] ?? null;
        $sort       = $params['sort'] ?? 'newest';
        $search     = $params['search'] ?? null;

        $builder = $this->builder();

        // 分類篩選
        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }

        // 關鍵字搜尋
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->orLike('short_description', $search)
                ->groupEnd();
        }

        // 取得總數
        $total = $builder->countAllResults(false);

        // 排序
        switch ($sort) {
            case 'price-asc':
                $builder->orderBy('price', 'ASC');
                break;
            case 'price-desc':
                $builder->orderBy('price', 'DESC');
                break;
            case 'popular':
                $builder->orderBy('featured', 'DESC')->orderBy('created_at', 'DESC');
                break;
            case 'newest':
            default:
                $builder->orderBy('created_at', 'DESC');
                break;
        }

        // 分頁
        $offset = ($page - 1) * $limit;
        $products = $builder->limit($limit, $offset)->get()->getResult($this->returnType);

        return [
            'products' => $products,
            'total'    => $total,
            'page'     => $page,
            'limit'    => $limit,
            'hasMore'  => ($offset + count($products)) < $total,
        ];
    }

    /**
     * 透過 SKU 查詢商品
     */
    public function findBySku(string $sku): ?Product
    {
        return $this->where('sku', $sku)->first();
    }

    /**
     * 取得精選商品
     */
    public function getFeatured(int $limit = 10): array
    {
        return $this->where('featured', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * 更新庫存
     */
    public function updateStock(string $productId, int $quantity): bool
    {
        return $this->where('id', $productId)
            ->set('stock', "stock - {$quantity}", false)
            ->update();
    }

    /**
     * 檢查庫存是否足夠
     */
    public function hasStock(string $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        return $product && $product->stock >= $quantity;
    }
}
