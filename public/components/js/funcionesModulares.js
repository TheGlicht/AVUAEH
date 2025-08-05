console.log("Archivo modulares funcionando");

let rolSeleccionado = "";

document.addEventListener('DOMContentLoaded', function() {
    // Selección de rol
    const roleCards = document.querySelectorAll('.role-card');
    const roleSelector = document.getElementById('roleSelector');
    const loginContainer = document.getElementById('loginContainer');
    const loginTitle = document.getElementById('loginTitle');
    const backButton = document.getElementById('backButton');
    
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remover selección anterior
            roleCards.forEach(c => c.classList.remove('selected'));
            
            // Seleccionar nuevo rol
            this.classList.add('selected');
            rolSeleccionado = this.dataset.role;
            
            // Actualizar título del login
            const roleName = rolSeleccionado.charAt(0).toUpperCase() + rolSeleccionado.slice(1);
            loginTitle.textContent = `Iniciar Sesión como ${roleName}`;
            
            // Mostrar formulario de login
            roleSelector.style.display = 'none';
            loginContainer.classList.add('active');
            
            // Enfocar campo de email
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 300);
        });
    });
    
    // Botón para volver a selección de roles
    backButton.addEventListener('click', function() {
        loginContainer.classList.remove('active');
        roleSelector.style.display = 'flex';
        rolSeleccionado = "";
        
        // Deseleccionar tarjeta
        roleCards.forEach(card => card.classList.remove('selected'));
    });
});