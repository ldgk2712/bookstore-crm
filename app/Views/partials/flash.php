<?php $success = get_flash('success'); ?>
<?php if ($success): ?>
    <div class="flash-success"><?= e($success) ?></div>
<?php endif; ?>
