<?php

namespace Tests\Unit\Entities;

use App\Entities\User;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * User Entity 單元測試
 * @internal
 */
final class UserEntityTest extends CIUnitTestCase
{
    /**
     * 測試建立 User Entity
     */
    public function testCreateUserEntity(): void
    {
        $user = new User([
            'id'    => 'user_12345',
            'email' => 'test@example.com',
            'name'  => '測試使用者',
            'phone' => '0912345678',
        ]);
        
        $this->assertEquals('user_12345', $user->id);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('測試使用者', $user->name);
        $this->assertEquals('0912345678', $user->phone);
    }

    /**
     * 測試設定密碼
     */
    public function testSetPassword(): void
    {
        $user = new User();
        $user->setPassword('testPassword123');
        
        // 密碼應該被雜湊
        $this->assertArrayHasKey('password_hash', $user->toRawArray());
        $this->assertNotEquals('testPassword123', $user->toRawArray()['password_hash']);
    }

    /**
     * 測試驗證正確密碼
     */
    public function testVerifyCorrectPassword(): void
    {
        $user = new User([
            'password_hash' => password_hash('testPassword123', PASSWORD_DEFAULT),
        ]);
        
        $this->assertTrue($user->verifyPassword('testPassword123'));
    }

    /**
     * 測試驗證錯誤密碼
     */
    public function testVerifyWrongPassword(): void
    {
        $user = new User([
            'password_hash' => password_hash('testPassword123', PASSWORD_DEFAULT),
        ]);
        
        $this->assertFalse($user->verifyPassword('wrongPassword'));
    }

    /**
     * 測試轉換為 API 回應格式
     */
    public function testToApiResponse(): void
    {
        $user = new User([
            'id'      => 'user_12345',
            'email'   => 'test@example.com',
            'name'    => '測試使用者',
            'phone'   => '0912345678',
            'address' => '測試地址',
        ]);
        
        $response = $user->toApiResponse();
        
        $this->assertIsArray($response);
        $this->assertEquals('user_12345', $response['id']);
        $this->assertEquals('test@example.com', $response['email']);
        $this->assertEquals('測試使用者', $response['name']);
        $this->assertEquals('0912345678', $response['phone']);
        $this->assertEquals('測試地址', $response['address']);
    }

    /**
     * 測試 API 回應不包含密碼
     */
    public function testApiResponseExcludesPassword(): void
    {
        $user = new User([
            'id'            => 'user_12345',
            'email'         => 'test@example.com',
            'name'          => '測試使用者',
            'phone'         => '0912345678',
            'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
        ]);
        
        $response = $user->toApiResponse();
        
        $this->assertArrayNotHasKey('password_hash', $response);
        $this->assertArrayNotHasKey('password', $response);
    }

    /**
     * 測試空地址為 null
     */
    public function testEmptyAddressIsNull(): void
    {
        $user = new User([
            'id'    => 'user_12345',
            'email' => 'test@example.com',
            'name'  => '測試使用者',
            'phone' => '0912345678',
        ]);
        
        $response = $user->toApiResponse();
        
        $this->assertNull($response['address']);
    }
}
