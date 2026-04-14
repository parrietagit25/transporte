<?php
/** @var array<int, array<string, mixed>> $servicios */
/** @var array<string, string> $config */
$waNum = preg_replace('/\D+/', '', $config['whatsapp_numero'] ?? '');
$waPref = trim((string) ($config['whatsapp_mensaje'] ?? 'Hola, quiero información.'));
$waBase = $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' : '#';
?>
<section class="page-hero py-5">
  <div class="container py-4">
    <p class="section-eyebrow text-uppercase text-accent fw-semibold mb-2">Portafolio</p>
    <h1 class="display-5 fw-bold">Servicios especializados</h1>
    <p class="lead text-secondary col-lg-8">Soluciones para transporte pesado, unidades modulares, cama bajas, gándolas y cadena logística con enfoque industrial y cumplimiento medible.</p>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <?php foreach ($servicios as $s): ?>
      <?php
        $msg = rawurlencode($waPref . ' — Servicio: ' . (string) $s['titulo']);
        $waLink = $waBase !== '#' ? $waBase . $msg : '#';
        $img = (string) ($s['imagen'] ?? '');
        $benefLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($s['beneficios'] ?? ''))));
      ?>
      <article class="row align-items-center g-4 mb-5 pb-5 service-split-row">
        <div class="col-lg-6">
          <div class="ratio ratio-16x9 rounded-4 overflow-hidden service-showcase-img">
            <img src="<?= e($img) ?>" class="object-fit-cover" alt="<?= e((string) $s['titulo']) ?>">
          </div>
        </div>
        <div class="col-lg-6">
          <h2 class="h3 fw-bold mb-3"><?= e((string) $s['titulo']) ?></h2>
          <p class="text-secondary"><?= nl2br(e((string) $s['descripcion'])) ?></p>
          <?php if ($benefLines !== []): ?>
            <h3 class="h6 text-uppercase text-muted mt-4">Beneficios</h3>
            <ul class="list-unstyled mb-4">
              <?php foreach ($benefLines as $b): ?>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-accent me-2"></i><?= e($b) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-success rounded-pill px-4" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-1"></i> WhatsApp</a>
            <a class="btn btn-primary rounded-pill px-4" href="<?= e(public_url('index.php?p=servicio&slug=' . urlencode((string) $s['slug']))) ?>">Más información</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
