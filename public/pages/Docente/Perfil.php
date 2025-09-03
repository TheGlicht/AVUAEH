<!-- php logica de programacion -->
<?php
session_start();

// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
  require_once dirname(__DIR__, 3) . '/resources/DB/Alumno/materiasDB.php';
    $materiaDb = new MateriaDb();
    $materias = $materiaDb->showMateria();
?>

<!-- Estructura del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuDocente.php';?>

  <header class="container text-center mt-5 mb-3">
    <h1 class="fw-bold text-danger"><i class="fa-solid fa-circle-user"></i> Perfil</h1>
  </header>

  <div class="container my-5" style="max-width: 900px;">
    <!-- Formulario de Perfil -->
    <div class="card mb-4 shadow">
      <div class="card-header bg-primary text-white fw-bold">
        <i class="fa-solid fa-user-pen"></i> Información del Perfil
      </div>
      <div class="card-body">
        <form id="profileForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="nombre" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre de Usuario</label>
              <input type="text" class="form-control" id="usuario" required>
            </div>           
            <div class="col-md-12">
              <label class="form-label">Correo electronico</label>
              <!-- Aqui debe de ir un input para mostrar el correo electronico -->
            </div>
          </div>
          <div class="d-grid mt-4">
            <!-- Boton para guardar datos del docente -->
            <button type="submit" class="btn btn-success">Guardar Perfil</button>
          </div>
        </form>
      </div>
    </div>

        <!-- Tabla de materias agregadas -->
        <div class="container my-5" style="max-width: 1000px;">
          <div class="card mb-4 shadow">
            <div class="card-header bg-primary text-white fw-bold">
              <i class="fa-solid fa-plus"></i> Agregar Materia
            </div>
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <div class="input-group mb-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar materia...">
                    <button class="btn btn-outline-secondary" id="searchBtn" type="button">Buscar</button>
                  </div>
                  <select id="materiaSelect" class="form-select" required>
                    <option value="">Seleccione una materia</option>
                    <?php foreach ($materias as $materia): ?>
                        <option value="<?= htmlspecialchars($materia['id_materias']) ?>">
                            <?= htmlspecialchars($materia['nombre']) ?> - Semestre <?= htmlspecialchars($materia['semestre']) ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 text-end">
                  <!-- Boton para guardar la relacion DocMAterias -->
                  <button type="button" id="agregarMateria" class="btn btn-success mt-2">
                    <i class="fa-solid fa-plus"></i> Agregar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <form id="formMaterias">
    <div class="card shadow">
      <div class="card-header bg-secondary text-white fw-bold">
        <i class="fa-solid fa-list-check"></i> Materias Registradas
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered text-center align-middle" id="tablaMaterias">
          <thead class="table-dark">
            <tr>
              <th>Materia</th>              
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </form>
</div>
</div>



  <?php include '../../../resources/templates/footer.php';?>

  <!-- Scripts -->
  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="../../components/js/Doc/Perfil.js"></script>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>