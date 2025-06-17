<!-- php logica de programacion -->

<!-- Estructura del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../../../resources/templates/menuDocente.php';?>

  <header class="container text-center mt-5 mb-3">
    <h1 class="fw-bold text-primary"><i class="fa-solid fa-circle-user"></i> Perfil</h1>
  </header>

  <div class="container my-5" style="max-width: 900px;">
    <!-- Formulario de Perfil -->
    <div class="card mb-4 shadow">
      <div class="card-header bg-primary text-white fw-bold">
        <i class="fa-solid fa-user-pen"></i> Información del Perfil
      </div>
      <div class="card-body">
        <form id="profileForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="nombre" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre de Usuario</label>
              <input type="text" class="form-control" id="usuario" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Semestre</label>
              <select class="form-select" id="semestre">
                <option value="">Selecciona</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Grupo</label>
              <input type="number" class="form-control" id="grupo">
            </div>
            <div class="col-md-12">
              <label class="form-label">Certificados o Reconocimientos (PDF)</label>
              <input type="file" class="form-control" id="certificados" accept="application/pdf" multiple>
            </div>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-success">Guardar Perfil</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Sección: Materias asignadas -->
    <div class="card shadow">
      <div class="card-header bg-secondary text-white fw-bold">
        <i class="fa-solid fa-chalkboard-user"></i> Materias Asignadas
      </div>
      <div class="card-body">
        <form id="formMaterias" class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Nombre de la Materia</label>
            <select class="form-select" id="materia" required>
              <option value="">Selecciona una materia</option>
              <option value="Matemáticas">Matemáticas</option>
              <option value="Física">Física</option>
              <option value="Química">Química</option>
              <option value="Biología">Biología</option>
              <option value="Programación">Programación</option>
              <option value="Bases de Datos">Bases de Datos</option>
              <option value="Redes">Redes</option>
              <option value="Inglés">Inglés</option>
              <option value="Ética Profesional">Ética Profesional</option>
              <!-- Puedes agregar más opciones según lo que manejen en tu plantel -->
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Semestre</label>
            <select class="form-select" id="semestreMateria" required>
              <option value="">Selecciona</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Grupo</label>
            <input type="text" class="form-control" id="grupoMateria" required>
          </div>
          <div class="col-md-12 d-grid">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-plus"></i> Agregar Materia</button>
          </div>
        </form>

        <!-- Tabla de materias agregadas -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Materia</th>
                <th>Semestre</th>
                <th>Grupo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaMaterias">
              <!-- Aquí se insertarán las materias -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <?php include '../../../resources/templates/footer.php';?>

  <!-- Scripts -->
  <script src="../../components/js/jquery-3.7.1.js"></script>
  <script src="../../components/js/bootstrap.bundle.min.js"></script>
  <script src="../../components/js/KitFontAwesome.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('formMaterias');
      const tabla = document.getElementById('tablaMaterias');

      form.addEventListener('submit', (e) => {
        e.preventDefault();

        const materia = document.getElementById('materia').value.trim();
        const semestre = document.getElementById('semestreMateria').value;
        const grupo = document.getElementById('grupoMateria').value.trim();

        if (materia && semestre && grupo) {
          const fila = document.createElement('tr');
          fila.innerHTML = `
            <td>${materia}</td>
            <td>${semestre}</td>
            <td>${grupo}</td>
            <td><button class="btn btn-sm btn-danger borrar"><i class="fa-solid fa-trash"></i></button></td>
          `;
          tabla.appendChild(fila);

          // Limpia el formulario
          form.reset();
        }
      });

      // Delegar evento para borra
