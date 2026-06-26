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

    public function allBusinessRoles(): array
    {
        $stmt = $this->db()->query("SELECT * FROM roles WHERE name IN ('veto', 'asv') ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function isBusinessRoleId(int $id): bool
    {
        $stmt = $this->db()->prepare("SELECT id FROM roles WHERE id = :id AND name IN ('veto', 'asv') LIMIT 1");
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetch();
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
