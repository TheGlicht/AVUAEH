<nav class="navbar navbar-expand-sm navbar-light bg-primary">
    <a id="username-menu" class="navbar-brand mx-3 text-white">Menú</a>
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
                <a class="nav-link mx-3 text-white" href="./index.php"><i class="fa-solid fa-house"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./Vales.php"><i class="fa-solid fa-flask-vial"></i> Vales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./Materias.php"><i class="fa-solid fa-book"></i> Materias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-3 text-white" href="./Contactos.php"><i class="fa-solid fa-users"></i> Contactos</a>
            </li>
            <li class="nav-item dropdown mx-2">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i> Más
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">                    
                    <li><a class="dropdown-item" href="./Perfil.php"><i class="fa-solid fa-circle-user"></i> Perfil</a></li>
                    <li><a class="dropdown-item" href="./AtencionCliente.php"><i class="fa-solid fa-wrench"></i> Soporte</a></li>
                    <li><a class="dropdown-item" onclick="cerrarSesion()"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a></li>                    
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    // Para obtener los datos del alumno en el menu
    fetch('../../../resources/api/Alumnos/apiData.php')
    .then(response => {
        if(!response.ok) throw new Error("No autorizado");
        return response.json();
    })
    .then(data =>{
        const username = data.username || 'Menú';
        document.getElementById('username-menu').textContent = username;
    })
    .catch(error => {
        console.warm('Error al obtener el nombre de usuario:', error);
        document.getElementById('username-menu').textContent = 'Menú';
    });

    // Para cerrar sesion correctamente
    function cerrarSesion(){
        fetch("../../../resources/api/apiLogout.php")
        .then(()=> {
            window.location.href = "../../../public/index.php";
        })
        .catch(err => console.warm("Error al cerrar sesión", err));
    }
</script>