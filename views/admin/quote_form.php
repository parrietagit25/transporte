<?php /** @var ?array<string, mixed> $row */ ?>
<?php $isEdit = $row !== null; ?>
<?php
$estados = [
    'pendiente' => 'Pendiente',
    'en_revision' => 'En revisión',
    'enviada' => 'Enviada',
    'aprobada' => 'Aprobada',
    'rechazada' => 'Rechazada',
    'cerrada' => 'Cerrada',
];
?>
<form method="post" action="<?= e(admin_url(['route' => 'cotizacion_guardar'])) ?>" class="card border-0 shadow-sm">
  <div class="card-body p-4">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $isEdit ? (int) $row['id'] : 0 ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Nombre del cliente</label>
        <input class="form-control" name="cliente_nombre" required value="<?= e((string) ($row['cliente_nombre'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Empresa</label>
        <input class="form-control" name="cliente_empresa" value="<?= e((string) ($row['cliente_empresa'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Estado</label>
        <select class="form-select" name="estado">
          <?php foreach ($estados as $k => $lab): ?>
            <option value="<?= e($k) ?>" <?= ($row['estado'] ?? 'pendiente') === $k ? 'selected' : '' ?>><?= e($lab) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input class="form-control" name="cliente_telefono" required value="<?= e((string) ($row['cliente_telefono'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Correo</label>
        <input class="form-control" type="email" name="cliente_correo" required value="<?= e((string) ($row['cliente_correo'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Tipo de servicio</label>
        <input class="form-control" name="tipo_servicio" required value="<?= e((string) ($row['tipo_servicio'] ?? '')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Origen</label>
        <input class="form-control" name="origen" required value="<?= e((string) ($row['origen'] ?? '')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Destino</label>
        <input class="form-control" name="destino" required value="<?= e((string) ($row['destino'] ?? '')) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tipo de carga</label>
        <input class="form-control" name="tipo_carga" required value="<?= e((string) ($row['tipo_carga'] ?? '')) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Peso estimado</label>
        <input class="form-control" name="peso_estimado" value="<?= e((string) ($row['peso_estimado'] ?? '')) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Dimensiones</label>
        <input class="form-control" name="dimensiones" value="<?= e((string) ($row['dimensiones'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha requerida</label>
        <input class="form-control" type="date" name="fecha_requerida" value="<?= e((string) ($row['fecha_requerida'] ?? '')) ?>">
      </div>
      <div class="col-12">
        <hr class="text-muted">
        <h3 class="h6 text-uppercase text-muted mb-3">Montos (COP)</h3>
      </div>
      <div class="col-md-4">
        <label class="form-label">Subtotal antes de IVA</label>
        <input class="form-control" type="number" name="subtotal_sin_iva" step="0.01" min="0" placeholder="0"
          value="<?= $row !== null && array_key_exists('subtotal_sin_iva', $row) && $row['subtotal_sin_iva'] !== null ? e((string) (float) $row['subtotal_sin_iva']) : '' ?>">
        <div class="form-text">Servicio principal u operación (sin IVA).</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Otros cargos</label>
        <input class="form-control" type="number" name="otros_cargos" step="0.01" min="0" placeholder="0"
          value="<?= e((string) (float) ($row['otros_cargos'] ?? 0)) ?>">
        <div class="form-text">Peajes, escolta, almacenaje, etc.</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">% IVA</label>
        <input class="form-control" type="number" name="iva_pct" step="0.01" min="0" max="100" placeholder="19"
          value="<?= e((string) (float) ($row['iva_pct'] ?? 19)) ?>">
        <div class="form-text">Por defecto 19 %. Se recalcula al guardar.</div>
      </div>
      <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea class="form-control" name="observaciones" rows="4"><?= e((string) ($row['observaciones'] ?? '')) ?></textarea>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-between py-3">
    <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'cotizaciones'])) ?>">Volver al listado</a>
    <button class="btn btn-primary px-4" type="submit">Guardar</button>
  </div>
</form>
