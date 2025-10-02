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
        header("Location: solicitudes.php");
        exit();
    } catch (PDOException $ex) {
        $_SESSION['error_solicitudes'] = "Error al eliminar: " . $ex->getMessage();
    }
}

// --- Actualizar estado (AJAX) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    try {
        $sql = "SELECT estatus FROM ValesA WHERE id_vales = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['update_id']]);
        $estatus = $stmt->fetchColumn();

        if ($estatus !== false && $estatus < 2) {
            $nuevoEstado = $estatus + 1;
            $sql = "UPDATE ValesA SET estatus = ? WHERE id_vales = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nuevoEstado, $_POST['update_id']]);
        } else {
            $nuevoEstado = $estatus;
        }

        echo json_encode([
            "success" => true,
            "newStatus" => (int)$nuevoEstado
        ]);
    } catch (PDOException $ex) {
        echo json_encode([
            "success" => false,
            "error" => $ex->getMessage()
        ]);
    }
    exit; // cortar aquí porque es respuesta AJAX
}

// --- Consultar solicitudes ---
try {
    $sql = "SELECT v.id_vales, a.username AS alumno, 
                   m.nombre AS materia, v.diaLab, v.horaLab,
                   da.grupo,
                   CASE v.id_lab
                       WHEN 1 THEN 'Lab. Electrónica'
                       WHEN 2 THEN 'Lab. Control'
                       WHEN 3 THEN 'Lab. Fisico-Quimica'
                       WHEN 4 THEN 'Laboratorio 1'
                       WHEN 5 THEN 'Laboratorio 2'
                       WHEN 6 THEN 'Laboratorio 3'
                       WHEN 7 THEN 'Laboratorio 4'
                       ELSE 'Desconocido'
                   END AS laboratorio,
                   k.nombre AS kit,
                   v.estatus,
                   GROUP_CONCAT(CONCAT(mat.nombre, ' (', km.cantidad, ')') SEPARATOR ', ') AS materiales
            FROM ValesA v
            LEFT JOIN Alumno a ON v.id_alumno = a.id_alumno
            LEFT JOIN DatosA da ON a.id_alumno = da.id_alumno
            LEFT JOIN Materias m ON v.id_materias = m.id_materias
            LEFT JOIN Kit k ON v.id_kit = k.id_kit
            LEFT JOIN KitMaterial km ON k.id_kit = km.id_kit
            LEFT JOIN Material mat ON km.id_material = mat.id_material
            GROUP BY v.id_vales, a.username, m.nombre, v.diaLab, v.horaLab, v.id_lab, k.nombre, v.estatus, da.grupo
            ORDER BY v.diaLab, v.horaLab;";
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
            <th>Grupo</th>
            <th>Laboratorio</th>
            <th>Estado</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($solicitudes)): ?>
            <?php foreach ($solicitudes as $s): ?>
              <?php
                $clase = $s['estatus'] == 0 ? 'table-warning' :
                         ($s['estatus'] == 1 ? 'table-info' : 'table-success');
              ?>
              <tr id="row-<?= $s['id_vales'] ?>" class="<?= $clase ?>" data-id="<?= $s['id_vales'] ?>">
                <td><?= htmlspecialchars($s['alumno']) ?></td>
                <td><?= htmlspecialchars($s['materia']) ?></td>
                <td><?= htmlspecialchars($s['kit']) ?></td>
                <td><?= htmlspecialchars($s['materiales']) ?></td>
                <td><?= htmlspecialchars($s['diaLab']) ?></td>
                <td><?= htmlspecialchars($s['horaLab']) ?></td>
                <td><?= htmlspecialchars($s['grupo']) ?></td>
                <td><?= htmlspecialchars($s['laboratorio']) ?></td>
                <td class="estado">
                  <?php
                    switch ($s['estatus']) {
                      case 0: echo "Pendiente"; break;
                      case 1: echo "Entregado"; break;
                      case 2: echo "Devuelto"; break;
                    }
                  ?>
                </td>
                <td class="acciones">
                  <button type="button" 
                          class="btn btn-success btn-sm updateEstado"
                          data-id="<?= $s['id_vales'] ?>"
                          <?= $s['estatus'] == 2 ? 'disabled' : '' ?>>
                    <?php 
                      if ($s['estatus'] == 0) echo "Marcar Entregado";
                      elseif ($s['estatus'] == 1) echo "Marcar Devuelto";
                      else echo "Finalizado";
                    ?>
                  </button>

                  <form method="POST" action="solicitudes.php" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($s['id_vales']) ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center">No hay solicitudes registradas</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php include '../../../resources/templates/footer.php';?>

  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    // Filtrado dinámico
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

        const grupo = fila.cells[6]?.textContent.toLowerCase() || "";
        const materia = fila.cells[1]?.textContent.toLowerCase() || "";
        const fecha = fila.cells[4]?.textContent || "";

        if (valGrupo && !grupo.includes(valGrupo)) mostrar = false;
        if (valMateria && !materia.includes(valMateria)) mostrar = false;
        if (valFecha && fecha !== valFecha) mostrar = false;

        fila.style.display = mostrar ? "" : "none";
      }
    }

    filtroGrupo.addEventListener("input", filtrarTabla);
    filtroMateria.addEventListener("input", filtrarTabla);
    filtroFecha.addEventListener("input", filtrarTabla);

    // --- Manejo AJAX para actualizar estado ---
    document.querySelectorAll(".updateEstado").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.id;

        btn.disabled = true;
        btn.textContent = "Actualizando...";

        fetch("solicitudes.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "update_id=" + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const fila = document.getElementById("row-" + id);
            const estadoCell = fila.querySelector(".estado");
            const accionesCell = fila.querySelector(".acciones");

            if (data.newStatus === 1) {
              estadoCell.textContent = "Entregado";
              fila.classList.remove("table-warning");
              fila.classList.add("table-info");
              btn.textContent = "Marcar Devuelto";
              btn.disabled = false;
            } else if (data.newStatus === 2) {
              estadoCell.textContent = "Devuelto";
              fila.classList.remove("table-info");
              fila.classList.add("table-success");
              accionesCell.innerHTML = `
                <form method="POST" action="solicitudes.php" style="display:inline;">
                  <input type="hidden" name="delete_id" value="${id}">
                  <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>`;
            }
          } else {
            alert("Error: " + data.error);
          }
        })
        .catch(err => {
          alert("Error de conexión");
          console.error(err);
        });
      });
    });
  });
  </script>
</body>
</html>
