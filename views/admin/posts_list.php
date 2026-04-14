<?php /** @var array<int, array<string, mixed>> $items */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <p class="text-secondary mb-0">Publicaciones visibles según estado.</p>
  <a class="btn btn-primary" href="<?= e(admin_url(['route' => 'publicacion_nueva'])) ?>"><i class="bi bi-plus-lg me-1"></i>Nueva publicación</a>
</div>
<div class="table-responsive card border-0 shadow-sm">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>Fecha</th>
        <th>Título</th>
        <th>Estado</th>
        <th>Destacado</th>
        <th class="text-end">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td class="small text-muted"><?= e((string) $it['fecha_publicacion']) ?></td>
          <td class="fw-semibold"><?= e((string) $it['titulo']) ?></td>
          <td>
            <?php if (($it['estado'] ?? '') === 'publicado'): ?>
              <span class="badge text-bg-success">Publicado</span>
            <?php else: ?>
              <span class="badge text-bg-warning text-dark">Borrador</span>
            <?php endif; ?>
          </td>
          <td><?= (int) ($it['destacado'] ?? 0) ? 'Sí' : '—' ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= e(admin_url(['route' => 'publicacion_editar', 'id' => (int) $it['id']])) ?>">Editar</a>
            <form class="d-inline" method="post" action="<?= e(admin_url(['route' => 'publicacion_eliminar'])) ?>" onsubmit="return confirm('¿Eliminar esta publicación?');">
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
