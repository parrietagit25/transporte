<?php
/** @var array<string, mixed> $servicio */
/** @var array<string, string> $config */
$waNum = preg_replace('/\D+/', '', $config['whatsapp_numero'] ?? '');
$waPref = trim((string) ($config['whatsapp_mensaje'] ?? 'Hola, quiero información.'));
$msg = rawurlencode($waPref . ' — Servicio: ' . (string) $servicio['titulo']);
$waLink = $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' . $msg : '#';
$img = (string) ($servicio['imagen'] ?? '');
$benefLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($servicio['beneficios'] ?? ''))));
?>
<section class="page-hero py-4">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(public_url('index.php?p=inicio')) ?>">Inicio</a></li>
        <li class="breadcrumb-item"><a href="<?= e(public_url('index.php?p=servicios')) ?>">Servicios</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e((string) $servicio['titulo']) ?></li>
      </ol>
    </nav>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-lg-7">
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden service-showcase-img mb-4">
          <img src="<?= e($img) ?>" class="object-fit-cover" alt="">
        </div>
        <h1 class="h2 fw-bold mb-3"><?= e((string) $servicio['titulo']) ?></h1>
        <div class="text-secondary lead-size"><?= nl2br(e((string) $servicio['descripcion'])) ?></div>
      </div>
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm sticky-lg-top service-side-card" style="top:96px;">
          <div class="card-body p-4">
            <h2 class="h5 mb-3">Beneficios</h2>
            <ul class="list-unstyled small mb-4">
              <?php foreach ($benefLines as $b): ?>
                <li class="mb-2"><i class="bi bi-check2 text-accent me-2"></i><?= e($b) ?></li>
              <?php endforeach; ?>
            </ul>
            <div class="d-grid gap-2">
              <a class="btn btn-success rounded-pill" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-2"></i>Contactar por WhatsApp</a>
              <a class="btn btn-outline-primary rounded-pill" href="<?= e(public_url('index.php?p=contacto')) ?>">Solicitar información</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
