// Verificación de carga de archivo
console.log("Archivo modales funcionando");

// Instancia global del modal
let loginModalInstance = null;

function showForm(role) {
  const modalTitle = document.getElementById("loginModalLabel");
  modalTitle.textContent =
    "Entrar como " + role.charAt(0).toUpperCase() + role.slice(1);

  const modalElement = document.getElementById("loginModal");

  // Si ya existe una instancia, reutilizarla
  if (!loginModalInstance) {
    loginModalInstance = new bootstrap.Modal(modalElement);
  }

  // Mostrar el modal
  loginModalInstance.show();

  // Solo aplicar el focus cuando el modal está completamente mostrado
  modalElement.addEventListener(
    "shown.bs.modal",
    () => {
      const emailInput = modalElement.querySelector("input[type='email'], input[type='text']");
      if (emailInput) {
        emailInput.focus();
      }
    },
    { once: true }
  );
}



