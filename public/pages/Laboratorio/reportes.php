<!-- php logica de programacion -->
<?php
 session_start();
// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Material Roto</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuLab.php';?>

  <div class="container mt-4">
    <h2 class="mb-4 text-center">Reporte de Material Roto</h2>

    <!-- Formulario para reporte -->
    <form id="formReporte">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Nombre del Encargado</label>
          <input type="text" class="form-control" id="nombreEncargado" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Fecha límite de reposición</label>
          <input type="text" class="form-control" id="fechaLimite" readonly>
        </div>
      </div>

      <div id="equipoAlumnos" class="mb-3">
        <label class="form-label">Alumnos del equipo</label>
        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Nombre del alumno" required>
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Número de cuenta" required>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="agregarAlumno()">+ Agregar alumno</button>

      <!-- Material roto -->
      <div class="mb-3">
        <label class="form-label">Material Roto</label>
        <div id="materialRoto">
          <div class="row g-2 mb-2">
            <div class="col-md-8">
              <select class="form-select" name="material[]">
                <option selected disabled>Selecciona material</option>
                <option value="Vaso de precipitados">Vaso de precipitados</option>
                <option value="Probeta">Probeta</option>
                <option value="Tubos de ensayo">Tubos de ensayo</option>
                <option value="Matraz Erlenmeyer">Matraz Erlenmeyer</option>
              </select>
            </div>
            <div class="col-md-4">
              <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" min="1" required>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" onclick="agregarMaterial()">+ Agregar otro material</button>
      </div>

      <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Generar Reporte</button>
    </form>

    <hr class="my-4">

    <!-- Tabla de reportes pendientes -->
    <h4 class="mb-3">Reportes Pendientes</h4>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead class="table-warning">
          <tr>
            <th>Equipo</th>
            <th>Material</th>
            <th>Cantidad</th>
            <th>Encargado</th>
            <th>Fecha Límite</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaReportes">
          <!-- Se agregan filas con JS -->
        </tbody>
      </table>
    </div>
  </div>

  <?php include '../../../resources/templates/footer.php';?>

  <!-- Scripts -->
  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="../../components/js/Lab/reporte.js"></script>
</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>
