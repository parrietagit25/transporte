<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class UserRepository
{
    public function updatePassword(int $id, string $hash): void
    {
        Database::pdo()->prepare('UPDATE usuarios_admin SET password_hash = :h WHERE id = :id')->execute([
            'h' => $hash,
            'id' => $id,
        ]);
    }
}
