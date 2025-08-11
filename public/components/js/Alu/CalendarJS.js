// Corroboracion de carga de archivo
console.log("Archivo Calendar Cargado Correctamente");

// Funcion para el calendario
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;
    // let events = [];

    // Modal references
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm = document.getElementById('eventForm');
    const eventTitleInput = document.getElementById('eventTitle');
    const eventDateInput = document.getElementById('eventDate');
    const eventIdInput = document.getElementById('eventId');
    const deleteEventBtn = document.getElementById('deleteEventBtn');

  //   function fetchEvents() {
  //     fetch('../../../resources/api/Alumnos/apiEventos.php?action=listar')
  //         .then(response => response.text())
  //         .then(data => {
  //             document.getElementById('eventTableBody').innerHTML = data; // Asegúrate de que esto esté correcto
  //             refreshCalendarEvents();
  //         })
  //         .catch(error => console.error('Error al obtener eventos:', error));
  // }
  
    // function refreshCalendarEvents() {
    //     calendar.removeAllEvents();
    //     events.forEach(ev => {
    //         calendar.addEvent({
    //             id: ev.id,
    //             title: ev.title,
    //             start: ev.start,
    //             allDay: true
    //         });
    //     });
    // }


    // Función para extraer eventos del HTML de la tabla
    function parseEventsFromTable() {
      const rows = document.querySelectorAll('#eventTableBody tr');
      const events = [];
      
      rows.forEach(row => {
          const title = row.cells[0].textContent;
          const date = row.cells[1].textContent;
          const id = row.querySelector('button').getAttribute('data-id');
          
          events.push({
              id: id,
              title: title,
              start: date,
              allDay: true
          });
      });
      
      return events;
  }

  function loadAndDisplayEvents() {
    fetch('../../../resources/api/Alumnos/apiEventos.php?action=listar')
        .then(response => response.text())
        .then(html => {
            // Actualizar la tabla
            document.getElementById('eventTableBody').innerHTML = html;
            
            // Extraer eventos del HTML y mostrarlos en el calendario
            calendar.getEvents().forEach(event => event.remove()); // Limpiar eventos existentes
            const events = parseEventsFromTable();
            
            events.forEach(ev => {
                calendar.addEvent({
                    id: ev.id,
                    title: ev.title,
                    start: ev.start,
                    allDay: true
                });
            });
        })
        .catch(error => console.error('Error:', error));
}

    // Inicializar FullCalendar
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        dateClick(info) {
            // Abrir modal para agregar evento
            eventIdInput.value = '';
            eventTitleInput.value = '';
            eventDateInput.value = info.dateStr;
            deleteEventBtn.classList.add('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Agregar Evento';
        },
        eventClick(info) {
            // Abrir modal para editar evento
            const ev = events.find(e => e.id === info.event.id);
            if (ev) {
                eventIdInput.value = ev.id;
                eventTitleInput.value = ev.title;
                eventDateInput.value = ev.start;
                deleteEventBtn.classList.remove('d-none');
                eventModal.show();
                document.getElementById('eventModalLabel').textContent = 'Editar Evento';
            }
        }
    });
    calendar.render();

    // Cargar eventos al inicio
    // fetchEvents();
    loadAndDisplayEvents();

    // Form submit para guardar evento
    eventForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const id = eventIdInput.value;
      const title = eventTitleInput.value.trim();
      const description = document.getElementById('eventDescription').value.trim(); // Agregar esta línea
      const date = eventDateInput.value;

      if (!title || !date) {
          alert('Por favor, ingresa título y fecha del evento.');
          return;
      }

      const action = id ? 'editar' : 'agregar';
      const formData = new FormData();
      formData.append('action', action);
      formData.append('titulo', title);
      formData.append('descripcion', description); // Agregar esta línea
      formData.append('fecha', date);
      if (id) {
          formData.append('id_evento', id);
      }

      fetch('../../../resources/api/Alumnos/apiEventos.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(result => {
          if (result === "OK") {
              fetchEvents(); // Recargar eventos
              eventModal.hide();
          } else {
              alert('Error al guardar el evento: ' + result); // Mostrar el mensaje de error
          }
      })
      .catch(error => console.error('Error al guardar el evento:', error));
    });


    // Botón eliminar evento
    deleteEventBtn.addEventListener('click', () => {
        const id = eventIdInput.value;
        if (!id) return;

        const formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id_evento', id);

        fetch('../../../resources/api/Alumnos/apiEventos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result === "OK") {
                fetchEvents(); // Recargar eventos
                eventModal.hide();
            } else {
                alert('Error al eliminar el evento.');
            }
        })
        .catch(error => console.error('Error al eliminar el evento:', error));
    });

    // Delegación de eventos para botones editar y eliminar en la tabla
    document.getElementById('eventTableBody').addEventListener('click', function(e) {
        const target = e.target.closest('button');
        if (!target) return;
        const eventId = target.getAttribute('data-id');
        if (target.classList.contains('edit-btn')) {
            const ev = events.find(e => e.id === eventId);
            if (ev) {
                eventIdInput.value = ev.id;
                eventTitleInput.value = ev.title;
                eventDateInput.value = ev.start;
                deleteEventBtn.classList.remove('d-none');
                eventModal.show();
                document.getElementById('eventModalLabel').textContent = 'Editar Evento';
            }
        } else if (target.classList.contains('delete-btn')) {
            if (confirm('¿Seguro que quieres eliminar este evento?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('id_evento', eventId);

                fetch('../../../resources/api/Alumnos/apiEventos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    if (result === "OK") {
                        fetchEvents(); // Recargar eventos
                    } else {
                        alert('Error al eliminar el evento.');
                    }
                })
                .catch(error => console.error('Error al eliminar el evento:', error));
            }
        }
    });
});
