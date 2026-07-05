<?php

/**
 * Xử lý form công khai "Đăng ký tư vấn mua sách".
 * Dữ liệu được lưu thẳng vào bảng customers (status mặc định 'new'),
 * tương đương "public lead" trong yêu cầu đề bài.
 */
class InquiryService
{
    public function __construct(private CustomerRepository $repo)
    {
    }

    public function submit(array $input): array
    {
        // Honeypot: field ẩn "website" - người dùng thật không thấy, bot điền vào -> chặn.
        if (trim($input['website'] ?? '') !== '') {
            return ['success' => false, 'errors' => ['general' => 'Yêu cầu không hợp lệ.']];
        }

        // Rate limit theo session: chặn submit lại trong 5 giây.
        if (is_rate_limited('inquiry_submit', 5)) {
            return ['success' => false, 'errors' => ['general' => 'Bạn thao tác quá nhanh, vui lòng thử lại sau.']];
        }

        $name         = trim($input['name'] ?? '');
        $email        = trim($input['email'] ?? '');
        $phone        = trim($input['phone'] ?? '');
        $bookInterest = trim($input['book_interest'] ?? '');

        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Họ tên không được để trống.';
        }
        if ($email === '') {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->repo->create([
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'book_interest' => $bookInterest,
                'status'        => 'new',
                'note'          => 'Gửi từ form công khai.',
            ]);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['email' => 'Email này đã đăng ký tư vấn trước đó.']];
        }
    }
}
