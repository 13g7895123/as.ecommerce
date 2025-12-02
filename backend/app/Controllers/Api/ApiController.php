<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * API 基礎控制器
 * 提供統一的 JSON 回應格式與共用方法
 */
class ApiController extends BaseController
{
    /** @var array 快取的 JSON 輸入資料 */
    private ?array $cachedJsonInput = null;

    /**
     * 成功回應
     * 
     * @param mixed $data 回應資料
     * @param int $statusCode HTTP 狀態碼
     */
    protected function success($data = null, int $statusCode = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }

    /**
     * 錯誤回應
     * 
     * @param string $message 錯誤訊息
     * @param int $statusCode HTTP 狀態碼
     * @param mixed $data 附加資料
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
     * 取得 JSON Request Body（含快取）
     */
    protected function getJsonInput(): array
    {
        if ($this->cachedJsonInput === null) {
            $this->cachedJsonInput = $this->request->getJSON(true) ?? [];
        }
        return $this->cachedJsonInput;
    }

    /**
     * 取得分頁參數
     * 
     * @param int $defaultLimit 預設每頁數量
     * @param int $maxLimit 最大每頁數量
     */
    protected function getPaginationParams(int $defaultLimit = 20, int $maxLimit = 100): array
    {
        $page = max((int) ($this->request->getGet('page') ?? 1), 1);
        $limit = min(max((int) ($this->request->getGet('limit') ?? $defaultLimit), 1), $maxLimit);
        
        return [
            'page'   => $page,
            'limit'  => $limit,
            'offset' => ($page - 1) * $limit,
        ];
    }

    /**
     * 驗證輸入資料
     * 
     * @param array $rules 驗證規則
     * @param array $messages 自訂錯誤訊息
     * @param array|null $data 要驗證的資料，預設為 JSON 輸入
     * @return array|bool 驗證成功回傳 true，失敗回傳錯誤陣列
     */
    protected function validateInput(array $rules, array $messages = [], ?array $data = null)
    {
        $data = $data ?? $this->getJsonInput();
        $validation = service('validation');
        $validation->setRules($rules, $messages);
        
        if (!$validation->run($data)) {
            return $validation->getErrors();
        }
        
        return true;
    }
}
