<?php /** @var array<string, mixed> $row */ ?>
<?php
$labels = [
    'pendiente' => 'Pendiente', 'en_revision' => 'En revisión', 'enviada' => 'Enviada',
    'aprobada' => 'Aprobada', 'rechazada' => 'Rechazada', 'cerrada' => 'Cerrada',
];
$badgeClass = [
    'pendiente' => 'text-bg-warning',
    'en_revision' => 'text-bg-info',
    'enviada' => 'text-bg-primary',
    'aprobada' => 'text-bg-success',
    'rechazada' => 'text-bg-danger',
    'cerrada' => 'text-bg-secondary',
];
$est = (string) ($row['estado'] ?? '');
$badge = $badgeClass[$est] ?? 'text-bg-secondary';
$sub = isset($row['subtotal_sin_iva']) && $row['subtotal_sin_iva'] !== null ? (float) $row['subtotal_sin_iva'] : null;
$otros = isset($row['otros_cargos']) ? (float) $row['otros_cargos'] : 0.0;
$ivaPct = isset($row['iva_pct']) ? (float) $row['iva_pct'] : 19.0;
$ivaMonto = isset($row['iva_monto']) ? (float) $row['iva_monto'] : 0.0;
$total = isset($row['total']) && $row['total'] !== null ? (float) $row['total'] : null;
$baseImponible = $sub !== null ? $sub + $otros : null;
?>
<div class="row g-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
          <div>
            <p class="text-muted small mb-1">Cotización #<?= (int) $row['id'] ?></p>
            <h2 class="h4 mb-0"><?= e((string) $row['cliente_nombre']) ?></h2>
            <p class="text-secondary mb-0"><?= e((string) ($row['cliente_empresa'] ?? '')) ?></p>
          </div>
          <span class="badge <?= e($badge) ?> fs-6"><?= e($labels[$est] ?? $est) ?></span>
        </div>
        <hr>
        <div class="row g-3 small">
          <div class="col-md-6"><strong>Teléfono</strong><br><?= e((string) $row['cliente_telefono']) ?></div>
          <div class="col-md-6"><strong>Correo</strong><br><?= e((string) $row['cliente_correo']) ?></div>
          <div class="col-md-6"><strong>Tipo de servicio</strong><br><?= e((string) $row['tipo_servicio']) ?></div>
          <div class="col-md-6"><strong>Fecha requerida</strong><br><?= e((string) ($row['fecha_requerida'] ?? '—')) ?></div>
          <div class="col-md-6"><strong>Origen</strong><br><?= e((string) $row['origen']) ?></div>
          <div class="col-md-6"><strong>Destino</strong><br><?= e((string) $row['destino']) ?></div>
          <div class="col-md-6"><strong>Tipo de carga</strong><br><?= e((string) $row['tipo_carga']) ?></div>
          <div class="col-md-6"><strong>Peso estimado</strong><br><?= e((string) ($row['peso_estimado'] ?? '—')) ?></div>
          <div class="col-12"><strong>Dimensiones</strong><br><?= e((string) ($row['dimensiones'] ?? '—')) ?></div>
          <div class="col-12"><strong>Observaciones</strong><br><?= nl2br(e((string) ($row['observaciones'] ?? ''))) ?></div>
          <div class="col-md-6 text-muted"><strong>Creada</strong><br><?= e((string) $row['created_at']) ?></div>
          <div class="col-md-6 text-muted"><strong>Actualizada</strong><br><?= e((string) ($row['updated_at'] ?? '—')) ?></div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
      <div class="card-body p-4">
        <h3 class="h5 mb-3">Montos (COP)</h3>
        <?php if ($sub === null): ?>
          <p class="text-secondary small mb-0">Sin valores monetarios registrados. Edite la cotización para ingresar subtotal, otros cargos e IVA.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
              <tbody>
                <tr>
                  <td class="text-muted">Subtotal (antes de IVA)</td>
                  <td class="text-end fw-semibold"><?= e(format_cop($sub)) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Otros cargos</td>
                  <td class="text-end"><?= e(format_cop($otros)) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Base imponible</td>
                  <td class="text-end"><?= e(format_cop($baseImponible)) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">IVA (<?= e((string) $ivaPct) ?> %)</td>
                  <td class="text-end"><?= e(format_cop($ivaMonto)) ?></td>
                </tr>
                <tr class="table-light">
                  <td class="fw-bold">Total</td>
                  <td class="text-end fw-bold fs-5 text-primary"><?= e(format_cop($total)) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h3 class="h6">Cambiar estado</h3>
        <form method="post" action="<?= e(admin_url(['route' => 'cotizacion_estado'])) ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
          <select class="form-select mb-2" name="estado">
            <?php foreach ($labels as $k => $lab): ?>
              <option value="<?= e($k) ?>" <?= $est === $k ? 'selected' : '' ?>><?= e($lab) ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-primary w-100" type="submit">Actualizar estado</button>
        </form>
      </div>
    </div>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h3 class="h6 mb-3">Documentos y envío</h3>
        <div class="d-grid gap-2">
          <button type="button" class="btn btn-outline-secondary" disabled title="Próximamente disponible">
            <i class="bi bi-file-earmark-pdf me-2"></i>Generar PDF
          </button>
          <button type="button" class="btn btn-outline-secondary" disabled title="Próximamente disponible">
            <i class="bi bi-envelope me-2"></i>Enviar por correo
          </button>
        </div>
        <p class="small text-muted mb-0 mt-2">Estas acciones se habilitarán cuando se integre el módulo de PDF y envío.</p>
      </div>
    </div>
    <div class="d-grid gap-2">
      <a class="btn btn-outline-primary" href="<?= e(admin_url(['route' => 'cotizacion_editar', 'id' => (int) $row['id']])) ?>">Editar cotización</a>
      <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'cotizaciones'])) ?>">Volver al listado</a>
    </div>
  </div>
</div>
