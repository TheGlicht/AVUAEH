<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CrearCuenta</title>
    <link rel="stylesheet" href="../components/css/styleRegistro.css" />
    <link rel="stylesheet" href="../components/css/bootstrap.min.css" />
    <link rel="icon" type="icon" href="../components/assets/Garza/Garza3.png" />
  </head>
  <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-primary">
        <a class="navbar-brand mx-3 text-white">A crear una cuenta!!</a>
        <button
        class="navbar-toggler d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapsibleNavId"
        aria-controls="collapsibleNavId"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
          <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="nav-link text-white">Ta tengo una cuenta</a>
            </li>
            <li class="nav-item activate">
              <a class="nav-link mx-3 text-white" href="../index.php"
                >Acceder</a
              >
            </li>
          </ul>
        </div>
      </nav>
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-primary">Agenda Virtual UAEH</h1>
        <p class="lead text-secondary">
            Regístrate utilizando tu correo institucional y una contraseña nueva, 
            para poder acceder a diversas funciones que facilitaran tus actividades 
            académicas.
        </p>
      </header>

    <!-- Formulario para registrarse -->
      <main class="text-center align-items-center">
        <form class="form">
            <p class="title">Registro </p>
                <div class="flex">
                <label>
                    <input class="input" type="text" placeholder="" required="">
                    <span>Username</span>
                </label>
        
                <label>
                    <select class="input" required>
                      <option value="" disabled selected>Selecciona tu rol</option>
                      <option value="alumno">Alumno</option>
                      <option value="laboratorio">Laboratorio</option>
                      <option value="profesor">Profesor</option>
                    </select>
                    <span>Rol</span>
                  </label>
                  
            </div>  
                    
            <label>
                <input class="input" type="email" placeholder="" required="">
                <span>Email</span>
            </label> 
                
            <label class="password-container">
                <input id="password" class="input" type="password" placeholder="" required="">
                <span>Contraseña</span>
                <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
              </label>
              
              <label class="password-container">
                <input id="confirmPassword" class="input" type="password" placeholder="" required="">
                <span>Confirmar contraseña</span>
                <i class="fa-solid fa-eye toggle-password" data-target="confirmPassword"></i>
              </label>
              
            <button class="submit">Submit</button>
        </form>
      </main>
      
      
      <footer class="bg-primary">
        <p>&copy; 2023 Innovater Code Company. Todos los derechos reservados.</p>
      </footer>  
    
    <!-- Scripts -->
    <script src="../components/js/jquery-3.7.1.js"></script>
    <script src="../components/js/bootstrap.bundle.min.js"></script>
    <script src="../components/js/KitFontAwesome.js"></script>
    <script>    
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
    </script>
  </body>
</html>
