<!-- php logica de programacion -->

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
    <h2 class="mb-4 text-center">📝 Solicitudes de Material de Alumnos</h2>

    <!-- Filtros -->
    <div class="row mb-3">
      <div class="col-md-3">
        <input type="text" id="filtroGrupo" class="form-control" placeholder="Filtrar por grupo" />
      </div>
      <div class="col-md-3">
        <input type="text" id="filtroMateria" class="form-control" placeholder="Filtrar por materia" />
      </div>
      <div class="col-md-3">
        <input type="date" id="filtroFecha" class="form-control" />
      </div>
    </div>

    <!-- Tabla de solicitudes -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>Alumno</th>
            <th>Materiales Solicitados</th>
            <th>Fecha Práctica</th>
            <th>Hora</th>
            <th>Grupo</th>
            <th>Materia</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody id="tablaSolicitudes">
          <tr>
            <td>Juan Pérez</td>
            <td>Vasos de precipitado, Ácido clorhídrico</td>
            <td>2025-06-14</td>
            <td>10:00</td>
            <td>Grupo A</td>
            <td>Química</td>
            <td>
              <button class="btn btn-success btn-sm me-1"><i class="fa-solid fa-check"></i></button>
              <button class="btn btn-danger btn-sm me-1"><i class="fa-solid fa-xmark"></i></button>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalModificar"><i class="fa-solid fa-pen"></i></button>
            </td>
          </tr>
          <!-- Más solicitudes aquí -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal: Modificar Solicitud -->
  <div class="modal fade" id="modalModificar" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="formModificarSolicitud">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title">Modificar Solicitud</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label for="modAlumno" class="form-label">Alumno</label>
              <input type="text" class="form-control" id="modAlumno" readonly />
            </div>
            <div class="col-md-6">
              <label for="modGrupo" class="form-label">Grupo</label>
              <input type="text" class="form-control" id="modGrupo" />
            </div>
            <div class="col-md-6">
              <label for="modMateria" class="form-label">Materia</label>
              <input type="text" class="form-control" id="modMateria" />
            </div>
            <div class="col-md-6">
              <label for="modFecha" class="form-label">Fecha</label>
              <input type="date" class="form-control" id="modFecha" />
            </div>
            <div class="col-md-6">
              <label for="modHora" class="form-label">Hora</label>
              <input type="time" class="form-control" id="modHora" />
            </div>
            <div class="col-md-12">
              <label for="modMateriales" class="form-label">Materiales</label>
              <textarea id="modMateriales" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Guardar Cambios</button>
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
    <script>
    // Evento para simular modificación y notificación
    document.getElementById('formModificarSolicitud').addEventListener('submit', function(e) {
      e.preventDefault();
      alert("✅ Solicitud modificada y alumno notificado automáticamente.");
      bootstrap.Modal.getInstance(document.getElementById('modalModificar')).hide();
    });

</body>
</html>