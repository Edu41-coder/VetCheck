<?php

namespace App\Models;

use App\Core\Model;

class TaskCheck extends Model
{
    public function getChecksByInstance(int $instanceId): array
    {
        $sql = 'SELECT tc.*, u.name AS user_name
                FROM task_checks tc
                INNER JOIN users u ON u.id = tc.user_id
                WHERE tc.instance_id = :instance_id
                ORDER BY tc.checked_at ASC';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['instance_id' => $instanceId]);

        return $stmt->fetchAll();
    }

    public function getChecksMapByInstance(int $instanceId): array
    {
        $rows = $this->getChecksByInstance($instanceId);
        $map = [];

        foreach ($rows as $row) {
            $taskId = (int) $row['task_id'];
            if (!isset($map[$taskId])) {
                $map[$taskId] = $row;
            }
        }

        return $map;
    }

    public function isTaskAlreadyChecked(int $instanceId, int $taskId): bool
    {
        $stmt = $this->db()->prepare(
            'SELECT id FROM task_checks WHERE instance_id = :instance_id AND task_id = :task_id LIMIT 1'
        );
        $stmt->execute([
            'instance_id' => $instanceId,
            'task_id' => $taskId,
        ]);

        return (bool) $stmt->fetch();
    }

    public function checkTask(int $instanceId, int $taskId, int $userId, ?string $note = null): bool
    {
        if ($this->isTaskAlreadyChecked($instanceId, $taskId)) {
            return false;
        }

        $stmt = $this->db()->prepare(
            'INSERT INTO task_checks (instance_id, task_id, user_id, note) VALUES (:instance_id, :task_id, :user_id, :note)'
        );

        return $stmt->execute([
            'instance_id' => $instanceId,
            'task_id' => $taskId,
            'user_id' => $userId,
            'note' => $note,
        ]);
    }

    public function history(array $filters = []): array
    {
        $sql = 'SELECT tc.id,
                       tc.checked_at,
                       tc.note,
                       ci.date AS checklist_date,
                       c.id AS checklist_id,
                       c.name AS checklist_name,
                       t.id AS task_id,
                       t.title AS task_title,
                       u.id AS user_id,
                       u.name AS user_name
                FROM task_checks tc
                INNER JOIN checklist_instances ci ON ci.id = tc.instance_id
                INNER JOIN checklists c ON c.id = ci.checklist_id
                INNER JOIN checklist_tasks t ON t.id = tc.task_id
                INNER JOIN users u ON u.id = tc.user_id
                WHERE 1=1';

        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= ' AND u.id = :user_id';
            $params['user_id'] = (int) $filters['user_id'];
        }

        if (!empty($filters['checklist_id'])) {
            $sql .= ' AND c.id = :checklist_id';
            $params['checklist_id'] = (int) $filters['checklist_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= ' AND ci.date >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= ' AND ci.date <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $sortMap = [
            'date' => 'ci.date',
            'checklist' => 'c.name',
            'task' => 't.title',
            'user' => 'u.name',
            'checked_at' => 'tc.checked_at',
        ];

        $sortBy = (string) ($filters['sort_by'] ?? 'checked_at');
        $sortColumn = $sortMap[$sortBy] ?? 'tc.checked_at';

        $sortDir = strtolower((string) ($filters['sort_dir'] ?? 'desc'));
        $sortDir = $sortDir === 'asc' ? 'ASC' : 'DESC';

        $sql .= ' ORDER BY ' . $sortColumn . ' ' . $sortDir . ', tc.id DESC';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
