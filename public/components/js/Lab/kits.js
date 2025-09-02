console.log("Archivo kits funcionando");

document.addEventListener("DOMContentLoaded", cargarKits);

function cargarKits() {
  fetch("../../../resources/api/Laboratorio/apiKits.php?completo=1")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.querySelector(".row.row-cols-1");
      contenedor.innerHTML = ""; // limpio el contenido

      // Agrupar kits
      const kits = {};
      data.forEach(item => {
        if (!kits[item.id_kit]) {
          kits[item.id_kit] = {
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

      // Pintar tarjetas
      Object.entries(kits).forEach(([id, kit]) => {
        const card = document.createElement("div");
        card.className = "col";
        card.innerHTML = `
          <div class="card shadow h-100">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0"><i class="fa-solid fa-flask"></i> ${kit.nombre} (${kit.materia})</h5>
              <button class="btn btn-sm btn-warning" onclick="editarKit(${id})"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger" onclick="eliminarKit(${id})"><i class="fa-solid fa-trash"></i></button>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush small">
                ${kit.materiales.map(m => `<li class="list-group-item">${m.nombre} - ${m.cantidad}</li>`).join("")}
              </ul>
            </div>
          </div>`;
        contenedor.appendChild(card);
      });
    });
}

// Función para editar un kit
// function editarKit(id_kit) {
//     // Aquí puedes abrir tu modal con los datos
//     console.log("Editar kit:", id_kit);
  
//     // Ejemplo: obtener datos del kit desde API
//     fetch("../../../resources/api/Laboratorio/apiKits.php?completo=1")
//       .then(res => res.json())
//       .then(data => {
//         const kit = data.find(k => k.id_kit == id_kit);
//         if (!kit) return alert("Kit no encontrado");
  
//         // Aquí llenas tu modal con los datos del kit
//         alert(`Abrir modal para editar: ${kit.kit_nombre}`);
//       });
//   }

function editarKit(id_kit) {
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById("modalEditarKit"));
    modal.show();
  
    // Limpiar formulario antes de llenarlo
    document.getElementById("formEditarKit").reset();
    document.getElementById("contenedorMaterialesKit").innerHTML = "";
  
    // Guardar id del kit (puedes usar un hidden input)
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
        // Agrupar para este kit
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
  
  // Función para crear filas dinámicas en el modal
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
  


  // Función para agregar fila vacía desde botón (+)
  function agregarMaterialKit() {
    agregarFilaMaterial();
  }
  
  // Guardar cambios del kit
  document.getElementById("formEditarKit").addEventListener("submit", function(e) {
    e.preventDefault();

    const id_kit = document.getElementById("id_kit_edit").value;
    const materiales_id = Array.from(document.getElementsByName("materiales_id[]")).map(i => i.value);
    const cantidades = Array.from(document.getElementsByName("cantidades[]")).map(i => i.value);

    const materiales = materiales_id.map((id, index) => ({
        id_material: id,
        cantidad: cantidades[index]
    }));

    // Enviar PUT a la API
    fetch("../../../resources/api/Laboratorio/apiKits.php", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_kit, materiales })
    })
    .then(res => res.text()) // <- primero text para diagnosticar errores
    // .then(respText => {
    //     try {
    //         const resp = JSON.parse(respText); // parseamos manualmente
    //         if (resp.success) {
    //             alert("Cambios guardados correctamente");
    //             cargarKits();
    //             bootstrap.Modal.getInstance(document.getElementById("modalEditarKit")).hide();
    //         } else {
    //             alert("Error al guardar cambios: " + (resp.error || respText));
    //         }
    //     } catch (err) {
    //         console.error("Respuesta inválida de la API:", respText);
    //         alert("Ocurrió un error al guardar cambios. Revisa la consola.");
    //     }
    // })
    .catch(err => {
        console.error(err);
        alert("Ocurrió un error al guardar cambios.");
    });
});




  // Función para eliminar un kit
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
  
