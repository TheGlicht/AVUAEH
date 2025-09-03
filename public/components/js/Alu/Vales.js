// 🔹 Definir la ruta base del API (desde Vales.php)
const API_URL = "../../../resources/api/Alumnos/apiVales.php";

// 🔹 Obtener referencias a los elementos del DOM
const materiaSelect   = document.getElementById('materia');
const docenteSelect   = document.getElementById('docente');
const kitSelect       = document.getElementById('kit');
const tablaBody       = document.querySelector('#tablaVales tbody');

// ✅ Cargar materias
async function cargarMaterias() {
  try {
    const resp = await fetch(`${API_URL}?action=materias`);
    const materias = await resp.json();

    materiaSelect.innerHTML = '<option value="" disabled selected>Selecciona una materia</option>';
    materias.forEach(m => {
      const opt = document.createElement('option');
      opt.value = m.id_materias;
      opt.textContent = m.nombre;
      materiaSelect.appendChild(opt);
    });
  } catch (err) {
    console.error("Error al cargar materias:", err);
  }
}

// ✅ Cargar docentes por materia
async function cargarDocentes(id_materia) {
  try {
    const resp = await fetch(`${API_URL}?action=docentes&id_materia=${id_materia}`);
    const docentes = await resp.json();

    docenteSelect.innerHTML = '<option value="" disabled selected>Selecciona un docente</option>';
    docentes.forEach(d => {
      const opt = document.createElement('option');
      opt.value = d.id_docente;
      opt.textContent = d.nombreCompleto;
      docenteSelect.appendChild(opt);
    });
  } catch (err) {
    console.error("Error al cargar docentes:", err);
  }
}

// ✅ Cargar kits por materia
async function cargarKits(id_materia) {
  try {
    const resp = await fetch(`${API_URL}?action=kits&id_materia=${id_materia}`);
    const kits = await resp.json();

    kitSelect.innerHTML = '<option value="" disabled selected>Selecciona un kit</option>';
    kits.forEach(k => {
      const opt = document.createElement('option');
      opt.value = k.id_kit;
      opt.textContent = k.nombre;
      kitSelect.appendChild(opt);
    });
  } catch (err) {
    console.error("Error al cargar kits:", err);
  }
}

// ✅ Cargar vales existentes
async function cargarVales() {
  try {
    const resp = await fetch(`${API_URL}?action=vales`);
    const vales = await resp.json();

    tablaBody.innerHTML = '';
    if (vales.length > 0) {
      vales.forEach(vale => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td>${vale.materia}</td>
          <td>${vale.docente}</td>
          <td>${vale.diaLab}</td>
          <td>${vale.horaLab}</td>
          <td>${vale.id_lab}</td>
          <td>${vale.kit}</td>
          <td>
            <button class="btn btn-danger btn-sm eliminar" data-id="${vale.id_vales}">
              Eliminar
            </button>
          </td>
        `;
        tablaBody.appendChild(fila);
      });

      // Agregar eventos a los botones eliminar
      document.querySelectorAll('.eliminar').forEach(btn => {
        btn.addEventListener('click', async () => {
          if (confirm('¿Seguro que quieres eliminar este vale?')) {
            await eliminarVale(btn.dataset.id);
            await cargarVales(); // refrescar tabla
          }
        });
      });
    } else {
      tablaBody.innerHTML = `<tr><td colspan="7" class="text-center">No hay vales registrados</td></tr>`;
    }
  } catch (err) {
    console.error("Error al cargar vales:", err);
  }
}

// ✅ Eliminar vale
async function eliminarVale(id_vales) {
  try {
    await fetch(API_URL, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_vales })
    });
  } catch (err) {
    console.error("Error al eliminar vale:", err);
  }
}

// 🔹 Inicialización
document.addEventListener("DOMContentLoaded", async () => {
  await cargarMaterias();
  await cargarVales();

  // Cuando cambie materia, cargar docentes y kits
  materiaSelect.addEventListener("change", async () => {
    const id_materia = materiaSelect.value;
    if (id_materia) {
      await cargarDocentes(id_materia);
      await cargarKits(id_materia);
    }
  });
});
