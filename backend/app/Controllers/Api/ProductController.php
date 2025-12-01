<?php

namespace App\Controllers\Api;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProductController extends ApiController
{
    protected ProductModel $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * GET /api/products
     * 取得商品列表
     */
    public function index(): ResponseInterface
    {
        $params = [
            'page'       => (int) ($this->request->getGet('page') ?? 1),
            'limit'      => (int) ($this->request->getGet('limit') ?? 20),
            'categoryId' => $this->request->getGet('categoryId'),
            'sort'       => $this->request->getGet('sort') ?? 'newest',
            'search'     => $this->request->getGet('search'),
        ];

        // 限制每頁數量
        $params['limit'] = min(max($params['limit'], 1), 100);
        $params['page'] = max($params['page'], 1);

        $result = $this->productModel->getProducts($params);

        return $this->success([
            'products' => array_map(fn($p) => $p->toApiResponse(), $result['products']),
            'total'    => $result['total'],
            'page'     => $result['page'],
            'limit'    => $result['limit'],
            'hasMore'  => $result['hasMore'],
        ]);
    }

    /**
     * GET /api/products/:id
     * 取得商品詳情
     */
    public function show(string $id): ResponseInterface
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->error('商品不存在', 404);
        }

        return $this->success($product->toDetailResponse());
    }

    /**
     * GET /api/categories
     * 取得分類列表
     */
    public function categories(): ResponseInterface
    {
        $categories = $this->categoryModel->findAll();

        return $this->success(
            array_map(fn($c) => $c->toApiResponse(), $categories)
        );
    }
}
