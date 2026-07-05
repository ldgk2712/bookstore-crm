<?php

class OrderRepository
{
    private const SORTABLE_COLUMNS = ['id', 'order_code', 'customer_name', 'total_amount', 'status', 'created_at'];

    public function __construct(private PDO $db)
    {
    }

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM book_orders";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE order_code LIKE :kw1 OR customer_name LIKE :kw2 OR customer_email LIKE :kw3";
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

        $sql = "SELECT id, order_code, customer_name, customer_email, book_title, quantity,
                       total_amount, status, created_at
                FROM book_orders";
        $params = [];

        if ($keyword !== '') {
            $sql .= " WHERE order_code LIKE :kw1 OR customer_name LIKE :kw2 OR customer_email LIKE :kw3";
            $like = '%' . $keyword . '%';
            $params = ['kw1' => $like, 'kw2' => $like, 'kw3' => $like];
        }

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
        $stmt = $this->db->prepare("SELECT * FROM book_orders WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO book_orders (order_code, customer_name, customer_email, book_title, quantity, total_amount, status)
                VALUES (:order_code, :customer_name, :customer_email, :book_title, :quantity, :total_amount, :status)";
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate order_code.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE book_orders
                SET order_code = :order_code, customer_name = :customer_name,
                    customer_email = :customer_email, book_title = :book_title,
                    quantity = :quantity, total_amount = :total_amount, status = :status,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int) $e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Duplicate order_code.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM book_orders WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
