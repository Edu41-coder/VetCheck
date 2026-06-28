<?php

namespace App\Models;

use App\Core\Model;

class CounterInstance extends Model
{
    public function findByCounterAndDate(int $counterId, string $date): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT * FROM counter_instances
             WHERE counter_id = :counter_id AND date = :date LIMIT 1'
        );
        $stmt->execute([
            'counter_id' => $counterId,
            'date'       => $date,
        ]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getOrCreate(int $counterId, string $date, ?int $createdBy): array
    {
        $instance = $this->findByCounterAndDate($counterId, $date);
        if ($instance) {
            return $instance;
        }

        $stmt = $this->db()->prepare(
            'INSERT INTO counter_instances (counter_id, date, created_by)
             VALUES (:counter_id, :date, :created_by)'
        );
        $stmt->execute([
            'counter_id' => $counterId,
            'date'       => $date,
            'created_by' => $createdBy,
        ]);

        $id = (int) $this->db()->lastInsertId();
        return $this->findById($id) ?? [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare(
            'SELECT * FROM counter_instances WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
