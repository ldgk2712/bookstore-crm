<?php

class HealthController
{
    public function __construct(private PDO $db)
    {
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        $dbStatus = 'down';
        try {
            $this->db->query('SELECT 1');
            $dbStatus = 'ok';
        } catch (Throwable $e) {
            log_error('Health check DB error: ' . $e->getMessage());
        }

        echo json_encode([
            'app'       => 'ok',
            'database'  => $dbStatus,
            'timestamp' => date('c'),
        ]);
    }
}
