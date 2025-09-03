document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendarioLaboratorios');
  const modalEl = document.getElementById('modalPreprogramar');
  const modal = new bootstrap.Modal(modalEl);

  // Referencias a inputs del modal
  const form = document.getElementById('formPreprogramar');
  const materiaSelect = document.getElementById('materiaKit');
  const docenteSelect = document.getElementById('docenteLab');
  const semestreSelect = document.getElementById('semestre');
  const grupoInput = document.getElementById('grupo');
  const laboratorioSelect = document.getElementById('laboratorio');
  const fechaInput = document.getElementById('fecha');
  const horaInput = document.getElementById('hora');


// Mapeo de laboratorio
const laboratoriosMap = {
  1: "Lab. Electrónica",
  2: "Lab. Control",
  3: "Lab. Fisico-Quimica",
  4: "Laboratorio 1",
  5: "Laboratorio 2",
  6: "Laboratorio 3",
  7: "Laboratorio 4"
};


  // Campo oculto para id de práctica (para editar)
  let currentPracticaId = null;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridWeek',
    firstDay: 1,
    locale: 'es',
  
    // Aquí reemplazas lo que tienes ahora
    events: {
      url: '../../../resources/api/Laboratorio/apiPracticas.php',
      method: 'GET',
      extraParams: {
        action: 'listar'
      },
      failure: function () {
        alert('Error cargando las prácticas');
      }
    },
    eventDataTransform: function(eventData) {
      return {
        id: eventData.id_practica,
        title: eventData.materia + " - " + laboratoriosMap[eventData.id_lab],
        start: eventData.fecha + "T" + eventData.hora
      };
    },
  
    eventClick: function(info) {
      const event = info.event;
      currentPracticaId = event.id;
  
      // Aquí ya puedes usar event.extendedProps si necesitas
      fechaInput.value = event.startStr.split('T')[0];
      horaInput.value = event.startStr.split('T')[1]?.substring(0,5) || '';
  
      modal.show();
    }
  });
  

  calendar.render();

  // Función auxiliar para seleccionar opción por valor
  function selectOptionByValue(selectElement, value) {
      if (!value) return;
      const option = selectElement.querySelector(`option[value="${value}"]`);
      if (option) {
          selectElement.value = value;
      }
  }

  // Manejar envío del formulario (agregar o editar)
  form.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(form);

      // Añadir id_practica si estamos editando
      if (currentPracticaId) {
          formData.append('id_practica', currentPracticaId);
      }

      // El valor de laboratorio ya es numérico en el select
      formData.set('laboratorio', laboratorioSelect.value);

      // Determinar acción
      const action = currentPracticaId ? 'editar' : 'agregar';
      formData.set('action', action);

      fetch('../../../resources/api/Laboratorio/apiPracticas.php', {
          method: 'POST',
          body: formData
      })
      .then(res => res.text())
      .then(result => {
          if (result === 'OK') {
              alert(`Práctica ${action === 'agregar' ? 'guardada' : 'actualizada'} correctamente`);
              calendar.refetchEvents();
              modal.hide();
              form.reset();
              currentPracticaId = null;
          } else {
              alert('Error: ' + result);
          }
      })
      .catch(err => {
          console.error('Error al guardar práctica:', err);
          alert('Error al guardar práctica');
      });
  });

  // Crear botón eliminar dinámicamente en el modal footer
  const modalFooter = modalEl.querySelector('.modal-footer');
  let deleteBtn = modalFooter.querySelector('#deletePracticaBtn');
  if (!deleteBtn) {
      deleteBtn = document.createElement('button');
      deleteBtn.type = 'button';
      deleteBtn.className = 'btn btn-danger';
      deleteBtn.id = 'deletePracticaBtn';
      deleteBtn.textContent = 'Eliminar';
      modalFooter.insertBefore(deleteBtn, modalFooter.querySelector('.btn-secondary'));
  }

  // Manejar eliminación
  deleteBtn.addEventListener('click', function() {
      if (!currentPracticaId) return alert('No hay práctica seleccionada para eliminar.');

      if (!confirm('¿Está seguro que desea eliminar esta práctica?')) return;

      const formData = new FormData();
      formData.append('action', 'eliminar');
      formData.append('id_practica', currentPracticaId);

      fetch('../../../resources/api/Laboratorio/apiPracticas.php', {
          method: 'POST',
          body: formData
      })
      .then(res => res.text())
      .then(result => {
          if (result === 'OK') {
              alert('Práctica eliminada correctamente');
              calendar.refetchEvents();
              modal.hide();
              form.reset();
              currentPracticaId = null;
          } else {
              alert('Error al eliminar: ' + result);
          }
      })
      .catch(err => {
          console.error('Error al eliminar práctica:', err);
          alert('Error al eliminar práctica');
      });
  });

  // Inicializar carga de materias y docentes si usas AJAX (opcional)
  // cargarMaterias();
  // cargarDocentes();

});
