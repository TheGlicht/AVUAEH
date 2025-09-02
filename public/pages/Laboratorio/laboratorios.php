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
  <title>Home</title>
  
  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- FullCalendar CSS correcto -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

</head>
<body>
    <?php include '../../../resources/templates/menuLab.php';?>
    
    <div class="container my-4">
    <h2 class="mb-4 text-center">📅 Gestión de Prácticas y Laboratorios</h2>

    <!-- Filtros -->
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filtroLaboratorio" class="form-select">
          <option value="todos">Todos los Laboratorios</option>
          <option value="lab1">Laboratorio 1</option>
          <option value="lab2">Laboratorio 2</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="filtroMateria" class="form-select">
          <option value="todas">Todas las Materias</option>
          <option value="quimica">Química</option>
          <option value="fisica">Física</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="filtroDocente" class="form-select">
          <option value="todos">Todos los Docentes</option>
          <option value="profa">Profa. García</option>
          <option value="profb">Prof. Ramírez</option>
        </select>
      </div>
      <div class="col-md-3 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPreprogramar">
          <i class="fa-solid fa-calendar-plus me-1"></i> Preprogramar práctica
        </button>
      </div>
    </div>

    <!-- Calendario -->
    <div id="calendarioLaboratorios"></div>
  </div>

  <!-- Modal: Preprogramar práctica -->
  <div class="modal fade" id="modalPreprogramar" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="formPreprogramar">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Preprogramar Práctica</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label for="materia" class="form-label">Materia</label>
              <select id="materia" class="form-select" required>
                <option selected disabled>Selecciona</option>
                <option value="quimica">Química</option>
                <option value="fisica">Física</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="docente" class="form-label">Docente</label>
              <select id="docente" class="form-select" required>
                <option selected disabled>Selecciona</option>
                <option value="profa">Profa. García</option>
                <option value="profb">Prof. Ramírez</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="grupo" class="form-label">Grupo</label>
              <input type="text" class="form-control" id="grupo" required>
            </div>
            <div class="col-md-6">
              <label for="laboratorio" class="form-label">Laboratorio</label>
              <select id="laboratorio" class="form-select" required>
                <option selected disabled>Selecciona</option>
                <option value="lab1">Laboratorio 1</option>
                <option value="lab2">Laboratorio 2</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="fecha" class="form-label">Fecha</label>
              <input type="date" id="fecha" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="hora" class="form-label">Hora</label>
              <input type="time" id="hora" class="form-control" required>
            </div>
            <div class="col-md-12">
              <label for="guia" class="form-label">Guía de práctica (PDF)</label>
              <input type="file" id="guia" class="form-control" accept=".pdf">
            </div>
            <div class="col-md-12">
              <label for="kit" class="form-label">Kit de materiales</label>
              <select id="kit" class="form-select">
                <option selected disabled>Selecciona un kit</option>
                <option value="kit1">Kit de Química Básica</option>
                <option value="kit2">Kit Física Óptica</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

    
    <?php include '../../../resources/templates/footer.php';?>
    
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="../../components/js/Lab/Laboratorios.js"></script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>