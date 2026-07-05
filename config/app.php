<?php
// config/app.php
// Đổi debug = false khi deploy production để không lộ SQLSTATE/stack trace.

return [
    'name'  => 'Bookstore Order CRM',
    'debug' => true, // TODO: set false khi production
    'session_timeout' => 900, // 15 phút không hoạt động -> bắt login lại
];
