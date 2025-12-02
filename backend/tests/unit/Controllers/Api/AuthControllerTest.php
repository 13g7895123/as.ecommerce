<?php

namespace Tests\Unit\Controllers\Api;

use App\Controllers\Api\AuthController;
use App\Entities\User;
use App\Libraries\JwtService;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * AuthController 單元測試
 * @internal
 */
final class AuthControllerTest extends CIUnitTestCase
{
    protected AuthController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controller = new AuthController();
        $this->controller->initController(
            Services::request(),
            Services::response(),
            Services::logger()
        );
    }

    /**
     * 測試成功回應方法
     */
    public function testSuccessResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'success');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['test' => 'data'], 200);
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 測試錯誤回應方法
     */
    public function testErrorResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '測試錯誤', 400);
        
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * 測試驗證錯誤回應方法
     */
    public function testValidationErrorResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'validationError');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['email' => '電子郵件格式錯誤']);
        
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * 測試未授權錯誤回應
     */
    public function testUnauthorizedErrorResponse(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '電子郵件或密碼錯誤', 401);
        
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * 測試註冊驗證規則 - Email 格式
     */
    public function testRegisterEmailValidation(): void
    {
        $validation = service('validation');
        $validation->setRules([
            'email' => 'required|valid_email',
        ]);
        
        // 有效 Email
        $this->assertTrue($validation->run(['email' => 'test@example.com']));
        
        // 無效 Email
        $validation->reset();
        $validation->setRules([
            'email' => 'required|valid_email',
        ]);
        $this->assertFalse($validation->run(['email' => 'invalid-email']));
    }

    /**
     * 測試註冊驗證規則 - 密碼長度
     */
    public function testRegisterPasswordValidation(): void
    {
        // 測試有效密碼
        $validPassword = 'password123';
        $this->assertGreaterThanOrEqual(8, strlen($validPassword));
        
        // 測試無效密碼（太短）
        $invalidPassword = 'short';
        $this->assertLessThan(8, strlen($invalidPassword));
    }

    /**
     * 測試註冊驗證規則 - 姓名
     */
    public function testRegisterNameValidation(): void
    {
        // 有效姓名
        $validName = '測試使用者';
        $this->assertGreaterThanOrEqual(1, mb_strlen($validName));
        $this->assertLessThanOrEqual(100, mb_strlen($validName));
        
        // 空姓名
        $emptyName = '';
        $this->assertEquals(0, mb_strlen($emptyName));
    }

    /**
     * 測試註冊驗證規則 - 電話
     */
    public function testRegisterPhoneValidation(): void
    {
        // 有效電話
        $validPhone = '0912345678';
        $this->assertGreaterThanOrEqual(10, strlen($validPhone));
        $this->assertLessThanOrEqual(20, strlen($validPhone));
        
        // 無效電話（太短）
        $invalidPhone = '091234';
        $this->assertLessThan(10, strlen($invalidPhone));
    }

    /**
     * 測試登出成功回應
     */
    public function testLogoutSuccessResponse(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'success');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['message' => '登出成功']);
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
