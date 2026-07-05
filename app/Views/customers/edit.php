<h1><?= e($title) ?> #<?= e((string) $customer['id']) ?></h1>

<?php if (!empty($errors['general'])): ?><div class="flash-error"><?= e($errors['general']) ?></div><?php endif; ?>

<form method="POST" action="/customers/update">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e((string) $customer['id']) ?>">

    <label>Name</label>
    <input type="text" name="name" value="<?= old($old, 'name') ?>">
    <?php if (!empty($errors['name'])): ?><div class="field-error"><?= e($errors['name']) ?></div><?php endif; ?>

    <label>Email</label>
    <input type="email" name="email" value="<?= old($old, 'email') ?>">
    <?php if (!empty($errors['email'])): ?><div class="field-error"><?= e($errors['email']) ?></div><?php endif; ?>

    <label>Phone</label>
    <input type="text" name="phone" value="<?= old($old, 'phone') ?>">

    <label>Book interest</label>
    <input type="text" name="book_interest" value="<?= old($old, 'book_interest') ?>">

    <label>Status</label>
    <select name="status">
        <?php foreach (['new', 'contacted', 'converted', 'closed'] as $s): ?>
            <option value="<?= $s ?>" <?= ($old['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><div class="field-error"><?= e($errors['status']) ?></div><?php endif; ?>

    <label>Note</label>
    <textarea name="note"><?= old($old, 'note') ?></textarea>

    <button type="submit" style="margin-top:12px;">Update</button>
</form>

<form method="POST" action="/customers/delete" style="margin-top:8px;" onsubmit="return confirm('Xoá khách hàng này?');">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e((string) $customer['id']) ?>">
    <button type="submit" class="btn-danger">Delete</button>
</form>
