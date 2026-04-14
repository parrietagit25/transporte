<?php
/** @var string $content */
/** @var string $pageTitle */
/** @var array<string, string> $config */
$empresa = $config['empresa_nombre'] ?? app_config('name');
$waNum = preg_replace('/\D+/', '', $config['whatsapp_numero'] ?? '');
$waMsg = rawurlencode($config['whatsapp_mensaje'] ?? 'Hola, quiero información.');
$waLink = $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' . $waMsg : '#';
$logo = $config['logo_path'] ?? '/assets/img/logo.png';
$logoUrl = str_starts_with((string) $logo, 'http') ? (string) $logo : public_url(ltrim((string) $logo, '/'));
$current = $_GET['p'] ?? 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? '') ?> — <?= e($empresa) ?></title>
  <meta name="description" content="<?= e($config['meta_description'] ?? '') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body class="theme-body theme-premium">
  <nav class="navbar navbar-expand-lg navbar-dark brand-nav sticky-top align-items-lg-center py-2 py-lg-3">
    <div class="container position-relative">
      <a class="navbar-brand d-flex align-items-center gap-2 gap-lg-3 py-1" href="<?= e(public_url('index.php?p=inicio')) ?>">
        <span class="brand-logo-wrap d-inline-flex align-items-center justify-content-center rounded-3">
          <img src="<?= e($logoUrl) ?>" alt="<?= e($empresa) ?>" class="brand-logo" height="40">
        </span>
        <span class="brand-text d-none d-sm-inline text-white fw-semibold"><?= e($empresa) ?></span>
      </a>
      <button class="navbar-toggler border-0 rounded-3 shadow-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-1">
          <li class="nav-item"><a class="nav-link <?= $current === 'inicio' ? 'active' : '' ?>" href="<?= e(public_url('index.php?p=inicio')) ?>">Inicio</a></li>
          <li class="nav-item"><a class="nav-link <?= $current === 'servicios' || $current === 'servicio' ? 'active' : '' ?>" href="<?= e(public_url('index.php?p=servicios')) ?>">Servicios</a></li>
          <li class="nav-item"><a class="nav-link <?= $current === 'publicaciones' || $current === 'publicacion' ? 'active' : '' ?>" href="<?= e(public_url('index.php?p=publicaciones')) ?>">Proyectos</a></li>
          <li class="nav-item"><a class="nav-link" href="#" id="nav-asistente-ia">Asistente</a></li>
          <li class="nav-item"><a class="nav-link <?= $current === 'contacto' ? 'active' : '' ?>" href="<?= e(public_url('index.php?p=contacto')) ?>">Contacto</a></li>
          <li class="nav-item dropdown ms-lg-1 mt-2 mt-lg-0">
            <a class="nav-link dropdown-toggle d-inline-flex align-items-center gap-1 py-lg-2" href="#" id="navLangDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Seleccionar idioma">
              <i class="bi bi-globe2" aria-hidden="true"></i>
              <span class="small fw-semibold">ES</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end lang-dropdown shadow" aria-labelledby="navLangDropdown">
              <li><h6 class="dropdown-header text-uppercase small mb-0">Idioma</h6></li>
              <li>
                <span class="dropdown-item d-flex align-items-center justify-content-between active pe-none" aria-current="true">
                  Español
                  <i class="bi bi-check2 text-accent" aria-hidden="true"></i>
                </span>
              </li>
              <li>
                <a class="dropdown-item" href="#" onclick="return false;">English</a>
              </li>
              <li><p class="dropdown-item-text small text-muted mb-0 px-3 py-1">Solo visual por ahora.</p></li>
            </ul>
          </li>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
            <a class="btn btn-accent btn-sm px-4 rounded-pill" href="<?= e(public_url('index.php?p=contacto')) ?>">Solicitar información</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <?= $content ?>
  </main>

  <footer class="brand-footer mt-5 position-relative">
    <div class="container py-5 footer-inner position-relative">
      <div class="row g-4 align-items-start">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="<?= e($logoUrl) ?>" alt="" class="brand-logo opacity-90" height="44">
          </div>
          <p class="text-white-50 small mb-0"><?= e($config['meta_description'] ?? '') ?></p>
        </div>
        <div class="col-md-4 col-lg-3">
          <h6 class="text-uppercase text-accent fw-semibold mb-3 footer-heading">Contacto</h6>
          <ul class="list-unstyled text-white-50 small mb-0">
            <li class="mb-2"><i class="bi bi-telephone me-2 text-accent"></i><?= e($config['telefono'] ?? '') ?></li>
            <li class="mb-2"><i class="bi bi-envelope me-2 text-accent"></i><?= e($config['correo'] ?? '') ?></li>
            <li><i class="bi bi-geo-alt me-2 text-accent"></i><?= e($config['direccion'] ?? '') ?></li>
          </ul>
        </div>
        <div class="col-md-4 col-lg-3">
          <h6 class="text-uppercase text-accent fw-semibold mb-3 footer-heading">Enlaces</h6>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><a class="link-light link-underline-opacity-0" href="<?= e(public_url('index.php?p=servicios')) ?>">Servicios</a></li>
            <li class="mb-2"><a class="link-light link-underline-opacity-0" href="<?= e(public_url('index.php?p=publicaciones')) ?>">Proyectos y novedades</a></li>
            <li><a class="link-light link-underline-opacity-0" href="<?= e(public_url('index.php?p=contacto')) ?>">Contacto</a></li>
          </ul>
        </div>
        <div class="col-md-4 col-lg-2">
          <h6 class="text-uppercase text-accent fw-semibold mb-3 footer-heading">Redes</h6>
          <div class="d-flex gap-2 footer-social">
            <?php if (!empty($config['facebook_url'])): ?>
              <a class="btn btn-footer-social rounded-circle" href="<?= e($config['facebook_url']) ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($config['instagram_url'])): ?>
              <a class="btn btn-footer-social rounded-circle" href="<?= e($config['instagram_url']) ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
            <?php endif; ?>
            <?php if (!empty($config['linkedin_url'])): ?>
              <a class="btn btn-footer-social rounded-circle" href="<?= e($config['linkedin_url']) ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <hr class="border-secondary my-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-white-50">
        <span>© <?= date('Y') ?> <?= e($empresa) ?>. Todos los derechos reservados.</span>
        <a class="link-light link-underline-opacity-0" href="<?= e(public_url('admin/index.php')) ?>"><i class="bi bi-shield-lock me-1"></i>Acceso administrativo</a>
      </div>
    </div>
  </footer>

  <?php
  $iaApi = public_url('api/ia.php');
  $iaCsrf = csrf_token();
  ?>
  <div class="float-actions-stack" aria-label="Asistente y WhatsApp">
    <?php require BASE_PATH . '/views/public/partials/ia_float_widget.php'; ?>
    <?php if ($waNum !== ''): ?>
    <a href="<?= e($waLink) ?>" class="whatsapp-float wa-in-stack shadow-lg" target="_blank" rel="noopener" aria-label="WhatsApp" title="WhatsApp — <?= e($config['whatsapp_mensaje'] ?? 'Contacto') ?>">
      <span class="whatsapp-float-inner"><i class="bi bi-whatsapp"></i></span>
    </a>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= e(asset_url('js/app.js')) ?>"></script>
  <script src="<?= e(asset_url('js/ia-assistant.js')) ?>" defer></script>
</body>
</html>
