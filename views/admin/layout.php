<?php
/** @var string $content */
/** @var string $pageTitle */
$cfg = (new \App\Models\ConfigRepository())->all();
$logo = $cfg['logo_path'] ?? '/assets/img/logo.png';
$logoUrl = str_starts_with((string) $logo, 'http') ? (string) $logo : public_url(ltrim((string) $logo, '/'));
$empresa = $cfg['empresa_nombre'] ?? app_config('name');
$route = $_GET['route'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? '') ?> — Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= e(asset_url('css/admin.css')) ?>">
</head>
<body class="admin-body">
  <div class="admin-shell d-flex">
    <aside class="admin-sidebar text-white p-3 d-flex flex-column">
      <div class="d-flex align-items-center gap-2 mb-4 px-1">
        <img src="<?= e($logoUrl) ?>" alt="" class="rounded" height="36">
        <span class="small fw-semibold text-truncate"><?= e($empresa) ?></span>
      </div>
      <nav class="nav flex-column gap-1 mb-auto">
        <a class="nav-link <?= $route === 'dashboard' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'dashboard'])) ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a class="nav-link <?= $route === 'config' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'config'])) ?>"><i class="bi bi-sliders me-2"></i>Configuración</a>
        <a class="nav-link <?= str_starts_with($route, 'servicio') || $route === 'servicios' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'servicios'])) ?>"><i class="bi bi-box-seam me-2"></i>Servicios</a>
        <a class="nav-link <?= str_starts_with($route, 'publicacion') || $route === 'publicaciones' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'publicaciones'])) ?>"><i class="bi bi-newspaper me-2"></i>Publicaciones</a>
        <a class="nav-link <?= str_starts_with($route, 'cotizacion') || $route === 'cotizaciones' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'cotizaciones'])) ?>"><i class="bi bi-calculator me-2"></i>Cotizador</a>
        <a class="nav-link <?= str_starts_with($route, 'contacto') || $route === 'contactos' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'contactos'])) ?>"><i class="bi bi-inbox me-2"></i>Contactos</a>
        <a class="nav-link <?= $route === 'demo_ia' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'demo_ia'])) ?>"><i class="bi bi-stars me-2"></i>Demo IA</a>
        <a class="nav-link <?= $route === 'perfil' ? 'active' : '' ?>" href="<?= e(admin_url(['route' => 'perfil'])) ?>"><i class="bi bi-person-gear me-2"></i>Perfil</a>
      </nav>
      <hr class="border-secondary">
      <a class="btn btn-outline-light btn-sm" href="<?= e(public_url('index.php?p=inicio')) ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1"></i>Ver sitio</a>
      <a class="btn btn-danger btn-sm mt-2" href="<?= e(admin_url(['route' => 'logout'])) ?>"><i class="bi bi-box-arrow-right me-1"></i>Salir</a>
    </aside>
    <div class="admin-main flex-grow-1 d-flex flex-column min-vh-100">
      <header class="admin-header border-bottom bg-white px-3 py-3 d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0"><?= e($pageTitle ?? '') ?></h1>
        <span class="text-muted small">Sesión segura</span>
      </header>
      <div class="admin-content flex-grow-1 p-3 p-lg-4">
        <?php if ($m = flash('ok')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert"><?= e($m) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button></div>
        <?php endif; ?>
        <?php if ($m = flash('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= e($m) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button></div>
        <?php endif; ?>
        <?= $content ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= e(asset_url('js/admin.js')) ?>"></script>
</body>
</html>
