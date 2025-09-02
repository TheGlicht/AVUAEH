document.addEventListener("DOMContentLoaded", () => {
  cargarMateriales();

  document.getElementById("formAgregar").addEventListener("submit", agregarMaterial);
  document.getElementById("formEditar").addEventListener("submit", actualizarMaterial);
  document.getElementById("formKit").addEventListener("submit", guardarKit);
});

async function cargarMateriales() {
  const res = await fetch("../../../resources/api/Laboratorio/apiInventario.php");
  const materiales = await res.json();

  const estadoTexto = {
    1: '<span class="badge bg-success">Funcional</span>',
    2: '<span class="badge bg-warning">Dañado</span>',
    3: '<span class="badge bg-danger">Faltante</span>'
  };

  let html = '';
  materiales.forEach(mat => {
    html += `
      <tr>
        <td><input type="checkbox" class="material-checkbox" data-id="${mat.id_material}"></td>
        <td>${mat.nombre}</td>
        <td>${mat.tipo}</td>
        <td>${mat.cantidad}</td>
        <td>${estadoTexto[mat.estado] || mat.estado}</td>
        <td>
          <button class="btn btn-warning btn-sm" onclick="editarMaterial(${mat.id_material}, '${mat.nombre}', '${mat.tipo}', ${mat.cantidad}, ${mat.estado})"><i class="fa fa-pen"></i></button>
          <button class="btn btn-danger btn-sm" onclick="eliminarMaterial(${mat.id_material})"><i class="fa fa-trash"></i></button>
        </td>
      </tr>
    `;
  });

  document.getElementById('tablaMateriales').innerHTML = html;
}

// Agregar material
async function agregarMaterial(e) {
  e.preventDefault();
  const data = {
    nombre: document.getElementById('nombreMaterial').value,
    tipo: document.getElementById('tipoMaterial').value,
    cantidad: parseInt(document.getElementById('cantidadMaterial').value),
    estado: parseInt(document.getElementById('estadoMaterial').value)
  };

  await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  bootstrap.Modal.getInstance(document.getElementById('modalAgregar')).hide();
  cargarMateriales();
}

function editarMaterial(id, nombre, tipo, cantidad, estado) {
  document.getElementById('idEditar').value = id;
  document.getElementById('nombreEditar').value = nombre;
  document.getElementById('tipoEditar').value = tipo;
  document.getElementById('cantidadEditar').value = cantidad;
  document.getElementById('estadoEditar').value = estado;
  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

// Actualizar material
async function actualizarMaterial(e) {
  e.preventDefault();
  const data = {
    id_material: parseInt(document.getElementById('idEditar').value),
    nombre: document.getElementById('nombreEditar').value,
    tipo: document.getElementById('tipoEditar').value,
    cantidad: parseInt(document.getElementById('cantidadEditar').value),
    estado: parseInt(document.getElementById('estadoEditar').value)
  };

  await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
  cargarMateriales();
}

// Eliminar material
async function eliminarMaterial(id) {
  if (!confirm("¿Seguro que deseas eliminar este material?")) return;

  await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_material: id })
  });

  cargarMateriales();
}

// Armar Kits
function seleccionarTodos(checkbox) {
  document.querySelectorAll(".material-checkbox").forEach(cb => cb.checked = checkbox.checked);
}

function armarKit() {
  cargarMaterias();
  new bootstrap.Modal(document.getElementById('modalKit')).show();
}

async function cargarMaterias() {
  const res = await fetch("../../../resources/api/Laboratorio/apiKits.php?materias=1");
  const materias = await res.json();

  let options = '<option value="">-- Selecciona Materia --</option>';
  materias.forEach(m => {
    options += `<option value="${m.id_materias}">${m.nombre} (Semestre ${m.semestre})</option>`;
  });

  document.getElementById("materiaKit").innerHTML = options;
}


async function guardarKit(e) {
  e.preventDefault();
  const nombreKit = document.getElementById('nombreKit').value;
  const idMateria = parseInt(document.getElementById('materiaKit').value);

  if (!idMateria) {
    alert("Debes seleccionar una materia para el kit");
    return;
  }

  const seleccionados = Array.from(document.querySelectorAll(".material-checkbox:checked")).map(cb => ({
    id_material: parseInt(cb.dataset.id),
    cantidad: 1
  }));

  const data = { nombre: nombreKit, id_materias: idMateria, materiales: seleccionados };

  await fetch("../../../resources/api/Laboratorio/apiKits.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  bootstrap.Modal.getInstance(document.getElementById('modalKit')).hide();
  alert("Kit creado correctamente");
}

