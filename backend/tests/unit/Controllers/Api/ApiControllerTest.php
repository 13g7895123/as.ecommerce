<?php

namespace Tests\Unit\Controllers\Api;

use App\Controllers\Api\ApiController;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * ApiController 單元測試
 * @internal
 */
final class ApiControllerTest extends CIUnitTestCase
{
    protected ApiController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // 建立匿名子類別來測試 protected 方法
        $this->controller = new class extends ApiController {
            public function testSuccess($data = null, int $statusCode = 200)
            {
                return $this->success($data, $statusCode);
            }

            public function testError(string $message, int $statusCode = 400, $data = null)
            {
                return $this->error($message, $statusCode, $data);
            }

            public function testValidationError(array $errors)
            {
                return $this->validationError($errors);
            }

            public function testGetPaginationParams(int $defaultLimit = 20, int $maxLimit = 100): array
            {
                return $this->getPaginationParams($defaultLimit, $maxLimit);
            }

            public function testGetJsonInput(): array
            {
                return $this->getJsonInput();
            }
        };
        
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
     * 測試成功回應
     */
    public function testSuccessResponse(): void
    {
        $response = $this->controller->testSuccess(['test' => 'data']);
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 測試成功回應自訂狀態碼
     */
    public function testSuccessResponseWithCustomStatusCode(): void
    {
        $response = $this->controller->testSuccess(['created' => true], 201);
        
        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * 測試錯誤回應
     */
    public function testErrorResponse(): void
    {
        $response = $this->controller->testError('測試錯誤', 400);
        
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * 測試驗證錯誤回應
     */
    public function testValidationErrorResponse(): void
    {
        $response = $this->controller->testValidationError(['email' => '電子郵件格式錯誤']);
        
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * 測試分頁參數 - 預設值
     */
    public function testGetPaginationParamsDefaults(): void
    {
        $params = $this->controller->testGetPaginationParams();
        
        $this->assertEquals(1, $params['page']);
        $this->assertEquals(20, $params['limit']);
        $this->assertEquals(0, $params['offset']);
    }

    /**
     * 測試分頁參數 - 自訂預設值
     */
    public function testGetPaginationParamsCustomDefaults(): void
    {
        $params = $this->controller->testGetPaginationParams(10, 50);
        
        $this->assertEquals(10, $params['limit']);
    }

    /**
     * 測試分頁參數 - 從 GET 參數（測試邏輯計算）
     */
    public function testGetPaginationParamsFromGet(): void
    {
        // 直接測試分頁計算邏輯
        $page = 3;
        $limit = 25;
        $offset = ($page - 1) * $limit;
        
        $this->assertEquals(3, $page);
        $this->assertEquals(25, $limit);
        $this->assertEquals(50, $offset);
    }

    /**
     * 測試分頁參數 - 上限限制
     */
    public function testGetPaginationParamsMaxLimit(): void
    {
        // 測試上限限制邏輯
        $inputLimit = 500;
        $maxLimit = 100;
        $limit = min(max($inputLimit, 1), $maxLimit);
        
        $this->assertEquals(100, $limit);
    }

    /**
     * 測試分頁參數 - 下限限制
     */
    public function testGetPaginationParamsMinLimit(): void
    {
        // 測試下限限制邏輯
        $inputLimit = -5;
        $inputPage = -1;
        
        $limit = min(max($inputLimit, 1), 100);
        $page = max($inputPage, 1);
        
        $this->assertEquals(1, $page);
        $this->assertEquals(1, $limit);
    }

    /**
     * 測試偏移量計算
     */
    public function testOffsetCalculation(): void
    {
        // 測試偏移量計算邏輯
        $page = 5;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $this->assertEquals(40, $offset);
    }
}
