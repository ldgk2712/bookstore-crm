<h1><?= e($title) ?></h1>
<p><a class="btn" href="/orders/create">+ Create Order</a></p>

<form method="GET" action="/orders">
    <input type="text" name="q" placeholder="Tìm theo mã đơn, tên khách" value="<?= e($keyword) ?>" style="width:260px;display:inline-block;">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Tìm kiếm</button>
</form>

<table>
    <thead>
    <tr>
        <th><a href="?q=<?= e($keyword) ?>&sort=order_code&direction=<?= $sort === 'order_code' && $direction === 'asc' ? 'desc' : 'asc' ?>">Order code</a></th>
        <th>Customer</th>
        <th>Book title</th>
        <th>Qty</th>
        <th><a href="?q=<?= e($keyword) ?>&sort=total_amount&direction=<?= $sort === 'total_amount' && $direction === 'asc' ? 'desc' : 'asc' ?>">Total</a></th>
        <th><a href="?q=<?= e($keyword) ?>&sort=status&direction=<?= $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
        <th><a href="?q=<?= e($keyword) ?>&sort=created_at&direction=<?= $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc' ?>">Created at</a></th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $o): ?>
        <tr>
            <td><?= e($o['order_code']) ?></td>
            <td><?= e($o['customer_name']) ?></td>
            <td><?= e($o['book_title']) ?></td>
            <td><?= e((string) $o['quantity']) ?></td>
            <td><?= e(number_format((float) $o['total_amount'])) ?></td>
            <td><?= e($o['status']) ?></td>
            <td><?= e($o['created_at']) ?></td>
            <td><a href="/orders/edit?id=<?= e((string) $o['id']) ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?>
        <tr><td colspan="8">Không có dữ liệu.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<div class="pagination" style="margin-top:12px;">
    Showing page <?= e((string) $page) ?> / <?= e((string) $totalPages) ?> (tổng <?= e((string) $totalItems) ?>)
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="?q=<?= e($keyword) ?>&sort=<?= e($sort) ?>&direction=<?= e($direction) ?>&page=<?= $p ?>"><?= $p ?></a>
    <?php endfor; ?>
</div>
