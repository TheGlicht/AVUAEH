// Verificacion de archivo funcionando
console.log("Archivo modales funcionando");
// Funciones para el index
let loginModalInstance = null;

function showForm(role) {
  const modalTitle = document.getElementById("loginModalLabel");
  modalTitle.textContent =
    "Entrar como " + role.charAt(0).toUpperCase() + role.slice(1);

  const modalElement = document.getElementById("loginModal");
  const modalInstance = new bootstrap.Modal(modalElement);
  
  modalInstance.show();

  // Esperar a que el modal se abra antes de enfocar
  modalElement.addEventListener("shown.bs.modal", () => {
    modalElement.querySelector("input[type='email']").focus();
  }, { once: true });
}



