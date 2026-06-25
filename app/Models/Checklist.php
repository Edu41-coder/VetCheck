<?php

namespace App\Models;

use App\Core\Model;

class Checklist extends Model
{
    public function all(): array
    {
        $stmt = $this->db()->query('SELECT * FROM checklists ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM checklists WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $checklist = $stmt->fetch();
        return $checklist ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO checklists (slug, name, description) VALUES (:slug, :name, :description)'
        );
        $stmt->execute([
            'slug' => $data['slug'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare(
            'UPDATE checklists SET slug = :slug, name = :name, description = :description WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'slug' => $data['slug'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare('DELETE FROM checklists WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
