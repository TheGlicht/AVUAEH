<!-- php logica de programacion -->
<?php
session_start();

// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

<!-- Estructutra del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grupos</title>
  
  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- FullCalendar CSS correcto -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

</head>
<body>
    <?php include '../../../resources/templates/menuDocente.php';?>
    <header class="container text-center mt-5 mb-3">
      <h1 class="fw-bold text-primary"><i class="fa-solid fa-people-group"></i> Grupos</h1>
    </header>

    <div class="container mb-5">
      <!-- Sección: Lista de grupos por materia -->
      <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fa-solid fa-book me-2"></i> Materias y Grupos</h5>
        </div>
        <div class="card-body">
          <div id="grupoContainer" class="row g-3">
            <!-- Se llenará con JS -->
          </div>
        </div>
      </div>

      <!-- Modal: Formar Grupo de Laboratorio -->
      <div class="modal fade" id="modalFormarGrupo" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form id="formGrupoLab">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa-solid fa-flask me-2"></i> Formar Grupo de Laboratorio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body row g-3">
                <div class="col-md-6">
                  <label for="materia" class="form-label">Materia</label>
                  <select id="materia" class="form-select" required>
                    <option selected disabled>Selecciona</option>
                    <option>Química</option>
                    <option>Física</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="grupo" class="form-label">Grupo (Ej: 4A)</label>
                  <input type="text" id="grupo" class="form-control" required>
                </div>
                <div class="col-md-12">
                  <label for="alumnos" class="form-label">Alumnos (números de cuenta separados por coma)</label>
                  <textarea id="alumnos" class="form-control" rows="3" placeholder="Ej: 2023001, 2023002, 2023003"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Botón fijo: formar grupo -->
      <button class="btn btn-success position-fixed bottom-0 end-0 m-4 shadow" data-bs-toggle="modal" data-bs-target="#modalFormarGrupo">
        <i class="fa-solid fa-plus me-1"></i> Formar Grupo de Laboratorio
      </button>
      </div>
          
    <?php include '../../../resources/templates/footer.php';?>
    
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Doc/Grupos.js"></script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>