<!-- php logica de programacion -->

<!-- Estructutra del sitio web -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  
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
    
    <div class="container mt-4">
  <h2 class="mb-4 text-center">Inventario de Laboratorio</h2>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-6 col-lg-4">
        <select class="form-select" id="filtroTipo" onchange="filtrarMateriales()">
            <option value="todos">Todos los tipos</option>
            <option value="vidrio">Vidrio</option>
            <option value="reactivo">Reactivo</option>
            <option value="equipo">Equipo</option>
            <option value="otro">Otro</option>
        </select>
        </div>
        <div class="col text-end">
        <button class="btn btn-success me-2"><i class="fa-solid fa-plus"></i> Agregar Material</button>
        <button class="btn btn-primary" onclick="armarKit()"><i class="fa-solid fa-toolbox"></i> Armar Kit</button>
        </div>
    </div>

    <!-- Tabla de materiales -->
    <div class="table-responsive">
        <table class="table table-striped align-middle text-center">
        <thead class="table-primary">
            <tr>
            <th scope="col"><input type="checkbox" onclick="seleccionarTodos(this)"></th>
            <th scope="col">Nombre</th>
            <th scope="col">Tipo</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Estado</th>
            <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaMateriales">
            <!-- Ejemplo de fila -->
            <tr data-tipo="vidrio">
            <td><input type="checkbox" class="material-checkbox"></td>
            <td>Vaso de precipitados</td>
            <td>Vidrio</td>
            <td>15</td>
            <td><span class="badge bg-success">Funcional</span></td>
            <td>
                <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-wrench"></i></button>
            </td>
            </tr>
            <!-- Más filas dinámicas -->
        </tbody>
        </table>
    </div>
    </div>


    <!-- Modal: Agregar Material -->
<div class="modal fade" id="modalAgregarMaterial" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formAgregarMaterial">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalAgregarLabel"><i class="fa-solid fa-plus"></i> Agregar Nuevo Material</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label for="nombreMaterial" class="form-label">Nombre del Material</label>
            <input type="text" class="form-control" id="nombreMaterial" required>
          </div>
          <div class="col-md-6">
            <label for="tipoMaterial" class="form-label">Tipo</label>
            <select class="form-select" id="tipoMaterial" required>
              <option value="" disabled selected>Selecciona</option>
              <option value="vidrio">Vidrio</option>
              <option value="reactivo">Reactivo</option>
              <option value="equipo">Equipo</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="cantidadMaterial" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidadMaterial" min="1" required>
          </div>
          <div class="col-md-6">
            <label for="estadoMaterial" class="form-label">Estado</label>
            <select class="form-select" id="estadoMaterial" required>
              <option value="Funcional">Funcional</option>
              <option value="Dañado">Dañado</option>
              <option value="Faltante">Faltante</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Editar Material -->
<div class="modal fade" id="modalEditarMaterial" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formEditarMaterial">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="modalEditarLabel"><i class="fa-solid fa-pen"></i> Editar Material</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body row g-3">
          <input type="hidden" id="idMaterialEditar">
          <div class="col-md-6">
            <label for="nombreEditar" class="form-label">Nombre del Material</label>
            <input type="text" class="form-control" id="nombreEditar" required>
          </div>
          <div class="col-md-6">
            <label for="tipoEditar" class="form-label">Tipo</label>
            <select class="form-select" id="tipoEditar" required>
              <option value="vidrio">Vidrio</option>
              <option value="reactivo">Reactivo</option>
              <option value="equipo">Equipo</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="cantidadEditar" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidadEditar" required>
          </div>
          <div class="col-md-6">
            <label for="estadoEditar" class="form-label">Estado</label>
            <select class="form-select" id="estadoEditar" required>
              <option value="Funcional">Funcional</option>
              <option value="Dañado">Dañado</option>
              <option value="Faltante">Faltante</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Actualizar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
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