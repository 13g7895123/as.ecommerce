<?php

namespace App\Controllers\Api;

use App\Libraries\JwtService;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends ApiController
{
    protected UserModel $userModel;
    protected JwtService $jwtService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->jwtService = new JwtService();
    }

    /**
     * POST /api/auth/register
     * 會員註冊
     */
    public function register(): ResponseInterface
    {
        $input = $this->getJsonInput();

        // 驗證必填欄位
        $validationResult = $this->validateRegistration($input);
        if ($validationResult !== true) {
            return $this->validationError($validationResult);
        }

        // 建立使用者
        $userId = $this->userModel->generateId();
        
        $userData = [
            'id'            => $userId,
            'email'         => $input['email'],
            'password_hash' => password_hash($input['password'], PASSWORD_DEFAULT),
            'name'          => $input['name'],
            'phone'         => $input['phone'],
        ];

        if (!$this->userModel->insert($userData)) {
            return $this->error('註冊失敗，請稍後再試', 500);
        }

        // 取得新建立的使用者
        $user = $this->userModel->find($userId);

        // 產生 Token
        $token = $this->generateUserToken($user);

        return $this->success([
            'user'  => $user->toApiResponse(),
            'token' => $token,
        ]);
    }

    /**
     * POST /api/auth/login
     * 會員登入
     */
    public function login(): ResponseInterface
    {
        $input = $this->getJsonInput();

        // 驗證必填欄位
        $validationResult = $this->validateLogin($input);
        if ($validationResult !== true) {
            return $this->validationError($validationResult);
        }

        // 查詢使用者
        $user = $this->userModel->findByEmail($input['email']);

        if (!$user || !$user->verifyPassword($input['password'])) {
            return $this->error('電子郵件或密碼錯誤', 401);
        }

        // 產生 Token
        $token = $this->generateUserToken($user);

        return $this->success([
            'user'  => $user->toApiResponse(),
            'token' => $token,
        ]);
    }

    /**
     * POST /api/auth/logout
     * 會員登出
     */
    public function logout(): ResponseInterface
    {
        $token = $this->getCurrentToken();

        if ($token) {
            $this->jwtService->invalidateToken($token);
        }

        return $this->success([
            'message' => '登出成功',
        ]);
    }

    /**
     * 驗證註冊輸入
     * 
     * @return array|bool 驗證成功回傳 true，失敗回傳錯誤陣列
     */
    private function validateRegistration(array $input)
    {
        $rules = [
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'name'     => 'required|min_length[1]|max_length[100]',
            'phone'    => 'required|min_length[10]|max_length[20]',
        ];

        $messages = [
            'email' => [
                'required'    => '電子郵件為必填欄位',
                'valid_email' => '請輸入有效的電子郵件格式',
                'is_unique'   => '此電子郵件已被註冊',
            ],
            'password' => [
                'required'   => '密碼為必填欄位',
                'min_length' => '密碼至少需要 8 個字元',
            ],
            'name' => [
                'required'   => '姓名為必填欄位',
                'min_length' => '姓名至少需要 1 個字元',
                'max_length' => '姓名不可超過 100 個字元',
            ],
            'phone' => [
                'required'   => '電話為必填欄位',
                'min_length' => '電話至少需要 10 個字元',
                'max_length' => '電話不可超過 20 個字元',
            ],
        ];

        return $this->validateInput($rules, $messages, $input);
    }

    /**
     * 驗證登入輸入
     * 
     * @return array|bool 驗證成功回傳 true，失敗回傳錯誤陣列
     */
    private function validateLogin(array $input)
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => '電子郵件為必填欄位',
                'valid_email' => '請輸入有效的電子郵件格式',
            ],
            'password' => [
                'required' => '密碼為必填欄位',
            ],
        ];

        return $this->validateInput($rules, $messages, $input);
    }

    /**
     * 產生使用者 Token
     */
    private function generateUserToken($user): string
    {
        return $this->jwtService->generateToken([
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);
    }
}
