<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * API 基礎控制器
 * 提供統一的 JSON 回應格式
 */
class ApiController extends BaseController
{
    /**
     * 成功回應
     */
    protected function success($data = null, int $statusCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }

    /**
     * 錯誤回應
     */
    protected function error(string $message, int $statusCode = 400, $data = null): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'statusCode'    => $statusCode,
                'statusMessage' => $message,
                'data'          => $data,
            ]);
    }

    /**
     * 驗證錯誤回應
     */
    protected function validationError(array $errors): ResponseInterface
    {
        return $this->error('驗證失敗', 422, $errors);
    }

    /**
     * 取得目前登入的使用者
     */
    protected function getCurrentUser()
    {
        return $this->request->user ?? null;
    }

    /**
     * 取得目前的 Token
     */
    protected function getCurrentToken(): ?string
    {
        return $this->request->token ?? null;
    }

    /**
     * 取得 JSON Request Body
     */
    protected function getJsonInput(): array
    {
        return $this->request->getJSON(true) ?? [];
    }
}
