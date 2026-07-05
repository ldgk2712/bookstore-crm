<h1>500 Internal Server Error</h1>
<p>Hệ thống đang gặp sự cố. Vui lòng thử lại sau.</p>
<?php if (defined('APP_DEBUG') && APP_DEBUG && !empty($debugMessage)): ?>
    <pre style="background:#f6f6f6;padding:12px;"><?= e($debugMessage) ?></pre>
<?php endif; ?>
