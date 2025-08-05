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

        // // Crear FormData como en tu ejemplo funcional
        // const formData = new FormData();
        // formData.append("email", email);
        // formData.append("password", password);

        // let url = "";
        // let endpoint = "";

        // switch (rolSeleccionado.toLowerCase()) {
        //     case "alumno":
        //         endpoint = "Alumnos";
        //         break;
        //     case "profesor":
        //         endpoint = "Profesor";
        //         break;
        //     case "laboratorio":
        //         endpoint = "Laboratorio";
        //         break;
        //     default:
        //         mensaje.innerHTML = `<div class="alert alert-warning">Rol no reconocido.</div>`;
        //         return;
        // }

        // // Construir URL como en tu ejemplo funcional
        // url = `../../resources/api/${endpoint}/apiLogin.php`;

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

            // Enviar solicitud como en tu ejemplo funcional
            const response = await fetch("../resources/api/Alumnos/apiLogin.php", {
                method: "POST",
                body: formData 
              });
          
            const resultText = await response.text();

            // Verificar respuesta como en tu ejemplo funcional
            if (resultText.includes("Inicio de sesión exitoso")) {
                // Redirigir según el rol
                window.location.href = `./pages/${rolSeleccionado}/index.php`;
            } else {
                mensaje.innerHTML = `<div class="alert alert-danger">${resultText}</div>`;
            }

        } catch (error) {
            mensaje.innerHTML = `<div class="alert alert-danger">Error al conectar con el servidor. Por favor intenta nuevamente.</div>`;
            console.error("Error en login:", error);
        }
    });
});