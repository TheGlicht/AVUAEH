<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Virtual UAEH - Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="icon" href="./components/assets/Garza/Garza3.png">
    <link rel="stylesheet" href="./components/css/style.css">
  
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="./components/assets/Garza/Garza3.png" alt="Logo UAEH" width="40" height="40" class="me-2">
                <span>Agenda Virtual UAEH</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./pages/creaCuenta.php">
                            <i class="fas fa-user-plus me-1"></i> Crear Cuenta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pages/recuperarCuenta.php">
                            <i class="fas fa-key me-1"></i> Recuperar Contraseña
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-primary">Bienvenido a la Agenda Virtual</h1>
            <p class="lead text-muted">
                Selecciona tu rol para acceder a la plataforma educativa de la UAEH
            </p>
        </div>

        <div id="mensajeRegistro" class="mt-4 text-center"></div>


        <div class="role-selector" id="roleSelector">
            <div class="role-card card" data-role="Alumno" id="Alumno">
                <div class="card-body">
                    <div class="role-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="role-title">Alumno</h3>
                    <p>Accede a tus clases, tareas y calendario académico</p>
                </div>
            </div>
            
            <div class="role-card card" data-role="Docente" id="Docente">
                <div class="card-body">
                    <div class="role-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="role-title">Profesor</h3>
                    <p>Gestiona tus cursos, asistencias y materiales</p>
                </div>
            </div>
            
            <div class="role-card card" data-role="Laboratorio" id="Laboratorio">
                <div class="card-body">
                    <div class="role-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3 class="role-title">Laboratorio</h3>
                    <p>Administra recursos y reservas de espacios</p>
                </div>
            </div>
        </div>
        
        <div id="loginContainer" class="login-container">
            <div class="card">
                <div class="card-header">
                    <h2 id="loginTitle">Iniciar Sesión</h2>
                </div>
                <div class="login-form">
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Institucional</label>
                            <input type="email" class="form-control" id="email" placeholder="tu.correo@uaeh.edu.mx" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit">Iniciar Sesión</button>
                        <button type="button" id="backButton" class="btn btn-submit back-btn">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2023 Innovater Code Company. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block mx-2">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                    </div>
                    <div class="d-inline-block mx-2">
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                    <div class="d-inline-block mx-2">
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="./components/js/jquery-3.7.1.js"></script>
    <script src="./components/js/bootstrap.bundle.min.js"></script>
    <script src="./components/js/funcionesModulares.js"></script>
    <script src="./components/js/KitFontAwesome.js"></script>
    <script src="./components/js/acceso.js"></script>
    
</body>
</html>