console.log("Archivo Materias.js cargado correctamente");

const tabla = document.querySelector('#tablaMaterias tbody');
const btnAgregar = document.getElementById('agregarMateria');
const materiaSelect = document.getElementById('materiaSelect');
const searchInput = document.getElementById('searchInput');
const searchBtn = document.getElementById('searchBtn');
let materiasAgregadas = [];

// Buscador
function filtrarMaterias() {
  const filtro = searchInput.value.toLowerCase();
  Array.from(materiaSelect.options).forEach(option => {
    const texto = option.text.toLowerCase();
    option.style.display = texto.includes(filtro) || option.value === "" ? "block" : "none";
  });
}
searchInput.addEventListener('input', filtrarMaterias);
searchBtn.addEventListener('click', filtrarMaterias);

// Cargar materias registradas al iniciar
document.addEventListener('DOMContentLoaded', () => {
  fetch('../../../resources/api/Alumnos/apiRelacion.php?action=listar')
    .then(res => res.json())
    .then(data => {
      data.forEach(m => {
        agregarFila(m.nombre, m.parcial1, m.parcial2, m.ordinario, m.id_materias, false);
      });
    });
});

// Agregar materia
btnAgregar.addEventListener('click', () => {
  const materiaId = materiaSelect.value;
  const materiaNombre = materiaSelect.options[materiaSelect.selectedIndex].text;
  if (!materiaId || materiasAgregadas.includes(materiaId)) {
    alert('Seleccione una materia válida que no esté agregada.');
    return;
  }

  // Guardar en DB
  fetch('../../../resources/api/Alumnos/apiRelacion.php?action=agregar', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `id_materias=${materiaId}`
  })
  .then(res => res.text())
  .then(res => {
    if(res.trim() === "OK"){
      agregarFila(materiaNombre, 0, 0, 0, materiaId, true);
    } else {
      alert("Error al agregar la materia.");
    }
  });
});

function agregarFila(nombre, p1=0, p2=0, ord=0, id_materia, agregarArray=true){
  if(agregarArray) materiasAgregadas.push(id_materia);

  const fila = document.createElement('tr');
  fila.dataset.id = id_materia;
  fila.innerHTML = `
    <td>
      <input type="hidden" name="materia_id[]" value="${id_materia}">
      <input type="text" class="form-control-plaintext text-center" readonly value="${nombre}">
    </td>
    <td><input type="number" class="form-control calificacion" name="parcial1[]" min="0" max="100" value="${p1}"></td>
    <td><input type="number" class="form-control calificacion" name="parcial2[]" min="0" max="100" value="${p2}"></td>
    <td><input type="number" class="form-control calificacion" name="ordinario[]" min="0" max="100" value="${ord}"></td>
    <td class="promedio fw-bold text-primary">0.0</td>
    <td class="faltante fw-bold text-danger">0.0</td>
    <td>
      <button type="button" class="btn btn-sm btn-success guardar"><i class="fa-solid fa-floppy-disk"></i></button>
      <button type="button" class="btn btn-sm btn-danger eliminar"><i class="fa-solid fa-trash"></i></button>
    </td>
  `;
  tabla.appendChild(fila);
  actualizarEventos();
  calcularPromedios();
}

function actualizarEventos(){
  tabla.querySelectorAll('.calificacion').forEach(input => {
    input.addEventListener('input', calcularPromedios);
  });

  tabla.querySelectorAll('.eliminar').forEach(btn => {
    btn.addEventListener('click', e => {
      const fila = e.target.closest('tr');
      const id_materia = fila.dataset.id;
      fetch('../../../resources/api/Alumnos/apiRelacion.php?action=eliminar', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id_materias=${id_materia}`
      }).then(res => res.text()).then(res=>{
        if(res.trim() === "OK"){
          materiasAgregadas = materiasAgregadas.filter(id => id !== id_materia);
          fila.remove();
        } else alert("Error al eliminar");
      });
    });
  });

  tabla.querySelectorAll('.guardar').forEach(btn => {
    btn.addEventListener('click', e => {
      const fila = e.target.closest('tr');
      const id_materia = fila.dataset.id;
      const inputs = fila.querySelectorAll('.calificacion');
      const p1 = inputs[0].value || 0;
      const p2 = inputs[1].value || 0;
      const ord = inputs[2].value || 0;

      fetch('../../../resources/api/Alumnos/apiRelacion.php?action=actualizar', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id_materias=${id_materia}&parcial1=${p1}&parcial2=${p2}&ordinario=${ord}`
      }).then(res => res.text()).then(res=>{
        if(res.trim() === "OK") calcularPromedios();
        else alert("Error al guardar cambios");
      });
    });
  });
}

function calcularPromedios(){
  tabla.querySelectorAll('tr').forEach(fila => {
    const inputs = fila.querySelectorAll('.calificacion');
    const p1 = parseFloat(inputs[0]?.value) || 0;
    const p2 = parseFloat(inputs[1]?.value) || 0;
    const ord = parseFloat(inputs[2]?.value) || 0;
    const promedio = (p1*0.3 + p2*0.3 + ord*0.4).toFixed(1);
    fila.querySelector('.promedio').textContent = promedio;
    const falta = Math.max(0, 60 - promedio).toFixed(1);
    fila.querySelector('.faltante').textContent = falta > 0 ? falta : "0.0";
  });
}
