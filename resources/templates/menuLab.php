<nav class="navbar navbar-expand-sm navbar-light bg-primary">
    <a class="navbar-brand mx-3 text-white">Menú</a>
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

            <li class="nav-item activate">
                <a class="nav-link mx-3 text-white" href="./index.php">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./inventario.php">
                    <i class="fa-solid fa-boxes-stacked"></i> Inventario
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./laboratorios.php">
                    <i class="fa-solid fa-flask-vial"></i> Laboratorios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./solicitudes.php">
                    <i class="fa-solid fa-envelope-open-text"></i> Solicitudes
                </a>
            </li>
            <li class="nav-item dropdown mx-2">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i> Más
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="./kits.php">
                            <i class="fa-solid fa-toolbox"></i> Kits de Laboratorio
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="./reportes.php">
                            <i class="fa-solid fa-file-circle-exclamation"></i> Reportes de Daños
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="./soporte.php">
                            <i class="fa-solid fa-wrench"></i> Soporte
                        </a>
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a></li>
                </ul>
            </li>

        </ul>
    </div>
</nav>
