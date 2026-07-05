<?php

class CustomerController
{
    public function __construct(private CustomerService $service)
    {
    }

    public function index(): void
    {
        require_login();
        $data = $this->service->getList($_GET);
        render('customers/index', ['title' => 'Customer Management'] + $data);
    }

    public function create(): void
    {
        require_login();
        render('customers/create', ['title' => 'Create Customer', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        require_login();
        $result = $this->service->create($_POST);

        if (!$result['success']) {
            render('customers/create', [
                'title'  => 'Create Customer',
                'errors' => $result['errors'],
                'old'    => $_POST,
            ]);
            return;
        }

        flash('success', 'Khách hàng đã được tạo thành công.');
        redirect('/customers');
    }

    public function edit(): void
    {
        require_login();
        $id = (int) ($_GET['id'] ?? 0);
        $customer = $this->service->findById($id);

        if (!$customer) {
            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            return;
        }

        render('customers/edit', [
            'title'    => 'Edit Customer',
            'customer' => $customer,
            'errors'   => [],
            'old'      => $customer,
        ]);
    }

    public function update(): void
    {
        require_login();
        $id     = (int) ($_POST['id'] ?? 0);
        $result = $this->service->update($id, $_POST);

        if (!$result['success']) {
            render('customers/edit', [
                'title'    => 'Edit Customer',
                'customer' => ['id' => $id] + $_POST,
                'errors'   => $result['errors'],
                'old'      => $_POST,
            ]);
            return;
        }

        flash('success', 'Cập nhật khách hàng thành công.');
        redirect('/customers');
    }

    public function delete(): void
    {
        require_login();
        $id = (int) ($_POST['id'] ?? 0);
        $this->service->delete($id);

        flash('success', 'Đã xoá khách hàng.');
        redirect('/customers');
    }
}
