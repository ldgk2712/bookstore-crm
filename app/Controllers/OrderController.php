<?php

class OrderController
{
    public function __construct(private OrderService $service)
    {
    }

    public function index(): void
    {
        require_login();
        $data = $this->service->getList($_GET);
        render('orders/index', ['title' => 'Order Management'] + $data);
    }

    public function create(): void
    {
        require_login();
        render('orders/create', ['title' => 'Create Order', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        require_login();
        $result = $this->service->create($_POST);

        if (!$result['success']) {
            render('orders/create', [
                'title'  => 'Create Order',
                'errors' => $result['errors'],
                'old'    => $_POST,
            ]);
            return;
        }

        flash('success', 'Đơn hàng đã được tạo thành công.');
        redirect('/orders');
    }

    public function edit(): void
    {
        require_login();
        $id    = (int) ($_GET['id'] ?? 0);
        $order = $this->service->findById($id);

        if (!$order) {
            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            return;
        }

        render('orders/edit', [
            'title'  => 'Edit Order',
            'order'  => $order,
            'errors' => [],
            'old'    => $order,
        ]);
    }

    public function update(): void
    {
        require_login();
        $id     = (int) ($_POST['id'] ?? 0);
        $result = $this->service->update($id, $_POST);

        if (!$result['success']) {
            render('orders/edit', [
                'title'  => 'Edit Order',
                'order'  => ['id' => $id] + $_POST,
                'errors' => $result['errors'],
                'old'    => $_POST,
            ]);
            return;
        }

        flash('success', 'Cập nhật đơn hàng thành công.');
        redirect('/orders');
    }

    public function delete(): void
    {
        require_login();
        $id = (int) ($_POST['id'] ?? 0);
        $this->service->delete($id);

        flash('success', 'Đã xoá đơn hàng.');
        redirect('/orders');
    }
}
