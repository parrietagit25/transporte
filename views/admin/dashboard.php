<?php
/** @var array<string, string> $cfg */
/** @var int $nServicios */
/** @var int $nPosts */
/** @var int $nQuotes */
/** @var int $nUnread */
?>
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <p class="text-muted small mb-1">Servicios</p>
        <p class="display-6 fw-bold mb-0"><?= (int) $nServicios ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <p class="text-muted small mb-1">Publicaciones</p>
        <p class="display-6 fw-bold mb-0"><?= (int) $nPosts ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <p class="text-muted small mb-1">Cotizaciones</p>
        <p class="display-6 fw-bold mb-0"><?= (int) $nQuotes ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <p class="text-muted small mb-1">Mensajes sin leer</p>
        <p class="display-6 fw-bold mb-0"><?= (int) $nUnread ?></p>
      </div>
    </div>
  </div>
</div>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h2 class="h6 mb-3">Resumen comercial</h2>
        <p class="text-secondary mb-2"><strong><?= e($cfg['empresa_nombre'] ?? '') ?></strong></p>
        <p class="text-secondary small mb-0"><?= e($cfg['meta_description'] ?? '') ?></p>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h2 class="h6 mb-3">Accesos rápidos</h2>
        <div class="d-grid gap-2">
          <a class="btn btn-outline-primary" href="<?= e(admin_url(['route' => 'cotizacion_nueva'])) ?>"><i class="bi bi-plus-lg me-1"></i>Nueva cotización</a>
          <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'publicacion_nueva'])) ?>"><i class="bi bi-plus-lg me-1"></i>Nueva publicación</a>
          <a class="btn btn-outline-secondary" href="<?= e(admin_url(['route' => 'config'])) ?>"><i class="bi bi-sliders me-1"></i>Editar configuración</a>
        </div>
      </div>
    </div>
  </div>
</div>
