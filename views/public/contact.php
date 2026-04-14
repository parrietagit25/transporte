<?php
/** @var array<string, string> $config */
/** @var list<string> $errors */
/** @var array<string, string> $old */
$errors = $errors ?? [];
$old = $old ?? [];
$mapQuery = rawurlencode($config['direccion'] ?? '');
$mapEmbed = trim((string) ($config['mapa_embed_url'] ?? ''));
$waNumClean = preg_replace('/\D+/', '', $config['whatsapp_numero'] ?? '');
$waMsgEnc = rawurlencode(trim((string) ($config['whatsapp_mensaje'] ?? 'Hola, quiero información.')));
?>
<section class="page-hero py-5">
  <div class="container py-3">
    <p class="section-eyebrow text-uppercase text-accent fw-semibold mb-2">Comercial</p>
    <h1 class="display-5 fw-bold">Contacto</h1>
    <p class="lead text-secondary">Estamos listos para evaluar su requerimiento con el nivel de detalle que su operación merece.</p>
  </div>
</section>

<section class="pb-5">
  <div class="container">
    <?php if ($errors !== []): ?>
      <div class="alert alert-danger"><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
    <?php endif; ?>
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h2 class="h5 mb-3">Datos de contacto</h2>
            <ul class="list-unstyled text-secondary mb-4">
              <li class="mb-3"><i class="bi bi-telephone text-accent me-2"></i><?= e($config['telefono'] ?? '') ?></li>
              <li class="mb-3"><i class="bi bi-whatsapp text-accent me-2"></i>
                <?php if ($waNumClean !== ''): ?>
                  <a href="https://wa.me/<?= e($waNumClean) ?>?text=<?= e($waMsgEnc) ?>" target="_blank" rel="noopener">WhatsApp comercial</a>
                <?php else: ?>
                  <span class="text-secondary">Configure WhatsApp en el panel</span>
                <?php endif; ?>
              </li>
              <li class="mb-3"><i class="bi bi-envelope text-accent me-2"></i><?= e($config['correo'] ?? '') ?></li>
              <li><i class="bi bi-geo-alt text-accent me-2"></i><?= e($config['direccion'] ?? '') ?></li>
            </ul>
            <div class="ratio ratio-4x3 rounded-3 overflow-hidden border">
              <?php if ($mapEmbed !== ''): ?>
                <iframe title="Mapa" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= e($mapEmbed) ?>"></iframe>
              <?php else: ?>
                <iframe title="Mapa" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                  src="https://www.google.com/maps?q=<?= e($mapQuery) ?>&output=embed"></iframe>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4 p-lg-5">
            <h2 class="h5 mb-3">Envíenos un mensaje</h2>
            <form method="post" action="<?= e(public_url('index.php?p=contacto')) ?>" class="needs-validation" novalidate>
              <?= csrf_field() ?>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="nombre">Nombre</label>
                  <input class="form-control" type="text" id="nombre" name="nombre" required minlength="3" value="<?= e($old['nombre'] ?? '') ?>">
                  <div class="invalid-feedback">Indique un nombre de al menos 3 caracteres.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="email">Correo</label>
                  <input class="form-control" type="email" id="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
                  <div class="invalid-feedback">Indique un correo válido.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="telefono">Teléfono</label>
                  <input class="form-control" type="text" id="telefono" name="telefono" value="<?= e($old['telefono'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="asunto">Asunto</label>
                  <input class="form-control" type="text" id="asunto" name="asunto" value="<?= e($old['asunto'] ?? '') ?>">
                </div>
                <div class="col-12">
                  <label class="form-label" for="mensaje">Mensaje</label>
                  <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required minlength="10"><?= e($old['mensaje'] ?? '') ?></textarea>
                  <div class="invalid-feedback">El mensaje debe tener al menos 10 caracteres.</div>
                </div>
                <div class="col-12">
                  <button class="btn btn-primary btn-lg" type="submit">Enviar mensaje</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
