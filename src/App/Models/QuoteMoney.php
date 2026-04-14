<?php

declare(strict_types=1);

namespace App\Models;

/** Cálculo de IVA y total para cotizaciones (COP). */
final class QuoteMoney
{
    /**
     * @param array<string, mixed> $post Datos de $_POST del formulario
     * @return array{subtotal_sin_iva: ?float, otros_cargos: float, iva_pct: float, iva_monto: float, total: ?float}
     */
    public static function fromPost(array $post): array
    {
        $sub = parse_money_input($post['subtotal_sin_iva'] ?? null);
        $otros = parse_money_input($post['otros_cargos'] ?? null);
        if ($otros === null) {
            $otros = 0.0;
        }
        $ivaPct = round((float) ($post['iva_pct'] ?? 19), 2);
        if ($ivaPct < 0.0) {
            $ivaPct = 0.0;
        }
        if ($ivaPct > 100.0) {
            $ivaPct = 100.0;
        }
        if ($sub === null) {
            return [
                'subtotal_sin_iva' => null,
                'otros_cargos' => max(0.0, $otros),
                'iva_pct' => $ivaPct,
                'iva_monto' => 0.0,
                'total' => null,
            ];
        }
        $sub = max(0.0, $sub);
        $base = $sub + max(0.0, $otros);
        $iva = round($base * ($ivaPct / 100.0), 2);
        $total = round($base + $iva, 2);

        return [
            'subtotal_sin_iva' => $sub,
            'otros_cargos' => max(0.0, $otros),
            'iva_pct' => $ivaPct,
            'iva_monto' => $iva,
            'total' => $total,
        ];
    }
}
