<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recuperar Contraseña</title>
  <link rel="stylesheet" href="../components/css/styleRegistro.css" />
  <link rel="stylesheet" href="../components/css/bootstrap.min.css" />
  <link rel="icon" type="icon" href="../components/assets/Garza/Garza3.png" />
</head>
<body>
  <nav class="navbar navbar-expand-sm navbar-light bg-primary">
    <a class="navbar-brand mx-3 text-white">¿Olvidaste tu contraseña?</a>
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
      data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
      <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
        <li class="nav-item activate">
          <a class="nav-link mx-3 text-white" href="../index.php">Acceder</a>
        </li>
      </ul>
    </div>
  </nav>

  <header class="container text-center mt-5 mb-3">
    <h1 class="fw-bold text-primary">Recuperación de Contraseña</h1>
    <p class="lead text-secondary">
      Restablece tu contraseña ingresando tu correo institucional y eligiendo una nueva clave segura.
    </p>
  </header>

  <main class="text-center align-items-center">
    <form class="form">
      <p class="title">Actualizar Contraseña</p>

      <label>
        <input class="input" type="email" placeholder="" required />
        <span>Correo institucional</span>
      </label> 

      <label class="password-container">
        <input id="newPassword" class="input" type="password" placeholder="" required />
        <span>Nueva contraseña</span>
        <i class="fa-solid fa-eye toggle-password" data-target="newPassword"></i>
      </label>

      <label class="password-container">
        <input id="confirmNewPassword" class="input" type="password" placeholder="" required />
        <span>Confirmar nueva contraseña</span>
        <i class="fa-solid fa-eye toggle-password" data-target="confirmNewPassword"></i>
      </label>

      <button class="submit" type="submit">Actualizar</button>
    </form>
  </main>

  <footer class="bg-primary mt-4">
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
