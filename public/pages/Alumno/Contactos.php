<!-- php de logica de programacion -->

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>
    <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/css/styleContactos.css">
    <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <!-- php de invocacion de menú -->
    <?php include '../../../resources/templates/menuAlumno.php';?>
  
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-primary"><i class="fa-solid fa-users"></i> Contactos</h1>        
      </header>


      <div class="container mt-4">
        <h2 class="text-center text-primary mb-4"><i class="fa-solid fa-address-book"></i> Agenda Escolar</h2>

        <!-- Formulario -->
        <form id="contactForm" class="card p-3 mb-4 shadow">
          <input type="hidden" id="contactIndex" value="">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" id="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" id="telefono" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" id="correo" class="form-control" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success">Guardar Contacto</button>
          </div>
        </form>

        <!-- Lista de contactos -->
        <div id="contactList" class="row row-cols-1 row-cols-md-2 g-4">
          <!-- Contactos serán insertados aquí -->
        </div>
      </div>
<br>
      <!-- php de invocacion de footer -->
      <?php include '../../../resources/templates/footer.php';?>
  
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <script src="../../components/js/Alu/Contactos.js"></script>
    <!-- Descomentar este script en caso de ser necesario -->
    <!-- <script src=".../../components/js/funcionesModulares.js"></script> -->

    <!-- Modal de chat privado -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chatModalLabel">Chat con <span id="chatContactName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="chatMessages" style="max-height: 400px; overflow-y: auto; background-color: #f9f9f9;">
        <!-- Mensajes del chat se insertan aquí -->
      </div>
      <div class="modal-footer">
        <input type="text" id="chatInput" class="form-control" placeholder="Escribe un mensaje..." />
        <button class="btn btn-primary" onclick="sendChatMessage()">Enviar</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>