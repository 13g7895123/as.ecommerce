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

        // 更新姓名
        if (isset($input['name'])) {
            $validation = service('validation');
            $validation->setRules([
                'name' => 'min_length[1]|max_length[100]',
            ]);

            if (!$validation->run(['name' => $input['name']])) {
                return $this->validationError($validation->getErrors());
            }

            $updateData['name'] = $input['name'];
        }

        // 更新電話
        if (isset($input['phone'])) {
            $validation = service('validation');
            $validation->setRules([
                'phone' => 'min_length[10]|max_length[20]',
            ]);

            if (!$validation->run(['phone' => $input['phone']])) {
                return $this->validationError($validation->getErrors());
            }

            $updateData['phone'] = $input['phone'];
        }

        // 更新密碼
        if (isset($input['newPassword'])) {
            // 需要驗證舊密碼
            if (!isset($input['currentPassword'])) {
                return $this->error('更改密碼需要提供目前密碼', 400);
            }

            if (!$user->verifyPassword($input['currentPassword'])) {
                return $this->error('目前密碼不正確', 400);
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
                return $this->validationError($validation->getErrors());
            }

            $updateData['password_hash'] = password_hash($input['newPassword'], PASSWORD_DEFAULT);
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
}
