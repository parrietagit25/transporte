<?php
/** @var string $iaApi @var string $iaCsrf */
?>
<div id="ia-float-panel" class="ia-float-panel d-none" role="dialog" aria-modal="true" aria-labelledby="ia-float-title" aria-hidden="true">
  <div
    id="ia-assistant-root"
    class="ia-float-card"
    data-api="<?= e($iaApi) ?>"
    data-csrf="<?= e($iaCsrf) ?>"
  >
    <div class="ia-card-header ia-float-header d-flex align-items-center justify-content-between gap-2 py-2 px-3">
      <div class="d-flex align-items-center gap-2 min-w-0">
        <span class="rounded-3 bg-white bg-opacity-10 p-2 flex-shrink-0"><i class="bi bi-stars text-warning"></i></span>
        <div class="min-w-0">
          <div id="ia-float-title" class="fw-semibold small text-truncate">Asistente</div>
          <div class="text-white-50" style="font-size: 0.7rem">ES / EN · escribe o habla; la IA sigue tu idioma</div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-1 flex-shrink-0">
        <span class="badge rounded-pill bg-warning text-dark d-none" id="ia-badge-reunion">Agendado</span>
        <button type="button" class="btn btn-sm btn-link text-white-50 text-decoration-none p-1" id="ia-float-close" aria-label="Cerrar chat">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
    </div>
    <div class="ia-messages ia-messages-float" id="ia-messages" aria-live="polite"></div>
    <audio id="ia-tts-player" playsinline preload="none" style="position:absolute;width:0;height:0;opacity:0;pointer-events:none" aria-hidden="true"></audio>
    <div class="p-2 border-top bg-white ia-toolbar ia-toolbar-float">
      <textarea class="form-control form-control-sm mb-2" id="ia-input" rows="2" placeholder="O escriba aquí y pulse Enviar…" maxlength="8000"></textarea>
      <div class="d-flex flex-wrap gap-1 align-items-center">
        <button type="button" class="btn btn-primary btn-sm px-3" id="ia-send">Enviar</button>
        <div class="btn-group btn-group-sm" role="group" aria-label="Idioma del micrófono">
          <button type="button" class="btn btn-outline-secondary ia-speech-lang active" data-ia-speech-lang="es-419" title="Reconocimiento español">ES</button>
          <button type="button" class="btn btn-outline-secondary ia-speech-lang" data-ia-speech-lang="en-US" title="English speech">EN</button>
        </div>
        <button type="button" class="btn btn-success btn-sm ia-call-btn" id="ia-call-toggle" title="Hablar como en una llamada" aria-pressed="false">
          <i class="bi bi-telephone-outbound-fill me-1" aria-hidden="true"></i><span class="ia-call-label">Llamada</span>
        </button>
        <span class="text-muted small ms-auto d-none text-truncate" style="max-width:9rem" id="ia-status"></span>
      </div>
    </div>
  </div>
</div>
<button type="button" class="ia-float-btn shadow-lg" id="ia-float-toggle" aria-controls="ia-float-panel" aria-expanded="false" title="Asistente virtual">
  <span class="ia-float-btn-inner"><i class="bi bi-stars" aria-hidden="true"></i></span>
</button>
