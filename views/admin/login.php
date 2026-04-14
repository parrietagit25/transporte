<?php if ($m = flash('error')): ?>
  <div class="alert alert-danger"><?= e($m) ?></div>
<?php endif; ?>
<div class="card shadow border-0">
  <div class="card-body p-4">
    <form method="post" action="<?= e(admin_url(['route' => 'login'])) ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label" for="username">Usuario</label>
        <input class="form-control" type="text" id="username" name="username" required autocomplete="username">
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Contraseña</label>
        <input class="form-control" type="password" id="password" name="password" required autocomplete="current-password">
      </div>
      <button class="btn btn-primary w-100" type="submit">Ingresar</button>
    </form>
    <p class="small text-muted mt-3 mb-0 text-center">Usuario demo: <strong>admin</strong> / <strong>Admin2024!</strong></p>
  </div>
</div>
