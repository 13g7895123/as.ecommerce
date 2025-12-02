<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends ApiController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /api/user/profile
     * 取得個人資料
     */
    public function profile(): ResponseInterface
    {
        $user = $this->getCurrentUser();

        return $this->success($user->toApiResponse());
    }

    /**
     * PUT /api/user/profile
     * 更新個人資料
     */
    public function updateProfile(): ResponseInterface
    {
        $user = $this->getCurrentUser();
        $input = $this->getJsonInput();

        // 準備更新資料
        $updateData = [];
        $errors = [];

        // 更新姓名
        if (isset($input['name'])) {
            $nameResult = $this->validateField('name', $input['name'], [
                'name' => 'min_length[1]|max_length[100]',
            ]);
            if ($nameResult !== true) {
                $errors = array_merge($errors, $nameResult);
            } else {
                $updateData['name'] = $input['name'];
            }
        }

        // 更新電話
        if (isset($input['phone'])) {
            $phoneResult = $this->validateField('phone', $input['phone'], [
                'phone' => 'min_length[10]|max_length[20]',
            ]);
            if ($phoneResult !== true) {
                $errors = array_merge($errors, $phoneResult);
            } else {
                $updateData['phone'] = $input['phone'];
            }
        }

        // 更新密碼
        if (isset($input['newPassword'])) {
            $passwordResult = $this->validatePasswordUpdate($user, $input);
            if (is_string($passwordResult)) {
                return $this->error($passwordResult, 400);
            }
            if (is_array($passwordResult)) {
                $errors = array_merge($errors, $passwordResult);
            } else {
                $updateData['password_hash'] = password_hash($input['newPassword'], PASSWORD_DEFAULT);
            }
        }

        // 若有驗證錯誤
        if (!empty($errors)) {
            return $this->validationError($errors);
        }

        // 若無更新資料
        if (empty($updateData)) {
            return $this->success([
                'message' => '沒有需要更新的資料',
                'user'    => $user->toApiResponse(),
            ]);
        }

        // 執行更新
        if (!$this->userModel->update($user->id, $updateData)) {
            return $this->error('更新失敗，請稍後再試', 500);
        }

        // 取得更新後的使用者資料
        $updatedUser = $this->userModel->find($user->id);

        return $this->success([
            'message' => '更新成功',
            'user'    => $updatedUser->toApiResponse(),
        ]);
    }

    /**
     * 驗證單一欄位
     * 
     * @return array|bool 驗證成功回傳 true，失敗回傳錯誤陣列
     */
    private function validateField(string $field, $value, array $rules)
    {
        $validation = service('validation');
        $validation->setRules($rules);
        
        if (!$validation->run([$field => $value])) {
            return $validation->getErrors();
        }
        
        return true;
    }

    /**
     * 驗證密碼更新
     * 
     * @return array|string|bool 錯誤訊息字串、錯誤陣列或 true
     */
    private function validatePasswordUpdate($user, array $input)
    {
        // 需要驗證舊密碼
        if (!isset($input['currentPassword'])) {
            return '更改密碼需要提供目前密碼';
        }

        if (!$user->verifyPassword($input['currentPassword'])) {
            return '目前密碼不正確';
        }

        $validation = service('validation');
        $validation->setRules([
            'newPassword' => 'min_length[8]',
        ], [
            'newPassword' => [
                'min_length' => '新密碼至少需要 8 個字元',
            ],
        ]);

        if (!$validation->run(['newPassword' => $input['newPassword']])) {
            return $validation->getErrors();
        }

        return true;
    }
}
