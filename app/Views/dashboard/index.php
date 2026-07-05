<h1>Dashboard</h1>
<p>Xin chào, <?= e($userName) ?>. Trang này yêu cầu đăng nhập hợp lệ.</p>

<div style="display:flex;gap:16px;">
    <div style="flex:1;background:#f0f4ff;padding:16px;border-radius:8px;">
        <div>Tổng khách hàng</div>
        <div style="font-size:1.8em;font-weight:bold;"><?= e((string) $totalCustomers) ?></div>
    </div>
    <div style="flex:1;background:#fff4e6;padding:16px;border-radius:8px;">
        <div>Tổng đơn hàng</div>
        <div style="font-size:1.8em;font-weight:bold;"><?= e((string) $totalOrders) ?></div>
    </div>
</div>

<p style="margin-top:20px;">
    <a href="/customers">Quản lý khách hàng</a> &nbsp;|&nbsp;
    <a href="/orders">Quản lý đơn hàng</a> &nbsp;|&nbsp;
    <a href="/health">Health check (JSON)</a>
</p>
