<?php

namespace App\Core;

class Response
{
    public function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
