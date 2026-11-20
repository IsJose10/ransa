/**
 * RANSA - Interacciones específicas de autenticación
 */

(function (global) {
  'use strict';

  function showRedirectMessage() {
    const params = new URLSearchParams(global.location ? global.location.search : '');
    const messages = {
      error: ['warning', 'Los datos de acceso son incorrectos.'],
      logout: ['success', 'Sesión cerrada correctamente.'],
      session: ['warning', 'Su sesión ha expirado, por favor vuelva a autenticarse.']
    };
    const message = messages[params.get('status')];
    if (message && global.RansaUI) {
      global.RansaUI.notify(message[0], message[1]);
    }
  }

  global.togglePasswordVisibility = function () {
    const passwordInput = document.getElementById('pa');
    const icon = document.getElementById('togglePasswordIcon');
    if (!passwordInput || !icon) return;

    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    icon.classList.toggle('fa-eye', !isHidden);
    icon.classList.toggle('fa-eye-slash', isHidden);
  };

  global.handleLoginSubmit = function (event) {
    const username = document.getElementById('us')?.value || '';
    const password = document.getElementById('pa')?.value || '';
    const validation = global.RansaLogic.validateLogin({ us: username, pa: password });

    if (!validation.isValid) {
      event.preventDefault();
      global.RansaUI.notify('warning', validation.errors.join(' '));
      return false;
    }

    const btn = document.getElementById('btnSubmit');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Ingresando...';
    }
    return true;
  };

  function bindLoginControls() {
    const form = document.getElementById('loginForm');
    const toggle = document.getElementById('btnTogglePassword');
    const searchForm = document.querySelector('.ransa-search-form');

    form?.addEventListener('submit', global.handleLoginSubmit);
    toggle?.addEventListener('click', global.togglePasswordVisibility);
    searchForm?.addEventListener('submit', (event) => event.preventDefault());
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      showRedirectMessage();
      bindLoginControls();
    });
  } else {
    showRedirectMessage();
    bindLoginControls();
  }
})(typeof window !== 'undefined' ? window : globalThis);
