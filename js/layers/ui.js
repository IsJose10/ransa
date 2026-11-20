/**
 * RANSA - Capa de Front / Presentación / UI (DOM Manipulation, Events & Rendering)
 * 
 * Gestiona la interacción del usuario, el renderizado de vistas,
 * la captura de eventos, los estados visuales (carga/feedback)
 * y la delegación hacia las capas de Lógica y Conexión.
 */

(function (global) {
  'use strict';

  // Referencias a las capas colaboradoras
  const Connection = () => global.RansaConnection;
  const Logic = () => global.RansaLogic;

  const RansaUI = {
    /**
     * Sistema unificado de notificaciones visuales (Toasts o Alerts flotantes)
     */
    notify(type = 'info', message = '', title = '') {
      if (global.toast && typeof global.toast[type] === 'function') {
        global.toast[type](message, title);
        return;
      }

      // Si no existe el sistema de toast de CoolAdmin, renderizamos un banner accesible
      const containerId = 'ransa-alert-container';
      let container = document.getElementById(containerId);
      if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '99999';
        container.style.maxWidth = '360px';
        document.body.appendChild(container);
      }

      const alertEl = document.createElement('div');
      const bsType = type === 'error' ? 'danger' : (type === 'warning' ? 'warning' : (type === 'success' ? 'success' : 'primary'));
      alertEl.className = `alert alert-${bsType} alert-dismissible fade show shadow-sm mb-2`;
      alertEl.role = 'alert';
      alertEl.innerHTML = `
        ${title ? `<strong>${title}</strong><br>` : ''}
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;

      container.appendChild(alertEl);
      setTimeout(() => {
        alertEl.classList.remove('show');
        setTimeout(() => alertEl.remove(), 250);
      }, 4000);
    },

    /**
     * Muestra diálogo de confirmación no bloqueante o estándar
     */
    confirm(message) {
      return window.confirm(message);
    },

    /**
     * Módulo de presentación para Gestión de Roles (roles.php / roles_l.php)
     */
    roles: {
      reset() {
        Connection().loadFragment('roles.php', {}, 'contenedor');
      },

      view(codigo) {
        Connection().loadFragment('roles.php', { cod: codigo }, 'contenedor');
      },

      save(op) {
        const form = document.getElementById('form') || (document.forms ? document.forms['form'] : null);
        const opInput = document.getElementById('op');
        const codigoInput = document.getElementById('codigo');
        const nombreInput = document.getElementById('nombre');

        const codigoVal = codigoInput ? codigoInput.value : '';
        const nombreVal = nombreInput ? nombreInput.value : '';

        // Construir payload a través de la capa de Lógica
        const payload = Logic().buildRolePayload(op, codigoVal, nombreVal);

        // Validar negocio mediante la capa de Lógica
        const validation = Logic().validateRole(payload);
        if (!validation.isValid) {
          RansaUI.notify('warning', validation.errors.join(' '));
          if (nombreInput && (!nombreVal || nombreVal.trim().length < 2)) {
            nombreInput.focus();
          }
          return;
        }

        // Si es eliminación, solicitar confirmación
        if (op === 'E') {
          const confirmed = RansaUI.confirm('¿Está seguro de que desea eliminar este rol?');
          if (!confirmed) return;
        }

        // Asignar al input oculto si existe
        if (opInput) opInput.value = op;

        // Delegar envío y actualización a la capa de Conexión
        Connection().loadFragment('roles_l.php', payload, 'contenedor', {
          onSuccess: () => {
            const actionText = op === 'I' ? 'insertado' : (op === 'M' ? 'modificado' : 'eliminado');
            RansaUI.notify('success', `Rol ${actionText} exitosamente.`);
          }
        });
      },

      insert() {
        this.save('I');
      },

      modify() {
        this.save('M');
      },

      remove() {
        this.save('E');
      },

      filterTable(term) {
        RansaUI.filterTableRows('myTable', term, 1);
      }
    },

    /**
     * Módulo de presentación para Asociación Aplicaciones - Roles (roles_aplicaciones.php)
     */
    roleApps: {
      reset() {
        Connection().loadFragment('roles_aplicaciones.php', {}, 'contenedor');
      },

      query() {
        const selectRol = document.getElementById('rol');
        const rolVal = selectRol ? selectRol.value : '';
        if (!rolVal) {
          RansaUI.notify('warning', 'Por favor seleccione un rol.');
          if (selectRol) selectRol.focus();
          return;
        }
        Connection().loadFragment('roles_aplicaciones.php', { rol: rolVal }, 'contenedor');
      },

      toggle(checkboxId, codAplicacion, codRol) {
        const checkbox = document.getElementById(checkboxId);
        const isChecked = checkbox ? checkbox.checked : false;
        const op = isChecked ? 'I' : 'E';

        const payload = Logic().buildRoleAppPayload(op, codRol, codAplicacion);
        const validation = Logic().validateRoleAppAssociation(payload);

        if (!validation.isValid) {
          RansaUI.notify('warning', validation.errors.join(' '));
          if (checkbox) checkbox.checked = !isChecked; // Revertir visualmente
          return;
        }

        Connection().loadFragment('roles_aplicaciones_l.php', payload, 'contenedor', {
          onSuccess: () => {
            const msg = isChecked ? 'Aplicación asociada al rol.' : 'Aplicación desasociada del rol.';
            RansaUI.notify('info', msg);
          },
          onError: () => {
            if (checkbox) checkbox.checked = !isChecked; // Revertir en error
          }
        });
      },

      filterTable(term) {
        RansaUI.filterTableRows('myTable', term, 1);
      }
    },

    /**
     * Módulo de presentación para Asociación Usuarios - Roles (apli_roles.php)
     */
    userRoles: {
      reset() {
        Connection().loadFragment('apli_roles.php', {}, 'contenedor');
      },

      query() {
        const selectUser = document.getElementById('rol');
        const userVal = selectUser ? selectUser.value : '';
        if (!userVal) {
          RansaUI.notify('warning', 'Por favor seleccione un usuario.');
          if (selectUser) selectUser.focus();
          return;
        }
        Connection().loadFragment('apli_roles.php', { rol: userVal }, 'contenedor');
      },

      toggle(checkboxId, codUsuario, codRol) {
        const checkbox = document.getElementById(checkboxId);
        const isChecked = checkbox ? checkbox.checked : false;
        const op = isChecked ? 'I' : 'E';

        const payload = Logic().buildUserRolePayload(op, codRol, codUsuario);
        const validation = Logic().validateUserRoleAssociation(payload);

        if (!validation.isValid) {
          RansaUI.notify('warning', validation.errors.join(' '));
          if (checkbox) checkbox.checked = !isChecked;
          return;
        }

        Connection().loadFragment('apli_roles_l.php', payload, 'contenedor', {
          onSuccess: () => {
            const msg = isChecked ? 'Rol asignado al usuario.' : 'Rol desasignado del usuario.';
            RansaUI.notify('info', msg);
          },
          onError: () => {
            if (checkbox) checkbox.checked = !isChecked;
          }
        });
      },

      filterTable(term) {
        RansaUI.filterTableRows('myTable', term, 1);
      }
    },

    /**
     * Módulo de presentación para Cambio de Contraseña (cambio.php / cambio_l.php)
     */
    password: {
      change() {
        const claveActualInput = document.getElementById('clave_actual');
        const claveNuevaInput = document.getElementById('clave_nueva');
        const claveConfirmarInput = document.getElementById('clave_confirmar');

        const clave_actual = claveActualInput ? claveActualInput.value : '';
        const clave_nueva = claveNuevaInput ? claveNuevaInput.value : '';
        const clave_confirmar = claveConfirmarInput ? claveConfirmarInput.value : '';

        // Validar mediante la capa de Lógica
        const validation = Logic().validatePasswordChange({ clave_actual, clave_nueva, clave_confirmar });
        if (!validation.isValid) {
          RansaUI.notify('warning', validation.errors.join(' '));
          if (!clave_actual && claveActualInput) {
            claveActualInput.focus();
          } else if ((!clave_nueva || clave_nueva.length < 4) && claveNuevaInput) {
            claveNuevaInput.focus();
          } else if (claveConfirmarInput) {
            claveConfirmarInput.focus();
          }
          return;
        }

        // Delegar envío a la capa de Conexión
        Connection().loadFragment('cambio_l.php', { clave_actual, clave_nueva }, 'contenedor');
      },

      toggleVisibility(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById('icon_' + fieldId);
        if (!input || !icon) return;

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      }
    },

    /**
     * Algoritmo de filtrado de filas de tabla en vivo
     */
    filterTableRows(tableId, term, columnIndex = 1) {
      const table = document.getElementById(tableId);
      if (!table) return;

      const rows = table.getElementsByTagName('tr');
      // Empezar en 1 para saltar fila header
      for (let i = 1; i < rows.length; i++) {
        // Si es fila de footer (colspan), no ocultarla
        if (rows[i].querySelector('th[colspan]')) continue;

        const cell = rows[i].getElementsByTagName('td')[columnIndex];
        if (cell) {
          const text = cell.textContent || cell.innerText || '';
          const match = Logic().matchesSearch(text, term);
          rows[i].style.display = match ? '' : 'none';
        }
      }
    },

    /**
     * Vinculación automática de eventos del DOM en vistas cargadas dinámicamente
     */
    bindLoadedView(container) {
      const target = container || document;

      // 1. Vincular filtro de búsqueda de tablas
      const searchInput = target.querySelector('#myInput');
      if (searchInput && !searchInput.dataset.bound) {
        searchInput.dataset.bound = 'true';
        searchInput.addEventListener('input', (e) => {
          this.filterTableRows('myTable', e.target.value, 1);
        });
      }

      // 2. Prevenir submit recargador en formularios estándar
      const forms = target.querySelectorAll('form[onsubmit="return false;"]');
      forms.forEach((f) => {
        if (!f.dataset.bound) {
          f.dataset.bound = 'true';
          f.addEventListener('submit', (e) => e.preventDefault());
        }
      });
    },

    /**
     * Inicialización global de la capa de UI
     */
    init() {
      // Escuchar eventos de carga de fragmentos dinámicos en el contenedor
      document.addEventListener('ransa:fragment-loaded', (e) => {
        this.bindLoadedView(e.detail && e.detail.container);
      });

      // Delegación de eventos para enlaces de navegación del sidebar
      document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-app-url], .js-app-link');
        if (link) {
          e.preventDefault();
          const url = link.getAttribute('data-app-url') || link.getAttribute('href');
          if (url && url !== '#' && !url.startsWith('javascript:')) {
            Connection().loadFragment(url, {}, 'contenedor');
          }
        }
      });

      // Vinculación inicial de la vista actual
      if (document.readyState !== 'loading') {
        this.bindLoadedView(document);
      } else {
        document.addEventListener('DOMContentLoaded', () => this.bindLoadedView(document));
      }
    }
  };

  // Exponer globalmente
  global.RansaUI = RansaUI;

  // Auto-iniciar la capa de UI
  RansaUI.init();

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = RansaUI;
  }
})(typeof window !== 'undefined' ? window : globalThis);
