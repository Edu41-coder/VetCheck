<?php

namespace App\Models;

use App\Core\Model;

class ChecklistSection extends Model
{
    public function findByChecklistId(int $checklistId): array
    {
        $stmt = $this->db()->prepare(
            'SELECT * FROM checklist_sections WHERE checklist_id = :checklist_id ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['checklist_id' => $checklistId]);

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO checklist_sections (checklist_id, title, sort_order) VALUES (:checklist_id, :title, :sort_order)'
        );
        $stmt->execute([
            'checklist_id' => $data['checklist_id'],
            'title' => $data['title'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return (int) $this->db()->lastInsertId();
    }
}
