<?php

namespace App\Models;

use App\Core\Model;

class CounterItem extends Model
{
    public function findByCounterId(int $counterId): array
    {
        $sql = 'SELECT i.*, s.title AS section_title
                FROM counter_items i
                LEFT JOIN counter_sections s ON s.id = i.section_id
                WHERE i.counter_id = :counter_id
                ORDER BY COALESCE(s.sort_order, 0) ASC, s.id ASC, i.sort_order ASC, i.id ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['counter_id' => $counterId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM counter_items WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO counter_items (counter_id, section_id, sort_order, title, description)
             VALUES (:counter_id, :section_id, :sort_order, :title, :description)'
        );
        $stmt->execute([
            'counter_id' => $data['counter_id'],
            'section_id' => $data['section_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'title'      => $data['title'],
            'description'=> $data['description'] ?? null,
        ]);
        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare(
            'UPDATE counter_items
             SET section_id = :section_id,
                 sort_order = :sort_order,
                 title = :title,
                 description = :description
             WHERE id = :id'
        );
        return $stmt->execute([
            'id'         => $id,
            'section_id' => $data['section_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'title'      => $data['title'],
            'description'=> $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare('DELETE FROM counter_items WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
