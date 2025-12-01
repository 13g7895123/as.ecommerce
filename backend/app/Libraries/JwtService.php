<?php

namespace App\Libraries;

use App\Models\TokenBlacklistModel;
use CodeIgniter\HTTP\IncomingRequest;
use Exception;

/**
 * JWT 認證服務
 * 簡易實作，生產環境建議使用 firebase/php-jwt 套件
 */
class JwtService
{
    private string $secretKey;
    private int $expiration;
    private string $algorithm = 'HS256';

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET', 'your-secret-key-change-in-production');
        $this->expiration = (int) env('JWT_EXPIRATION', 86400); // 預設 24 小時
    }

    /**
     * 產生 JWT Token
     */
    public function generateToken(array $payload): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => $this->algorithm,
        ];

        $payload['iat'] = time();
        $payload['exp'] = time() + $this->expiration;

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secretKey, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    /**
     * 驗證並解析 JWT Token
     */
    public function validateToken(string $token): ?array
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        // 驗證簽名
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secretKey, true);
        $expectedSignature = $this->base64UrlEncode($signature);

        if (!hash_equals($expectedSignature, $signatureEncoded)) {
            return null;
        }

        // 解析 payload
        $payload = json_decode($this->base64UrlDecode($payloadEncoded), true);

        if (!$payload) {
            return null;
        }

        // 檢查過期時間
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        // 檢查黑名單
        $blacklistModel = new TokenBlacklistModel();
        if ($blacklistModel->isBlacklisted($token)) {
            return null;
        }

        return $payload;
    }

    /**
     * 從 Request 中取得 Token
     */
    public function getTokenFromRequest(IncomingRequest $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (empty($header)) {
            return null;
        }

        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * 將 Token 加入黑名單（登出用）
     */
    public function invalidateToken(string $token): bool
    {
        $payload = $this->validateToken($token);
        
        if (!$payload) {
            return false;
        }

        $blacklistModel = new TokenBlacklistModel();
        $expiresAt = date('Y-m-d H:i:s', $payload['exp']);
        
        return $blacklistModel->addToBlacklist($token, $expiresAt);
    }

    /**
     * 取得 Token 過期時間戳記
     */
    public function getExpiration(string $token): ?int
    {
        $payload = $this->validateToken($token);
        return $payload['exp'] ?? null;
    }

    /**
     * Base64 URL 安全編碼
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL 安全解碼
     */
    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}
