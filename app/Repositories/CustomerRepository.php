<?php

class CustomerRepository
{
    /** Whitelist cột được phép sort -> chặn SQL injection qua tham số sort. */
    private const SORTABLE_COLUMNS = ['id', 'name', 'email', 'status', 'created_at'];

    public function __construct(private PDO $db)
    {
    }

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM customers";
        $params = [];

        if ($keyword !== '') {
            // Dùng 3 tên tham số riêng biệt vì PDO (emulate prepares = false)
            // không cho phép lặp lại cùng 1 named parameter nhiều lần trong 1 câu SQL.
            $sql .= " WHERE name LIKE :kw1 OR email LIKE :kw2 OR phone LIKE :kw3";
            $like = '%' . $keyword . '%';
            $params = ['kw1' => $like, 'kw2' => $like, 'kw3' => $like];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, string $sort, string $direction, int $limit, int $offset): array
    {
        $sort      = in_array($sort, self::SORTABLE_COLUMNS, true) ? $sort : 'created_at';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "SELECT id, name, email, phone, book_interest, status, created_at FROM customers";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :kw1 OR email LIKE :kw2 OR phone LIKE :kw3";
            $like = '%' . $keyword . '%';
            $params = ['kw1' => $like, 'kw2' => $like, 'kw3' => $like];
        }

        // $sort/$direction đã qua whitelist ở trên nên nối chuỗi an toàn (không phải input trực tiếp).
        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO customers (name, email, phone, book_interest, status, note)
                VALUES (:name, :email, :phone, :book_interest, :status, :note)";
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate customer email.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE customers
                SET name = :name, email = :email, phone = :phone,
                    book_interest = :book_interest, status = :status, note = :note,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate customer email.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
