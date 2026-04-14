<?php
/** @var list<array<string, mixed>> $sesiones */
/** @var list<array<string, mixed>> $reuniones */
/** @var list<array<string, mixed>> $mensajes */
/** @var int $sesion_vista */
?>
<div class="row g-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3 border-bottom">
        <h2 class="h6 mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>Reuniones solicitadas vía asistente</h2>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Fecha / hora</th>
              <th>Contacto</th>
              <th>Correo</th>
              <th>Teléfono</th>
              <th>Motivo</th>
              <th>Estado</th>
              <th>Sesión</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($reuniones === []): ?>
              <tr><td colspan="8" class="text-muted py-4 text-center">Aún no hay reuniones agendadas por el asistente.</td></tr>
            <?php else: ?>
              <?php foreach ($reuniones as $r): ?>
                <tr>
                  <td><?= (int) ($r['id'] ?? 0) ?></td>
                  <td><?= e((string) ($r['fecha_hora'] ?? '')) ?></td>
                  <td><?= e((string) ($r['nombre_contacto'] ?? '')) ?></td>
                  <td><?= e((string) ($r['email'] ?? '')) ?></td>
                  <td><?= e((string) ($r['telefono'] ?? '')) ?></td>
                  <td class="text-break" style="max-width:14rem"><?= e((string) ($r['motivo'] ?? '')) ?></td>
                  <td><span class="badge text-bg-secondary"><?= e((string) ($r['estado'] ?? '')) ?></span></td>
                  <td>
                    <a href="<?= e(admin_url(['route' => 'demo_ia', 'sesion' => (int) ($r['sesion_id'] ?? 0)])) ?>">#<?= (int) ($r['sesion_id'] ?? 0) ?></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h6 mb-0"><i class="bi bi-chat-dots me-2 text-primary"></i>Conversaciones con visitantes</h2>
        <?php if ($sesion_vista > 0): ?>
          <a class="btn btn-sm btn-outline-secondary" href="<?= e(admin_url(['route' => 'demo_ia'])) ?>">Cerrar conversación</a>
        <?php endif; ?>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Inicio</th>
              <th>IP</th>
              <th>Mensajes</th>
              <th>Reuniones</th>
              <th>Primer mensaje usuario</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($sesiones === []): ?>
              <tr><td colspan="7" class="text-muted py-4 text-center">Sin sesiones todavía.</td></tr>
            <?php else: ?>
              <?php foreach ($sesiones as $s): ?>
                <?php $sid = (int) ($s['id'] ?? 0); ?>
                <tr class="<?= $sesion_vista === $sid ? 'table-primary' : '' ?>">
                  <td><?= $sid ?></td>
                  <td><?= e((string) ($s['created_at'] ?? '')) ?></td>
                  <td><?= e((string) ($s['ip'] ?? '—')) ?></td>
                  <td><?= (int) ($s['n_mensajes'] ?? 0) ?></td>
                  <td><?= (int) ($s['n_reuniones'] ?? 0) ?></td>
                  <td class="text-break" style="max-width:18rem"><?php
                    $pu = (string) ($s['primer_usuario'] ?? '');
                    echo e(mb_substr($pu, 0, 120)) . (mb_strlen($pu) > 120 ? '…' : '');
                  ?></td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(admin_url(['route' => 'demo_ia', 'sesion' => $sid])) ?>">Ver chat</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php if ($sesion_vista > 0): ?>
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
          <h2 class="h6 mb-0">Mensajes — sesión #<?= (int) $sesion_vista ?></h2>
        </div>
        <div class="card-body">
          <?php if ($mensajes === []): ?>
            <p class="text-muted mb-0">No hay mensajes para esta sesión.</p>
          <?php else: ?>
            <ul class="list-unstyled mb-0">
              <?php foreach ($mensajes as $m): ?>
                <li class="mb-3 pb-3 border-bottom">
                  <div class="d-flex justify-content-between gap-2">
                    <span class="badge <?= ($m['rol'] ?? '') === 'user' ? 'text-bg-primary' : 'text-bg-secondary' ?>"><?= e((string) ($m['rol'] ?? '')) ?></span>
                    <span class="text-muted small"><?= e((string) ($m['created_at'] ?? '')) ?></span>
                  </div>
                  <div class="mt-2 small text-break" style="white-space:pre-wrap"><?= e((string) ($m['contenido'] ?? '')) ?></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
