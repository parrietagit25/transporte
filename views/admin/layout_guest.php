<?php
/** @var string $content */
/** @var string $pageTitle */
/** @var array<string, string> $cfg */
$cfg = $cfg ?? [];
$empresa = $cfg['empresa_nombre'] ?? app_config('name');
$logo = $cfg['logo_path'] ?? '/assets/img/logo.png';
$logoUrl = str_starts_with((string) $logo, 'http') ? (string) $logo : public_url(ltrim((string) $logo, '/'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? '') ?> — Panel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= e(asset_url('css/admin.css')) ?>">
</head>
<body class="admin-guest d-flex align-items-center py-5">
  <div class="container col-md-5 col-lg-4">
    <div class="text-center mb-4">
      <div class="admin-login-brand mx-auto mb-3">
        <img src="<?= e($logoUrl) ?>" alt="<?= e($empresa) ?>" class="admin-login-logo rounded-3 shadow">
      </div>
      <h1 class="h4 text-white">Panel administrativo</h1>
      <p class="text-white-50 small mb-0"><?= e($empresa) ?></p>
    </div>
    <?= $content ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
