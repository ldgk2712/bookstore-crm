<h1><?= e($title) ?></h1>
<p><a class="btn" href="/customers/create">+ Create Customer</a></p>

<form method="GET" action="/customers">
    <input type="text" name="q" placeholder="Tìm theo tên, email, SĐT" value="<?= e($keyword) ?>" style="width:260px;display:inline-block;">
    <input type="hidden" name="sort" value="<?= e($sort) ?>">
    <input type="hidden" name="direction" value="<?= e($direction) ?>">
    <button type="submit">Tìm kiếm</button>
</form>

<table>
    <thead>
    <tr>
        <th><a href="?q=<?= e($keyword) ?>&sort=id&direction=<?= $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc' ?>">ID</a></th>
        <th><a href="?q=<?= e($keyword) ?>&sort=name&direction=<?= $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc' ?>">Name</a></th>
        <th>Email</th>
        <th>Phone</th>
        <th>Book interest</th>
        <th><a href="?q=<?= e($keyword) ?>&sort=status&direction=<?= $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
        <th><a href="?q=<?= e($keyword) ?>&sort=created_at&direction=<?= $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc' ?>">Created at</a></th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= e((string) $c['id']) ?></td>
            <td><?= e($c['name']) ?></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['phone']) ?></td>
            <td><?= e($c['book_interest']) ?></td>
            <td><?= e($c['status']) ?></td>
            <td><?= e($c['created_at']) ?></td>
            <td><a href="/customers/edit?id=<?= e((string) $c['id']) ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($customers)): ?>
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
