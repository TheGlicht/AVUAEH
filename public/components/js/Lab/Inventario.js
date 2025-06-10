 // Ejecutar cuando el DOM esté completamente cargado
 document.addEventListener('DOMContentLoaded', function () {

    // Botón para abrir modal de agregar
    const btnAgregar = document.querySelector('.btn-success');
    if (btnAgregar) {
      btnAgregar.addEventListener('click', function () {
        const modalAgregar = new bootstrap.Modal(document.getElementById('modalAgregarMaterial'));
        modalAgregar.show();
      });
    }

    // Botones para abrir modal de editar
    const botonesEditar = document.querySelectorAll('.btn-warning');
    botonesEditar.forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('nombreEditar').value = 'Ejemplo';
        document.getElementById('tipoEditar').value = 'vidrio';
        document.getElementById('cantidadEditar').value = '10';
        document.getElementById('estadoEditar').value = 'Funcional';
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarMaterial'));
        modalEditar.show();
      });
    });

    // Envío de formularios
    document.getElementById('formAgregarMaterial').addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Material agregado (simulado)');
      bootstrap.Modal.getInstance(document.getElementById('modalAgregarMaterial')).hide();
    });

    document.getElementById('formEditarMaterial').addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Material actualizado (simulado)');
      bootstrap.Modal.getInstance(document.getElementById('modalEditarMaterial')).hide();
    });

    // Inicializar filtro por defecto
    filtrarMateriales();
  });

  // Función para filtrar materiales por tipo
  function filtrarMateriales() {
    const tipo = document.getElementById('filtroTipo').value;
    const filas = document.querySelectorAll('#tablaMateriales tr');

    filas.forEach(fila => {
      const tipoFila = fila.getAttribute('data-tipo');
      if (tipo === 'todos' || tipo === tipoFila) {
        fila.style.display = '';
      } else {
        fila.style.display = 'none';
      }
    });
  }

  // Selección de todos los checkboxes
  function seleccionarTodos(source) {
    const checkboxes = document.querySelectorAll('.material-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = source.checked);
  }

  // Función para armar kit
  function armarKit() {
    const seleccionados = Array.from(document.querySelectorAll('.material-checkbox')).filter(cb => cb.checked);
    if (seleccionados.length === 0) {
      alert('Selecciona al menos un material para armar un kit.');
    } else {
      alert(`Has seleccionado ${seleccionados.length} materiales para armar el kit.`);
    }
  }