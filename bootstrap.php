<?php

// --- Session cookie setup PHẢI chạy trước session_start() ---
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// --- Simple autoload (không cần Composer) ---
spl_autoload_register(function ($class) {
    $dirs = ['app/Core', 'app/Controllers', 'app/Services', 'app/Repositories'];
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/app/Core/helpers.php';

$appConfig = require __DIR__ . '/config/app.php';
$dbConfig  = require __DIR__ . '/config/database.php';

define('APP_DEBUG', $appConfig['debug']);

// --- Kết nối DB (bắt lỗi an toàn, không lộ chi tiết cho user ở production) ---
try {
    $pdo = Database::connect($dbConfig);
} catch (PDOException $e) {
    log_error('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    if (APP_DEBUG) {
        echo 'DB connection error: ' . $e->getMessage();
    } else {
        echo 'Hệ thống đang gặp sự cố, vui lòng thử lại sau.';
    }
    exit;
}

// --- DI container siêu đơn giản: key = tên class, value = instance ---
$container = [];
$container['pdo'] = $pdo;

$container[UserRepository::class]     = new UserRepository($pdo);
$container[CustomerRepository::class] = new CustomerRepository($pdo);
$container[OrderRepository::class]    = new OrderRepository($pdo);

$container[AuthService::class]     = new AuthService($container[UserRepository::class]);
$container[CustomerService::class] = new CustomerService($container[CustomerRepository::class]);
$container[OrderService::class]    = new OrderService($container[OrderRepository::class]);
$container[InquiryService::class]  = new InquiryService($container[CustomerRepository::class]);

$container[HomeController::class]      = new HomeController();
$container[AuthController::class]      = new AuthController($container[AuthService::class]);
$container[DashboardController::class] = new DashboardController(
    $container[CustomerRepository::class],
    $container[OrderRepository::class]
);
$container[InquiryController::class]  = new InquiryController($container[InquiryService::class]);
$container[CustomerController::class] = new CustomerController($container[CustomerService::class]);
$container[OrderController::class]    = new OrderController($container[OrderService::class]);
$container[HealthController::class]   = new HealthController($pdo);

return $container;
