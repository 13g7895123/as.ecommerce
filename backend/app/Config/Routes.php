<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * API Routes
 * --------------------------------------------------------------------
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    
    // 認證模組 (Auth) - 公開路由
    $routes->post('auth/register', 'AuthController::register');
    $routes->post('auth/login', 'AuthController::login');

    // 商品模組 (Products) - 公開路由
    $routes->get('products', 'ProductController::index');
    $routes->get('products/(:segment)', 'ProductController::show/$1');
    $routes->get('categories', 'ProductController::categories');

    // 需要認證的路由
    $routes->group('', ['filter' => 'auth'], static function ($routes) {
        // 認證模組 - 需登入
        $routes->post('auth/logout', 'AuthController::logout');

        // 訂單模組 (Orders)
        $routes->get('orders', 'OrderController::index');
        $routes->post('orders', 'OrderController::create');
        $routes->get('orders/(:segment)', 'OrderController::show/$1');

        // 使用者模組 (User)
        $routes->get('user/profile', 'UserController::profile');
        $routes->put('user/profile', 'UserController::updateProfile');
    });
});
