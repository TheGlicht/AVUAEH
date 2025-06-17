<!-- php logica de programación -->

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

  <script>
    // Inicializa fecha límite automática
    const fechaLimite = new Date();
    fechaLimite.setDate(fechaLimite.getDate() + 14);
    document.getElementById("fechaLimite").value = fechaLimite.toISOString().split("T")[0];

    function agregarAlumno() {
      const grupo = document.createElement("div");
      grupo.classList = "row g-2 mb-2";
      grupo.innerHTML = `
        <div class="col-md-6"><input type="text" class="form-control" placeholder="Nombre del alumno" required></div>
        <div class="col-md-6"><input type="text" class="form-control" placeholder="Número de cuenta" required></div>`;
      document.getElementById("equipoAlumnos").appendChild(grupo);
    }

    function agregarMaterial() {
      const grupo = document.createElement("div");
      grupo.classList = "row g-2 mb-2";
      grupo.innerHTML = `
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
        </div>`;
      document.getElementById("materialRoto").appendChild(grupo);
    }

    document.getElementById("formReporte").addEventListener("submit", function(e) {
      e.preventDefault();

      const encargado = document.getElementById("nombreEncargado").value;
      const fecha = document.getElementById("fechaLimite").value;
      const materiales = [...document.querySelectorAll("select[name='material[]']")].map(s => s.value);
      const cantidades = [...document.querySelectorAll("input[name='cantidad[]']")].map(i => i.value);
      const alumnos = [...document.querySelectorAll("#equipoAlumnos .row")].map(row => {
        const inputs = row.querySelectorAll("input");
        return `${inputs[0].value} (${inputs[1].value})`;
      }).join(", ");

      materiales.forEach((mat, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${alumnos}</td>
          <td>${mat}</td>
          <td>${cantidades[index]}</td>
          <td>${encargado}</td>
          <td>${fecha}</td>
          <td>
            <button class="btn btn-sm btn-success" onclick="this.closest('tr').remove()"><i class="fa-solid fa-check"></i></button>
            <button class="btn btn-sm btn-danger" onclick="this.closest('tr').classList.add('table-danger')"><i class="fa-solid fa-xmark"></i></button>
          </td>`;
        document.getElementById("tablaReportes").appendChild(row);
      });

      // Exportar a PDF (simple)
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.text("Reporte de Material Roto", 20, 20);
      doc.text(`Encargado: ${encargado}`, 20, 30);
      doc.text(`Fecha límite: ${fecha}`, 20, 40);
      doc.text(`Equipo: ${alumnos}`, 20, 50);
      materiales.forEach((mat, i) => {
        doc.text(`- ${mat} (x${cantidades[i]})`, 20, 60 + i * 10);
      });
      doc.save(`Reporte_${encargado}_${fecha}.pdf`);
    });
  </script>
</body>
</html>
