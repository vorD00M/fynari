<?php

namespace core;

class Controller
{
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function input(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
