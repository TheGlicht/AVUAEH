<?php
// Vales.php
session_start();

// evitar caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// comprobar sesión
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// ----------- conexión a BD -----------
require_once dirname(__DIR__, 3) . '/resources/DB/conexion.php';
$conn = Conexion::getInstancia()->getDbh();

// ----------- Endpoints AJAX ----------
if (isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'];

    try {
        if ($action === 'materias') {
            $stmt = $conn->query("SELECT id_materias, nombre FROM Materias WHERE laboratorio = 1");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit();
        }

        if ($action === 'docentes') {
            $id_materia = $_GET['id_materia'] ?? null;
            if (!$id_materia) { echo json_encode([]); exit(); }

            $sql = "SELECT DISTINCT d.id_docente, d.nombreCompleto
                    FROM Docentes d
                    INNER JOIN ValesA v ON v.id_docente = d.id_docente
                    WHERE v.id_materias = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_materia]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                $rows = $conn->query("SELECT id_docente, nombreCompleto FROM Docentes")->fetchAll(PDO::FETCH_ASSOC);
            }
            echo json_encode($rows);
            exit();
        }

        if ($action === 'kits') {
            $id_materia = $_GET['id_materia'] ?? null;
            if (!$id_materia) { echo json_encode([]); exit(); }

            $stmt = $conn->prepare("SELECT id_kit, nombre FROM Kit WHERE id_materias = ?");
            $stmt->execute([$id_materia]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit();
        }

        if ($action === 'vales') {
            $sql = "SELECT v.id_vales, m.nombre AS materia, d.nombreCompleto AS docente,
                           v.diaLab, v.horaLab, v.id_lab, k.nombre AS kit
                    FROM ValesA v
                    LEFT JOIN Materias m ON v.id_materias = m.id_materias
                    LEFT JOIN Docentes d ON v.id_docente = d.id_docente
                    LEFT JOIN Kit k ON v.id_kit = k.id_kit
                    LEFT JOIN Alumno a ON v.id_alumno = a.id_alumno
                    WHERE a.username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['username']]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit();
        }

        echo json_encode(['error' => 'Acción no reconocida']);
        exit();
    } catch (PDOException $ex) {
        echo json_encode(['error' => $ex->getMessage()]);
        exit();
    }
}

// ----------- POST: eliminar vale ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    try {
        $sql = "DELETE FROM ValesA WHERE id_vales = ? AND id_alumno = (
                    SELECT id_alumno FROM Alumno WHERE username = ?
                )";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['delete_id'], $_SESSION['username']]);
    } catch (PDOException $ex) {
        $_SESSION['error_vales'] = "Error al eliminar: " . $ex->getMessage();
    }
    header("Location: Vales.php");
    exit();
}

// ----------- POST: nuevo vale ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['id_materias'], $_POST['id_docente'], $_POST['diaLab'], $_POST['horaLab'], $_POST['id_lab'], $_POST['id_kit'])) {

    $fecha_ts = strtotime($_POST['diaLab']);
    $hoy = strtotime(date('Y-m-d'));
    $max = strtotime('+3 days', $hoy);
    if ($fecha_ts < $hoy || $fecha_ts > $max) {
        $_SESSION['error_vales'] = "La fecha debe ser hoy o dentro de los próximos 3 días.";
        header("Location: Vales.php");
        exit();
    }

    try {
        $sql = "INSERT INTO ValesA (id_materias, id_docente, diaLab, horaLab, id_lab, id_kit, id_alumno)
                SELECT ?, ?, ?, ?, ?, ?, a.id_alumno
                FROM Alumno a
                WHERE a.username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $_POST['id_materias'],
            $_POST['id_docente'],
            $_POST['diaLab'],
            $_POST['horaLab'],
            $_POST['id_lab'],
            $_POST['id_kit'],
            $_SESSION['username']
        ]);
    } catch (PDOException $ex) {
        $_SESSION['error_vales'] = "Error al guardar: " . $ex->getMessage();
    }
    header("Location: Vales.php");
    exit();
}

// ----------- Consultas para la vista ----------
try {
    $materias = $conn->query("SELECT id_materias, nombre FROM Materias WHERE laboratorio = 1")->fetchAll(PDO::FETCH_ASSOC);
    $kits = $conn->query("SELECT id_kit, nombre, id_materias FROM Kit")->fetchAll(PDO::FETCH_ASSOC);

    $sqlVales = "SELECT v.id_vales, m.nombre AS materia, d.nombreCompleto AS docente,
                        v.diaLab, v.horaLab, v.id_lab, k.nombre AS kit
                 FROM ValesA v
                 LEFT JOIN Materias m ON v.id_materias = m.id_materias
                 LEFT JOIN Docentes d ON v.id_docente = d.id_docente
                 LEFT JOIN Kit k ON v.id_kit = k.id_kit
                 LEFT JOIN Alumno a ON v.id_alumno = a.id_alumno
                 WHERE a.username = ?";
    $stmt = $conn->prepare($sqlVales);
    $stmt->execute([$_SESSION['username']]);
    $vales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    $materias = $kits = $vales = [];
    $_SESSION['error_vales'] = "Error en consultas: " . $ex->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Vales</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleVales.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />

</head>
<body>
  <?php include '../../../resources/templates/menuAlumno.php';?>

  <main class="container py-4">
    <header class="text-center mb-4">
      <h1 class="fw-bold text-danger"><i class="fa-solid fa-flask-vial"></i> Vales</h1>
    </header>

    <?php if (!empty($_SESSION['error_vales'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_vales']); unset($_SESSION['error_vales']); ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm p-4 mb-4">
          <form method="POST" action="Vales.php" id="labForm">
            <div class="mb-3">
              <label for="materia" class="form-label">Nombre de la Materia</label>
              <select name="id_materias" id="materia" class="form-select" required>
                <option value="" disabled selected>Selecciona una materia</option>
                <?php foreach ($materias as $m): ?>
                  <option value="<?= htmlspecialchars($m['id_materias']) ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="docente" class="form-label">Profesor</label>
              <select name="id_docente" id="docente" class="form-select" required>
                <option value="" disabled selected>Selecciona un docente</option>
                <!-- Opciones cargadas dinámicamente via AJAX cuando seleccionas materia -->
              </select>
            </div>

            <div class="mb-3">
              <label for="fecha" class="form-label">Día de Laboratorio</label>
              <input type="date" name="diaLab" id="fecha" class="form-control" required>
              <small class="text-muted">Debe ser dentro de los próximos 3 días.</small>
            </div>

            <div class="mb-3">
              <label for="hora" class="form-label">Hora de Laboratorio</label>
              <input type="time" name="horaLab" id="hora" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="laboratorio" class="form-label">Laboratorio</label>
              <select name="id_lab" id="laboratorio" class="form-select" required>
                <option value=""disabled selected>Selecciona un laboratorio</option>
                <option value="1" id="1">Lab. Electrónica</option>
                <option value="2" id="2">Lab. Control</option>
                <option value="3" id="3">Lab. Fisico-Quimica</option>
                <option value="4" id="4">Laboratorio 1</option>
                <option value="5" id="5">Laboratorio 2</option>
                <option value="6" id="6">Laboratorio 3</option>
                <option value="7" id="7">Laboratorio 4</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="kit" class="form-label">Kit de Material</label>
              <select name="id_kit" id="kit" class="form-select" required>
                <option value="" disabled selected>Selecciona un kit</option>
                <?php foreach ($kits as $k): ?>
                  <option value="<?= htmlspecialchars($k['id_kit']) ?>" data-materia="<?= htmlspecialchars($k['id_materias']) ?>">
                    <?= htmlspecialchars($k['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">Aceptar</button>
          </form>
        </div>
      </div>
    </div>

    <section class="mt-4">
      <h3 class="fw-bold text-secondary">Mis Vales Generados</h3>
      <div class="table-responsive">
        <table class="table table-striped" id="tablaVales">
          <thead>
            <tr>
              <th>Materia</th>
              <th>Docente</th>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Laboratorio</th>
              <th>Kit</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($vales)): ?>
              <?php foreach ($vales as $v): ?>
                <tr>
                  <td><?= htmlspecialchars($v['materia']) ?></td>
                  <td><?= htmlspecialchars($v['docente']) ?></td>
                  <td><?= htmlspecialchars($v['diaLab']) ?></td>
                  <td><?= htmlspecialchars($v['horaLab']) ?></td>
                  <td><?= htmlspecialchars($v['id_lab']) ?></td>
                  <td><?= htmlspecialchars($v['kit']) ?></td>
                  <td>
                    <form method="POST" action="Vales.php" style="display:inline;">
                      <input type="hidden" name="delete_id" value="<?= htmlspecialchars($v['id_vales']) ?>">
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este vale?')">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center">No hay vales registrados</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <?php include '../../../resources/templates/footer.php';?>

  <!-- scripts -->
  <script>
    // Filtra los kits que imprimimos en server (data-materia) y solicita docentes por AJAX
    document.addEventListener('DOMContentLoaded', function() {
      const materia = document.getElementById('materia');
      const docente = document.getElementById('docente');
      const kit = document.getElementById('kit');

      materia.addEventListener('change', async function() {
        const id = this.value;

        // Filtrar kits (mostramos/ocultamos según data-materia)
        document.querySelectorAll('#kit option').forEach(opt => {
          if (!opt.value) return;
          opt.style.display = (opt.dataset.materia === id) ? 'block' : 'none';
        });
        kit.value = '';

        // Cargar docentes vía fetch a este mismo archivo (endpoint action=docentes)
        try {
          const resp = await fetch(`Vales.php?action=docentes&id_materia=${encodeURIComponent(id)}`);
          const data = await resp.json();
          // vaciar select docente
          docente.innerHTML = '<option value=\"\" disabled selected>Selecciona un docente</option>';
          data.forEach(d => {
            const o = document.createElement('option');
            o.value = d.id_docente;
            o.textContent = d.nombreCompleto;
            docente.appendChild(o);
          });
        } catch (err) {
          console.error('Error cargando docentes:', err);
        }
      });
    });
  </script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>
  <script src="../../components/js/vales.js"></script>
<!--Scripts extra -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

</body>
</html>
