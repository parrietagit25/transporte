(function () {
  'use strict';

  var root = document.getElementById('ia-assistant-root');
  if (!root) return;

  var panel = document.getElementById('ia-float-panel');
  var toggle = document.getElementById('ia-float-toggle');
  var closeBtn = document.getElementById('ia-float-close');

  var api = root.getAttribute('data-api') || '';
  var csrf = root.getAttribute('data-csrf') || '';
  var messagesEl = document.getElementById('ia-messages');
  var inputEl = document.getElementById('ia-input');
  var sendBtn = document.getElementById('ia-send');
  var callBtn = document.getElementById('ia-call-toggle');
  var callLabel = root.querySelector('.ia-call-label');
  var statusEl = document.getElementById('ia-status');
  var badgeReunion = document.getElementById('ia-badge-reunion');

  var sessionToken = null;
  var lastAssistantText = '';
  var recognition = null;

  var callModeActive = false;
  var voiceTurnActive = false;
  var speechBuffer = '';
  var lastInterim = '';
  var flushTimer = null;
  var currentAudio = null;

  var FLUSH_MS = 1050;
  var MIN_INTERIM_SEND = 4;
  var speechLang = 'es-419';

  function setStatus(t, show) {
    if (!statusEl) return;
    statusEl.textContent = t || '';
    statusEl.classList.toggle('d-none', !show);
  }

  function appendBubble(role, text) {
    if (!messagesEl) return;
    var div = document.createElement('div');
    div.className = 'ia-msg ' + (role === 'user' ? 'ia-msg-user' : 'ia-msg-bot');
    div.textContent = text;
    messagesEl.appendChild(div);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function setPanelOpen(open) {
    if (!panel || !toggle) return;
    if (open) {
      panel.classList.remove('d-none');
      panel.setAttribute('aria-hidden', 'false');
      toggle.setAttribute('aria-expanded', 'true');
      if (inputEl) {
        setTimeout(function () {
          inputEl.focus();
        }, 80);
      }
    } else {
      stopCallMode();
      panel.classList.add('d-none');
      panel.setAttribute('aria-hidden', 'true');
      toggle.setAttribute('aria-expanded', 'false');
    }
  }

  window.openIaAssistantPanel = function () {
    setPanelOpen(true);
  };

  if (toggle && panel) {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      setPanelOpen(panel.classList.contains('d-none'));
    });
  }
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      setPanelOpen(false);
    });
  }

  var navIa = document.getElementById('nav-asistente-ia');
  if (navIa) {
    navIa.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      setPanelOpen(true);
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && panel && !panel.classList.contains('d-none')) {
      setPanelOpen(false);
    }
  });

  document.addEventListener('click', function (e) {
    if (!panel || panel.classList.contains('d-none')) return;
    var t = e.target;
    if (!(t instanceof Node)) return;
    if (panel.contains(t) || (toggle && toggle.contains(t))) return;
    setPanelOpen(false);
  });

  if (panel) {
    panel.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  }

  function postJson(body) {
    return fetch(api, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrf,
      },
      body: JSON.stringify(body),
      credentials: 'same-origin',
    }).then(function (r) {
      return r.text().then(function (txt) {
        var j = {};
        try {
          j = txt ? JSON.parse(txt) : {};
        } catch (x) {
          throw new Error('Respuesta no válida del servidor.');
        }
        if (!r.ok) {
          throw new Error((j && j.error) || txt || 'Error HTTP ' + r.status);
        }
        if (j && j.ok === false) {
          throw new Error(j.error || 'Solicitud rechazada');
        }
        return j;
      });
    });
  }

  function ensureSession() {
    if (sessionToken) return Promise.resolve(sessionToken);
    setStatus('Iniciando sesión…', true);
    return postJson({ action: 'session', _csrf: csrf }).then(function (res) {
      if (!res.ok || !res.session_token) throw new Error(res.error || 'No se pudo crear la sesión');
      sessionToken = res.session_token;
      setStatus('', false);
      return sessionToken;
    });
  }

  function stopListening() {
    if (!recognition) return;
    try {
      recognition.stop();
    } catch (err) {
      /* */
    }
    root.classList.remove('ia-call-listening');
  }

  function clearFlushTimer() {
    if (flushTimer) {
      clearTimeout(flushTimer);
      flushTimer = null;
    }
  }

  function flushSpeechBuffer() {
    flushTimer = null;
    var fromFinals = speechBuffer.replace(/\s+/g, ' ').trim();
    speechBuffer = '';
    var toSend = fromFinals;
    if (!toSend && lastInterim.trim().length >= MIN_INTERIM_SEND) {
      toSend = lastInterim.trim();
      lastInterim = '';
    }
    if (toSend.length < 2) return;
    if (voiceTurnActive || !callModeActive) return;
    sendUserText(toSend);
  }

  function scheduleFlush() {
    clearFlushTimer();
    flushTimer = setTimeout(flushSpeechBuffer, FLUSH_MS);
  }

  function stopCallMode() {
    callModeActive = false;
    voiceTurnActive = false;
    speechBuffer = '';
    lastInterim = '';
    clearFlushTimer();
    stopListening();
    if (currentAudio) {
      try {
        currentAudio.pause();
        currentAudio.currentTime = 0;
      } catch (e) {
        /* */
      }
      currentAudio = null;
    }
    var el = document.getElementById('ia-tts-player');
    if (el) {
      try {
        el.pause();
        el.removeAttribute('src');
        el.load();
      } catch (e2) {
        /* */
      }
    }
    root.classList.remove('ia-call-active', 'ia-call-listening');
    if (callBtn) {
      callBtn.classList.remove('btn-danger');
      callBtn.classList.add('btn-success');
      callBtn.setAttribute('aria-pressed', 'false');
      if (callLabel) callLabel.textContent = 'Llamada';
    }
  }

  /** Desbloquea audio tras gesto del usuario (autoplay con respuesta async). */
  function primeAutoplayOnUserGesture() {
    try {
      var AC = window.AudioContext || window.webkitAudioContext;
      if (AC) {
        if (!window.__iaAudioCtx) {
          window.__iaAudioCtx = new AC();
        }
        var ctx = window.__iaAudioCtx;
        if (ctx.state === 'suspended') {
          ctx.resume();
        }
        var buf = ctx.createBuffer(1, Math.ceil(ctx.sampleRate * 0.04), ctx.sampleRate);
        var src = ctx.createBufferSource();
        src.buffer = buf;
        src.connect(ctx.destination);
        src.start(0);
      }
    } catch (e1) {
      /* */
    }

    var el = document.getElementById('ia-tts-player');
    if (!el) return;
    try {
      el.muted = true;
      el.volume = 0.01;
      el.src =
        'data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQAAAAA=';
      var p = el.play();
      if (p && typeof p.then === 'function') {
        p.then(function () {
          el.pause();
          el.currentTime = 0;
          el.muted = false;
          el.volume = 1;
          el.removeAttribute('src');
          el.load();
        }).catch(function () {
          el.muted = false;
          el.volume = 1;
        });
      }
    } catch (e2) {
      el.muted = false;
      el.volume = 1;
    }
  }

  function getTtsPlayer() {
    var el = document.getElementById('ia-tts-player');
    if (!el) {
      el = document.createElement('audio');
      el.id = 'ia-tts-player';
      el.setAttribute('playsinline', '');
      el.setAttribute('preload', 'none');
      el.style.cssText = 'position:absolute;width:0;height:0;opacity:0;pointer-events:none';
      root.appendChild(el);
    }
    return el;
  }

  function micContextOk() {
    if (window.isSecureContext) return true;
    var h = location.hostname || '';
    return h === 'localhost' || h === '127.0.0.1' || h === '[::1]';
  }

  function startCallMode() {
    if (!recognition) {
      setStatus('Este navegador no soporta reconocimiento de voz.', true);
      return;
    }
    if (!micContextOk()) {
      setStatus('Abra el sitio por HTTPS o localhost para usar el micrófono.', true);
    }
    callModeActive = true;
    speechBuffer = '';
    lastInterim = '';
    root.classList.add('ia-call-active');
    if (callBtn) {
      callBtn.classList.remove('btn-success');
      callBtn.classList.add('btn-danger');
      callBtn.setAttribute('aria-pressed', 'true');
      if (callLabel) callLabel.textContent = 'Colgar';
    }
    setStatus('Escuchando… hable con naturalidad', true);
    try {
      recognition.start();
      root.classList.add('ia-call-listening');
    } catch (err) {
      setStatus('No se pudo iniciar el micrófono. Cierre y pulse Llamada otra vez.', true);
    }
  }

  function restartListeningSoon() {
    if (!callModeActive || !recognition || voiceTurnActive) return;
    setTimeout(function () {
      if (!callModeActive || voiceTurnActive) return;
      try {
        recognition.start();
        root.classList.add('ia-call-listening');
        setStatus('Escuchando…', true);
      } catch (err) {
        /* ya activo */
      }
    }, 420);
  }

  function playTtsForText(text) {
    text = (text || '').trim();
    if (!text) return Promise.resolve();
    return postJson({ action: 'tts', _csrf: csrf, text: text })
      .then(function (res) {
        if (!res.ok || !res.audio_base64) throw new Error(res.error || 'TTS falló');
        var bin = atob(res.audio_base64);
        var bytes = new Uint8Array(bin.length);
        for (var i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
        var blob = new Blob([bytes], { type: res.mime || 'audio/mpeg' });
        var url = URL.createObjectURL(blob);
        var el = getTtsPlayer();
        return new Promise(function (resolve, reject) {
          el.onended = function () {
            currentAudio = null;
            URL.revokeObjectURL(url);
            el.onerror = null;
            resolve();
          };
          el.onerror = function () {
            currentAudio = null;
            URL.revokeObjectURL(url);
            reject(new Error('No se pudo reproducir el audio.'));
          };
          try {
            el.pause();
          } catch (e0) {
            /* */
          }
          el.src = url;
          el.muted = false;
          el.volume = 1;
          currentAudio = el;
          setStatus('Respondiendo…', true);
          var p = el.play();
          if (p && typeof p.then === 'function') {
            p.catch(function (err) {
              URL.revokeObjectURL(url);
              reject(err || new Error('El navegador bloqueó el audio.'));
            });
          }
        });
      });
  }

  function sendUserText(text) {
    text = (text || '').trim();
    if (!text) return Promise.resolve();
    if (voiceTurnActive) return Promise.resolve();
    if (text.length > 8000) text = text.slice(0, 8000);

    voiceTurnActive = true;
    stopListening();
    clearFlushTimer();
    speechBuffer = '';
    lastInterim = '';

    appendBubble('user', text);
    if (inputEl && inputEl.value.trim() === text) inputEl.value = '';
    setStatus('Pensando…', true);
    if (sendBtn) sendBtn.disabled = true;

    return ensureSession()
      .then(function (tok) {
        return postJson({
          action: 'chat',
          _csrf: csrf,
          session_token: tok,
          message: text,
        });
      })
      .then(function (res) {
        if (!res.ok) throw new Error(res.error || 'Error');
        lastAssistantText = res.reply || '';
        appendBubble('assistant', lastAssistantText);
        if (res.reunion_id && badgeReunion) {
          badgeReunion.classList.remove('d-none');
          setTimeout(function () {
            badgeReunion.classList.add('d-none');
          }, 6000);
        }
        if (callModeActive && lastAssistantText) {
          return playTtsForText(lastAssistantText).catch(function (err) {
            var m = err && err.message ? err.message : String(err);
            setStatus('Voz: ' + m, true);
            appendBubble('assistant', '(No se pudo leer en voz: ' + m + '). Puede seguir escribiendo.');
          });
        }
      })
      .catch(function (e) {
        var err = 'Lo siento, hubo un error: ' + (e.message || String(e));
        appendBubble('assistant', err);
        if (callModeActive) {
          return playTtsForText('Error de conexión.').catch(function () {});
        }
      })
      .finally(function () {
        voiceTurnActive = false;
        if (sendBtn) sendBtn.disabled = false;
        if (callModeActive) {
          setStatus('Escuchando…', true);
          restartListeningSoon();
        } else {
          setStatus('', false);
        }
      });
  }

  function sendMessage() {
    var text = (inputEl && inputEl.value) ? inputEl.value.trim() : '';
    if (!text) return;
    inputEl.value = '';
    sendUserText(text);
  }

  if (sendBtn) {
    sendBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      sendMessage();
    });
  }
  if (inputEl) {
    inputEl.addEventListener('keydown', function (ev) {
      if (ev.key === 'Enter' && !ev.shiftKey) {
        ev.preventDefault();
        sendMessage();
      }
    });
  }

  function initSpeech() {
    var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SR) {
      if (callBtn) {
        callBtn.disabled = true;
        callBtn.title = 'Voz no disponible en este navegador';
      }
      return;
    }
    recognition = new SR();
    recognition.lang = speechLang;
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onresult = function (ev) {
      if (!callModeActive || voiceTurnActive) return;
      for (var i = ev.resultIndex; i < ev.results.length; i++) {
        var row = ev.results[i];
        var transcript = row[0] && row[0].transcript ? row[0].transcript.trim() : '';
        if (!transcript) continue;
        if (row.isFinal) {
          lastInterim = '';
          speechBuffer = (speechBuffer ? speechBuffer + ' ' : '') + transcript;
        } else {
          lastInterim = transcript;
        }
      }
      if (speechBuffer || lastInterim) {
        scheduleFlush();
      }
    };

    recognition.onerror = function (ev) {
      root.classList.remove('ia-call-listening');
      if (!callModeActive) return;
      if (ev.error === 'no-speech' || ev.error === 'aborted') return;
      if (ev.error === 'not-allowed') {
        setStatus('Permita el micrófono en la barra del navegador.', true);
        stopCallMode();
        return;
      }
      setStatus('Micrófono: ' + (ev.error || 'error'), true);
    };

    recognition.onend = function () {
      root.classList.remove('ia-call-listening');
      if (!callModeActive || voiceTurnActive) return;
      setTimeout(function () {
        if (!callModeActive || voiceTurnActive) return;
        try {
          recognition.start();
          root.classList.add('ia-call-listening');
        } catch (err) {
          /* */
        }
      }, 160);
    };
  }

  function applySpeechLangButtons() {
    var btns = root.querySelectorAll('.ia-speech-lang');
    btns.forEach(function (b) {
      var lang = b.getAttribute('data-ia-speech-lang') || '';
      b.classList.toggle('active', lang === speechLang);
    });
  }

  function setSpeechLang(lang) {
    if (!lang) return;
    speechLang = lang;
    if (recognition) {
      recognition.lang = speechLang;
    }
    applySpeechLangButtons();
    if (callModeActive && recognition && !voiceTurnActive) {
      stopListening();
      setTimeout(function () {
        if (!callModeActive || voiceTurnActive) return;
        try {
          recognition.start();
          root.classList.add('ia-call-listening');
        } catch (err) {
          /* */
        }
      }, 200);
    }
  }

  root.querySelectorAll('.ia-speech-lang').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var lang = btn.getAttribute('data-ia-speech-lang');
      if (lang) setSpeechLang(lang);
    });
  });
  applySpeechLangButtons();

  if (callBtn) {
    callBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (!recognition) return;
      if (callModeActive) {
        stopCallMode();
        setStatus('', false);
      } else {
        primeAutoplayOnUserGesture();
        startCallMode();
      }
    });
  }

  initSpeech();
})();
