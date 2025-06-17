<!-- php logica de programacion -->

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
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-calendar-days"></i> Calendario Alumnos</h1>
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
              <label for="eventTitle" class="form-label">Título del Evento</label>
              <input type="text" class="form-control" id="eventTitle" required>
            </div>            
            <div class="mb-3">
              <label for="eventTitle" class="form-label">Descripción</label>
              <input type="text" class="form-control" id="eventDescription" required>
            </div>
            <div class="mb-3">
              <label for="eventHour" class="form-label">Hora</label>
              <input type="time" class="form-control" id="eventHour" required>
            </div>
            <div class="mb-3">
              <label for="eventMateria" class="form-label">Materia</label>
              <select name="materia" id="eventMateria" class="form-control" placeholder="Selecciona una..." required></select>
            </div>
            <div class="mb-3">
            <label for="eventSemestre" class="form-label">Semestre</label>
            <select name="semestre" id="eventSemestre" class="form-control" placeholder="Selecciona una..." required></select>
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
    <script src="../../components/js/Doc/Calendar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</body>
</html>