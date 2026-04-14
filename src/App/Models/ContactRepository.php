<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ContactRepository
{
    public function create(array $d): int
    {
        $sql = 'INSERT INTO contactos_recibidos (nombre, email, telefono, asunto, mensaje, leido, ip)
                VALUES (:n, :e, :t, :a, :m, 0, :ip)';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'n' => $d['nombre'],
            'e' => $d['email'],
            't' => $d['telefono'],
            'a' => $d['asunto'],
            'm' => $d['mensaje'],
            'ip' => $d['ip'] ?? null,
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public function findAllAdmin(int $limit = 300): array
    {
        $sql = 'SELECT * FROM contactos_recibidos ORDER BY created_at DESC LIMIT ' . (int) $limit;
        return Database::pdo()->query($sql)->fetchAll();
    }

    public function markRead(int $id): void
    {
        Database::pdo()->prepare('UPDATE contactos_recibidos SET leido = 1 WHERE id = :id')->execute(['id' => $id]);
    }

    public function unreadCount(): int
    {
        $n = Database::pdo()->query('SELECT COUNT(*) FROM contactos_recibidos WHERE leido = 0')->fetchColumn();
        return (int) $n;
    }
}
