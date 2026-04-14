<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private const SESSION_KEY = 'admin_user_id';

    public static function attempt(string $username, string $password): bool
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, password_hash, activo FROM usuarios_admin WHERE username = :u LIMIT 1'
        );
        $stmt->execute(['u' => $username]);
        $row = $stmt->fetch();
        if (!$row || !(int) $row['activo']) {
            return false;
        }
        if (!password_verify($password, $row['password_hash'])) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = (int) $row['id'];
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]) && (int) $_SESSION[self::SESSION_KEY] > 0;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION[self::SESSION_KEY] : null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect(public_url('admin/index.php?route=login'));
        }
    }
}
