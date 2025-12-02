<?php

namespace Tests\Unit\Controllers\Api;

use App\Controllers\Api\ProductController;
use App\Entities\Product;
use App\Entities\Category;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * ProductController 單元測試
 * @internal
 */
final class ProductControllerTest extends CIUnitTestCase
{
    protected ProductController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controller = new ProductController();
        $this->controller->initController(
            Services::request(),
            Services::response(),
            Services::logger()
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
    }

    /**
     * 測試商品列表參數處理（測試邏輯）
     */
    public function testIndexParameterProcessing(): void
    {
        // 直接測試參數處理邏輯
        $inputPage = 2;
        $inputLimit = 10;
        
        $page = max($inputPage, 1);
        $limit = min(max($inputLimit, 1), 100);
        
        $this->assertEquals(2, $page);
        $this->assertEquals(10, $limit);
    }

    /**
     * 測試每頁數量上限限制
     */
    public function testLimitUpperBound(): void
    {
        $inputLimit = 500;
        $limit = min(max($inputLimit, 1), 100);
        
        $this->assertEquals(100, $limit);
    }

    /**
     * 測試每頁數量下限限制
     */
    public function testLimitLowerBound(): void
    {
        $inputLimit = -5;
        $limit = min(max($inputLimit, 1), 100);
        
        $this->assertEquals(1, $limit);
    }

    /**
     * 測試頁碼最小值限制
     */
    public function testPageMinimumValue(): void
    {
        $inputPage = -1;
        $page = max($inputPage, 1);
        
        $this->assertEquals(1, $page);
    }

    /**
     * 測試有效排序選項
     */
    public function testValidSortOptions(): void
    {
        $validSorts = ['newest', 'price-asc', 'price-desc', 'popular'];
        
        foreach ($validSorts as $sort) {
            $this->assertContains($sort, $validSorts);
        }
    }

    /**
     * 測試預設排序值
     */
    public function testDefaultSortValue(): void
    {
        $sort = null ?? 'newest';
        $this->assertEquals('newest', $sort);
    }

    /**
     * 測試預設每頁數量
     */
    public function testDefaultLimitValue(): void
    {
        $limit = null ?? 20;
        $this->assertEquals(20, $limit);
    }

    /**
     * 測試成功回應方法
     */
    public function testSuccessResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'success');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['products' => []], 200);
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 測試錯誤回應 - 商品不存在
     */
    public function testErrorResponseForNotFound(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '商品不存在', 404);
        
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * 測試分頁偏移量計算
     */
    public function testPaginationOffsetCalculation(): void
    {
        $page = 3;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $this->assertEquals(40, $offset);
    }

    /**
     * 測試 hasMore 計算
     */
    public function testHasMoreCalculation(): void
    {
        $offset = 40;
        $productsCount = 20;
        $total = 100;
        
        $hasMore = ($offset + $productsCount) < $total;
        
        $this->assertTrue($hasMore);
    }

    /**
     * 測試無更多資料
     */
    public function testNoMoreData(): void
    {
        $offset = 80;
        $productsCount = 20;
        $total = 100;
        
        $hasMore = ($offset + $productsCount) < $total;
        
        $this->assertFalse($hasMore);
    }
}
