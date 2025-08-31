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

document.getElementById("passwordForm").addEventListener("submit", async function(e) {
    e.preventDefault(); //Evitar recarga de pagina 

    // Variables para lectura de datos
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("CNewPassword").value;
    const rol = document.getElementById("rol").value;
    const mensaje = document.getElementById("mensajeRegistro");
  
    // Validar formato institucional
    const emailRegex = /^[a-z]{2}\d{6}@uaeh\.edu\.mx$/;
    if (!emailRegex.test(email)) {
        mensaje.innerHTML = `<div class="alert alert-danger">El correo no tiene el formato institucional válido.</div>`;
        return;
    }

    // Validacion de secciones de contraseña
    if (password !== confirmPassword) {
        mensaje.innerHTML = `<div class="alert alert-danger">Las contraseñas no coinciden.</div>`;
        return;
    }

    // Validacion de los roles
    if (rol === "alumno") {
        try {
          const formData = new FormData();
          formData.append("email", email);
          formData.append("password", password); // nombre corregido
    
          const response = await fetch("../../resources/api/Alumnos/apiUpdatePass.php", {
            method: "POST",
            body: formData
          });
    
          const resultText = await response.text();
          mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
          document.getElementById("passwordForm").reset();
    
        } catch (error) {
          mensaje.innerHTML = `<div class="alert alert-danger">Error al actualizar. Intenta más tarde.</div>`;
          console.error(error);
        }
    } else if(rol === "profesor"){
      try{
        const formData = new FormData();
          formData.append("email", email);
          formData.append("password", password); // nombre corregido
    
          const response = await fetch("../../resources/api/Docente/apiUpdatePass.php", {
            method: "POST",
            body: formData
          });
    
          const resultText = await response.text();
          mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
          document.getElementById("passwordForm").reset();
    
      }catch(error){
        mensaje.innerHTML = `<div class="alert alert-danger">Error al actualizar. Intenta más tarde.</div>`;
        console.error(error);
      }
    } else if(rol === "laboratorio"){
      try{
          const formData = new FormData();
            formData.append("email", email);
            formData.append("password", password); // nombre corregido
      
            const response = await fetch("../../resources/api/Laboratorio/apiUpdatePass.php", {
              method: "POST",
              body: formData
            });
      
            const resultText = await response.text();
            mensaje.innerHTML = `<div class="alert alert-success">${resultText}</div>`;
            document.getElementById("passwordForm").reset();
      }catch(error){
        mensaje.innerHTML = `<div class="alert alert-danger">Error al actualizar. Intenta más tarde.</div>`;
        console.error(error);
      }
    }

});