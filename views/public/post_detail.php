<?php
/** @var array<string, mixed> $publicacion */
?>
<section class="page-hero py-3">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="<?= e(public_url('index.php?p=inicio')) ?>">Inicio</a></li>
        <li class="breadcrumb-item"><a href="<?= e(public_url('index.php?p=publicaciones')) ?>">Publicaciones</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e(mb_strimwidth((string) $publicacion['titulo'], 0, 48, '…')) ?></li>
      </ol>
    </nav>
  </div>
</section>

<section class="pb-5">
  <div class="container col-lg-9">
    <?php $pi = (string) ($publicacion['imagen_destacada'] ?? ''); ?>
    <div class="ratio ratio-21x9 rounded-4 overflow-hidden service-showcase-img mb-4">
      <img src="<?= e($pi) ?>" class="object-fit-cover" alt="">
    </div>
    <time class="text-muted"><?= e((string) $publicacion['fecha_publicacion']) ?></time>
    <h1 class="display-6 fw-bold mt-2 mb-3"><?= e((string) $publicacion['titulo']) ?></h1>
    <p class="lead text-secondary"><?= e((string) $publicacion['resumen']) ?></p>
    <div class="article-body text-secondary"><?= nl2br(e((string) $publicacion['contenido'])) ?></div>
    <div class="mt-4">
      <a class="btn btn-primary" href="<?= e(public_url('index.php?p=publicaciones')) ?>"><i class="bi bi-arrow-left me-2"></i>Volver a publicaciones</a>
    </div>
  </div>
</section>
