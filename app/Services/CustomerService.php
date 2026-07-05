<?php

class CustomerService
{
    private const PER_PAGE = 10;
    private const STATUSES = ['new', 'contacted', 'converted', 'closed'];

    public function __construct(private CustomerRepository $repo)
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
        $page       = min($page, $totalPages); // chuẩn hoá page quá lớn về totalPages
        $offset     = ($page - 1) * self::PER_PAGE;

        return [
            'customers'  => $this->repo->getPaginated($keyword, $sort, $direction, self::PER_PAGE, $offset),
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
            return ['success' => false, 'errors' => ['email' => 'Email này đã tồn tại trong hệ thống.']];
        }
    }

    public function update(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Khách hàng không tồn tại.']];
        }

        $validation = $this->validate($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->update($id, $validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['email' => 'Email này đã tồn tại trong hệ thống.']];
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

        $name         = trim($input['name'] ?? '');
        $email        = trim($input['email'] ?? '');
        $phone        = trim($input['phone'] ?? '');
        $bookInterest = trim($input['book_interest'] ?? '');
        $status       = trim($input['status'] ?? 'new');
        $note         = trim($input['note'] ?? '');

        if ($name === '') {
            $errors['name'] = 'Họ tên không được để trống.';
        }

        if ($email === '') {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if (!in_array($status, self::STATUSES, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => [
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'book_interest' => $bookInterest,
                'status'        => $status,
                'note'          => $note,
            ],
        ];
    }
}
