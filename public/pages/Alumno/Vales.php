<!-- php de logica de programacion -->

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vales</title>
    <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/css/styleVales.css">
    <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <!-- php de invocacion de menú -->
    <?php include '../../../resources/templates/menuAlumno.php';?>
  
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-primary"><i class="fa-solid fa-flask-vial"></i> Vales</h1>        
      </header>

     
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm p-4 mb-4">
              <form id="labForm">
                <div class="mb-3">
                  <label for="materia" class="form-label">Nombre de la Materia</label>
                  <select id="materia" class="form-select" required>
                    <option value="" disabled selected>Selecciona una materia</option>
                    <option value="quimica">Química</option>
                    <option value="fisica">Física</option>
                    <option value="electronica">Electrónica</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="profesor" class="form-label">Profesor</label>
                  <input type="text" id="profesor" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label for="fecha" class="form-label">Día de Laboratorio</label>
                  <input type="date" id="fecha" class="form-control" required>
                  <small class="text-muted">Debe ser dentro de los próximos 3 días.</small>
                </div>

                <div class="mb-3">
                  <label for="laboratorio" class="form-label">Laboratorio</label>
                  <select id="laboratorio" class="form-select" required>
                    <option value="" disabled selected>Selecciona un laboratorio</option>
                    <option value="Lab 1">Lab 1</option>
                    <option value="Lab 2">Lab 2</option>
                    <option value="Lab 3">Lab 3</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="kit" class="form-label">Kit de Material</label>
                  <select id="kit" class="form-select" required>
                    <option value="" disabled selected>Selecciona un kit</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary">Aceptar</button>
              </form>
            </div>
          </div>
        </div>

        <div id="vale" class="vale d-none mx-auto">
          <h3 class="text-success">Vale de Material</h3>
          <p><strong>Materia:</strong> <span id="valeMateria"></span></p>
          <p><strong>Profesor:</strong> <span id="valeProfesor"></span></p>
          <p><strong>Fecha:</strong> <span id="valeFecha"></span></p>
          <p><strong>Laboratorio:</strong> <span id="valeLab"></span></p>
          <p><strong>Kit:</strong> <span id="valeKit"></span></p>
          <p><strong>Materiales:</strong></p>
          <ul id="valeMateriales"></ul>
        </div>
        
        <div class="text-center mt-3 d-none" id="pdfContainer">
          <button class="btn btn-outline-success" id="downloadPDF">
            <i class="fa-solid fa-file-pdf"></i> Descargar Vale en PDF
          </button>
        </div>

      </div>

<br>
      <!-- php de invocacion de footer -->
      <?php include '../../../resources/templates/footer.php';?>
  
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Vales.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
</body>
</html>