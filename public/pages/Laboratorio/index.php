<?php
session_start();
// no-cache headers...
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
    // Usa la MISMA ruta que en tu apiContador.php para evitar errores de path
    require_once dirname(__DIR__, 3) . '/resources/DB/Laboratorio/ContadorDB.php';

    $db = new ContadorDb();

    // Valores por defecto
    $c_practicas   = 0;
    $c_solicitudes = 0;
    $c_materiales  = 0;
    $c_reportes    = 0;

    // Lee de la BD (maneja ausencias con ?? 0)
    try {
        $r = $db->contarPracticas();   $c_practicas   = (int)($r['id_practica']  ?? 0);
        $r = $db->contarSolicitudes();  $c_solicitudes = (int)($r['total'] ?? 0);
        $r = $db->contarMaterial();    $c_materiales  = (int)($r['id_material']  ?? 0);
        $r = $db->contarDanos();       $c_reportes    = (int)($r['id_dano']      ?? 0);
    } catch (Throwable $e) {
        // En producción: registra el error
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuLab.php';?>

  <div class="container mt-4">
    <h2 class="mb-4 text-center text">Panel Principal - Laboratorios</h2>
    <br>

    <div class="row g-4">
      <!-- Próximas prácticas programadas -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-start border-4 border-success">
          <div class="card-body d-flex align-items-center">
            <i class="fa-solid fa-calendar-check fa-2x text-success me-3"></i>
            <div>
              <h6 class="card-title mb-0">Próximas prácticas</h6>
              <p class="card-text fs-5 fw-bold" id="practicas"><?= $c_practicas ?> Programadas</p>
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
              <p class="card-text fs-5 fw-bold" id="solicitudes"><?= $c_solicitudes ?> sin revisar</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Material en Inventario -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-start border-4 border-danger">
          <div class="card-body d-flex align-items-center">
            <i class="fa-solid fa-boxes-packing fa-2x text-danger me-3"></i>
            <div>
              <h6 class="card-title mb-0">Material en Inventario</h6>
              <p class="card-text fs-5 fw-bold" id="materiales"><?= $c_materiales ?> items</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Reportes de daño -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-start border-4 border-secondary">
          <div class="card-body d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation fa-2x text-secondary me-3"></i>
            <div>
              <h6 class="card-title mb-0">Reportes de daño</h6>
              <p class="card-text fs-5 fw-bold" id="reportes"><?= $c_reportes ?> sin resivir</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- row -->
  </div><!-- container -->

  <br>

  <?php include '../../../resources/templates/footer.php';?>

  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <!-- Ya no necesitas Panel1.js; puedes quitar esta línea -->
  <!-- <script src="../../components/js/Lab/Panel1.js"></script> -->
</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
