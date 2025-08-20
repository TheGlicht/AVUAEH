console.log("Archivo Materias.js cargado correctamente");

const tabla = document.querySelector('#tablaMaterias tbody');
const btnAgregar = document.getElementById('agregarMateria');
const materiaSelect = document.getElementById('materiaSelect');
const searchInput = document.getElementById('searchInput');
let materiasAgregadas = [];

// Evento para cargar las materias en el select
document.addEventListener('DOMContentLoaded', () => {
  // Verificamos que el select existe
  if (!materiaSelect) {
    console.error('No se encontró el elemento materiaSelect');
    return;
  }

  fetch('../../../resources/api/apiMaterias.php?action=mostrarM')
    .then(res => {
      if (!res.ok) {
        throw new Error(`Error HTTP! estado: ${res.status}`);
      }
      return res.text();
    })
    .then(html => {
      console.log("Respuesta del servidor:", html);
      
      // Verificamos que la respuesta no esté vacía
      if (!html.trim()) {
        throw new Error('La respuesta del servidor está vacía');
      }
      
      // Insertamos las opciones después de la primera opción
      materiaSelect.insertAdjacentHTML('beforeend', html);
      console.log("Materias cargadas correctamente");
    })
    .catch(err => {
      console.error('Error cargando materias:', err);
      // Mostrar mensaje de error en caso de fallo
      materiaSelect.innerHTML = '<option value="">Error cargando materias</option>';
    });
});

// Resto del código permanece igual...
// Evento para agregar materia a la tabla
btnAgregar.addEventListener('click', () => {
  const materiaId = materiaSelect.value;
  const materiaNombre = materiaSelect.options[materiaSelect.selectedIndex].text;
  
  // Validar selección y evitar duplicados
  if (!materiaId || materiasAgregadas.includes(materiaId)) {
    alert('Por favor selecciona una materia válida o que no esté ya agregada');
    return;
  }
  
  materiasAgregadas.push(materiaId);
  
  // Crear nueva fila en la tabla
  const fila = document.createElement('tr');
  fila.innerHTML = `
    <td>
      <input type="hidden" name="materia_id[]" value="${materiaId}">
      <input type="text" class="form-control-plaintext text-center" readonly value="${materiaNombre}">
    </td>
    <td><input type="number" class="form-control calificacion" name="parcial1[]" min="0" max="100"></td>
    <td><input type="number" class="form-control calificacion" name="parcial2[]" min="0" max="100"></td>
    <td><input type="number" class="form-control calificacion" name="ordinario[]" min="0" max="100"></td>
    <td class="promedio fw-bold text-primary">0.0</td>
    <td class="faltante fw-bold text-danger">0.0</td>
    <td><button type="button" class="btn btn-sm btn-danger eliminar"><i class="fa-solid fa-trash"></i></button></td>
  `;
  tabla.appendChild(fila);
  actualizarEventos();
});

function actualizarEventos() {
  // Eventos para los campos de calificación
  tabla.querySelectorAll('.calificacion').forEach(input => {
    input.addEventListener('input', calcularPromedios);
  });

  // Eventos para los botones de eliminar
  tabla.querySelectorAll('.eliminar').forEach(btn => {
    btn.addEventListener('click', e => {
      const fila = e.target.closest('tr');
      const materiaId = fila.querySelector('input[name="materia_id[]"]').value;
      
      // Eliminar materia de la lista de agregadas
      materiasAgregadas = materiasAgregadas.filter(id => id !== materiaId);
      fila.remove();
    });
  });
}

function calcularPromedios() {
  tabla.querySelectorAll('tr').forEach(fila => {
    const inputs = fila.querySelectorAll('.calificacion');
    const p1 = parseFloat(inputs[0].value) || 0;
    const p2 = parseFloat(inputs[1].value) || 0;
    const ord = parseFloat(inputs[2].value) || 0;

    // Calcular promedio ponderado
    const promedio = (p1 * 0.3 + p2 * 0.3 + ord * 0.4).toFixed(1);
    fila.querySelector('.promedio').textContent = promedio;

    // Calcular cuanto falta para 60
    const falta = Math.max(0, (60 - promedio)).toFixed(1);
    fila.querySelector('.faltante').textContent = falta > 0 ? falta : "0.0";
  });
}