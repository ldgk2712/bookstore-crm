<?php

class OrderService
{
    private const PER_PAGE = 10;
    private const STATUSES = ['pending', 'paid', 'shipping', 'completed', 'cancelled'];

    public function __construct(private OrderRepository $repo)
    {
    }

    public function getList(array $query): array
    {
        $keyword   = trim($query['q'] ?? '');
        $sort      = trim($query['sort'] ?? 'created_at');
        $direction = trim($query['direction'] ?? 'desc');
        $page      = max(1, (int) ($query['page'] ?? 1));

        $totalItems = $this->repo->countAll($keyword);
        $totalPages = max(1, (int) ceil($totalItems / self::PER_PAGE));
        $page       = min($page, $totalPages);
        $offset     = ($page - 1) * self::PER_PAGE;

        return [
            'orders'     => $this->repo->getPaginated($keyword, $sort, $direction, self::PER_PAGE, $offset),
            'keyword'    => $keyword,
            'sort'       => $sort,
            'direction'  => $direction,
            'page'       => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
        ];
    }

    public function findById(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    public function create(array $input): array
    {
        $validation = $this->validate($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->create($validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['order_code' => 'Mã đơn hàng này đã tồn tại.']];
        }
    }

    public function update(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Đơn hàng không tồn tại.']];
        }

        $validation = $this->validate($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->update($id, $validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['order_code' => 'Mã đơn hàng này đã tồn tại.']];
        }
    }

    public function delete(int $id): array
    {
        if ($id <= 0) {
            return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        }
        $this->repo->delete($id);
        return ['success' => true, 'errors' => []];
    }

    private function validate(array $input): array
    {
        $errors = [];

        $orderCode     = trim($input['order_code'] ?? '');
        $customerName  = trim($input['customer_name'] ?? '');
        $customerEmail = trim($input['customer_email'] ?? '');
        $bookTitle     = trim($input['book_title'] ?? '');
        $quantity      = (int) ($input['quantity'] ?? 0);
        $totalAmount   = (float) ($input['total_amount'] ?? -1);
        $status        = trim($input['status'] ?? 'pending');

        if ($orderCode === '') {
            $errors['order_code'] = 'Mã đơn hàng không được để trống.';
        }
        if ($customerName === '') {
            $errors['customer_name'] = 'Tên khách hàng không được để trống.';
        }
        if ($customerEmail !== '' && !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email không đúng định dạng.';
        }
        if ($quantity <= 0) {
            $errors['quantity'] = 'Số lượng phải lớn hơn 0.';
        }
        if ($totalAmount < 0) {
            $errors['total_amount'] = 'Tổng tiền không được âm.';
        }
        if (!in_array($status, self::STATUSES, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => [
                'order_code'     => $orderCode,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
                'book_title'     => $bookTitle,
                'quantity'       => $quantity,
                'total_amount'   => $totalAmount,
                'status'         => $status,
            ],
        ];
    }
}
