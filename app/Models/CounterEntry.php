<?php

namespace App\Models;

use App\Core\Model;

class CounterEntry extends Model
{
    /**
     * Ajoute un comptage (+1). Pas de contrainte d'unicité : plusieurs entrées
     * pour le même item/jour sont autorisées. La suppression n'est pas exposée.
     */
    public function addCount(int $instanceId, int $itemId, int $userId, ?string $note = null): bool
    {
        $stmt = $this->db()->prepare(
            'INSERT INTO counter_entries (instance_id, item_id, user_id, note)
             VALUES (:instance_id, :item_id, :user_id, :note)'
        );
        return $stmt->execute([
            'instance_id' => $instanceId,
            'item_id'     => $itemId,
            'user_id'     => $userId,
            'note'        => $note,
        ]);
    }

    /** Nombre de comptages du jour par item → [item_id => count] */
    public function getDailyCountsMap(int $instanceId): array
    {
        $sql = 'SELECT item_id, COUNT(*) AS cnt
                FROM counter_entries
                WHERE instance_id = :instance_id
                GROUP BY item_id';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['instance_id' => $instanceId]);
        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[(int) $row['item_id']] = (int) $row['cnt'];
        }
        return $map;
    }

    /** Total global par item (toutes instances) → [item_id => count] */
    public function getTotalCountsMap(int $counterId): array
    {
        $sql = 'SELECT ce.item_id, COUNT(*) AS cnt
                FROM counter_entries ce
                INNER JOIN counter_instances ci ON ci.id = ce.instance_id
                WHERE ci.counter_id = :counter_id
                GROUP BY ce.item_id';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['counter_id' => $counterId]);
        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[(int) $row['item_id']] = (int) $row['cnt'];
        }
        return $map;
    }

    /** Toutes les entrées d'une instance avec le nom utilisateur */
    public function getEntriesByInstance(int $instanceId): array
    {
        $sql = 'SELECT ce.*, u.name AS user_name
                FROM counter_entries ce
                INNER JOIN users u ON u.id = ce.user_id
                WHERE ce.instance_id = :instance_id
                ORDER BY ce.counted_at ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['instance_id' => $instanceId]);
        return $stmt->fetchAll();
    }

    /** Historique détaillé (une ligne par entrée) */
    public function history(array $filters = []): array
    {
        $sql = 'SELECT ce.id,
                       ce.counted_at,
                       ce.note,
                       ci.date AS counter_date,
                       c.id AS counter_id,
                       c.name AS counter_name,
                       ci_item.id AS item_id,
                       ci_item.title AS item_title,
                       u.id AS user_id,
                       u.name AS user_name
                FROM counter_entries ce
                INNER JOIN counter_instances ci ON ci.id = ce.instance_id
                INNER JOIN counters c ON c.id = ci.counter_id
                INNER JOIN counter_items ci_item ON ci_item.id = ce.item_id
                INNER JOIN users u ON u.id = ce.user_id
                WHERE 1=1';

        $params = [];
        $this->applyCommonFilters($sql, $params, $filters);

        $sortMap = [
            'date'       => 'ci.date',
            'counter'    => 'c.name',
            'item'       => 'ci_item.title',
            'user'       => 'u.name',
            'counted_at' => 'ce.counted_at',
        ];

        $sortBy     = (string) ($filters['sort_by'] ?? 'counted_at');
        $sortColumn = $sortMap[$sortBy] ?? 'ce.counted_at';
        $sortDir    = strtolower((string) ($filters['sort_dir'] ?? 'desc')) === 'asc' ? 'ASC' : 'DESC';

        $sql .= ' ORDER BY ' . $sortColumn . ' ' . $sortDir . ', ce.id DESC';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Agrégat par item pour graphique en barres */
    public function countsByItem(array $filters = []): array
    {
        $sql = 'SELECT ci_item.title AS item_title, COUNT(*) AS total
                FROM counter_entries ce
                INNER JOIN counter_instances ci ON ci.id = ce.instance_id
                INNER JOIN counters c ON c.id = ci.counter_id
                INNER JOIN counter_items ci_item ON ci_item.id = ce.item_id
                INNER JOIN users u ON u.id = ce.user_id
                WHERE 1=1';
        $params = [];
        $this->applyCommonFilters($sql, $params, $filters);
        $sql .= ' GROUP BY ci_item.id, ci_item.title ORDER BY total DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Agrégat par jour pour graphique en ligne */
    public function countsByDay(array $filters = []): array
    {
        $sql = 'SELECT ci.date AS day, COUNT(*) AS total
                FROM counter_entries ce
                INNER JOIN counter_instances ci ON ci.id = ce.instance_id
                INNER JOIN counters c ON c.id = ci.counter_id
                INNER JOIN counter_items ci_item ON ci_item.id = ce.item_id
                INNER JOIN users u ON u.id = ce.user_id
                WHERE 1=1';
        $params = [];
        $this->applyCommonFilters($sql, $params, $filters);
        $sql .= ' GROUP BY ci.date ORDER BY ci.date ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Agrégat par utilisateur pour graphique donut */
    public function countsByUser(array $filters = []): array
    {
        $sql = 'SELECT u.name AS user_name, COUNT(*) AS total
                FROM counter_entries ce
                INNER JOIN counter_instances ci ON ci.id = ce.instance_id
                INNER JOIN counters c ON c.id = ci.counter_id
                INNER JOIN counter_items ci_item ON ci_item.id = ce.item_id
                INNER JOIN users u ON u.id = ce.user_id
                WHERE 1=1';
        $params = [];
        $this->applyCommonFilters($sql, $params, $filters);
        $sql .= ' GROUP BY u.id, u.name ORDER BY total DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function applyCommonFilters(string &$sql, array &$params, array $filters): void
    {
        if (!empty($filters['user_id'])) {
            $sql .= ' AND u.id = :user_id';
            $params['user_id'] = (int) $filters['user_id'];
        }
        if (!empty($filters['counter_id'])) {
            $sql .= ' AND c.id = :counter_id';
            $params['counter_id'] = (int) $filters['counter_id'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= ' AND ci.date >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= ' AND ci.date <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }
    }
}
