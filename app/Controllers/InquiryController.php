<?php

class InquiryController
{
    public function __construct(private InquiryService $inquiryService)
    {
    }

    public function create(): void
    {
        render('inquiries/create', ['title' => 'Đăng ký tư vấn mua sách', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $result = $this->inquiryService->submit($_POST);

        if (!$result['success']) {
            render('inquiries/create', [
                'title'  => 'Đăng ký tư vấn mua sách',
                'errors' => $result['errors'],
                'old'    => $_POST,
            ]);
            return;
        }

        // PRG: redirect sau POST thành công để F5 không tạo dữ liệu trùng.
        flash('success', 'Cảm ơn bạn đã đăng ký tư vấn! Chúng tôi sẽ liên hệ sớm.');
        redirect('/inquiries/create');
    }
}
