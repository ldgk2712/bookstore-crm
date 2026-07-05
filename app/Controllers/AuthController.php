<?php

class AuthController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            redirect('/dashboard');
        }

        $timeout = isset($_GET['timeout']) ? 'Phiên làm việc đã hết hạn, vui lòng đăng nhập lại.' : null;

        render('auth/login', [
            'title'  => 'Login',
            'errors' => [],
            'old'    => [],
            'notice' => $timeout,
        ]);
    }

    public function handleLogin(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->authService->attemptLogin($email, $password);

        if (!$user) {
            render('auth/login', [
                'title'  => 'Login',
                'errors' => ['general' => 'Email hoặc mật khẩu không đúng.'],
                'old'    => ['email' => $email],
                'notice' => null,
            ]);
            return;
        }

        // Chống session fixation: bắt buộc đổi session ID sau khi login thành công.
        session_regenerate_id(true);

        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_name']     = $user['name'];
        $_SESSION['last_activity'] = time();

        flash('success', 'Đăng nhập thành công.');
        redirect('/dashboard');
    }

    public function logout(): void
    {
        // Xoá sạch dữ liệu session hiện tại và huỷ session cũ trên server.
        $_SESSION = [];
        session_destroy();

        // Bắt đầu 1 session hoàn toàn mới rồi bắt buộc đổi sang session ID mới
        // (session_regenerate_id sẽ tự gửi Set-Cookie mới cho trình duyệt).
        // Nếu không làm bước này, PHP sẽ cố dùng lại session ID cũ (đã bị destroy)
        // vì $_COOKIE vẫn còn giữ giá trị cũ, khiến flash message bên dưới bị mất.
        session_start();
        session_regenerate_id(true);

        flash('success', 'Bạn đã đăng xuất thành công.');

        redirect('/login');
    }
}
