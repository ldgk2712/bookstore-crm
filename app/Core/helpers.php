<?php

/** Escape output an toàn để chống XSS. Mọi dữ liệu từ DB/user in ra View phải qua e(). */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect + dừng thực thi ngay (dùng cho PRG pattern). */
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/** Render 1 view con vào trong layout chung. */
function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../Views/' . $layout . '.php';
}

/** Render 1 partial (nav, flash...) dùng lại nhiều nơi. */
function partial(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/partials/' . $view . '.php';
}

/** Set flash message 1 lần (success/error). */
function flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

/** Lấy flash message ra và xoá luôn (chỉ hiện 1 lần). */
function get_flash(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) {
        return null;
    }
    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

/** Lấy lại giá trị cũ của input khi form bị lỗi (giữ old input). */
function old(array $oldData, string $field, string $default = ''): string
{
    return e($oldData[$field] ?? $default);
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/**
 * Bắt buộc phải login mới cho vào trang. Có kiểm tra timeout không hoạt động.
 * Đặt ở đầu Controller action nào cần bảo vệ.
 */
function require_login(): void
{
    $appConfig = require __DIR__ . '/../../config/app.php';
    $timeout   = $appConfig['session_timeout'];

    if (empty($_SESSION['user_id'])) {
        redirect('/login');
    }

    if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        $_SESSION = [];
        session_destroy();
        redirect('/login?timeout=1');
    }

    $_SESSION['last_activity'] = time();
}

/**
 * Rate limit đơn giản theo session: chặn submit lại quá nhanh (chống spam form public).
 * Trả về true nếu request bị chặn (đang trong thời gian chờ).
 */
function is_rate_limited(string $key, int $seconds = 5): bool
{
    $now  = time();
    $last = $_SESSION['rate_limit'][$key] ?? 0;

    if (($now - $last) < $seconds) {
        return true;
    }

    $_SESSION['rate_limit'][$key] = $now;
    return false;
}

/** Ghi log lỗi an toàn vào storage/logs/app.log thay vì in ra màn hình. */
function log_error(string $message): void
{
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents(__DIR__ . '/../../storage/logs/app.log', $line, FILE_APPEND);
}

/**
 * Lấy CSRF token của session hiện tại, tự sinh nếu chưa có.
 * Token dùng chung cho cả session (không đổi mỗi request) để tránh việc mở
 * nhiều tab/form cùng lúc bị lỗi "token cũ" khi submit form mở trước đó.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** In ra input ẩn chứa CSRF token, dùng trong mọi <form method="POST">. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Kiểm tra CSRF token gửi lên trong $_POST có khớp với token của session không.
 * Dùng hash_equals() thay vì === để tránh timing attack.
 */
function verify_csrf(): bool
{
    $submitted = $_POST['csrf_token'] ?? '';
    $expected  = $_SESSION['csrf_token'] ?? '';

    return $expected !== '' && hash_equals($expected, $submitted);
}