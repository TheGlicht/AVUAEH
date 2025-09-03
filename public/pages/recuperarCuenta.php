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
  <nav class="navbar navbar-expand-sm navbar-light bg-warning">
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
    <h1 class="fw-bold text-danger">Recuperación de Contraseña</h1>
    <p class="lead text-secondary">
      Restablece tu contraseña ingresando tu correo institucional y eligiendo una nueva clave segura.
    </p>
  </header>

   <!-- Para mostrar mensajes -->
   <div id="mensajeRegistro" class="mt-3"></div>

  <main class="text-center align-items-center">
    <form id="passwordForm" class="form">
      <p class="title text-danger">Actualizar Contraseña</p>

      <label>
        <select id="rol" class="input" required>
          <option value="" disabled selected>Selecciona tu rol</option>
          <option value="alumno">Alumno</option>
          <option value="laboratorio">Laboratorio</option>
          <option value="profesor">Profesor</option>
        </select>
        <span>Rol</span>
      </label>  

      <label>
        <input id="email" class="input" type="email" placeholder="" required />
        <span>Correo institucional</span>
      </label> 

      <label class="password-container">
        <input id="newPassword" class="input" type="password" placeholder="" required />
        <span>Nueva contraseña</span>
        <i class="fa-solid fa-eye toggle-password" data-target="newPassword"></i>
      </label>

      <label class="password-container">
        <input id="CNewPassword" class="input" type="password" placeholder="" required />
        <span>Confirmar nueva contraseña</span>
        <i class="fa-solid fa-eye toggle-password" data-target="CNewPassword"></i>
      </label>

      <button class="submit bg-danger" id="btnActualizar type="submit">Actualizar</button>
    </form>
  </main>
  
  <footer class="bg-warning mt-4">
    <p>&copy; 2023 Innovater Code Company. Todos los derechos reservados.</p>
  </footer>

  <!-- Scripts -->
  <script src="../components/js/jquery-3.7.1.js"></script>
  <script src="../components/js/bootstrap.bundle.min.js"></script>
  <script src="../components/js/KitFontAwesome.js"></script>
  <script src="../components/js/recuperarPas.js"></script>
</body>
</html>
