<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Role extends Model
{
    public function all(): array
    {
        $stmt = $this->db()->query('SELECT * FROM roles ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function findByName(string $name): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM roles WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $role = $stmt->fetch();

        return $role ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM roles WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $role = $stmt->fetch();

        return $role ?: null;
    }
}
