<?php
/** @var array<int, array<string, mixed>> $items */
/** @var string $q */
/** @var string $estado */
$estados = [
    '' => 'Todos',
    'pendiente' => 'Pendiente',
    'en_revision' => 'En revisión',
    'enviada' => 'Enviada',
    'aprobada' => 'Aprobada',
    'rechazada' => 'Rechazada',
    'cerrada' => 'Cerrada',
];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <form class="row g-2 align-items-center flex-grow-1" method="get" action="<?= e(admin_url()) ?>">
    <input type="hidden" name="route" value="cotizaciones">
    <div class="col-auto">
      <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Buscar cliente, empresa o servicio">
    </div>
    <div class="col-auto">
      <select class="form-select" name="estado">
        <?php foreach ($estados as $k => $lab): ?>
          <option value="<?= e($k) ?>" <?= $estado === $k ? 'selected' : '' ?>><?= e($lab) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-primary" type="submit">Filtrar</button>
    </div>
  </form>
  <a class="btn btn-primary" href="<?= e(admin_url(['route' => 'cotizacion_nueva'])) ?>"><i class="bi bi-plus-lg me-1"></i>Nueva cotización</a>
</div>
<div class="table-responsive card border-0 shadow-sm">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Empresa</th>
        <th>Servicio</th>
        <th class="text-end">Total</th>
        <th>Estado</th>
        <th>Creada</th>
        <th class="text-end">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td>#<?= (int) $it['id'] ?></td>
          <td class="fw-semibold"><?= e((string) $it['cliente_nombre']) ?></td>
          <td><?= e((string) ($it['cliente_empresa'] ?? '')) ?></td>
          <td><?= e((string) $it['tipo_servicio']) ?></td>
          <td class="text-end small fw-semibold">
            <?php $t = $it['total'] ?? null; ?>
            <?php if ($t !== null && $t !== ''): ?>
              <?= e(format_cop((float) $t)) ?>
            <?php else: ?>
              <span class="text-muted">—</span>
            <?php endif; ?>
          </td>
          <td>
            <form method="post" action="<?= e(admin_url(['route' => 'cotizacion_estado'])) ?>" class="d-flex gap-1 align-items-center">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
              <select class="form-select form-select-sm" name="estado" onchange="this.form.submit()">
                <?php foreach (array_slice($estados, 1) as $k => $lab): ?>
                  <option value="<?= e($k) ?>" <?= ($it['estado'] ?? '') === $k ? 'selected' : '' ?>><?= e($lab) ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td class="small text-muted"><?= e((string) $it['created_at']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="<?= e(admin_url(['route' => 'cotizacion', 'id' => (int) $it['id']])) ?>">Detalle</a>
            <a class="btn btn-sm btn-outline-primary" href="<?= e(admin_url(['route' => 'cotizacion_editar', 'id' => (int) $it['id']])) ?>">Editar</a>
            <form class="d-inline" method="post" action="<?= e(admin_url(['route' => 'cotizacion_eliminar'])) ?>" onsubmit="return confirm('¿Eliminar cotización?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
