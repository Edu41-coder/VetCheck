<?php

namespace App\Models;

use App\Core\Model;

class ChecklistInstance extends Model
{
    public function findByChecklistAndDate(int $checklistId, string $date): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT * FROM checklist_instances WHERE checklist_id = :checklist_id AND date = :date LIMIT 1'
        );
        $stmt->execute([
            'checklist_id' => $checklistId,
            'date' => $date,
        ]);

        $instance = $stmt->fetch();
        return $instance ?: null;
    }

    public function getOrCreate(int $checklistId, string $date, ?int $createdBy): array
    {
        $instance = $this->findByChecklistAndDate($checklistId, $date);
        if ($instance) {
            return $instance;
        }

        $stmt = $this->db()->prepare(
            'INSERT INTO checklist_instances (checklist_id, date, created_by) VALUES (:checklist_id, :date, :created_by)'
        );
        $stmt->execute([
            'checklist_id' => $checklistId,
            'date' => $date,
            'created_by' => $createdBy,
        ]);

        $id = (int) $this->db()->lastInsertId();
        return $this->findById($id) ?? [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM checklist_instances WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $instance = $stmt->fetch();
        return $instance ?: null;
    }
}
