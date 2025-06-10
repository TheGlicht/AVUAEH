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
    
    <div class="container mt-4">
    <h2 class="mb-4 text-center">Panel Principal - Laboratorios</h2>

  <div class="row g-4">

    <!-- Laboratorios en uso hoy -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-start border-4 border-primary">
        <div class="card-body d-flex align-items-center">
          <i class="fa-solid fa-flask fa-2x text-primary me-3"></i>
          <div>
            <h6 class="card-title mb-0">Laboratorios en uso hoy</h6>
            <p class="card-text fs-5 fw-bold">4 Laboratorios</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Próximas prácticas programadas -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-start border-4 border-success">
        <div class="card-body d-flex align-items-center">
          <i class="fa-solid fa-calendar-check fa-2x text-success me-3"></i>
          <div>
            <h6 class="card-title mb-0">Próximas prácticas</h6>
            <p class="card-text fs-5 fw-bold">7 Programadas</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Solicitudes de material pendientes -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-start border-4 border-warning">
        <div class="card-body d-flex align-items-center">
          <i class="fa-solid fa-envelope-open-text fa-2x text-warning me-3"></i>
          <div>
            <h6 class="card-title mb-0">Solicitudes pendientes</h6>
            <p class="card-text fs-5 fw-bold">3 Sin revisar</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Materiales en bajo stock -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-start border-4 border-danger">
        <div class="card-body d-flex align-items-center">
          <i class="fa-solid fa-boxes-packing fa-2x text-danger me-3"></i>
          <div>
            <h6 class="card-title mb-0">Material en bajo stock</h6>
            <p class="card-text fs-5 fw-bold">5 Materiales</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Notificaciones o reportes -->
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm border-start border-4 border-secondary">
        <div class="card-body d-flex align-items-center">
          <i class="fa-solid fa-triangle-exclamation fa-2x text-secondary me-3"></i>
          <div>
            <h6 class="card-title mb-0">Reportes de daño</h6>
            <p class="card-text fs-5 fw-bold">2 Pendientes</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Calendario -->
  <div class="row mt-5">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fa-solid fa-calendar-days"></i> Calendario de Prácticas</h5>
          <div id="calendar"></div>
        </div>
      </div>
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
    <script src="../../components/js/Lab/Panel1.js"></script>

</body>
</html>