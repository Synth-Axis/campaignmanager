<?php

class HealthController
{
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        try {
            // Confirm the connection and imported login table without reading user data.
            (new Database())->db->query('SELECT user_id FROM users LIMIT 0');
            echo json_encode(['status' => 'ok']);
        } catch (Throwable $error) {
            error_log('Healthcheck failed: ' . $error->getMessage());
            http_response_code(503);
            echo json_encode(['status' => 'unavailable']);
        }
    }
}
