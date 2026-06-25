<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function all(): array
    {
        $sql = 'SELECT u.*, r.name AS role_name
                FROM users u
                INNER JOIN roles r ON r.id = u.role_id
                ORDER BY u.id DESC';

        return $this->db()->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT u.*, r.name AS role_name
                FROM users u
                INNER JOIN roles r ON r.id = u.role_id
                WHERE u.id = :id
                LIMIT 1';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $id]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT u.*, r.name AS role_name
                FROM users u
                INNER JOIN roles r ON r.id = u.role_id
                WHERE u.email = :email
                LIMIT 1';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password_hash, role_id, is_admin, avatar_path)
            VALUES (:name, :email, :password_hash, :role_id, :is_admin, :avatar_path)';

        $stmt = $this->db()->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role_id' => $data['role_id'],
            'is_admin' => $data['is_admin'] ?? 0,
            'avatar_path' => $data['avatar_path'] ?? null,
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE users
                SET name = :name,
                    email = :email,
                    role_id = :role_id,
                    is_admin = :is_admin,
                    avatar_path = :avatar_path
                WHERE id = :id';

        $stmt = $this->db()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
            'is_admin' => $data['is_admin'] ?? 0,
            'avatar_path' => $data['avatar_path'] ?? null,
        ]);
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = $this->db()->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'password_hash' => $passwordHash,
        ]);
    }
}
