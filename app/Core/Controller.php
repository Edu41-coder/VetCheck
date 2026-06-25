<?php

namespace App\Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $viewFile);
        }

        require __DIR__ . '/../Views/layouts/main.php';
    }
}
