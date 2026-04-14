<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ConfigRepository
{
    public function all(): array
    {
        $stmt = Database::pdo()->query('SELECT clave, valor FROM configuracion_web');
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return is_array($rows) ? $rows : [];
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $stmt = Database::pdo()->prepare('SELECT valor FROM configuracion_web WHERE clave = :k LIMIT 1');
        $stmt->execute(['k' => $key]);
        $v = $stmt->fetchColumn();
        return $v !== false ? (string) $v : $default;
    }

    public function set(string $key, string $value): void
    {
        $sql = 'INSERT INTO configuracion_web (clave, valor) VALUES (:k, :v)
                ON DUPLICATE KEY UPDATE valor = VALUES(valor), updated_at = CURRENT_TIMESTAMP';
        Database::pdo()->prepare($sql)->execute(['k' => $key, 'v' => $value]);
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $k => $v) {
            $this->set((string) $k, (string) $v);
        }
    }
}
