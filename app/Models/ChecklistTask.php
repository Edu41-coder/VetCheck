<?php

namespace App\Models;

use App\Core\Model;

class ChecklistTask extends Model
{
    public function findByChecklistId(int $checklistId): array
    {
        $sql = 'SELECT t.*, s.title AS section_title
                FROM checklist_tasks t
                LEFT JOIN checklist_sections s ON s.id = t.section_id
                WHERE t.checklist_id = :checklist_id
                ORDER BY COALESCE(s.sort_order, 0) ASC, s.id ASC, t.sort_order ASC, t.id ASC';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['checklist_id' => $checklistId]);

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO checklist_tasks (checklist_id, section_id, sort_order, title, description)
             VALUES (:checklist_id, :section_id, :sort_order, :title, :description)'
        );
        $stmt->execute([
            'checklist_id' => $data['checklist_id'],
            'section_id' => $data['section_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM checklist_tasks WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $task = $stmt->fetch();
        return $task ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare(
            'UPDATE checklist_tasks
             SET section_id = :section_id,
                 sort_order = :sort_order,
                 title = :title,
                 description = :description
             WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'section_id' => $data['section_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare('DELETE FROM checklist_tasks WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
