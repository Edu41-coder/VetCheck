<?php

namespace App\Models;

use App\Core\Model;

class CounterSection extends Model
{
    public function findByCounterId(int $counterId): array
    {
        $stmt = $this->db()->prepare(
            'SELECT * FROM counter_sections
             WHERE counter_id = :counter_id
             ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['counter_id' => $counterId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM counter_sections WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO counter_sections (counter_id, title, sort_order)
             VALUES (:counter_id, :title, :sort_order)'
        );
        $stmt->execute([
            'counter_id' => $data['counter_id'],
            'title'      => $data['title'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare(
            'UPDATE counter_sections SET title = :title, sort_order = :sort_order WHERE id = :id'
        );
        return $stmt->execute([
            'id'         => $id,
            'title'      => $data['title'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare('DELETE FROM counter_sections WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
