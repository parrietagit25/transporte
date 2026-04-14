<div class="row">
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <h2 class="h6 mb-3">Cambiar contraseña</h2>
        <form method="post" action="<?= e(admin_url(['route' => 'perfil'])) ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label" for="password">Nueva contraseña</label>
            <input class="form-control" type="password" id="password" name="password" minlength="8" required autocomplete="new-password">
          </div>
          <div class="mb-3">
            <label class="form-label" for="password2">Confirmar</label>
            <input class="form-control" type="password" id="password2" name="password2" minlength="8" required autocomplete="new-password">
          </div>
          <button class="btn btn-primary" type="submit">Actualizar</button>
        </form>
      </div>
    </div>
  </div>
</div>
