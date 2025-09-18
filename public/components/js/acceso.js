let rolSeleccionado2 = null;

// Mostrar y ocultar contraseña
document.addEventListener("DOMContentLoaded", function () {
    // Selleccionar rol
    document.querySelectorAll(".role-card").forEach(card => {
        card.addEventListener("click", function () {
            rolSeleccionado2 = this.getAttribute("data-role"); // alumno, profesor, laboratorio

            // Mensaje opcional
            document.getElementById("mensajeRegistro").innerHTML =
                `<div class="alert alert-info">Rol seleccionado: <b>${rolSeleccionado2}</b></div>`;
        });
    });
});

// Toggle password (funciona con elementos con clase .toggle-password y data-target="idDelInput")
document.addEventListener('click', function (e) {
    const toggler = e.target.closest('.toggle-password');
    if (!toggler) return;
  
    const targetId = toggler.dataset.target;
    if (!targetId) return;
    const input = document.getElementById(targetId);
    if (!input) return;
  
    if (input.type === 'password') {
      input.type = 'text';
      toggler.classList.remove('fa-eye');
      toggler.classList.add('fa-eye-slash');
      toggler.setAttribute('aria-label', 'Ocultar contraseña');
    } else {
      input.type = 'password';
      toggler.classList.remove('fa-eye-slash');
      toggler.classList.add('fa-eye');
      toggler.setAttribute('aria-label', 'Mostrar contraseña');
    }
    // mantiene el foco en el input y evita scroll brusco
    try { input.focus({ preventScroll: true }); } catch (err) { input.focus(); }
  });
  
  // Soporte teclado: Enter o Space activa el toggle si el icono está enfocado
  document.addEventListener('keydown', function (e) {
    const active = document.activeElement;
    if (!active) return;
    if (!active.classList.contains('toggle-password')) return;
  
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      active.click();
    }
  });
  

// Funciones de programacion y control de acceso
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("loginForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const mensaje = document.getElementById("mensajeRegistro");

        // Limpiar mensajes anteriores
        mensaje.innerHTML = "";
        
        if (!email || !password || !rolSeleccionado) {
            mensaje.innerHTML = `<div class="alert alert-danger">Todos los campos son obligatorios y debes seleccionar un rol.</div>`;
            return;
        }

        // Validar formato de email institucional (como en crearCuenta.js)
        const emailRegex = /^[a-z]{2}\d{6}@uaeh\.edu\.mx$/;
        if (!emailRegex.test(email)) {
            mensaje.innerHTML = `<div class="alert alert-danger">El correo no tiene el formato institucional válido.</div>`;
            return;
        }

        try {
            const formData = new FormData();
            formData.append("email", email);
            formData.append("password", password);

            // Mostrar indicador de carga
            mensaje.innerHTML = `<div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Verificando credenciales...</p>
            </div>`;
        

            switch (rolSeleccionado2){
                // Caso para alumnos
                case "Alumno":
                    try{
                        const response = await fetch("../resources/api/Alumnos/apiLogin.php", {
                            method: "POST",
                            body: formData 
                        });
                        const resultText = await response.text();
                        // Si la respuesta es correcta redirige de acuerdo al perfil Alumno
                        if (resultText.includes("Inicio de sesión exitoso")) {
                            window.location.href = `./pages/Alumno/index.php`;
                        } else {
                            mensaje.innerHTML = `<div class="alert alert-danger">${resultText}</div>`;
                        }
                    }catch(error){
                        mensaje.innerHTML = `<div class="alert alert-danger">Error al ingresar. Intenta más tarde.</div>`;
                        console.error(error);
                    }
                break;
                // Caso para Docentes
                case "Docente":
                    try{
                        const response = await fetch("../resources/api/Docente/apiLoginD.php", {
                            method: "POST",
                            body: formData
                        });
                        const resultText = await response.text();
                        // Si la respuesta es correcta redirige al index docente
                        if(resultText.includes("Inicio de sesión exitoso")){
                            window.location.href = `./pages/Docente/index.php`;
                        } else{
                            mensaje.innerHTML = `<div class="alert alert-danger">${resultText}</div>`;
                        }
                    }catch(error){
                        mensaje.innerHTML = `<div class="alert alert-danger">Error al ingresar. Intenta más tarde.</div>`;
                        console.error(error);
                    }
                break;
                // Caso para Laboratorio
                case "Laboratorio":
                    try{
                        const response = await fetch("../resources/api/Laboratorio/apiLoginL.php", {
                            method: "POST",
                            body: formData
                        });
                        const resultText = await response.text();
                        // Si la respuesta es correcta redirige al index Laboratorio
                        if(resultText.includes("Inicio de sesión exitoso")){
                            window.location.href = `./pages/Laboratorio/index.php`;
                        }else{
                            mensaje.innerHTML = `<div class="alert alert-danger">${resultText}</div>`;
                        }
                    }catch(error){
                        mensaje.innerHTML = `<div class="alert alert-danger">Error al ingresar. Intenta más tarde.</div>`;
                        console.error(error);
                    }
                break;
            }
        } catch (error) {
            mensaje.innerHTML = `<div class="alert alert-danger">Error al conectar con el servidor. Por favor intenta nuevamente.</div>`;
            console.error("Error en login:", error);
        }
    });
});