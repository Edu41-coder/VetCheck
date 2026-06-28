<?php

namespace App\Models;

use App\Core\Model;

class Counter extends Model
{
    public function all(): array
    {
        $stmt = $this->db()->query('SELECT * FROM counters ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM counters WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO counters (slug, name, description, event_label)
             VALUES (:slug, :name, :description, :event_label)'
        );
        $stmt->execute([
            'slug'        => $data['slug'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'event_label' => $data['event_label'] ?? 'Événement',
        ]);
        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare(
            'UPDATE counters
             SET slug = :slug, name = :name, description = :description, event_label = :event_label
             WHERE id = :id'
        );
        return $stmt->execute([
            'id'          => $id,
            'slug'        => $data['slug'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'event_label' => $data['event_label'] ?? 'Événement',
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare('DELETE FROM counters WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
