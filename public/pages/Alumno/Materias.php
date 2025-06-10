<!-- php de logica de programacion -->

<!-- Estructura sitio web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias</title>
    <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../components/css/styleHome.css">
    <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png">
</head>
<body>
  <!-- php de invocacion de menú -->
    <?php include '../../../resources/templates/menuAlumno.php';?>
  
      <header class="container text-center mt-5 mb-3">
        <h1 class="fw-bold text-primary"><i class="fa-solid fa-book"></i> Materias</h1>        
      </header>


      <!-- php de invocacion de footer -->
      <?php include '../../../resources/templates/footer.php';?>
  
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>
    <!-- Descomentar este script en caso de ser necesario -->
    <!-- <script src=".../../components/js/funcionesModulares.js"></script> -->
</body>
</html>