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
  <title>Laboratorios</title>
  
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
            <h5 class="modal-title">Programar Práctica</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label for="materia" class="form-label">Materia</label>
              <!-- Select para cargar materias con labortaorio -->
             <select class="form-select" name="materiaKit" id="materiaKit"></select>
            </div>
            <div class="col-md-6">
              <label for="docente" class="form-label">Docente</label>
              <!-- Select para cargar a los profesores que tengan esa materia -->
              <select id="docenteLab" class="form-select" required>                
              </select>
            </div>
            <div class="col-md-6">
              <label for="grupo" class="form-label">Semestre</label>
              <select name="semestre" id="semestre"  class="form-select">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="grupo" class="form-label">Grupo</label>
              <input type="number" min="1" class="form-control" id="grupo" required>
            </div>
            <div class="col-md-6">
              <label for="laboratorio" class="form-label">Laboratorio</label>
                  <select id="laboratorio" class="form-select" required>
                    <option value="" disabled selected>Selecciona un laboratorio</option>
                    <option value="labElctronica" id="labElctronica">Lab. Electrónica</option>
                    <option value="labControl" id="labControl">Lab. Control</option>
                    <option value="labFisQui" id="labFisQui">Lab. Fisico-Quimica</option>
                    <option value="lab1" id="lab1">Laboratorio 1</option>
                    <option value="lab2" id="lab2">Laboratorio 2</option>
                    <option value="lab3" id="lab3">Laboratorio 3</option>
                    <option value="lab4" id="lab4">Laboratorio 4</option>
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
              <label for="kit" class="form-label">Kit de materiales</label>
              <!-- Este select sera automatico de acuerdo a la materia de la practica -->
              <select id="kit" class="form-select"></select>
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