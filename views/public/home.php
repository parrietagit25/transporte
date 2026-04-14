<?php
/** @var array<int, array<string, mixed>> $servicios */
/** @var array<int, array<string, mixed>> $destacadas */
/** @var array<string, string> $config */
$empresa = $config['empresa_nombre'] ?? app_config('name');
$heroImg = $config['hero_imagen_path'] ?? '';
$waNum = preg_replace('/\D+/', '', $config['whatsapp_numero'] ?? '');
$waMsg = rawurlencode($config['whatsapp_mensaje'] ?? '');
$waLink = $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' . $waMsg : '#';
?>
<section class="hero-brand position-relative overflow-hidden">
  <div class="hero-bg" style="background-image:url('<?= e($heroImg) ?>');"></div>
  <div class="hero-overlay"></div>
  <div class="hero-grid d-none d-md-block" aria-hidden="true"></div>
  <div class="container position-relative py-5 hero-content">
    <div class="row align-items-center min-vh-50 py-lg-5">
      <div class="col-lg-7">
        <p class="hero-eyebrow text-uppercase tracking-wide text-accent fw-semibold mb-3">Transporte &amp; logística industrial</p>
        <h1 class="display-4 fw-bold text-white mb-3 hero-title"><?= e($config['hero_titulo'] ?? '') ?></h1>
        <p class="lead hero-lead text-white-75 mb-4"><?= e($config['hero_subtitulo'] ?? '') ?></p>
        <div class="d-flex flex-wrap gap-3 hero-actions">
          <a class="btn btn-accent btn-lg px-4 rounded-pill" href="<?= e(public_url('index.php?p=contacto')) ?>"><i class="bi bi-chat-dots me-2"></i>Solicitar información</a>
          <a class="btn btn-hero-outline btn-lg px-4 rounded-pill" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-2"></i>Contactar por WhatsApp</a>
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4 pt-lg-2 text-white-50 small hero-trust">
          <span><i class="bi bi-shield-check text-accent me-1"></i> Seguridad operativa</span>
          <span><i class="bi bi-geo-alt text-accent me-1"></i> Cobertura nacional</span>
          <span><i class="bi bi-graph-up-arrow text-accent me-1"></i> Trazabilidad</span>
        </div>
      </div>
      <div class="col-lg-5 mt-5 mt-lg-0">
        <div class="glass-panel glass-panel--accent p-4 p-md-5">
          <h3 class="h5 text-white mb-3">¿Por qué <?= e($empresa) ?>?</h3>
          <ul class="list-unstyled text-white-75 mb-0 small">
            <li class="mb-3 d-flex gap-2"><i class="bi bi-check2-circle text-accent fs-5"></i> Equipos para carga pesada, gándolas y cama bajas.</li>
            <li class="mb-3 d-flex gap-2"><i class="bi bi-check2-circle text-accent fs-5"></i> Planeación de rutas y permisos con enfoque en continuidad.</li>
            <li class="d-flex gap-2"><i class="bi bi-check2-circle text-accent fs-5"></i> Equipo humano especializado y reportes claros al cliente.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-surface">
  <div class="container">
    <div class="row align-items-end mb-4 section-head">
      <div class="col-lg-8">
        <p class="section-eyebrow text-uppercase small fw-semibold text-accent mb-2">Capacidades</p>
        <h2 class="section-title mb-2">Servicios clave</h2>
        <p class="text-secondary section-lead mb-0">Capacidad real para proyectos industriales, infraestructura y cadena de suministro.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a class="btn btn-outline-primary rounded-pill px-4" href="<?= e(public_url('index.php?p=servicios')) ?>">Ver todos los servicios</a>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach (array_slice($servicios, 0, 5) as $s): ?>
        <div class="col-md-6 col-xl-4">
          <article class="card service-card h-100 border-0 shadow-premium">
            <div class="ratio ratio-16x9 rounded-top overflow-hidden">
              <?php $img = (string) ($s['imagen'] ?? ''); ?>
              <img src="<?= e($img) ?>" class="object-fit-cover" alt="<?= e((string) $s['titulo']) ?>">
            </div>
            <div class="card-body d-flex flex-column">
              <h3 class="h5 card-title"><?= e((string) $s['titulo']) ?></h3>
              <p class="card-text text-secondary small flex-grow-1"><?= e(mb_strimwidth(strip_tags((string) $s['descripcion']), 0, 140, '…')) ?></p>
              <a class="stretched-link fw-semibold text-decoration-none" href="<?= e(public_url('index.php?p=servicio&slug=' . urlencode((string) $s['slug']))) ?>">Más información <i class="bi bi-arrow-right"></i></a>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-5">
        <p class="section-eyebrow text-uppercase small fw-semibold text-accent mb-2">Diferencial</p>
        <h2 class="section-title">Ventajas competitivas</h2>
        <p class="text-secondary section-lead">Integramos ingeniería de transporte, cultura de seguridad y comunicación proactiva con su mesa de proyecto.</p>
      </div>
      <div class="col-lg-7">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="feature-tile h-100 p-4">
              <i class="bi bi-lightning-charge-fill text-accent fs-3 mb-2 d-block"></i>
              <h3 class="h6">Respuesta ágil</h3>
              <p class="small text-secondary mb-0">Equipos listos y ventanas operativas alineadas a su cronograma crítico.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-tile h-100 p-4">
              <i class="bi bi-diagram-3-fill text-accent fs-3 mb-2 d-block"></i>
              <h3 class="h6">Coordinación integral</h3>
              <p class="small text-secondary mb-0">Un solo interlocutor para ruta, permisos y acompañamiento en sitio.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-tile h-100 p-4">
              <i class="bi bi-patch-check-fill text-accent fs-3 mb-2 d-block"></i>
              <h3 class="h6">Cumplimiento</h3>
              <p class="small text-secondary mb-0">Protocolos documentados y trazabilidad de carga de origen a destino.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="feature-tile h-100 p-4">
              <i class="bi bi-stars text-accent fs-3 mb-2 d-block"></i>
              <h3 class="h6">Experiencia industrial</h3>
              <p class="small text-secondary mb-0">Proyectos EPC, minería, energía y manufactura con altos estándares.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-surface">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2 section-head">
      <div>
        <p class="section-eyebrow text-uppercase small fw-semibold text-accent mb-2">Casos reales</p>
        <h2 class="section-title mb-1">Proyectos y novedades destacados</h2>
        <p class="text-secondary section-lead mb-0">Casos de éxito, operativos y capacidad operativa reciente.</p>
      </div>
      <a class="btn btn-primary rounded-pill px-4" href="<?= e(public_url('index.php?p=publicaciones')) ?>">Ver todas</a>
    </div>
    <div class="row g-4">
      <?php foreach ($destacadas as $pub): ?>
        <div class="col-md-6 col-lg-3">
          <article class="card post-card h-100 border-0 shadow-premium">
            <?php $pi = (string) ($pub['imagen_destacada'] ?? ''); ?>
            <div class="ratio ratio-4x3 overflow-hidden rounded-top">
              <img src="<?= e($pi) ?>" class="object-fit-cover" alt="">
            </div>
            <div class="card-body">
              <time class="text-muted small d-block mb-1"><?= e((string) $pub['fecha_publicacion']) ?></time>
              <h3 class="h6 card-title"><?= e((string) $pub['titulo']) ?></h3>
              <p class="small text-secondary"><?= e(mb_strimwidth(strip_tags((string) $pub['resumen']), 0, 100, '…')) ?></p>
              <a class="fw-semibold stretched-link text-decoration-none" href="<?= e(public_url('index.php?p=publicacion&slug=' . urlencode((string) $pub['slug']))) ?>">Leer más</a>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <p class="section-eyebrow text-uppercase small fw-semibold text-accent text-center mb-2">Confianza</p>
    <h2 class="section-title text-center mb-5">Testimonios</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <figure class="testimonial p-4 h-100">
          <blockquote class="mb-3">“Ventana nocturna en Bogotá con tuneladora: cero sorpresas, acta firmada y el residente de obra muy cómodo con el nivel de detalle.”</blockquote>
          <figcaption class="small text-secondary mb-0"><strong>Ing. Paola Restrepo</strong> — Metro Liviano Aliado</figcaption>
        </figure>
      </div>
      <div class="col-md-4">
        <figure class="testimonial p-4 h-100">
          <blockquote class="mb-3">“Nos montaron campamento modular en tiempo récord. Módulos impecables y el equipo de campo entendía de logística minera.”</blockquote>
          <figcaption class="small text-secondary mb-0"><strong>David Murillo</strong> — Contratista LSTK Meta</figcaption>
        </figure>
      </div>
      <div class="col-md-4">
        <figure class="testimonial p-4 h-100">
          <blockquote class="mb-3">“El convoy del transformador fue un reto de túnel y altura. Salieron con plan B listo; eso para interventoría cuenta mucho.”</blockquote>
          <figcaption class="small text-secondary mb-0"><strong>Lucía Tobón</strong> — EPC Transmisión</figcaption>
        </figure>
      </div>
    </div>
  </div>
</section>

<section class="py-5 cta-final text-center position-relative overflow-hidden">
  <div class="cta-final__glow" aria-hidden="true"></div>
  <div class="container py-lg-4 position-relative">
    <h2 class="text-white display-6 fw-bold mb-3 cta-final-title">¿Listo para mover su próximo proyecto?</h2>
    <p class="text-white-50 lead mb-4 mx-auto cta-final-lead">Hable con nuestro equipo comercial y reciba acompañamiento desde el primer contacto.</p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a class="btn btn-accent btn-lg px-5 rounded-pill" href="<?= e(public_url('index.php?p=contacto')) ?>">Agendar conversación</a>
      <a class="btn btn-hero-outline btn-lg px-5 rounded-pill" href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp directo</a>
    </div>
  </div>
</section>
