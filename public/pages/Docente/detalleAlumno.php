<?php
session_start();

// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

// Aquí iría tu lógica de conexión a base de datos y consulta del alumno
// Por ejemplo:
// include '../../../resources/config/db.php';

// $idAlumno = $_GET['id'] ?? null;

// $alumno = null;
// if ($idAlumno) {
//     $stmt = $conn->prepare("SELECT * FROM alumnos WHERE id = ?");
//     $stmt->bind_param("i", $idAlumno);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $alumno = $result->fetch_assoc();
// }
// ?>

<!-- Estructura del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detalle del Alumno</title>
  
  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="image/png" href="../../components/assets/Garza/Garza3.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>

<?php include '../../../resources/templates/menuDocente.php'; ?>

<div class="container my-5" style="max-width: 800px;">
  <h2 class="text-center text-primary fw-bold mb-4"><i class="fa-solid fa-user-graduate"></i> Detalle del Alumno</h2>

  <?php if ($alumno): ?>
    <div class="card shadow">
      <div class="card-header bg-primary text-white fw-bold">
        Información del Alumno
      </div>
      <div class="card-body">
        <p><strong>Nombre completo:</strong> <?= htmlspecialchars($alumno['nombre_completo']) ?></p>
        <p><strong>Número de cuenta:</strong> <?= htmlspecialchars($alumno['numero_cuenta']) ?></p>
        <p><strong>Semestre:</strong> <?= htmlspecialchars($alumno['semestre']) ?></p>
        <p><strong>Grupo:</strong> <?= htmlspecialchars($alumno['grupo']) ?></p>
        <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($alumno['correo']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($alumno['telefono']) ?></p>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger text-center fw-bold">
      No se encontró información del alumno.
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="grupos.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
  </div>
</div>

<?php include '../../../resources/templates/footer.php'; ?>

<!-- Scripts -->
<script src="../../components/js/jquery-3.7.1.js"></script>
<script src="../../components/js/bootstrap.bundle.min.js"></script>
<script src="../../components/js/KitFontAwesome.js"></script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>