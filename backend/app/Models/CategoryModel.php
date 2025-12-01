<?php

namespace App\Models;

use App\Entities\Category;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = Category::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'name',
        'slug',
        'description',
        'icon',
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
        'name' => 'required|min_length[1]|max_length[100]',
        'slug' => 'required|alpha_dash|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => '分類名稱為必填欄位',
            'min_length' => '分類名稱至少需要 1 個字元',
            'max_length' => '分類名稱不可超過 100 個字元',
        ],
        'slug' => [
            'required'   => 'Slug 為必填欄位',
            'alpha_dash' => 'Slug 只能包含字母、數字、底線和連字號',
            'is_unique'  => '此 Slug 已被使用',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * 產生唯一 ID
     */
    public function generateId(): string
    {
        return 'cat_' . bin2hex(random_bytes(6));
    }

    /**
     * 透過 Slug 查詢分類
     */
    public function findBySlug(string $slug): ?Category
    {
        return $this->where('slug', $slug)->first();
    }
}
