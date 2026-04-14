<?php /** @var array<string, string> $cfg */ ?>
<form method="post" action="<?= e(admin_url(['route' => 'config'])) ?>" enctype="multipart/form-data" class="card border-0 shadow-sm needs-validation" novalidate>
  <div class="card-body p-4">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre de la empresa</label>
        <input class="form-control" name="empresa_nombre" value="<?= e($cfg['empresa_nombre'] ?? '') ?>" required>
        <div class="invalid-feedback">Indique el nombre de la empresa.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Meta descripción (SEO)</label>
        <input class="form-control" name="meta_description" value="<?= e($cfg['meta_description'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Teléfono</label>
        <input class="form-control" name="telefono" value="<?= e($cfg['telefono'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">WhatsApp (solo números, ej. 573001112233)</label>
        <input class="form-control" name="whatsapp_numero" value="<?= e($cfg['whatsapp_numero'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Correo</label>
        <input class="form-control" type="email" name="correo" value="<?= e($cfg['correo'] ?? '') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Mensaje predefinido WhatsApp</label>
        <textarea class="form-control" name="whatsapp_mensaje" rows="2"><?= e($cfg['whatsapp_mensaje'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Dirección</label>
        <textarea class="form-control" name="direccion" rows="2"><?= e($cfg['direccion'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Título principal del hero</label>
        <input class="form-control" name="hero_titulo" value="<?= e($cfg['hero_titulo'] ?? '') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Subtítulo del hero</label>
        <textarea class="form-control" name="hero_subtitulo" rows="3"><?= e($cfg['hero_subtitulo'] ?? '') ?></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Imagen / URL de fondo del hero</label>
        <input class="form-control" name="hero_imagen_path" value="<?= e($cfg['hero_imagen_path'] ?? '') ?>" placeholder="https://...">
        <div class="form-text">Puede ser una URL externa o una ruta bajo /uploads/… También puede subir archivo abajo.</div>
      </div>
      <div class="col-md-8">
        <label class="form-label">Subir imagen del hero (opcional)</label>
        <input class="form-control" type="file" name="hero_imagen" accept="image/*">
        <div class="form-text">Si sube archivo, reemplaza la URL anterior al guardar.</div>
      </div>
      <div class="col-12">
        <label class="form-label">Mapa embebido (URL iframe de Google Maps u otro)</label>
        <input class="form-control" name="mapa_embed_url" value="<?= e($cfg['mapa_embed_url'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?...">
        <div class="form-text">Opcional. Si está vacío, en contacto se usa búsqueda por dirección.</div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Facebook</label>
        <input class="form-control" name="facebook_url" value="<?= e($cfg['facebook_url'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Instagram</label>
        <input class="form-control" name="instagram_url" value="<?= e($cfg['instagram_url'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">LinkedIn</label>
        <input class="form-control" name="linkedin_url" value="<?= e($cfg['linkedin_url'] ?? '') ?>">
      </div>
      <div class="col-md-8">
        <label class="form-label">Subir logo (PNG/JPG/WebP)</label>
        <input class="form-control" type="file" name="logo" accept="image/*">
        <div class="form-text">Reemplaza el logo en cabecera, pie y panel. Ruta actual: <code><?= e($cfg['logo_path'] ?? '') ?></code></div>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <?php $lp = $cfg['logo_path'] ?? ''; ?>
        <?php $lu = str_starts_with((string) $lp, 'http') ? (string) $lp : public_url(ltrim((string) $lp, '/')); ?>
        <img src="<?= e($lu) ?>" alt="Logo actual" class="img-fluid border rounded p-2 bg-white" style="max-height:80px">
      </div>
    </div>
  </div>
  <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
    <button class="btn btn-primary px-4" type="submit">Guardar cambios</button>
  </div>
</form>
