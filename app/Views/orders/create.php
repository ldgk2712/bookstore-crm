<h1><?= e($title) ?></h1>

<?php if (!empty($errors['general'])): ?><div class="flash-error"><?= e($errors['general']) ?></div><?php endif; ?>

<form method="POST" action="/orders/store">
    <label>Order code</label>
    <input type="text" name="order_code" value="<?= old($old, 'order_code') ?>">
    <?php if (!empty($errors['order_code'])): ?><div class="field-error"><?= e($errors['order_code']) ?></div><?php endif; ?>

    <label>Customer name</label>
    <input type="text" name="customer_name" value="<?= old($old, 'customer_name') ?>">
    <?php if (!empty($errors['customer_name'])): ?><div class="field-error"><?= e($errors['customer_name']) ?></div><?php endif; ?>

    <label>Customer email</label>
    <input type="email" name="customer_email" value="<?= old($old, 'customer_email') ?>">
    <?php if (!empty($errors['customer_email'])): ?><div class="field-error"><?= e($errors['customer_email']) ?></div><?php endif; ?>

    <label>Book title</label>
    <input type="text" name="book_title" value="<?= old($old, 'book_title') ?>">

    <label>Quantity</label>
    <input type="number" name="quantity" value="<?= old($old, 'quantity', '1') ?>">
    <?php if (!empty($errors['quantity'])): ?><div class="field-error"><?= e($errors['quantity']) ?></div><?php endif; ?>

    <label>Total amount</label>
    <input type="number" step="0.01" name="total_amount" value="<?= old($old, 'total_amount', '0') ?>">
    <?php if (!empty($errors['total_amount'])): ?><div class="field-error"><?= e($errors['total_amount']) ?></div><?php endif; ?>

    <label>Status</label>
    <select name="status">
        <?php foreach (['pending', 'paid', 'shipping', 'completed', 'cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= ($old['status'] ?? 'pending') === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['status'])): ?><div class="field-error"><?= e($errors['status']) ?></div><?php endif; ?>

    <button type="submit" style="margin-top:12px;">Save Order</button>
</form>
