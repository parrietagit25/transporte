<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ServiceRepository
{
    public function findPublished(): array
    {
        $sql = 'SELECT * FROM servicios WHERE activo = 1 ORDER BY orden ASC, id ASC';
        return Database::pdo()->query($sql)->fetchAll();
    }

    public function findAllAdmin(): array
    {
        $sql = 'SELECT * FROM servicios ORDER BY orden ASC, id ASC';
        return Database::pdo()->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM servicios WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM servicios WHERE slug = :s AND activo = 1 LIMIT 1');
        $stmt->execute(['s' => $slug]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function create(array $d): int
    {
        $sql = 'INSERT INTO servicios (titulo, slug, descripcion, beneficios, imagen, orden, activo)
                VALUES (:titulo, :slug, :descripcion, :beneficios, :imagen, :orden, :activo)';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'titulo' => $d['titulo'],
            'slug' => $d['slug'],
            'descripcion' => $d['descripcion'],
            'beneficios' => $d['beneficios'],
            'imagen' => $d['imagen'],
            'orden' => (int) $d['orden'],
            'activo' => (int) $d['activo'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $sql = 'UPDATE servicios SET titulo=:titulo, slug=:slug, descripcion=:descripcion, beneficios=:beneficios,
                imagen=:imagen, orden=:orden, activo=:activo WHERE id=:id';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'titulo' => $d['titulo'],
            'slug' => $d['slug'],
            'descripcion' => $d['descripcion'],
            'beneficios' => $d['beneficios'],
            'imagen' => $d['imagen'],
            'orden' => (int) $d['orden'],
            'activo' => (int) $d['activo'],
        ]);
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM servicios WHERE id = :id')->execute(['id' => $id]);
    }
}
