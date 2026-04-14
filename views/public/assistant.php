<?php
/** @var array<string, string> $config */
?>
<section class="ia-page py-5">
  <div class="container ia-shell text-center">
    <h1 class="font-display fw-bold text-primary-deep mb-3">Asistente virtual</h1>
    <p class="text-muted mb-4">Abra la burbuja <strong class="text-primary">azul con estrella</strong> (encima de WhatsApp). Puede escribir en <strong>español o inglés</strong>: la IA responde en el mismo idioma. En <strong>Llamada</strong>, elija <strong>ES</strong> o <strong>EN</strong> según el idioma en que hable para el reconocimiento de voz; la respuesta hablada sigue el idioma del chat.</p>
    <button type="button" class="btn btn-primary btn-lg rounded-pill px-4" id="ia-page-open-bubble">
      <i class="bi bi-stars me-2"></i>Abrir asistente
    </button>
  </div>
</section>

<script>
document.getElementById('ia-page-open-bubble')?.addEventListener('click', function () {
  if (typeof window.openIaAssistantPanel === 'function') window.openIaAssistantPanel();
});
</script>
