// === Config ===
const API_URL = "../../../resources/api/Laboratorio/apiReporte.php";

// Inicializa fecha límite automática
const fechaLimite = new Date();
fechaLimite.setDate(fechaLimite.getDate() + 14);
document.getElementById("fechaLimite").value = fechaLimite.toISOString().split("T")[0];

// Helper para parsear JSON con fallback a texto (evita el “Unexpected token <” silencioso)
function fetchJSON(url, options) {
  return fetch(url, options).then(async (res) => {
    const text = await res.text();
    try {
      return JSON.parse(text);
    } catch (e) {
      console.error("Respuesta no-JSON del servidor:", text);
      throw e;
    }
  });
}

// ===== Alumnos =====
function agregarAlumno() {
  const grupo = document.createElement("div");
  grupo.classList = "row g-2 mb-2 alumno-row";
  grupo.innerHTML = `
    <div class="col-md-5"><input type="text" class="form-control" placeholder="Nombre del alumno" required></div>
    <div class="col-md-5"><input type="text" class="form-control" placeholder="Número de cuenta" required></div>
    <div class="col-md-2 d-flex justify-content-center">
      <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.alumno-row').remove()">
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>`;
  document.getElementById("equipoAlumnos").appendChild(grupo);
}


// ===== Materiales =====
let opcionesMaterialesHTML = '<option selected disabled>Selecciona material</option>';

function cargarOpcionesMateriales() {
  return fetchJSON(`${API_URL}?materiales=1`)
    .then((materiales) => {
      opcionesMaterialesHTML = '<option selected disabled>Selecciona material</option>';
      materiales.forEach((m) => {
        opcionesMaterialesHTML += `<option value="${m.id_material}">${m.nombre}</option>`;
      });

      // Rellena el primer <select> ya presente en el HTML
      const primerSelect = document.querySelector("select[name='material[]']");
      if (primerSelect) primerSelect.innerHTML = opcionesMaterialesHTML;
    })
    .catch((err) => {
      console.error("Error cargando materiales:", err);
      // deja la opción por defecto si falla
      opcionesMaterialesHTML = '<option selected disabled>Selecciona material</option>';
    });
}

// ======= Agregar Material ========
function agregarMaterial() {
  const grupo = document.createElement("div");
  grupo.classList = "row g-2 mb-2 material-row";
  grupo.innerHTML = `
    <div class="col-md-7">
      <select class="form-select" name="material[]">
        ${opcionesMaterialesHTML}
      </select>
    </div>
    <div class="col-md-3">
      <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" min="1" required>
    </div>
    <div class="col-md-2 d-flex justify-content-center">
      <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.material-row').remove()">
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>`;
  document.getElementById("materialRoto").appendChild(grupo);
}

// ===== Tabla de reportes =====
function cargarReportes() {
  fetchJSON(`${API_URL}?_=${Date.now()}`)
    .then((data) => {
      const tbody = document.getElementById("tablaReportes");
      tbody.innerHTML = "";
      data.forEach((dano) => {
        const materialTexto = dano.material || "Desconocido"; // 👈 corrección
        const cantidad = dano.cantidad ?? 1;
        const encargado = dano.encargado || "-";
        const nombreEquipo = (dano.nombreAlu ? `${dano.nombreAlu} (${dano.numeroCuenta || "-"})` : "-");

        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${nombreEquipo}</td>
          <td>${materialTexto}</td>
          <td>${cantidad}</td>
          <td>${encargado}</td>
          <td>${dano.fechaLimite || "-"}</td>
          <td>
            <button class="btn btn-sm btn-danger" onclick="eliminarReporte(${dano.id_dano}, this)">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>`;
        tbody.appendChild(row);
      });
    })
    .catch((err) => console.error("Error al cargar reportes:", err));
}

// Eliminar reporte
function eliminarReporte(id_dano, btn) {
  if (!confirm("¿Deseas eliminar este reporte?")) return;

  fetchJSON(API_URL, {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      action: 'DELETE',
      id_dano: id_dano
    })
  })
  .then((resp) => {
    if (resp.success) {
      btn.closest("tr").remove();
    } else {
      alert("Error al eliminar reporte: " + (resp.error || "desconocido"));
    }
  })
  .catch((err) => {
    console.error("Error al eliminar:", err);
    alert("Ocurrió un error al eliminar el reporte.");
  });
}


// Submit del formulario
document.getElementById("formReporte").addEventListener("submit", function(e){
  e.preventDefault();

  const encargado = document.getElementById("nombreEncargado").value;
  const fecha = document.getElementById("fechaLimite").value;

  // Alumnos
  const alumnos = [...document.querySelectorAll("#equipoAlumnos .row")].map(row => {
    const inputs = row.querySelectorAll("input");
    return { nombre: inputs[0].value, numeroCuenta: inputs[1].value };
  });

  // Materiales
  const cantidadesInputs = document.querySelectorAll("input[name='cantidad[]']");
  const materiales = [...document.querySelectorAll("select[name='material[]']")].map((s, i) => {
    return { id_material: parseInt(s.value), cantidad: parseInt(cantidadesInputs[i].value) };
  });

  // Enviar POST a API
  fetchJSON(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      alumnos: alumnos,
      materiales: materiales,
      fechaLimite: fecha,
      id_laboratorio: 1,
      encargado: encargado
    })
  })
  .then((resp) => {
    if (resp.success) {
      // generar PDF estético como pediste
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();

      // Encabezado
      doc.setFontSize(16);
      doc.setFont("helvetica", "bold");
      doc.text("Universidad Autónoma del Estado de Hidalgo", 105, 20, { align: "center" });
      doc.setFontSize(12);
      doc.setFont("helvetica", "normal");
      doc.text("Reporte de Material Roto", 105, 30, { align: "center" });

      doc.setFontSize(10);
      doc.text(`Encargado: ${encargado}`, 20, 45);
      doc.text(`Fecha límite: ${fecha}`, 150, 45);

      // Lista de alumnos
      doc.setFontSize(11);
      doc.text("Alumnos:", 20, 60);
      alumnos.forEach((al, idx) => {
        doc.text(`${idx + 1}. ${al.nombre} (${al.numeroCuenta})`, 30, 70 + idx * 8);
      });

      // Lista de materiales
      let startY = 70 + alumnos.length * 8 + 10;
      doc.text("Materiales dañados:", 20, startY);
      materiales.forEach((mat, i) => {
        const materialNombre = document.querySelector(`select[name='material[]'] option[value='${mat.id_material}']`)?.textContent || mat.id_material;
        doc.text(`- ${materialNombre} (x${mat.cantidad})`, 30, startY + 10 + i * 8);
      });

      // Espacio para firma
      let firmaY = startY + 30 + materiales.length * 8;
      doc.text("__________________________________", 105, firmaY, { align: "center" });
      doc.text("Firma del alumno responsable", 105, firmaY + 8, { align: "center" });

      // Guardar PDF
      doc.save(`Reporte_${encargado}_${fecha}.pdf`);

      // refrescar tabla y limpiar formulario
      cargarReportes();
      document.getElementById("formReporte").reset();
      document.getElementById("fechaLimite").value = fechaLimite.toISOString().split("T")[0];
    } else {
      alert("Error al generar el reporte: " + (resp.error || ""));
    }
  })
  .catch((err) => console.error("Error al generar reporte:", err));
});

// Cargar la tabla y las opciones del primer select al inicio
window.addEventListener("DOMContentLoaded", () => {
  cargarOpcionesMateriales().then(() => {
    cargarReportes();
  });
});
