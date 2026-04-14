<?php /** @var array<int, array<string, mixed>> $items */ ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <p class="text-secondary mb-0">Administre los servicios visibles en el sitio público.</p>
  <a class="btn btn-primary" href="<?= e(admin_url(['route' => 'servicio_nuevo'])) ?>"><i class="bi bi-plus-lg me-1"></i>Nuevo servicio</a>
</div>
<div class="table-responsive card border-0 shadow-sm">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>Orden</th>
        <th>Título</th>
        <th>Slug</th>
        <th>Activo</th>
        <th class="text-end">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= (int) $it['orden'] ?></td>
          <td class="fw-semibold"><?= e((string) $it['titulo']) ?></td>
          <td><code class="small"><?= e((string) $it['slug']) ?></code></td>
          <td><?= (int) $it['activo'] ? '<span class="badge text-bg-success">Sí</span>' : '<span class="badge text-bg-secondary">No</span>' ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= e(admin_url(['route' => 'servicio_editar', 'id' => (int) $it['id']])) ?>">Editar</a>
            <form class="d-inline" method="post" action="<?= e(admin_url(['route' => 'servicio_eliminar'])) ?>" onsubmit="return confirm('¿Eliminar este servicio?');">
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
