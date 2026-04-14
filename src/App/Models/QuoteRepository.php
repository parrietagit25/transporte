<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class QuoteRepository
{
    /** @return array<int, array<string, mixed>> */
    public function search(?string $q, ?string $estado, int $limit = 200): array
    {
        $sql = 'SELECT * FROM cotizaciones WHERE 1=1';
        $params = [];
        if ($estado !== null && $estado !== '') {
            $sql .= ' AND estado = :e';
            $params['e'] = $estado;
        }
        if ($q !== null && $q !== '') {
            $sql .= ' AND (cliente_nombre LIKE :q OR cliente_empresa LIKE :q2 OR tipo_servicio LIKE :q3)';
            $like = '%' . $q . '%';
            $params['q'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
        }
        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int) $limit;
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM cotizaciones WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function create(array $d): int
    {
        $sql = 'INSERT INTO cotizaciones (
            cliente_nombre, cliente_empresa, cliente_telefono, cliente_correo, tipo_servicio,
            origen, destino, tipo_carga, peso_estimado, dimensiones, fecha_requerida, observaciones,
            subtotal_sin_iva, otros_cargos, iva_pct, iva_monto, total, estado
        ) VALUES (
            :n, :emp, :tel, :mail, :srv, :orig, :dest, :tc, :peso, :dim, :freq, :obs,
            :sub, :otros, :ivap, :ivam, :tot, :est
        )';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'n' => $d['cliente_nombre'],
            'emp' => $d['cliente_empresa'],
            'tel' => $d['cliente_telefono'],
            'mail' => $d['cliente_correo'],
            'srv' => $d['tipo_servicio'],
            'orig' => $d['origen'],
            'dest' => $d['destino'],
            'tc' => $d['tipo_carga'],
            'peso' => $d['peso_estimado'],
            'dim' => $d['dimensiones'],
            'freq' => $d['fecha_requerida'] ?: null,
            'obs' => $d['observaciones'],
            'sub' => $d['subtotal_sin_iva'],
            'otros' => $d['otros_cargos'],
            'ivap' => $d['iva_pct'],
            'ivam' => $d['iva_monto'],
            'tot' => $d['total'],
            'est' => $d['estado'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        $sql = 'UPDATE cotizaciones SET
            cliente_nombre=:n, cliente_empresa=:emp, cliente_telefono=:tel, cliente_correo=:mail,
            tipo_servicio=:srv, origen=:orig, destino=:dest, tipo_carga=:tc, peso_estimado=:peso,
            dimensiones=:dim, fecha_requerida=:freq, observaciones=:obs,
            subtotal_sin_iva=:sub, otros_cargos=:otros, iva_pct=:ivap, iva_monto=:ivam, total=:tot,
            estado=:est
            WHERE id=:id';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'n' => $d['cliente_nombre'],
            'emp' => $d['cliente_empresa'],
            'tel' => $d['cliente_telefono'],
            'mail' => $d['cliente_correo'],
            'srv' => $d['tipo_servicio'],
            'orig' => $d['origen'],
            'dest' => $d['destino'],
            'tc' => $d['tipo_carga'],
            'peso' => $d['peso_estimado'],
            'dim' => $d['dimensiones'],
            'freq' => $d['fecha_requerida'] ?: null,
            'obs' => $d['observaciones'],
            'sub' => $d['subtotal_sin_iva'],
            'otros' => $d['otros_cargos'],
            'ivap' => $d['iva_pct'],
            'ivam' => $d['iva_monto'],
            'tot' => $d['total'],
            'est' => $d['estado'],
        ]);
    }

    public function updateEstado(int $id, string $estado): void
    {
        Database::pdo()->prepare('UPDATE cotizaciones SET estado = :e WHERE id = :id')->execute([
            'e' => $estado,
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM cotizaciones WHERE id = :id')->execute(['id' => $id]);
    }
}
