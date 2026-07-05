<h1><?= e($title) ?></h1>

<?php if (!empty($errors['general'])): ?>
    <div class="flash-error"><?= e($errors['general']) ?></div>
<?php endif; ?>

<form method="POST" action="/inquiries/store">
    <!-- Honeypot: field ẩn, người dùng thật sẽ không thấy/không điền. Bot thường tự động điền hết field. -->
    <div class="honeypot-field" aria-hidden="true">
        <label>Website</label>
        <input type="text" name="website" tabindex="-1" autocomplete="off">
    </div>

    <label>Họ tên</label>
    <input type="text" name="name" value="<?= old($old, 'name') ?>">
    <?php if (!empty($errors['name'])): ?><div class="field-error"><?= e($errors['name']) ?></div><?php endif; ?>

    <label>Email</label>
    <input type="email" name="email" value="<?= old($old, 'email') ?>">
    <?php if (!empty($errors['email'])): ?><div class="field-error"><?= e($errors['email']) ?></div><?php endif; ?>

    <label>Số điện thoại</label>
    <input type="text" name="phone" value="<?= old($old, 'phone') ?>">

    <label>Thể loại sách quan tâm</label>
    <input type="text" name="book_interest" value="<?= old($old, 'book_interest') ?>">

    <button type="submit" style="margin-top:12px;">Gửi đăng ký</button>
</form>
