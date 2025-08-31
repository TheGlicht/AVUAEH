// Funcion para los botones de contraseñas
document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", () => {
    const targetId = icon.getAttribute("data-target");
    const input = document.getElementById(targetId);
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
    });
});

// Logica del formulario
document.getElementById("registroForm").addEventListener("submit", async function(e) {
    e.preventDefault(); //Evitar recarga de pagina

    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const rol = document.getElementById("rol").value;
    const mensaje = document.getElementById("mensajeRegistro");
  
    // Validar formato institucional
    const emailRegex = /^[a-z]{2}\d{6}@uaeh\.edu\.mx$/;
    if (!emailRegex.test(email)) {
      mensaje.innerHTML = `<div class="alert alert-danger">El correo no tiene el formato institucional válido.</div>`;
      return;
    }
  
    if (password !== confirmPassword) {
      mensaje.innerHTML = `<div class="alert alert-danger">Las contraseñas no coinciden.</div>`;
      return;
    }
  
    // Validacion de los roles
    // if (rol !== "alumno") {
    //   mensaje.innerHTML = `<div class="alert alert-warning">Solo se permite registrar alumnos desde esta interfaz.</div>`;
    //   return;
    // }
  
    switch (rol){
      case "alumno":
        try{
          const formData = new FormData();
          formData.append("username", username);
          formData.append("email", email);
          formData.append("password", password);
          
          const response = await fetch("../../resources/api/Alumnos/apiRegistrerAlumno.php", {
            method: "POST",
            body: formData 
          });
      
          const resultText = await response.text();
      
          mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
          document.getElementById("registroForm").reset();
      
         } catch (error){
          mensaje.innerHTML = `<div class="alert alert-danger">Error al registrar. Intenta más tarde.</div>`;
              console.error(error);
         }
        break;

      case "profesor":
        try{
          const formData = new FormData();
          formData.append("username", username);
          formData.append("email", email);
          formData.append("password", password);

          const response = await fetch("../../resources/api/Docente/apiRegistrerDocente.php", { //Completar ruta
            method: "POST",
            body: formData
          });

          const resultText = await response.text();

          mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
          document.getElementById("registroForm").reset();
        } catch(error){
          mensaje.innerHTML = `<div class="alert alert-danger">Error al registrar. Intenta más tarde.</div>`;
          console.error(error);
        }
        break;

      case "laboratorio":
        try{
          const formData = new FormData();
          formData.append("username", username);
          formData.append("email", email);
          formData.append("password", password);

          const response = await fetch("../../resources/api/Laboratorio/apiRegistrerLab.php", {
            method: "POST",
            body: formData
          });

          const resultText = await response.text();

          mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
          document.getElementById("registroForm").reset();

        } catch(error){
          mensaje.innerHTML = `<div class="alert alert-danger">Error al registrar. Intenta más tarde.</div>`;
          console.error(error);
        }
      break;
      
        default:
          console.log("No se encuentra dicho metodo");
    }
  


});