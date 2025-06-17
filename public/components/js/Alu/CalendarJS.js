// Corroboracion de carga de archivo
console.log("Archivo Calendar Cargado Correctamente");
// Funcion para el calendario
document.addEventListener('DOMContentLoaded', function(){
    const calendarEl = document.getElementById('calendar')    ;
    let calendar;
    let events = [];

    // Modal references
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm = document.getElementById('eventForm');
    const eventTitleInput =  document.getElementById('eventTitle');
    const eventDateInput = document.getElementById('eventDate');
    const eventIdInput = document.getElementById('eventId');
    const deleteEventBtn = document.getElementById('deleteEventBtn');

    function generateUID() {
        // simple unique id generator for events
        return 'ev-' + Math.random().toString(36).substr(2, 9);
      }
      function renderTable() {
        const tbody = document.getElementById('eventTableBody');
        tbody.innerHTML = '';
        events.forEach(ev => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${ev.title}</td>
            <td>${ev.start}</td>
            <td>
              <button class="btn btn-warning btn-sm me-1 edit-btn" data-id="${ev.id}" title="Editar">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button class="btn btn-danger btn-sm delete-btn" data-id="${ev.id}" title="Eliminar">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }

      function refreshCalendarEvents() {
        calendar.removeAllEvents();
        events.forEach(ev => {
          calendar.addEvent({
            id: ev.id,
            title: ev.title,
            start: ev.start,
            allDay: true
          });
        });
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
        // Form submit para guardar evento
        eventForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const id = eventIdInput.value;
          const title = eventTitleInput.value.trim();
          const date = eventDateInput.value;
          if (!title || !date) {
            alert('Por favor, ingresa título y fecha del evento.');
            return;
          }
          if (id) {
            // Editar evento existente
            const evIndex = events.findIndex(ev => ev.id === id);
            if (evIndex !== -1) {
              events[evIndex].title = title;
              events[evIndex].start = date;
            }
        } else {
            // Nuevo evento
            events.push({
              id: generateUID(),
              title: title,
              start: date
            });
          }
          refreshCalendarEvents();
          renderTable();
          eventModal.hide();
        });
        // Botón eliminar evento
        deleteEventBtn.addEventListener('click', () => {
          const id = eventIdInput.value;
          if (!id) return;
          events = events.filter(ev => ev.id !== id);
          refreshCalendarEvents();
          renderTable();
          eventModal.hide();
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
            events = events.filter(ev => ev.id !== eventId);
            refreshCalendarEvents();
            renderTable();
          }
        }
      });
    });