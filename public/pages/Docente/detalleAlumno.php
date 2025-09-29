<?php
session_start();

if(isset($_SESSION['username'])) {
  include_once __DIR__ . '/../../../resources/DB/conexion.php';
  
  $conexion = Conexion::getInstancia();
  $dbh = $conexion->getDbh();

  $idAlumno = $_GET['id'] ?? null;
  $alumno = null;
  $materias = [];

  if ($idAlumno) {
    // Consulta para datos básicos del alumno
    $stmt = $dbh->prepare("SELECT d.nombreCompleto, d.semestre, d.grupo, a.username, a.email
                           FROM DatosA d
                           INNER JOIN Alumno a ON d.id_alumno = a.id_alumno
                           WHERE a.id_alumno = ?");
    $stmt->execute([$idAlumno]);
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consulta para las materias del alumno
    $stmt1 = $dbh->prepare("SELECT m.nombre, m.semestre, m.laboratorio 
                            FROM AluMateria am
                            INNER JOIN Materias m ON am.id_materias = m.id_materias
                            WHERE am.id_alumno = ?");
    $stmt1->execute([$idAlumno]);
    $materias = $stmt1->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle del Alumno</title>
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
</head>
<body>
<header class="container text-center mt-5 mb-3">
  <h1 class="fw-bold text-danger"><i class="fa-solid fa-circle-user"></i> Perfil Alumno</h1>
</header>

<div class="container my-5" style="max-width: 900px;">
  <!-- Perfil del Alumno -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white fw-bold">
      <i class="fa-solid fa-user-graduate"></i> Información del Alumno
    </div>
    <div class="card-body">
      <form>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($alumno['nombreCompleto']) ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($alumno['username']) ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Semestre</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($alumno['semestre']) ?>" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Grupo</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($alumno['grupo']) ?>" readonly>
          </div>
          <div class="col-md-12">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($alumno['email']) ?>" readonly>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Materias del Alumno -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-success text-white fw-bold">
      <i class="fa-solid fa-book"></i> Materias Inscritas
    </div>
    <div class="card-body">
      <?php if (!empty($materias)) : ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Nombre de la Materia</th>
                <th>Semestre</th>
                <th>¿Laboratorio?</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($materias as $index => $m): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($m['nombre']) ?></td>
                  <td><?= htmlspecialchars($m['semestre']) ?></td>
                  <td><?= $m['laboratorio'] ? 'Sí' : 'No' ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else : ?>
        <p class="text-muted">Este alumno no tiene materias registradas.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="grupos.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
  </div>
</div>

<script src="../../components/js/KitFontAwesome.js"></script>
</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>
