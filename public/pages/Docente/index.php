<?php
session_start();

// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
  require_once dirname(__DIR__, 3) . '/resources/DB/Docente/eventosDB.php';
  $materiaDb = new MateriasDocente();
  $materias = $materiaDb->getMateriasDoc($_SESSION['username']);

?>

<?php
require_once __DIR__ . '/../../../resources/DB/conexion.php';
$conexion = Conexion::getInstancia();
$pdo = $conexion->getDbh();

// Obtengo el id_docente a partir del username de la sesión
$idDocente = 0;
if (isset($_SESSION['username'])) {
    $stmt = $pdo->prepare("SELECT id_docente FROM Docentes WHERE username = :username");
    $stmt->execute([':username' => $_SESSION['username']]);
    $docente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($docente) {
        $idDocente = $docente['id_docente'];
    }
}
?>


<!-- Estructutra del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario-Alumnos</title>
  
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
    <h1 class="fw-bold text-danger"><i class="fa-solid fa-calendar-days"></i> Calendario Alumnos</h1>
  </header>

  <!-- Calendario -->
  <div class="container mb-5">
    <div id="calendar"></div>
  </div>

  <!-- Modal para agregar eventos -->
  <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Agregar Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="eventForm">
            <input type="hidden" id="eventId">
            <div class="mb-3">         

            <input type="hidden" id="docenteId" value="<?= htmlspecialchars($idDocente) ?>">
            <label for="eventTitle" class="form-label">Título del Evento</label>
              <input type="text" class="form-control" id="eventTitle" required>
            </div>            
            <div class="mb-3">
              <label for="eventTitle" class="form-label">Descripción</label>
              <input type="text" class="form-control" id="eventDescription" required>
            </div>            
            <div class="mb-3">
              <label for="eventMateria" class="form-label">Materia</label>
              <select name="materia" id="eventMateria" class="form-control" placeholder="Selecciona una..." required>
              <option value="">Seleccione una materia</option>
                <?php foreach ($materias as $materia): ?>
                    <option value="<?= htmlspecialchars($materia['id_materias']) ?>">
                        <?= htmlspecialchars($materia['nombre']) ?> - Semestre <?= htmlspecialchars($materia['semestre']) ?>
                    </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
            <label for="eventSemestre" class="form-label">Semestre</label>
              <select class="form-select" id="eventSemestre" name="semestre" required>
                  <option value="">Selecciona</option>
                  <option value="1">1</option><option value="2">2</option>
                  <option value="3">3</option><option value="4">4</option>
                  <option value="5">5</option><option value="6">6</option>
                </select>
            </div>
            <div class="mb-3">
              <label for="eventGrupo" class="form-label">Grupo</label>
              <input type="number" class="form-control" id="eventGrupo" min="1">
            </div>
            <div class="mb-3">
              <label for="eventDate" class="form-label">Fecha del Evento</label>
              <input type="date" class="form-control" id="eventDate" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger d-none" id="deleteEventBtn">Eliminar</button>
          <button type="submit" class="btn btn-primary" id="saveEvent" form="eventForm">Guardar Evento</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de eventos registrados -->
  <div class="container mt-5">
    <h2>Eventos Registrados</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Título</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="eventTableBody">
        <!-- Se llenará desde JS -->
      </tbody>
    </table>
  </div>

<div class="my-4">
  <br>
</div>
    
    <?php include '../../../resources/templates/footer.php';?>
    
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Doc/Calendar2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>