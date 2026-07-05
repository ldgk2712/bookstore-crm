<h1>Login</h1>

<?php if (!empty($notice)): ?>
    <div class="flash-error"><?= e($notice) ?></div>
<?php endif; ?>

<?php if (!empty($errors['general'])): ?>
    <div class="flash-error"><?= e($errors['general']) ?></div>
<?php endif; ?>

<form method="POST" action="/login">
    <label>Email</label>
    <input type="email" name="email" value="<?= old($old, 'email') ?>" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" style="margin-top:12px;">Login</button>
</form>

<p style="margin-top:16px;color:#666;font-size:0.9em;">Tài khoản demo: admin@example.com / 123456</p>
