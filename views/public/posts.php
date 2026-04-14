<?php
/** @var array<int, array<string, mixed>> $publicaciones */
?>
<section class="page-hero py-5">
  <div class="container py-3">
    <p class="section-eyebrow text-uppercase text-accent fw-semibold mb-2">Novedades</p>
    <h1 class="display-5 fw-bold">Publicaciones y proyectos</h1>
    <p class="lead text-secondary col-lg-8">Historias de operación, equipos movilizados y soluciones logísticas para clientes exigentes.</p>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <div class="row g-4">
      <?php foreach ($publicaciones as $pub): ?>
        <div class="col-md-6 col-lg-4">
          <article class="card post-card h-100 border-0 shadow-premium">
            <?php $pi = (string) ($pub['imagen_destacada'] ?? ''); ?>
            <div class="ratio ratio-4x3 overflow-hidden rounded-top">
              <img src="<?= e($pi) ?>" class="object-fit-cover" alt="">
            </div>
            <div class="card-body d-flex flex-column">
              <time class="text-muted small"><?= e((string) $pub['fecha_publicacion']) ?></time>
              <h2 class="h5 mt-1 card-title"><?= e((string) $pub['titulo']) ?></h2>
              <p class="text-secondary small flex-grow-1"><?= e((string) $pub['resumen']) ?></p>
              <a class="fw-semibold stretched-link" href="<?= e(public_url('index.php?p=publicacion&slug=' . urlencode((string) $pub['slug']))) ?>">Ver detalle</a>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
