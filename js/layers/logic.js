/**
 * RANSA - Capa de Lógica de Negocio (Business Rules, Validation & Data Transformation)
 * 
 * Funciones puras independientes del DOM y de la capa HTTP.
 * Centraliza validaciones de entrada, normalización de búsquedas,
 * reglas de asignación y armado de payloads para el backend.
 */

(function (global) {
  'use strict';

  /**
   * Normaliza texto para comparaciones (minúsculas, sin acentos/diacríticos)
   */
  function normalizeText(text) {
    if (typeof text !== 'string') return '';
    return text
      .trim()
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '');
  }

  const RansaLogic = {
    /**
     * Normaliza un string para búsqueda o comparación
     */
    normalize(text) {
      return normalizeText(text);
    },

    /**
     * Determina si un texto coincide con un término de búsqueda (insensible a acentos y mayúsculas)
     */
    matchesSearch(haystack, needle) {
      if (!needle || !needle.trim()) return true;
      if (!haystack) return false;
      const cleanHaystack = normalizeText(String(haystack));
      const cleanNeedle = normalizeText(String(needle));
      return cleanHaystack.includes(cleanNeedle);
    },

    /**
     * Valida los datos del formulario de Roles
     * @param {Object} data - { op, codigo, nombre }
     * @returns {{ isValid: boolean, errors: string[] }}
     */
    validateRole(data = {}) {
      const errors = [];
      const op = String(data.op || '').toUpperCase();
      const nombre = typeof data.nombre === 'string' ? data.nombre.trim() : '';
      const codigo = Number(data.codigo);

      if (!['I', 'M', 'E'].includes(op)) {
        errors.push("Operación no válida. Debe ser 'I' (Insertar), 'M' (Modificar) o 'E' (Eliminar).");
      }

      if (op === 'I' || op === 'M') {
        if (!nombre) {
          errors.push('El nombre del rol es requerido.');
        } else if (nombre.length < 2) {
          errors.push('El nombre del rol debe contener al menos 2 caracteres.');
        } else if (nombre.length > 80) {
          errors.push('El nombre del rol no debe superar los 80 caracteres.');
        }
      }

      if (op === 'M' || op === 'E') {
        if (!codigo || isNaN(codigo) || codigo <= 0) {
          errors.push('Se requiere un identificador válido para modificar o eliminar el registro.');
        }
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    },

    /**
     * Valida la asociación de una Aplicación a un Rol
     * @param {Object} data - { op, cod_rol, cod_aplicacion }
     */
    validateRoleAppAssociation(data = {}) {
      const errors = [];
      const op = String(data.op || '').toUpperCase();
      const codRol = Number(data.cod_rol);
      const codApp = Number(data.cod_aplicacion);

      if (!['I', 'E'].includes(op)) {
        errors.push("Operación de asociación no válida. Debe ser 'I' (Asociar) o 'E' (Desasociar).");
      }
      if (!codRol || isNaN(codRol) || codRol <= 0) {
        errors.push('Identificador de rol no válido.');
      }
      if (!codApp || isNaN(codApp) || codApp <= 0) {
        errors.push('Identificador de aplicación no válido.');
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    },

    /**
     * Valida la asociación de un Rol a un Usuario
     * @param {Object} data - { op, cod_rol, cod_usuario }
     */
    validateUserRoleAssociation(data = {}) {
      const errors = [];
      const op = String(data.op || '').toUpperCase();
      const codRol = Number(data.cod_rol);
      const codUsu = Number(data.cod_usuario);

      if (!['I', 'E'].includes(op)) {
        errors.push("Operación de asignación no válida. Debe ser 'I' (Asignar) o 'E' (Desasignar).");
      }
      if (!codRol || isNaN(codRol) || codRol <= 0) {
        errors.push('Identificador de rol no válido.');
      }
      if (!codUsu || isNaN(codUsu) || codUsu <= 0) {
        errors.push('Identificador de usuario no válido.');
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    },

    /**
     * Valida los datos para cambio de contraseña
     * @param {Object} data - { clave_actual, clave_nueva, clave_confirmar }
     * @returns {{ isValid: boolean, errors: string[] }}
     */
    validatePasswordChange(data = {}) {
      const errors = [];
      const claveActual = typeof data.clave_actual === 'string' ? data.clave_actual : '';
      const claveNueva = typeof data.clave_nueva === 'string' ? data.clave_nueva : '';
      const claveConfirmar = typeof data.clave_confirmar === 'string' ? data.clave_confirmar : '';

      if (!claveActual) {
        errors.push('La contraseña actual es requerida.');
      }
      if (!claveNueva) {
        errors.push('La nueva contraseña es requerida.');
      } else if (claveNueva.length < 4) {
        errors.push('La nueva contraseña debe tener al menos 4 caracteres.');
      }
      if (!claveConfirmar) {
        errors.push('La confirmación de contraseña es requerida.');
      } else if (claveNueva && claveNueva !== claveConfirmar) {
        errors.push('La nueva contraseña y la confirmación no coinciden.');
      }
      if (claveActual && claveNueva && claveActual === claveNueva) {
        errors.push('La nueva contraseña debe ser diferente a la contraseña actual.');
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    },

    /**
     * Valida credenciales de inicio de sesión
     */
    validateLogin(credentials = {}) {
      const errors = [];
      const username = typeof credentials.us === 'string' ? credentials.us.trim() : '';
      const password = typeof credentials.pa === 'string' ? credentials.pa.trim() : '';

      if (!username) {
        errors.push('Por favor ingrese su nombre de usuario.');
      }
      if (!password) {
        errors.push('Por favor ingrese su contraseña.');
      }

      return {
        isValid: errors.length === 0,
        errors
      };
    },

    /**
     * Construye un payload limpio y tipado para la gestión de roles (roles_l.php)
     */
    buildRolePayload(op, codigo, nombre) {
      return {
        op: String(op).toUpperCase(),
        codigo: codigo ? String(codigo).trim() : '',
        nombre: nombre ? String(nombre).trim() : ''
      };
    },

    /**
     * Construye payload para asociación aplicación-rol (roles_aplicaciones_l.php)
     */
    buildRoleAppPayload(op, codRol, codAplicacion) {
      return {
        op: String(op).toUpperCase(),
        cod_rol: Number(codRol),
        cod_aplicacion: Number(codAplicacion)
      };
    },

    /**
     * Construye payload para asociación usuario-rol (apli_roles_l.php)
     */
    buildUserRolePayload(op, codRol, codUsuario) {
      return {
        op: String(op).toUpperCase(),
        cod_rol: Number(codRol),
        cod_usuario: Number(codUsuario)
      };
    },

    /**
     * Filtra filas de una tabla basado en una lista de registros y un criterio de búsqueda
     */
    filterRecords(records, query, fieldExtractor) {
      if (!Array.isArray(records)) return [];
      if (!query || !query.trim()) return records;

      return records.filter((item) => {
        const text = typeof fieldExtractor === 'function' ? fieldExtractor(item) : String(item);
        return this.matchesSearch(text, query);
      });
    }
  };

  // Exponer globalmente
  global.RansaLogic = RansaLogic;

  // Soporte para módulos ES / CommonJS
  if (typeof module !== 'undefined' && module.exports) {
    module.exports = RansaLogic;
  }
})(typeof window !== 'undefined' ? window : globalThis);
