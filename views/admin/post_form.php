<?php /** @var ?array<string, mixed> $row */ ?>
<?php $isEdit = $row !== null; ?>
<form method="post" action="<?= e(admin_url(['route' => 'publicacion_guardar'])) ?>" enctype="multipart/form-data" class="card border-0 shadow-sm">
  <div class="card-body p-4">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $isEdit ? (int) $row['id'] : 0 ?>">
    <div class="row g-3">
      <div class="col-md-8">
        <label class="form-label">Título</label>
        <input class="form-control" name="titulo" required value="<?= e((string) ($row['titulo'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fecha publicación</label>
        <input class="form-control" type="date" name="fecha_publicacion" value="<?= e((string) ($row['fecha_publicacion'] ?? date('Y-m-d'))) ?>">
      </div>
      <div class="col-md-8">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" value="<?= e((string) ($row['slug'] ?? '')) ?>" placeholder="Opcional, se genera desde el título">
      </div>
      <div class="col-md-4 d-flex align-items-end gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="destacado" id="destacado" <?= !empty($row['destacado']) ? 'checked' : '' ?>>
          <label class="form-check-label" for="destacado">Destacado en inicio</label>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Estado</label>
        <select class="form-select" name="estado">
          <option value="borrador" <?= ($row['estado'] ?? '') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
          <option value="publicado" <?= ($row['estado'] ?? '') === 'publicado' ? 'selected' : '' ?>>Publicado</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Resumen</label>
        <textarea class="form-control" name="resumen" rows="2" required><?= e((string) ($row['resumen'] ?? '')) ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Contenido</label>
        <textarea class="form-control" name="contenido" rows="8" required><?= e((string) ($row['contenido'] ?? '')) ?></textarea>
      </div>
      <div class="col-md-8">
        <label class="form-label">URL imagen destacada</label>
        <input class="form-control" name="imagen_destacada" value="<?= e((string) ($row['imagen_destacada'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">O subir imagen</label>
        <input class="form-control" type="file" name="imagen_archivo" accept="image/*">
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-between py-3">
    <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'publicaciones'])) ?>">Volver</a>
    <button class="btn btn-primary px-4" type="submit">Guardar</button>
  </div>
</form>
