<!-- php logica de programacion -->
<?php
 session_start();
// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

<!-- php logica de código -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kits de laboratorio</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body>

<?php include '../../../resources/templates/menuLab.php';?>

<div class="container mt-4">
  <h2 class="text-center mb-4"><i class="fa-solid fa-toolbox"></i> Kits de Laboratorio por Materia</h2>

  <!-- Botón para armar nuevo kit
  <div class="text-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarKit">
      <i class="fa-solid fa-pen-to-square"></i> Editar Kits
    </button>
  </div> -->

  <!-- Lista de kits -->
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <!-- Ejemplo de kit (puedes duplicar dinámicamente con PHP o JS) -->
    <div class="col">
      <div class="card shadow h-100">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="fa-solid fa-flask"></i> Kit de Química</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush small">
            <li class="list-group-item">Vaso de precipitados - 2 unidades</li>
            <li class="list-group-item">Ácido clorhídrico - 1 frasco</li>
            <li class="list-group-item">Mechero de Bunsen - 1 unidad</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow h-100">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0"><i class="fa-solid fa-microscope"></i> Kit de Biología</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush small">
            <li class="list-group-item">Portaobjetos - 10 unidades</li>
            <li class="list-group-item">Microscopio óptico - 1 unidad</li>
            <li class="list-group-item">Pinzas metálicas - 2 unidades</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow h-100">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0"><i class="fa-solid fa-atom"></i> Kit de Física</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush small">
            <li class="list-group-item">Multímetro digital - 1 unidad</li>
            <li class="list-group-item">Resistencias varias - 5 piezas</li>
            <li class="list-group-item">Cables de conexión - 6 piezas</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- Agrega más kits dinámicamente -->
  </div>
</div>

<!-- Modal: Editar Kit -->
<div class="modal fade" id="modalEditarKit" tabindex="-1" aria-labelledby="modalEditarKitLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formEditarKit">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEditarKitLabel"><i class="fa-solid fa-pen-to-square"></i> Editar Kit de Laboratorio</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          
          <!-- Materia (solo informativa) -->
          <div class="mb-3">
            <label for="materiaKit" class="form-label">Materia</label>
            <input type="text" class="form-control" id="materiaKit" name="materia" readonly>
          </div>

          <!-- Contenedor dinámico -->
          <div id="contenedorMaterialesKit"></div>
                    
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<br>

<?php include '../../../resources/templates/footer.php';?>

<!-- Scripts -->
<script src="../../components/js/jquery-3.7.1.js"></script>
<script src="../../components/js/bootstrap.bundle.min.js"></script>
<script src="../../components/js/KitFontAwesome.js"></script>
<script src="../../components/js/Lab/kits.js"></script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>