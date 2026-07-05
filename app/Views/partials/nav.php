<nav>
    <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="/dashboard">Dashboard</a>
        <a href="/customers">Customers</a>
        <a href="/orders">Orders</a>
        <a href="/inquiries/create">Public Inquiry Form</a>
        <form action="/logout" method="POST" style="display:inline">
            <button type="submit" style="background:none;border:none;color:#fff;cursor:pointer;padding:0;margin-left:16px;">Logout</button>
        </form>
    <?php else: ?>
        <a href="/inquiries/create">Đăng ký tư vấn</a>
        <a href="/login">Login</a>
    <?php endif; ?>
</nav>
