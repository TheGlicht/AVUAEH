<?php
session_start();

// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contactos</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuAlumno.php'; ?>

  <header class="container text-center mt-5 mb-3">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-users"></i> Contactos</h1>
  </header>

  <div class="container mt-4">
    <!-- Formulario de contacto -->
    <div class="card shadow-sm">
      <div class="card-body">
        <form id="contactForm">
          <input type="hidden" id="id_contacto" />
          <div class="row g-3">
            <div class="col-md-4">
              <label for="nombre" class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="nombre" required />
            </div>
            <div class="col-md-4">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="tel" class="form-control" id="telefono" required />
            </div>
            <div class="col-md-4">
              <label for="correo" class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="correo" required />
            </div>
          </div>
          <div class="d-flex gap-2 justify-content-end mt-3">
            <button type="button" id="cancelEditBtn" class="btn btn-secondary d-none">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="saveContactBtn">
              <i class="fa-solid fa-floppy-disk"></i> Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla de contactos -->
    <div class="mt-5">
      <h2>Contactos Registrados</h2>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Correo</th>
              <th style="width: 150px;">Acciones</th>
            </tr>
          </thead>
          <tbody id="contactTableBody">
            <!-- Se llenará desde JS con el HTML de la API -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include '../../../resources/templates/footer.php'; ?>

  <!-- Scripts -->
  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="../../components/js/Alu/Contactos.js"></script>
</body>
</html>

<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>
