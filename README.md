# Bookstore Order CRM (Lab06 Final - PHP Secure MVC)

Mini project quản lý **khách hàng đăng ký tư vấn mua sách (customers)** và **đơn đặt sách (book_orders)**
cho một nhà sách nhỏ. Xây dựng theo mô hình MVC (Controller mỏng - Service nghiệp vụ - Repository SQL - View hiển thị),
có login/session, form an toàn, PRG, anti-spam, search/pagination/sort whitelist, và xử lý lỗi an toàn cho production.

## 1. Cách tạo database

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

Tài khoản demo sau khi seed: **admin@example.com / 123456**

> Nếu `password_verify()` báo sai với hash seed sẵn, hãy tự tạo hash mới:
> `php -r "echo password_hash('123456', PASSWORD_DEFAULT);"` rồi UPDATE lại cột `password_hash` của user admin.

## 2. Cấu hình kết nối DB

Sửa `config/database.php` theo user/password MySQL của máy bạn.

## 3. Cách chạy server

```bash
php -S localhost:8000 -t public
```

Truy cập: http://localhost:8000

## 4. Danh sách route

| Method | URL | Controller@Action | Ghi chú |
|---|---|---|---|
| GET | / | HomeController@index | Trang giới thiệu / redirect dashboard |
| GET | /login | AuthController@login | Form login |
| POST | /login | AuthController@handleLogin | Xác thực + regenerate session |
| POST | /logout | AuthController@logout | Logout sạch |
| GET | /dashboard | DashboardController@index | Yêu cầu đăng nhập |
| GET | /inquiries/create | InquiryController@create | Form công khai (honeypot + rate limit) |
| POST | /inquiries/store | InquiryController@store | Validate + anti-spam + PRG |
| GET | /customers | CustomerController@index | List + search + pagination + sort |
| GET | /customers/create | CustomerController@create | Form thêm |
| POST | /customers/store | CustomerController@store | Create + duplicate handling + PRG |
| GET | /customers/edit?id=1 | CustomerController@edit | Form sửa |
| POST | /customers/update | CustomerController@update | Update + PRG |
| POST | /customers/delete | CustomerController@delete | Delete bằng POST |
| GET | /orders | OrderController@index | List + search + pagination + sort |
| GET | /orders/create | OrderController@create | Form thêm |
| POST | /orders/store | OrderController@store | Create + duplicate order_code + PRG |
| GET | /orders/edit?id=1 | OrderController@edit | Form sửa |
| POST | /orders/update | OrderController@update | Update + PRG |
| POST | /orders/delete | OrderController@delete | Delete bằng POST |
| GET | /health | HealthController@index | JSON kiểm tra app/db |
| ANY | (không tồn tại) | Router | 404 |
| (sai method) | (route tồn tại) | Router | 405 |

## 5. Cấu trúc thư mục

```text
bookstore-crm/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── CustomerController.php
│   │   ├── DashboardController.php
│   │   ├── HealthController.php
│   │   ├── HomeController.php
│   │   ├── InquiryController.php
│   │   └── OrderController.php
│   ├── Core/
│   │   ├── Database.php
│   │   ├── DuplicateRecordException.php
│   │   ├── Router.php
│   │   └── helpers.php
│   ├── Repositories/
│   │   ├── CustomerRepository.php
│   │   ├── OrderRepository.php
│   │   └── UserRepository.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── CustomerService.php
│   │   ├── InquiryService.php
│   │   └── OrderService.php
│   └── Views/
│       ├── auth/
│       │   └── login.php
│       ├── customers/
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── index.php
│       ├── dashboard/
│       │   ├── index.php
│       │   └── welcome.php
│       ├── errors/
│       │   ├── 403.php
│       │   ├── 404.php
│       │   ├── 405.php
│       │   └── 500.php
│       ├── inquiries/
│       │   └── create.php
│       ├── layouts/
│       │   └── main.php
│       ├── orders/
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── index.php
│       └── partials/
│           ├── flash.php
│           └── nav.php
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seed.sql
├── public/
│   └── index.php
├── storage/
│   └── logs/
├── bootstrap.php
└── README.md
```

## 6. Lưu ý debug / production

- `config/app.php` có cờ `debug`. Đặt `debug = false` khi deploy để ẩn chi tiết lỗi (SQLSTATE, path, stack trace)
  khỏi người dùng — lỗi sẽ được ghi vào `storage/logs/app.log` thay vì hiển thị.
- Mọi output ra View đều đi qua `e()` để chống XSS.
- Mọi truy vấn SQL dùng prepared statements (không nối chuỗi input vào SQL).

## 7. EXPLAIN mẫu (chạy trong MySQL client)

```sql
EXPLAIN SELECT id, name, email, phone, book_interest, status, created_at
FROM customers
WHERE status = 'new'
ORDER BY created_at DESC
LIMIT 10 OFFSET 0;
```

Nhận xét: nhờ có `INDEX idx_customers_status_created_at (status, created_at)`,
cột `key` trong kết quả EXPLAIN sẽ dùng index này thay vì quét toàn bảng (`key = NULL` / `type = ALL`).

## 8. Bảo vệ CSRF (khuyến khích, Câu 3)

Mọi form `POST` (create/update/delete/logout/login) đều có 1 input ẩn `csrf_token` do `csrf_field()`
sinh ra (`app/Core/helpers.php`), lưu trong session. `Router::dispatch()` kiểm tra token này bằng
`verify_csrf()` (dùng `hash_equals()`) **trước khi** cho request đi vào Controller — nếu thiếu hoặc
sai token, trả về `403 Forbidden` (`app/Views/errors/403.php`), request không chạm tới DB.

Test nhanh:
```bash
curl -i -X POST http://localhost:8000/customers/store -d "name=Hack&email=hack@example.com&status=new"
```
Phải trả về `HTTP/1.1 403 Forbidden` vì thiếu `csrf_token`.

## 9. Test cases

Toàn bộ 25 test case (TC01–TC25) — bao gồm login/logout, session timeout, CRUD 2 module,
duplicate handling, search/pagination/sort (kể cả input nguy hiểm), health check, 404/405,
production safe error, và EXPLAIN/index — đã được kiểm thử thủ công và trình bày đầy đủ
kèm ảnh minh chứng trong file báo cáo PDF nộp kèm project này.