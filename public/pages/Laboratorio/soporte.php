<!-- php de logica de programacion -->

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte</title>
    <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/css/styleSoporte.css">
    <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <!-- php de invocacion de menú -->
    <?php include '../../../resources/templates/menuLab.php';?>
  
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-primary"><i class="fa-solid fa-wrench"></i> Soporte</h1>        
      </header>

    
  <main class="container mb-5">
    <div class="row">
      <!-- Cambio de idioma -->
      <section class="col-md-6 mb-4">
        <div class="card p-4 shadow">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-language"></i> Idioma</h5>
          <select class="form-select" id="languageSelect">
            <option value="es">Español</option>
            <option value="en">English</option>
          </select>
        </div>
      </section>

      <!-- Tema oscuro -->
      <section class="col-md-6 mb-4">
        <div class="card p-4 shadow">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-moon"></i> Tema</h5>
          <button class="btn btn-dark w-100" id="themeToggle">Cambiar a tema oscuro</button>
        </div>
      </section>

      <!-- Contacto con soporte -->
      <section class="col-12 mb-4">
        <div class="card p-4 shadow">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-envelope"></i> Contacto con soporte técnico</h5>
          <form>
            <div class="mb-3">
              <label for="email" class="form-label">Tu correo:</label>
              <input type="email" class="form-control" id="email" placeholder="tucorreo@ejemplo.com" required>
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Mensaje:</label>
              <textarea class="form-control" id="message" rows="4" placeholder="Describe tu problema o sugerencia" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
          </form>
        </div>
      </section>
    </div>
  </main>


      <!-- php de invocacion de footer -->
      <?php include '../../../resources/templates/footer.php';?>
  
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Soporte.js"></script>
</body>
</html>