console.log("Archivo kits funcionando");

let todosLosKits = []; // almacenamos todos los kits de la API

document.addEventListener("DOMContentLoaded", () => {
  cargarKits();

  // Evento buscador dinámico
  const buscador = document.getElementById("buscarMateria");
  if (buscador) {
    buscador.addEventListener("input", function () {
      const texto = this.value.toLowerCase();
      const filtrados = todosLosKits.filter(kit =>
        kit.materia.toLowerCase().includes(texto) ||
        kit.nombre.toLowerCase().includes(texto)
      );
      pintarKits(filtrados);
    });
  }
});

// =========================
// CARGAR Y PINTAR KITS
// =========================
function cargarKits() {
  fetch("../../../resources/api/Laboratorio/apiKits.php?completo=1")
    .then(res => res.json())
    .then(data => {
      // Agrupar kits
      const kits = {};
      data.forEach(item => {
        if (!kits[item.id_kit]) {
          kits[item.id_kit] = {
            id: item.id_kit,
            nombre: item.kit_nombre,
            materia: item.materia_nombre,
            materiales: []
          };
        }
        if (item.material_nombre) {
          kits[item.id_kit].materiales.push({
            nombre: item.material_nombre,
            cantidad: item.cantidad
          });
        }
      });

      // Guardar en variable global
      todosLosKits = Object.values(kits);

      // Pintar en pantalla
      pintarKits(todosLosKits);
    })
    .catch(err => {
      console.error("Error cargando kits:", err);
    });
}

function pintarKits(lista) {
  const contenedor = document.querySelector(".row.row-cols-1");
  contenedor.innerHTML = ""; // limpio el contenido

  lista.forEach(kit => {
    const card = document.createElement("div");
    card.className = "col";
    card.innerHTML = `
      <div class="card shadow h-100">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="fa-solid fa-flask"></i> ${kit.nombre} (${kit.materia})</h5>
          <div>
            <button class="btn btn-sm btn-warning me-1" onclick="editarKit(${kit.id})"><i class="fa-solid fa-pen"></i></button>
            <button class="btn btn-sm btn-danger" onclick="eliminarKit(${kit.id})"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush small">
            ${kit.materiales.map(m => `<li class="list-group-item">${m.nombre} - ${m.cantidad}</li>`).join("")}
          </ul>
        </div>
      </div>`;
    contenedor.appendChild(card);
  });

  // Si no hay resultados
  if (lista.length === 0) {
    contenedor.innerHTML = `
      <div class="col">
        <div class="alert alert-warning">No se encontraron kits para esta materia.</div>
      </div>`;
  }
}

// =========================
// FUNCIONES CRUD
// =========================

// Función para editar un kit
function editarKit(id_kit) {
  const modal = new bootstrap.Modal(document.getElementById("modalEditarKit"));
  modal.show();

  // Limpiar formulario antes de llenarlo
  document.getElementById("formEditarKit").reset();
  document.getElementById("contenedorMaterialesKit").innerHTML = "";

  // Guardar id del kit en hidden input
  if (!document.getElementById("id_kit_edit")) {
    const hidden = document.createElement("input");
    hidden.type = "hidden";
    hidden.id = "id_kit_edit";
    hidden.name = "id_kit";
    document.getElementById("formEditarKit").appendChild(hidden);
  }
  document.getElementById("id_kit_edit").value = id_kit;

  // Traer datos del kit desde API
  fetch("../../../resources/api/Laboratorio/apiKits.php?completo=1")
    .then(res => res.json())
    .then(data => {
      const materiales = data.filter(k => k.id_kit == id_kit && k.id_material);

      if (materiales.length === 0) return alert("Kit no encontrado");

      // Nombre de materia
      document.getElementById("materiaKit").value = materiales[0].materia_nombre;

      // Llenar materiales dinámicamente
      materiales.forEach(mat => {
        agregarFilaMaterial(mat.id_material, mat.material_nombre, mat.cantidad);
      });
    });
}

// Crear filas dinámicas en el modal
function agregarFilaMaterial(id_material = "", nombre = "", cantidad = 1) {
  const contenedor = document.getElementById("contenedorMaterialesKit");

  const row = document.createElement("div");
  row.className = "row g-3 mb-3";

  row.innerHTML = `
    <div class="col-md-6">
      <label class="form-label">Material</label>
      <input type="text" class="form-control" value="${nombre}" readonly>
      <input type="hidden" name="materiales_id[]" value="${id_material}">
    </div>
    <div class="col-md-4">
      <label class="form-label">Cantidad</label>
      <input type="number" class="form-control" name="cantidades[]" min="1" value="${cantidad}">
    </div>
    <div class="col-md-2 d-flex">
      <button type="button" class="btn btn-danger w-100" onclick="this.closest('.row').remove();">
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>
  `;

  contenedor.appendChild(row);
}

// Botón (+) para agregar material vacío
function agregarMaterialKit() {
  agregarFilaMaterial();
}

// Guardar cambios del kit
document.getElementById("formEditarKit").addEventListener("submit", function (e) {
  e.preventDefault();

  const id_kit = document.getElementById("id_kit_edit").value;
  const materiales_id = Array.from(document.getElementsByName("materiales_id[]")).map(i => i.value);
  const cantidades = Array.from(document.getElementsByName("cantidades[]")).map(i => i.value);

  const materiales = materiales_id.map((id, index) => ({
    id_material: id,
    cantidad: cantidades[index]
  }));

  fetch("../../../resources/api/Laboratorio/apiKits.php", {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_kit, materiales })
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.success) {
        alert("Cambios guardados correctamente");
        cargarKits(); // refrescar sin recargar la página
        bootstrap.Modal.getInstance(document.getElementById("modalEditarKit")).hide();
      } else {
        alert("Error al guardar cambios: " + (resp.error || ""));
      }
    })
    .catch(err => {
      console.error(err);
      alert("Ocurrió un error al guardar cambios.");
    });
});

// Eliminar kit
function eliminarKit(id_kit) {
  if (!confirm("¿Seguro que deseas eliminar este kit?")) return;

  fetch("../../../resources/api/Laboratorio/apiKits.php", {
    method: "DELETE",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id_kit=${id_kit}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.success) {
        alert("Kit eliminado correctamente");
        cargarKits(); // refresca la lista
      } else {
        alert("Error al eliminar el kit: " + (resp.error || ""));
      }
    });
}
