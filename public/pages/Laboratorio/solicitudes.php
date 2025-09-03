<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Validar sesión
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

// Conexión
require_once dirname(__DIR__, 3) . '/resources/DB/conexion.php';
$conn = Conexion::getInstancia()->getDbh();

// --- Eliminar solicitud ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    try {
        $sql = "DELETE FROM ValesA WHERE id_vales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['delete_id']]);
    } catch (PDOException $ex) {
        $_SESSION['error_solicitudes'] = "Error al eliminar: " . $ex->getMessage();
    }
    header("Location: solicitudes.php");
    exit();
}

// --- Consultar solicitudes ---
try {
    $sql = "SELECT v.id_vales, a.username AS alumno, 
                   m.nombre AS materia, v.diaLab, v.horaLab, v.id_lab,
                   k.nombre AS kit,
                   GROUP_CONCAT(CONCAT(mat.nombre, ' (', km.cantidad, ')') SEPARATOR ', ') AS materiales
            FROM ValesA v
            LEFT JOIN Alumno a ON v.id_alumno = a.id_alumno
            LEFT JOIN Materias m ON v.id_materias = m.id_materias
            LEFT JOIN Kit k ON v.id_kit = k.id_kit
            LEFT JOIN KitMaterial km ON k.id_kit = km.id_kit
            LEFT JOIN Material mat ON km.id_material = mat.id_material
            GROUP BY v.id_vales, a.username, m.nombre, v.diaLab, v.horaLab, v.id_lab, k.nombre
            ORDER BY v.diaLab, v.horaLab";
    $solicitudes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $solicitudes = [];
    $_SESSION['error_solicitudes'] = "Error en consulta: " . $ex->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuLab.php';?>

  <div class="container my-4">
    <h2 class="mb-4 text-center">📝 Solicitudes de Material de Alumnos</h2>

    <?php if (!empty($_SESSION['error_solicitudes'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_solicitudes']); unset($_SESSION['error_solicitudes']); ?></div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="row mb-3">
      <div class="col-md-3">
        <input type="number" min="1" id="filtroGrupo" class="form-control" placeholder="Filtrar por grupo" />
      </div>
      <div class="col-md-3">
        <input type="text" id="filtroMateria" class="form-control" placeholder="Filtrar por materia" />
      </div>
      <div class="col-md-3">
        <input type="date" id="filtroFecha" class="form-control" />
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Kit</th>
            <th>Materiales Solicitados</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Laboratorio</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($solicitudes)): ?>
            <?php foreach ($solicitudes as $s): ?>
              <tr>
                <td><?= htmlspecialchars($s['alumno']) ?></td>
                <td><?= htmlspecialchars($s['materia']) ?></td>
                <td><?= htmlspecialchars($s['kit']) ?></td>
                <td><?= htmlspecialchars($s['materiales']) ?></td>
                <td><?= htmlspecialchars($s['diaLab']) ?></td>
                <td><?= htmlspecialchars($s['horaLab']) ?></td>
                <td><?= htmlspecialchars($s['id_lab']) ?></td>
                <td>
                  <form method="POST" action="solicitudes.php" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($s['id_vales']) ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta solicitud?')">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">No hay solicitudes registradas</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php include '../../../resources/templates/footer.php';?>

  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script>
// Filtrado dinámico
document.addEventListener("DOMContentLoaded", () => {
  const filtroGrupo   = document.getElementById("filtroGrupo");
  const filtroMateria = document.getElementById("filtroMateria");
  const filtroFecha   = document.getElementById("filtroFecha");
  const tabla         = document.querySelector("table tbody");

  function filtrarTabla() {
    const valGrupo   = filtroGrupo.value.trim().toLowerCase();
    const valMateria = filtroMateria.value.trim().toLowerCase();
    const valFecha   = filtroFecha.value.trim();

    for (let fila of tabla.rows) {
      let mostrar = true;

      // Columna grupo (en tu tabla corresponde al campo "id_lab", es la 6ta (index 6))
      const grupo = fila.cells[6]?.textContent.toLowerCase() || "";

      // Columna materia (2da, index 1)
      const materia = fila.cells[1]?.textContent.toLowerCase() || "";

      // Columna fecha (5ta, index 4)
      const fecha = fila.cells[4]?.textContent || "";

      // Validaciones de filtros
      if (valGrupo && !grupo.includes(valGrupo)) {
        mostrar = false;
      }
      if (valMateria && !materia.includes(valMateria)) {
        mostrar = false;
      }
      if (valFecha && fecha !== valFecha) {
        mostrar = false;
      }

      fila.style.display = mostrar ? "" : "none";
    }
  }

  filtroGrupo.addEventListener("input", filtrarTabla);
  filtroMateria.addEventListener("input", filtrarTabla);
  filtroFecha.addEventListener("input", filtrarTabla);
});
</script>

</body>
</html>
