document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();
  
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const mensaje = document.getElementById("mensajeRegistro");
  
    if (!email || !password || !rolSeleccionado) {
      mensaje.innerHTML = `<div class="alert alert-danger">Todos los campos son obligatorios y debes seleccionar un rol.</div>`;
      return;
    }
  
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);
  
    let url = "";
  
    switch (rolSeleccionado.toLowerCase()) {
      case "alumno":
        url = "./resources/api/Alumno/apiLogin.php";
        break;
      case "profesor":
        url = "./resources/api/Profesor/apiLogin.php";
        break;
      case "laboratorio":
        url = "./resources/api/Laboratorio/apiLogin.php";
        break;
      default:
        mensaje.innerHTML = `<div class="alert alert-warning">Rol no reconocido.</div>`;
        return;
    }
  
    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });
  
      const resultText = await response.text();
  
      if (resultText.includes("Inicio de sesión exitoso")) {
        window.location.href = `./pages/${rolSeleccionado}/index.php`; // redirige según el rol
      } else {
        mensaje.innerHTML = `<div class="alert alert-danger">${resultText}</div>`;
      }
  
    } catch (error) {
      mensaje.innerHTML = `<div class="alert alert-danger">Error en el servidor.</div>`;
      console.error(error);
    }
  });
  