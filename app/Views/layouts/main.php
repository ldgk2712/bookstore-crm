<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= e($title ?? 'Bookstore Order CRM') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f5f7; color: #222; }
        header.topbar { background: #1c2333; color: #fff; padding: 14px 24px; display: flex; justify-content: space-between; }
        header.topbar a { color: #fff; text-decoration: none; margin-left: 16px; }
        header.topbar a.brand { font-weight: bold; margin-left: 0; }
        main { max-width: 960px; margin: 24px auto; background: #fff; padding: 24px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e0e0e0; }
        .flash-success { background: #e6f9e9; color: #1b7a2b; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .flash-error { background: #fdeaea; color: #a11; padding: 10px; border-radius: 6px; margin-bottom: 12px; }
        .field-error { color: #c0392b; font-size: 0.85em; }
        input, select, textarea { padding: 6px; margin-bottom: 4px; width: 100%; box-sizing: border-box; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        button, .btn { background: #2d5fce; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
        .btn-danger { background: #c0392b; }
        .pagination a, .pagination span { margin-right: 6px; }
        .honeypot-field { position: absolute; left: -9999px; }
    </style>
</head>
<body>
<header class="topbar">
    <div><a class="brand" href="/">Bookstore Order CRM</a></div>
    <?php partial('nav'); ?>
</header>
<main>
    <?php partial('flash'); ?>
    <?= $content ?>
</main>
</body>
</html>
