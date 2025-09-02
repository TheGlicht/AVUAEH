<!-- php logica de programacion -->
<?php
 session_start();
// Evita que el navegador guarde en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(isset($_SESSION['username'])){
?>

<!-- Estructutra del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  
  <!-- Estilos -->
  <link rel="stylesheet" href="../../components/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../components/css/styleHome.css">
  <link rel="icon" type="icon" href="../../components/assets/Garza/Garza3.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- FullCalendar CSS correcto -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

</head>
<body>
    <?php include '../../../resources/templates/menuLab.php';?>
    
    <<div class="container mt-4">
    <h2>Inventario del Laboratorio</h2>

    <!-- Botones principales -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="fa fa-plus"></i> Agregar Material
        </button>
        <button class="btn btn-success" onclick="armarKit()">
            <i class="fa fa-box"></i> Armar Kit
        </button>
        <input type="checkbox" id="selectAll" onclick="seleccionarTodos(this)"> Seleccionar todos
    </div>

    <!-- Tabla -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaMateriales"></tbody>
    </table>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
  <div class="modal-dialog">
    <form id="formAgregar" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Agregar Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="nombreMaterial" class="form-control mb-2" placeholder="Nombre" required>
        <input type="text" id="tipoMaterial" class="form-control mb-2" placeholder="Tipo" required>
        <input type="number" id="cantidadMaterial" class="form-control mb-2" placeholder="Cantidad" required>
        <select id="estadoMaterial" class="form-select" required>
          <option value="1">Funcional</option>
          <option value="2">Dañado</option>
          <option value="3">Faltante</option>
        </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <form id="formEditar" class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Editar Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idEditar">
        <input type="text" id="nombreEditar" class="form-control mb-2" required>
        <input type="text" id="tipoEditar" class="form-control mb-2" required>
        <input type="number" id="cantidadEditar" class="form-control mb-2" required>
        <select id="estadoEditar" class="form-select" required>
          <option value="1">Funcional</option>
          <option value="2">Dañado</option>
          <option value="3">Faltante</option>
        </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-warning">Actualizar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Armar Kit -->
<div class="modal fade" id="modalKit" tabindex="-1">
  <div class="modal-dialog">
    <form id="formKit" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Armar Kit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="nombreKit" class="form-control mb-2" placeholder="Nombre del Kit" required>
        
        <!-- Aquí se cargan las materias dinámicamente -->
        <select id="materiaKit" class="form-select mb-2" required></select>
      </div>    
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Crear Kit</button>
      </div>
    </form>
  </div>
</div>



    <?php include '../../../resources/templates/footer.php';?>
    
    <!-- Scripts -->
    <script src="../../components/js/jquery-3.7.1.js"></script>
    <script src="../../components/js/bootstrap.bundle.min.js"></script>
    <script src="../../components/js/KitFontAwesome.js"></script>   
    <script src="../../components/js/Lab/Inventario.js"></script>

</body>
</html>
<?php
} else {
  header("Location: ../index.php");
  exit();
}
?>