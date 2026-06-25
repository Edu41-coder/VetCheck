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
}
