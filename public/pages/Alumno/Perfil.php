<!-- php de logica de programacion -->

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/css/styleHome.css">
    <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <!-- php de invocacion de menú -->
    <?php include '../../../resources/templates/menuAlumno.php';?>
  
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
                  <label class="form-label">Materias Cursando</label>
                  <textarea class="form-control" id="materias" rows="2"></textarea>
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

        <!-- Avance de Materias -->
        <div class="card shadow mb-5">
          <div class="card-header bg-secondary text-white fw-bold">
            <i class="fa-solid fa-chart-line"></i> Avance de Materias
          </div>
          <div class="card-body">
            <ul class="list-group" id="avanceMaterias">
              <li class="list-group-item">
                <strong>Matemáticas</strong>
                <div class="progress mt-2" style="height: 20px;">
                  <div class="progress-bar bg-primary" style="width: 80%">80%</div>
                </div>
              </li>
              <li class="list-group-item">
                <strong>Física</strong>
                <div class="progress mt-2" style="height: 20px;">
                  <div class="progress-bar bg-success" style="width: 100%">100%</div>
                </div>
              </li>
              <li class="list-group-item">
                <strong>Química</strong>
                <div class="progress mt-2" style="height: 20px;">
                  <div class="progress-bar bg-warning" style="width: 60%">60%</div>
                </div>
              </li>
            </ul>
          </div>
        </div>

      </div>


      <!-- php de invocacion de footer -->
      <?php include '../../../resources/templates/footer.php';?>
  
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Alu/Perfil.js"></script>
    <!-- Descomentar este script en caso de ser necesario -->
    <!-- <script src=".../../components/js/funcionesModulares.js"></script> -->
</body>
</html>