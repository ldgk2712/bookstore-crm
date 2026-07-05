<?php

/**
 * Front Controller: MỌI request đều đi qua file này (xem cấu hình .htaccess / router PHP built-in).
 */

$container = require __DIR__ . '/../bootstrap.php';

$router = new Router();

// ---- Route table ----
$router->get('/', [HomeController::class, 'index']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'handleLogin']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

// Public inquiry form (tương đương "public-leads")
$router->get('/inquiries/create', [InquiryController::class, 'create']);
$router->post('/inquiries/store', [InquiryController::class, 'store']);

// Module A: Customers
$router->get('/customers', [CustomerController::class, 'index']);
$router->get('/customers/create', [CustomerController::class, 'create']);
$router->post('/customers/store', [CustomerController::class, 'store']);
$router->get('/customers/edit', [CustomerController::class, 'edit']);
$router->post('/customers/update', [CustomerController::class, 'update']);
$router->post('/customers/delete', [CustomerController::class, 'delete']);

// Module B: Orders
$router->get('/orders', [OrderController::class, 'index']);
$router->get('/orders/create', [OrderController::class, 'create']);
$router->post('/orders/store', [OrderController::class, 'store']);
$router->get('/orders/edit', [OrderController::class, 'edit']);
$router->post('/orders/update', [OrderController::class, 'update']);
$router->post('/orders/delete', [OrderController::class, 'delete']);

$router->get('/health', [HealthController::class, 'index']);

// ---- Dispatch ----
$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path   = rtrim($path, '/') ?: '/';

try {
    $router->dispatch($method, $path, $container);
} catch (Throwable $e) {
    // Không lộ SQLSTATE / tên bảng / đường dẫn file cho user khi production (APP_DEBUG=false).
    log_error('Unhandled error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    render('errors/500', [
        'title'        => '500 Internal Server Error',
        'debugMessage' => APP_DEBUG ? ($e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine()) : null,
    ]);
}

