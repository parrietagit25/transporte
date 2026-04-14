<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class PostRepository
{
    public function findPublished(int $limit = 100, int $offset = 0): array
    {
        $sql = 'SELECT * FROM publicaciones WHERE estado = "publicado"
                ORDER BY destacado DESC, fecha_publicacion DESC, id DESC LIMIT :lim OFFSET :off';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findFeatured(int $n = 3): array
    {
        $sql = 'SELECT * FROM publicaciones WHERE estado = "publicado" AND destacado = 1
                ORDER BY fecha_publicacion DESC, id DESC LIMIT :n';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->bindValue('n', $n, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllAdmin(): array
    {
        $sql = 'SELECT * FROM publicaciones ORDER BY fecha_publicacion DESC, id DESC';
        return Database::pdo()->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM publicaciones WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT * FROM publicaciones WHERE slug = :s AND estado = "publicado" LIMIT 1'
        );
        $stmt->execute(['s' => $slug]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function create(array $d): int
    {
        $sql = 'INSERT INTO publicaciones (titulo, slug, resumen, contenido, imagen_destacada, fecha_publicacion, estado, destacado)
                VALUES (:titulo, :slug, :resumen, :contenido, :img, :fecha, :estado, :destacado)';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'titulo' => $d['titulo'],
            'slug' => $d['slug'],
            'resumen' => $d['resumen'],
            'contenido' => $d['contenido'],
            'img' => $d['imagen_destacada'],
            'fecha' => $d['fecha_publicacion'],
            'estado' => $d['estado'],
            'destacado' => (int) $d['destacado'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $sql = 'UPDATE publicaciones SET titulo=:titulo, slug=:slug, resumen=:resumen, contenido=:contenido,
                imagen_destacada=:img, fecha_publicacion=:fecha, estado=:estado, destacado=:destacado WHERE id=:id';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'titulo' => $d['titulo'],
            'slug' => $d['slug'],
            'resumen' => $d['resumen'],
            'contenido' => $d['contenido'],
            'img' => $d['imagen_destacada'],
            'fecha' => $d['fecha_publicacion'],
            'estado' => $d['estado'],
            'destacado' => (int) $d['destacado'],
        ]);
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM publicaciones WHERE id = :id')->execute(['id' => $id]);
    }
}
