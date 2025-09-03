<!-- php logica de programacion -->
<?php
 session_start();
// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

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
    <?php include '../../../resources/templates/menuDocente.php';?>
  
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-danger"><i class="fa-solid fa-wrench"></i> Soporte</h1>        
      </header>

    
  <main class="container mb-5">
    <div class="row">    
      <!-- Aqui se enviaran los reportes a mi correo de Innovater Code -->
      <!-- Contacto con soporte -->
      <section class="col-12 mb-4">
        <div class="card p-4 shadow">
          <h5 class="fw-bold mb-3"><i class="fa-solid fa-envelope"></i> Contacto con soporte técnico</h5>
          <form id="supportForm">
            <div class="mb-3">
              <label for="message" class="form-label">Mensaje:</label>
              <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar mensaje</button>
          </form>
        <div id="responseMsg" class="mt-3"></div>


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
    <!-- <script src="../../components/js/Soporte.js"></script> -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('supportForm');
    const responseMsg = document.getElementById('responseMsg');
  
    form.addEventListener('submit', (e) => {
      e.preventDefault();
  
      const formData = new FormData(form);
  
      fetch('../../../resources/api/Soporte/apiSoporte.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === "OK") {
          responseMsg.innerHTML = '<div class="alert alert-success">✅ Mensaje enviado correctamente</div>';
          form.reset();
        } else {
          responseMsg.innerHTML = '<div class="alert alert-danger">❌ Error: ' + data + '</div>';
        }
      })
      .catch(err => {
        responseMsg.innerHTML = '<div class="alert alert-danger">⚠️ Error de red</div>';
      });
    });
  });
  
</script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>