<?php /** @var array<int, array<string, mixed>> $items */ ?>
<div class="table-responsive card border-0 shadow-sm">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>Fecha</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Asunto</th>
        <th>Leído</th>
        <th class="text-end">Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr class="<?= (int) ($it['leido'] ?? 0) ? '' : 'table-warning' ?>">
          <td class="small"><?= e((string) $it['created_at']) ?></td>
          <td class="fw-semibold"><?= e((string) $it['nombre']) ?></td>
          <td><?= e((string) $it['email']) ?></td>
          <td><?= e((string) ($it['asunto'] ?? '')) ?></td>
          <td><?= (int) ($it['leido'] ?? 0) ? 'Sí' : 'No' ?></td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#m<?= (int) $it['id'] ?>">Ver</button>
            <?php if (!(int) ($it['leido'] ?? 0)): ?>
              <form class="d-inline" method="post" action="<?= e(admin_url(['route' => 'contacto_leido'])) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
                <button class="btn btn-sm btn-primary" type="submit">Marcar leído</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php foreach ($items as $it): ?>
  <div class="modal fade" id="m<?= (int) $it['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title h5">Mensaje de <?= e((string) $it['nombre']) ?></h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="small text-muted mb-2"><?= e((string) $it['email']) ?> · <?= e((string) ($it['telefono'] ?? '')) ?></p>
          <p><strong>Asunto:</strong> <?= e((string) ($it['asunto'] ?? '')) ?></p>
          <p class="mb-0"><?= nl2br(e((string) $it['mensaje'])) ?></p>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
