<?php
session_start();

// Evita caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perfil</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <?php include '../../../resources/templates/menuAlumno.php'; ?>

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
        <form id="formPerfil">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="nombre" name="nombreCompleto" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre de Usuario</label>
              <!-- Precargado desde sesión para que se vea de inmediato -->
              <input type="text" class="form-control" id="usuario" name="username"
                     value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Semestre</label>
              <select class="form-select" id="semestre" name="semestre" required>
                <option value="">Selecciona</option>
                <option value="1">1</option><option value="2">2</option>
                <option value="3">3</option><option value="4">4</option>
                <option value="5">5</option><option value="6">6</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Grupo</label>
              <input type="number" class="form-control" id="grupo" name="grupo" min="1" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Certificados o Reconocimientos (PDF)</label>
              <input type="file" class="form-control" id="certificados" accept="application/pdf" multiple>
              <!-- Nota: este input NO se envía aún a la API. Se deja como futuro extra. -->
            </div>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success">Guardar Perfil</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Avance de Materias (estático de ejemplo) -->
    <div class="card shadow mb-5">
      <div class="card-header bg-secondary text-white fw-bold">
        <i class="fa-solid fa-chart-line"></i> Avance de Materias
      </div>
      <div class="card-body">
        <!-- <ul class="list-group" id="avanceMaterias">
          <li class="list-group-item">
            <strong>Matemáticas</strong>
            <div class="progress mt-2" style="height: 20px;">
              <div class="progress-bar bg-primary" style="width: 80%">80%</div>
            </div>
          </li>
          <li class="list-group-item">
            <strong>Física</strong>
            <div class="progress mt-2" style="height: 20px;">
              <div class="progress-bar bg-success" style="width: 100%">100%</div>
            </div>
          </li>
          <li class="list-group-item">
            <strong>Química</strong>
            <div class="progress mt-2" style="height: 20px;">
              <div class="progress-bar bg-warning" style="width: 60%">60%</div>
            </div>
          </li>
        </ul> -->
        <ul class="list-group" id="avanceMaterias">
          <!-- Aquí se cargarán dinámicamente las materias con su progreso -->
        </ul>
      </div>
    </div>
  </div>

  <?php include '../../../resources/templates/footer.php'; ?>

  <!-- Scripts -->
  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="../../components/js/Alu/Perfil.js"></script>
</body>
</html>
