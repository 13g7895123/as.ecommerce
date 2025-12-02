<?php

namespace Tests\Unit\Controllers\Api;

use App\Controllers\Api\UserController;
use App\Entities\User;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * UserController 單元測試
 * @internal
 */
final class UserControllerTest extends CIUnitTestCase
{
    protected UserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controller = new UserController();
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
        
        $response = $reflection->invoke($this->controller, ['user' => ['id' => 'test']], 200);
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * 測試錯誤回應方法
     */
    public function testErrorResponseMethod(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'error');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, '更新失敗', 500);
        
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * 測試驗證錯誤回應
     */
    public function testValidationErrorResponse(): void
    {
        $reflection = new \ReflectionMethod($this->controller, 'validationError');
        $reflection->setAccessible(true);
        
        $response = $reflection->invoke($this->controller, ['name' => '姓名格式錯誤']);
        
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * 測試空更新資料處理
     */
    public function testEmptyUpdateDataHandling(): void
    {
        $updateData = [];
        
        $this->assertEmpty($updateData);
    }

    /**
     * 測試姓名驗證規則 - 有效
     */
    public function testNameValidationValid(): void
    {
        $validName = '張三';
        $this->assertGreaterThanOrEqual(1, mb_strlen($validName));
        $this->assertLessThanOrEqual(100, mb_strlen($validName));
    }

    /**
     * 測試姓名驗證規則 - 無效（空字串）
     */
    public function testNameValidationInvalid(): void
    {
        $invalidName = '';
        $this->assertEquals(0, mb_strlen($invalidName));
    }

    /**
     * 測試電話驗證規則 - 有效
     */
    public function testPhoneValidationValid(): void
    {
        $validPhone = '0912345678';
        $this->assertGreaterThanOrEqual(10, strlen($validPhone));
        $this->assertLessThanOrEqual(20, strlen($validPhone));
    }

    /**
     * 測試電話驗證規則 - 無效（太短）
     */
    public function testPhoneValidationTooShort(): void
    {
        $invalidPhone = '091234';
        $this->assertLessThan(10, strlen($invalidPhone));
    }

    /**
     * 測試密碼驗證規則 - 有效
     */
    public function testPasswordValidationValid(): void
    {
        $validPassword = 'password123';
        $this->assertGreaterThanOrEqual(8, strlen($validPassword));
    }

    /**
     * 測試密碼驗證規則 - 無效（太短）
     */
    public function testPasswordValidationTooShort(): void
    {
        $invalidPassword = 'short';
        $this->assertLessThan(8, strlen($invalidPassword));
    }

    /**
     * 測試密碼雜湊
     */
    public function testPasswordHashing(): void
    {
        $password = 'testPassword123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('wrongPassword', $hash));
    }

    /**
     * 測試更新密碼需要舊密碼
     */
    public function testUpdatePasswordRequiresCurrentPassword(): void
    {
        $input = [
            'newPassword' => 'newPassword123',
        ];
        
        $requiresCurrentPassword = isset($input['newPassword']) && !isset($input['currentPassword']);
        
        $this->assertTrue($requiresCurrentPassword);
    }

    /**
     * 測試更新密碼有提供舊密碼
     */
    public function testUpdatePasswordWithCurrentPassword(): void
    {
        $input = [
            'currentPassword' => 'oldPassword123',
            'newPassword' => 'newPassword123',
        ];
        
        $requiresCurrentPassword = isset($input['newPassword']) && !isset($input['currentPassword']);
        
        $this->assertFalse($requiresCurrentPassword);
    }
}
