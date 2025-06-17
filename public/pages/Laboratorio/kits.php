<!-- php logica de programación -->

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

  <!-- Botón para armar nuevo kit -->
  <div class="text-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarKit">
      <i class="fa-solid fa-pen-to-square"></i> Editar Kits
    </button>
  </div>

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
          <div class="mb-3">
            <label for="materiaKit" class="form-label">Materia</label>
            <select class="form-select" id="materiaKit" required>
              <option value="" disabled selected>Selecciona una materia</option>
              <option>Química</option>
              <option>Física</option>
              <option>Biología</option>
              <option>Electrónica</option>
            </select>
          </div>

          <div id="contenedorMaterialesKit">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Material</label>
                <select class="form-select" id="materiaKit" required>
                    <option value="" disabled selected>Selecciona un material</option>
                    <option>Vaso de precipitado</option>
                    <option>Mechero</option>
                    <option>Embudo de vidrio</option>
                    <option>Cintametrica</option>              
                    </select>              
                </div>
              <div class="col-md-4">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control" name="cantidad[]" min="1" value="1">
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100" onclick="this.closest('.row').remove();"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="button" class="btn btn-secondary" onclick="agregarMaterialKit()">
              <i class="fa-solid fa-plus"></i> Agregar Material
            </button>
          </div>
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

<script>
function agregarMaterialKit() {
  const container = document.getElementById('contenedorMaterialesKit');
  const row = document.createElement('div');
  row.className = 'row g-3 mb-3';
  row.innerHTML = `
    <div class="col-md-6">
 <select class="form-select" id="materiaKit" required>
                    <option value="" disabled selected>Selecciona un material</option>
                    <option>Vaso de precipitado</option>
                    <option>Mechero</option>
                    <option>Embudo de vidrio</option>
                    <option>Cintametrica</option>              
                    </select>      </div>
    <div class="col-md-4">
      <input type="number" class="form-control" name="cantidad[]" value="1" min="1">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="button" class="btn btn-danger w-100" onclick="this.closest('.row').remove();"><i class="fa-solid fa-trash"></i></button>
    </div>`;
  container.appendChild(row);
}
</script>

</body>
</html>
