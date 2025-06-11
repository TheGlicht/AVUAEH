<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./components/css/styleIndex.css" />
    <link rel="stylesheet" href="./components/css/bootstrap.min.css" />
    <link rel="icon" type="icon" href="./components/assets/Garza/Garza3.png" />
  </head>
  <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-primary">
      <a class="navbar-brand mx-3 text-white">Bienvenido</a>
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
            <a class="nav-link text-white">¿Aún no tienes una cuenta?</a>
          </li>
          <li class="nav-item activate">
            <a class="nav-link mx-3 text-white" href="./pages/creaCuenta.php">Crear Cuenta</a>
          </li>
          <li class="nav-item activate">
            <a class="nav-link mx-3 text-white" href="./pages/recuperarCuenta.php">Recuperar Contraseña</a>
          </li>
        </ul>
      </div>
    </nav>

    <header class="container text-center mt-5 mb-3">
      <h1 class="fw-bold text-primary">Agenda Virtual UAEH</h1>
      <p class="lead text-secondary">
        Ingresa a nuestra plataforma digital. Selecciona la casilla de acuerdo a
        tu rol dentro de la UAEH.
      </p>
    </header>

    <main class="text-center align-items-center">
      <button class="button" onclick="showForm('Alumno')">
        <div class="button-outer">
          <div class="button-inner">
            <span>Alumno</span>
          </div>
        </div>
      </button>

      <button class="button" onclick="showForm('Laboratorio')">
        <div class="button-outer">
          <div class="button-inner">
            <span>Laboratorio</span>
          </div>
        </div>
      </button>

      <button class="button" onclick="showForm('Profesor')">
        <div class="button-outer">
          <div class="button-inner">
            <span>Profesor</span>
          </div>
        </div>
      </button>
    </main>

    <!-- Modal para el formulario de login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Log in</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="form-box">
              <form class="form">
                <span class="title">Entrar</span>
                <span class="subtitle">Ingresa con tu correo institucional</span>
                <div class="form-container">
                  <input type="email" class="input" placeholder="Email" />
                  <input type="password" class="input" placeholder="Password" />
                </div>
                <button type="submit">Sign up</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="bg-primary text-white text-center py-3 mt-auto">
      <p class="mb-0">&copy; 2023 Innovater Code Company. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts -->
    <script src="./components/js/jquery-3.7.1.js"></script>
    <script src="./components/js/bootstrap.bundle.min.js"></script>
    <script src="./components/js/KitFontAwesome.js"></script>
    <script src="./components/js/funcionesModulares.js"></script>
  </body>
</html>
