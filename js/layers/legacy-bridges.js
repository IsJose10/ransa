/**
 * RANSA - Compatibilidad con markup legado
 *
 * Mantiene los nombres globales usados por fragmentos PHP antiguos,
 * delegando toda la operación en RansaUI.
 */

(function (global) {
  'use strict';

  const getViewTitle = () => document.querySelector('h3')?.textContent || '';
  const isUserRoleView = () => getViewTitle().includes('USUARIOS');

  global.restablecer = function () {
    const hasRoleForm = document.getElementById('codigo') && document.getElementById('nombre');
    const hasRoleSelect = document.getElementById('rol');

    if (hasRoleForm) {
      global.RansaUI.roles.reset();
    } else if (hasRoleSelect && isUserRoleView()) {
      global.RansaUI.userRoles.reset();
    } else {
      global.RansaUI.roleApps.reset();
    }
  };

  global.ver = function (cod) {
    global.RansaUI.roles.view(cod);
  };

  global.insertar = function () {
    global.RansaUI.roles.insert();
  };

  global.modificar = function () {
    global.RansaUI.roles.modify();
  };

  global.eliminar = function () {
    global.RansaUI.roles.remove();
  };

  global.consultar = function () {
    if (isUserRoleView()) {
      global.RansaUI.userRoles.query();
    } else {
      global.RansaUI.roleApps.query();
    }
  };

  global.actualizar = function (nombre, p1, p2) {
    if (isUserRoleView()) {
      global.RansaUI.userRoles.toggle(nombre, p1, p2);
    } else {
      global.RansaUI.roleApps.toggle(nombre, p1, p2);
    }
  };

  global.myFunction = function () {
    const input = document.getElementById('myInput');
    global.RansaUI.filterTableRows('myTable', input ? input.value : '', 1);
  };
})(typeof window !== 'undefined' ? window : globalThis);
