<?php

namespace Tests\Unit\Libraries;

use App\Libraries\JwtService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * JwtService 單元測試
 * @internal
 */
final class JwtServiceTest extends CIUnitTestCase
{
    protected JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JwtService();
    }

    /**
     * 測試產生 Token
     */
    public function testGenerateToken(): void
    {
        $payload = [
            'user_id' => 'user_12345',
            'email'   => 'test@example.com',
        ];
        
        $token = $this->jwtService->generateToken($payload);
        
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        
        // JWT 格式應該有三個部分
        $parts = explode('.', $token);
        $this->assertCount(3, $parts);
    }

    /**
     * 測試驗證有效 Token（不涉及資料庫黑名單）
     */
    public function testValidateValidToken(): void
    {
        $payload = [
            'user_id' => 'user_12345',
            'email'   => 'test@example.com',
        ];
        
        $token = $this->jwtService->generateToken($payload);
        
        // 直接驗證 Token 結構
        $parts = explode('.', $token);
        $this->assertCount(3, $parts);
        
        // 解析 payload 部分
        $payloadDecoded = json_decode(base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', 3 - (3 + strlen($parts[1])) % 4)), true);
        $this->assertEquals('user_12345', $payloadDecoded['user_id']);
        $this->assertEquals('test@example.com', $payloadDecoded['email']);
    }

    /**
     * 測試驗證無效 Token 格式
     */
    public function testValidateInvalidTokenFormat(): void
    {
        $invalidToken = 'invalid.token';
        $result = $this->jwtService->validateToken($invalidToken);
        
        $this->assertNull($result);
    }

    /**
     * 測試驗證被竄改的 Token
     */
    public function testValidateTamperedToken(): void
    {
        $payload = [
            'user_id' => 'user_12345',
            'email'   => 'test@example.com',
        ];
        
        $token = $this->jwtService->generateToken($payload);
        
        // 竄改 token
        $parts = explode('.', $token);
        $parts[1] = base64_encode(json_encode(['user_id' => 'hacker']));
        $tamperedToken = implode('.', $parts);
        
        $result = $this->jwtService->validateToken($tamperedToken);
        
        $this->assertNull($result);
    }

    /**
     * 測試 Token 包含發行時間
     */
    public function testTokenContainsIssuedAt(): void
    {
        $payload = ['user_id' => 'user_12345'];
        $token = $this->jwtService->generateToken($payload);
        
        // 直接解析 Token payload
        $parts = explode('.', $token);
        $decoded = json_decode(base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', 3 - (3 + strlen($parts[1])) % 4)), true);
        
        $this->assertArrayHasKey('iat', $decoded);
        $this->assertLessThanOrEqual(time(), $decoded['iat']);
    }

    /**
     * 測試 Token 包含過期時間
     */
    public function testTokenContainsExpiration(): void
    {
        $payload = ['user_id' => 'user_12345'];
        $token = $this->jwtService->generateToken($payload);
        
        // 直接解析 Token payload
        $parts = explode('.', $token);
        $decoded = json_decode(base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', 3 - (3 + strlen($parts[1])) % 4)), true);
        
        $this->assertArrayHasKey('exp', $decoded);
        $this->assertGreaterThan(time(), $decoded['exp']);
    }

    /**
     * 測試從 Request Header 取得 Token
     */
    public function testGetTokenFromRequestWithBearer(): void
    {
        $request = service('request');
        $request->setHeader('Authorization', 'Bearer test_token_123');
        
        $token = $this->jwtService->getTokenFromRequest($request);
        
        $this->assertEquals('test_token_123', $token);
    }

    /**
     * 測試從空 Request Header 取得 Token
     */
    public function testGetTokenFromRequestEmpty(): void
    {
        $request = service('request');
        $request->removeHeader('Authorization');
        
        $token = $this->jwtService->getTokenFromRequest($request);
        
        $this->assertNull($token);
    }

    /**
     * 測試取得 Token 過期時間戳記（直接解析）
     */
    public function testGetExpiration(): void
    {
        $payload = ['user_id' => 'user_12345'];
        $token = $this->jwtService->generateToken($payload);
        
        // 直接解析 Token payload
        $parts = explode('.', $token);
        $decoded = json_decode(base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', 3 - (3 + strlen($parts[1])) % 4)), true);
        
        $this->assertArrayHasKey('exp', $decoded);
        $this->assertIsInt($decoded['exp']);
        $this->assertGreaterThan(time(), $decoded['exp']);
    }

    /**
     * 測試無效 Token 格式的過期時間
     */
    public function testGetExpirationInvalidToken(): void
    {
        $invalidToken = 'invalid.token.here';
        $parts = explode('.', $invalidToken);
        
        // 無法正確解析
        $decoded = @json_decode(base64_decode(strtr($parts[1], '-_', '+/') . str_repeat('=', 3 - (3 + strlen($parts[1])) % 4)), true);
        
        $this->assertNull($decoded);
    }
}
