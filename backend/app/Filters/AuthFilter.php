<?php

namespace App\Filters;

use App\Libraries\JwtService;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * 在 Controller 執行前驗證 JWT Token
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtService = new JwtService();
        $token = $jwtService->getTokenFromRequest($request);

        if (!$token) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'statusCode'    => 401,
                    'statusMessage' => '未提供認證 Token',
                    'data'          => null,
                ]);
        }

        $payload = $jwtService->validateToken($token);

        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'statusCode'    => 401,
                    'statusMessage' => 'Token 無效或已過期',
                    'data'          => null,
                ]);
        }

        // 驗證使用者是否存在
        $userModel = new UserModel();
        $user = $userModel->find($payload['user_id']);

        if (!$user) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'statusCode'    => 401,
                    'statusMessage' => '使用者不存在',
                    'data'          => null,
                ]);
        }

        // 將使用者資訊存入 request，供 Controller 使用
        $request->user = $user;
        $request->token = $token;

        return $request;
    }

    /**
     * Controller 執行後（不需處理）
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 不需處理
    }
}
