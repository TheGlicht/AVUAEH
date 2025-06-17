<!-- php de lógica de programación -->
<?php
$materiasDisponibles = [
  "Matemáticas I",
  "Física Básica",
  "Programación",
  "Química",
  "Inglés Técnico",
  "Bases de Datos",
  "Redacción Académica"
];
?>

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Materias</title>
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>

<?php include '../../../resources/templates/menuAlumno.php'; ?>

<header class="container text-center mt-5 mb-3">
  <h1 class="fw-bold text-primary"><i class="fa-solid fa-book"></i> Materias</h1>
  <p class="text-muted">Agrega materias, registra tus calificaciones y verifica tu promedio</p>
</header>

<div class="container my-5" style="max-width: 1000px;">
  <!-- Selección de materia -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white fw-bold">
      <i class="fa-solid fa-plus"></i> Agregar Materia
    </div>
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-8">
          <select id="materiaSelect" class="form-select">
            <option value="">Selecciona una materia</option>
            <?php foreach ($materiasDisponibles as $materia): ?>
              <option value="<?= htmlspecialchars($materia) ?>"><?= $materia ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4 text-end">
          <button type="button" id="agregarMateria" class="btn btn-success"><i class="fa-solid fa-plus"></i> Agregar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de materias seleccionadas -->
  <form id="formMaterias">
    <div class="card shadow">
      <div class="card-header bg-secondary text-white fw-bold">
        <i class="fa-solid fa-list-check"></i> Materias Registradas
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered text-center align-middle" id="tablaMaterias">
          <thead class="table-dark">
            <tr>
              <th>Materia</th>
              <th>1er Parcial (30%)</th>
              <th>2do Parcial (30%)</th>
              <th>Ordinario (40%)</th>
              <th>Promedio</th>
              <th>Falta para 60</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </form>
</div>

<?php include '../../../resources/templates/footer.php'; ?>

<!-- Scripts -->
<script src="../../components/js/jquery-3.7.1.js"></script>
<script src="../../components/js/bootstrap.bundle.min.js"></script>
<script src="../../components/js/KitFontAwesome.js"></script>

<script>
  const tabla = document.querySelector('#tablaMaterias tbody');
  const btnAgregar = document.getElementById('agregarMateria');
  const materiaSelect = document.getElementById('materiaSelect');
  let materiasAgregadas = [];

  btnAgregar.addEventListener('click', () => {
    const materia = materiaSelect.value;
    if (!materia || materiasAgregadas.includes(materia)) return;

    materiasAgregadas.push(materia);

    const fila = document.createElement('tr');
    fila.innerHTML = `
      <td><input type="text" name="materia[]" class="form-control-plaintext text-center" readonly value="${materia}"></td>
      <td><input type="number" class="form-control calificacion" name="parcial1[]" min="0" max="100"></td>
      <td><input type="number" class="form-control calificacion" name="parcial2[]" min="0" max="100"></td>
      <td><input type="number" class="form-control calificacion" name="ordinario[]" min="0" max="100"></td>
      <td class="promedio fw-bold text-primary">0.0</td>
      <td class="faltante fw-bold text-danger">0.0</td>
      <td><button type="button" class="btn btn-sm btn-danger eliminar"><i class="fa-solid fa-trash"></i></button></td>
    `;
    tabla.appendChild(fila);
    actualizarEventos();
  });

  function actualizarEventos() {
    tabla.querySelectorAll('.calificacion').forEach(input => {
      input.addEventListener('input', calcularPromedios);
    });

    tabla.querySelectorAll('.eliminar').forEach(btn => {
      btn.addEventListener('click', e => {
        const fila = e.target.closest('tr');
        const materia = fila.querySelector('input[name="materia[]"]').value;
        materiasAgregadas = materiasAgregadas.filter(m => m !== materia);
        fila.remove();
      });
    });
  }

  function calcularPromedios() {
    tabla.querySelectorAll('tr').forEach(fila => {
      const p1 = parseFloat(fila.querySelector('input[name="parcial1[]"]').value) || 0;
      const p2 = parseFloat(fila.querySelector('input[name="parcial2[]"]').value) || 0;
      const ord = parseFloat(fila.querySelector('input[name="ordinario[]"]').value) || 0;

      const promedio = (p1 * 0.3 + p2 * 0.3 + ord * 0.4).toFixed(1);
      fila.querySelector('.promedio').textContent = promedio;

      const falta = Math.max(0, (60 - promedio)).toFixed(1);
      fila.querySelector('.faltante').textContent = falta > 0 ? falta : "0.0";
    });
  }
</script>

</body>
</html>
