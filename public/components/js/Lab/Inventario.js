let materialesGlobal = []; // guardamos todos los materiales cargados

document.addEventListener("DOMContentLoaded", () => {
  cargarMateriales();

  document.getElementById("formAgregar").addEventListener("submit", agregarMaterial);
  document.getElementById("formEditar").addEventListener("submit", actualizarMaterial);
  document.getElementById("formKit").addEventListener("submit", guardarKit);
});

// =======================
// Cargar materiales
// =======================
async function cargarMateriales() {
  const res = await fetch("../../../resources/api/Laboratorio/apiInventario.php");
  materialesGlobal = await res.json();
  renderTabla(materialesGlobal);
}

function renderTabla(lista) {
  let html = '';
  lista.forEach(mat => {
    html += `
      <tr>
        <td><input type="checkbox" class="material-checkbox" data-id="${mat.id_material}"></td>
        <td>${mat.nombre}</td>
        <td>${mat.tipo}</td>
        <td>${mat.cantidad}</td>
        <td>${mat.cantidad_funcional}</td>
        <td>${mat.cantidad_danado}</td>
        <td>${mat.cantidad_faltante}</td>
        <td>
          <button class="btn btn-warning btn-sm" onclick="editarMaterial(${mat.id_material}, '${mat.nombre}', '${mat.tipo}', ${mat.cantidad}, ${mat.cantidad_funcional}, ${mat.cantidad_danado}, ${mat.cantidad_faltante})"><i class="fa fa-pen"></i></button>
          <button class="btn btn-danger btn-sm" onclick="eliminarMaterial(${mat.id_material})"><i class="fa fa-trash"></i></button>
        </td>
      </tr>
    `;
  });
  document.getElementById('tablaMateriales').innerHTML = html;
}

// =======================
// Agregar material
// =======================
async function agregarMaterial(e) {
  e.preventDefault();
  const data = {
    nombre: document.getElementById('nombreMaterial').value,
    tipo: document.getElementById('tipoMaterial').value,
    cantidad: parseInt(document.getElementById('cantidadMaterial').value),
    cantidad_funcional: parseInt(document.getElementById('cantidadFuncional').value),
    cantidad_danado: parseInt(document.getElementById('cantidadDanado').value),
    cantidad_faltante: parseInt(document.getElementById('cantidadFaltante').value)
  };

  const res = await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  const result = await res.json();
  if (!result.success) {
    alert(result.message || "Error al agregar material");
    return;
  }

  bootstrap.Modal.getInstance(document.getElementById('modalAgregar')).hide();
  cargarMateriales();
}

// =======================
// Editar material
// =======================
function editarMaterial(id, nombre, tipo, cantidad, funcional, danado, faltante) {
  document.getElementById('idEditar').value = id;
  document.getElementById('nombreEditar').value = nombre;
  document.getElementById('tipoEditar').value = tipo;
  document.getElementById('cantidadEditar').value = cantidad;
  document.getElementById('funcionalEditar').value = funcional;
  document.getElementById('danadoEditar').value = danado;
  document.getElementById('faltanteEditar').value = faltante;
  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

async function actualizarMaterial(e) {
  e.preventDefault();
  const data = {
    id_material: parseInt(document.getElementById('idEditar').value),
    nombre: document.getElementById('nombreEditar').value,
    tipo: document.getElementById('tipoEditar').value,
    cantidad: parseInt(document.getElementById('cantidadEditar').value),
    cantidad_funcional: parseInt(document.getElementById('funcionalEditar').value),
    cantidad_danado: parseInt(document.getElementById('danadoEditar').value),
    cantidad_faltante: parseInt(document.getElementById('faltanteEditar').value)
  };

  const res = await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  const result = await res.json();
  if (!result.success) {
    alert(result.message || "Error al actualizar material");
    return;
  }

  bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
  cargarMateriales();
}

// =======================
// Eliminar material
// =======================
async function eliminarMaterial(id) {
  if (!confirm("¿Seguro que deseas eliminar este material?")) return;

  const res = await fetch("../../../resources/api/Laboratorio/apiInventario.php", {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_material: id })
  });

  const result = await res.json();
  if (!result.success) {
    alert(result.message || "Error al eliminar material");
    return;
  }

  cargarMateriales();
}

// =======================
// Seleccionar todos
// =======================
function seleccionarTodos(checkbox) {
  document.querySelectorAll(".material-checkbox").forEach(cb => cb.checked = checkbox.checked);
}

// =======================
// Armar Kit
// =======================
function armarKit() {
  const seleccionados = Array.from(document.querySelectorAll(".material-checkbox:checked"))
    .map(cb => {
      const id = parseInt(cb.dataset.id);
      return materialesGlobal.find(m => m.id_material === id);
    });

  if (seleccionados.length === 0) {
    alert("Debes seleccionar al menos un material para armar el kit");
    return;
  }

  // Construimos la tabla en el modal
  let html = '';
  seleccionados.forEach(mat => {
    html += `
      <tr>
        <td>${mat.nombre}</td>
        <td>${mat.cantidad_funcional}</td>
        <td>
          <input type="number" class="form-control cantidad-kit" 
            data-id="${mat.id_material}" 
            min="1" 
            max="${mat.cantidad_funcional}" 
            value="1">
        </td>
      </tr>
    `;
  });

  document.getElementById("tablaMaterialesKit").innerHTML = html;

  cargarMaterias(); // ahora sí existe
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

  // Recolectar materiales y cantidades
  const materialesSeleccionados = Array.from(document.querySelectorAll(".cantidad-kit")).map(input => ({
    id_material: parseInt(input.dataset.id),
    cantidad: parseInt(input.value)
  }));

  // Validar que ninguna cantidad sea inválida
  for (let m of materialesSeleccionados) {
    const mat = materialesGlobal.find(x => x.id_material === m.id_material);
    if (m.cantidad < 1 || m.cantidad > mat.cantidad_funcional) {
      alert(`La cantidad para "${mat.nombre}" debe estar entre 1 y ${mat.cantidad_funcional}`);
      return;
    }
  }

  const data = { nombre: nombreKit, id_materias: idMateria, materiales: materialesSeleccionados };

  const res = await fetch("../../../resources/api/Laboratorio/apiKits.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });

  const result = await res.json();
  if (!result.success) {
    alert(result.message || "Error al crear el kit");
    return;
  }

  bootstrap.Modal.getInstance(document.getElementById('modalKit')).hide();
  alert("Kit creado correctamente");
  cargarMateriales(); // refrescar inventario
}

// =======================
// Buscador dinámico
// =======================
document.getElementById("buscador").addEventListener("input", (e) => {
  const texto = e.target.value.toLowerCase();
  const filtrados = materialesGlobal.filter(m => 
    m.nombre.toLowerCase().includes(texto)
  );
  renderTabla(filtrados);
});
