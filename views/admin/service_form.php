<?php /** @var ?array<string, mixed> $row */ ?>
<?php $isEdit = $row !== null; ?>
<form method="post" action="<?= e(admin_url(['route' => 'servicio_guardar'])) ?>" enctype="multipart/form-data" class="card border-0 shadow-sm">
  <div class="card-body p-4">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $isEdit ? (int) $row['id'] : 0 ?>">
    <div class="row g-3">
      <div class="col-md-8">
        <label class="form-label">Título</label>
        <input class="form-control" name="titulo" required value="<?= e((string) ($row['titulo'] ?? '')) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Orden</label>
        <input class="form-control" type="number" name="orden" value="<?= e((string) ($row['orden'] ?? '0')) ?>">
      </div>
      <div class="col-md-8">
        <label class="form-label">Slug (URL)</label>
        <input class="form-control" name="slug" value="<?= e((string) ($row['slug'] ?? '')) ?>" placeholder="Se genera desde el título si lo deja vacío">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= !isset($row['activo']) || (int) $row['activo'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="activo">Activo en web</label>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="descripcion" rows="4" required><?= e((string) ($row['descripcion'] ?? '')) ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Beneficios (uno por línea)</label>
        <textarea class="form-control" name="beneficios" rows="5"><?= e((string) ($row['beneficios'] ?? '')) ?></textarea>
      </div>
      <div class="col-md-8">
        <label class="form-label">URL de imagen</label>
        <input class="form-control" name="imagen" value="<?= e((string) ($row['imagen'] ?? '')) ?>" placeholder="https://...">
      </div>
      <div class="col-md-4">
        <label class="form-label">O subir imagen</label>
        <input class="form-control" type="file" name="imagen_archivo" accept="image/*">
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-between py-3">
    <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'servicios'])) ?>">Volver</a>
    <button class="btn btn-primary px-4" type="submit">Guardar</button>
  </div>
</form>
