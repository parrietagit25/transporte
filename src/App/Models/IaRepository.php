<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class IaRepository
{
    public function createSession(?string $ip, ?string $userAgent): string
    {
        $token = $this->randomUuid();
        $stmt = Database::pdo()->prepare(
            'INSERT INTO ia_chat_sesiones (public_token, ip, user_agent) VALUES (:t, :ip, :ua)'
        );
        $stmt->execute([
            't' => $token,
            'ip' => $ip,
            'ua' => $userAgent !== null && strlen($userAgent) > 255 ? substr($userAgent, 0, 255) : $userAgent,
        ]);
        return $token;
    }

    public function findSessionIdByToken(string $token): ?int
    {
        if (strlen($token) !== 36) {
            return null;
        }
        $stmt = Database::pdo()->prepare('SELECT id FROM ia_chat_sesiones WHERE public_token = :t LIMIT 1');
        $stmt->execute(['t' => $token]);
        $r = $stmt->fetch();
        return $r ? (int) $r['id'] : null;
    }

    public function addMessage(int $sesionId, string $rol, string $contenido): void
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO ia_chat_mensajes (sesion_id, rol, contenido) VALUES (:sid, :rol, :c)'
        );
        $stmt->execute(['sid' => $sesionId, 'rol' => $rol, 'c' => $contenido]);
    }

    /** @return list<array{rol:string,contenido:string}> */
    public function messagesForOpenAi(int $sesionId, int $maxMessages = 30): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT rol, contenido FROM ia_chat_mensajes
             WHERE sesion_id = :sid AND rol IN (\'user\',\'assistant\')
             ORDER BY id DESC LIMIT :lim'
        );
        $stmt->bindValue(':sid', $sesionId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $maxMessages, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return array_reverse(array_map(static fn (array $r): array => [
            'rol' => (string) $r['rol'],
            'contenido' => (string) $r['contenido'],
        ], $rows));
    }

    public function insertReunion(
        int $sesionId,
        string $nombre,
        string $email,
        string $telefono,
        string $fechaHoraMysql,
        string $motivo
    ): int {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO ia_reuniones_solicitadas (sesion_id, nombre_contacto, email, telefono, fecha_hora, motivo)
             VALUES (:sid, :nom, :em, :tel, :fh, :mot)'
        );
        $stmt->execute([
            'sid' => $sesionId,
            'nom' => $nombre,
            'em' => $email,
            'tel' => $telefono,
            'fh' => $fechaHoraMysql,
            'mot' => $motivo,
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    /** @return list<array<string, mixed>> */
    public function adminListSessions(int $limit = 80): array
    {
        $lim = max(1, min(200, $limit));
        $sql = "SELECT s.id, s.public_token, s.ip, s.created_at,
                (SELECT COUNT(*) FROM ia_chat_mensajes m WHERE m.sesion_id = s.id) AS n_mensajes,
                (SELECT COUNT(*) FROM ia_reuniones_solicitadas r WHERE r.sesion_id = s.id) AS n_reuniones,
                (SELECT contenido FROM ia_chat_mensajes m2 WHERE m2.sesion_id = s.id AND m2.rol = 'user' ORDER BY m2.id ASC LIMIT 1) AS primer_usuario
                FROM ia_chat_sesiones s
                ORDER BY s.id DESC
                LIMIT {$lim}";
        return Database::pdo()->query($sql)->fetchAll();
    }

    /** @return list<array<string, mixed>> */
    public function adminMessages(int $sesionId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, rol, contenido, created_at FROM ia_chat_mensajes WHERE sesion_id = :sid ORDER BY id ASC'
        );
        $stmt->execute(['sid' => $sesionId]);
        return $stmt->fetchAll();
    }

    /** @return list<array<string, mixed>> */
    public function adminListReuniones(int $limit = 100): array
    {
        $lim = max(1, min(200, $limit));
        $sql = "SELECT r.*, s.public_token
                FROM ia_reuniones_solicitadas r
                INNER JOIN ia_chat_sesiones s ON s.id = r.sesion_id
                ORDER BY r.id DESC
                LIMIT {$lim}";
        return Database::pdo()->query($sql)->fetchAll();
    }

    private function randomUuid(): string
    {
        $b = random_bytes(16);
        $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
        $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
        $h = bin2hex($b);
        return substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-' . substr($h, 12, 4)
            . '-' . substr($h, 16, 4) . '-' . substr($h, 20, 12);
    }
}
