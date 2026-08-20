/* assets/js/main.js */

document.addEventListener('DOMContentLoaded', () => {
  console.log('SICAWN cargado correctamente.');

  // Punto de entrada general de la interfaz.
  // Cada módulo (login, padrón, pagos, reportes) puede
  // registrar aquí su función de inicialización.
  inicializarModulos();
});

function inicializarModulos() {
  // Ejemplo de patrón a seguir conforme se agreguen módulos:
  // if (document.querySelector('#form-login')) inicializarLogin();
  // if (document.querySelector('#tabla-padron')) inicializarPadron();
}
