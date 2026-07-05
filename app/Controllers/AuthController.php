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
        // Logout sạch: xoá toàn bộ dữ liệu session, xoá cookie session, destroy session.
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }

        session_destroy();
        redirect('/login');
    }
}
