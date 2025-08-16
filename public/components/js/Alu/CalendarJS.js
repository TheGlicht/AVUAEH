// Archivo: CalendarJS.js
// Versión mejorada con cierre automático de modal y actualización en tiempo real

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;
    
    // Referencias a elementos del DOM
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm = document.getElementById('eventForm');
    const eventTitleInput = document.getElementById('eventTitle');
    const eventDescriptionInput = document.getElementById('eventDescription');
    const eventDateInput = document.getElementById('eventDate');
    const eventIdInput = document.getElementById('eventId');
    const deleteEventBtn = document.getElementById('deleteEventBtn');

    // Función para parsear eventos desde la tabla HTML
    function parseEventsFromTable() {
        const rows = document.querySelectorAll('#eventTableBody tr');
        const events = [];
        
        rows.forEach(row => {
            const title = row.cells[0].textContent;
            const date = row.cells[1].textContent;
            const id = row.querySelector('button')?.getAttribute('data-id');
            
            if (id) {
                events.push({
                    id: id,
                    title: title,
                    start: date,
                    allDay: true
                });
            }
        });
        
        return events;
    }

    // Función principal para cargar y mostrar eventos
    function loadAndDisplayEvents() {
        fetch('../../../resources/api/Alumnos/apiEventos.php?action=listar')
            .then(response => response.text())
            .then(html => {
                // Actualizar la tabla de eventos
                document.getElementById('eventTableBody').innerHTML = html;
                
                // Extraer eventos del HTML
                const events = parseEventsFromTable();
                
                // Limpiar y actualizar calendario
                calendar.getEvents().forEach(event => event.remove());
                
                events.forEach(ev => {
                    calendar.addEvent({
                        id: ev.id,
                        title: ev.title,
                        start: ev.start,
                        allDay: true,
                        description: ev.description // Asegúrate que tu API devuelva esto
                    });
                });
            })
            .catch(error => console.error('Error al cargar eventos:', error));
    }

    // Configuración e inicialización del calendario
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        dateClick(info) {
            // Configurar modal para nuevo evento
            eventIdInput.value = '';
            eventTitleInput.value = '';
            eventDescriptionInput.value = '';
            eventDateInput.value = info.dateStr;
            deleteEventBtn.classList.add('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Agregar Evento';
        },
        eventClick(info) {
            // Configurar modal para editar evento
            eventIdInput.value = info.event.id;
            eventTitleInput.value = info.event.title;
            eventDescriptionInput.value = info.event.extendedProps.description || ''; // Asegúrate de que esto esté correcto
            eventDateInput.value = info.event.startStr.split('T')[0];
            deleteEventBtn.classList.remove('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Editar Evento';
        }        
    });

    // Renderizar calendario
    calendar.render();

    // Cargar eventos iniciales
    loadAndDisplayEvents();

    // Manejar envío del formulario
    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = eventIdInput.value;
        const title = eventTitleInput.value.trim();
        const description = eventDescriptionInput.value.trim();
        const date = eventDateInput.value;

        if (!title || !date) {
            alert('Por favor, complete todos los campos obligatorios.');
            return;
        }

        const action = id ? 'editar' : 'agregar';
        const formData = new FormData();
        formData.append('action', action);
        formData.append('titulo', title);
        formData.append('descripcion', description);
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
                loadAndDisplayEvents(); // Actualizar vista
                eventModal.hide(); // Cerrar modal
                
                // Resetear formulario
                eventForm.reset();
                eventIdInput.value = '';
            } else {
                alert('Error: ' + result);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    });

   // Manejar eliminación de evento
deleteEventBtn.addEventListener('click', () => {
    const id = eventIdInput.value;
    if (!id || !confirm('¿Está seguro que desea eliminar este evento?')) return;

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
            loadAndDisplayEvents(); // Actualizar vista
            eventModal.hide(); // Cerrar modal
        } else {
            alert('Error al eliminar: ' + result);
        }
    })
    .catch(error => console.error('Error:', error));
});


    // Delegación de eventos para la tabla
    document.getElementById('eventTableBody').addEventListener('click', function(e) {
        const target = e.target;
        
        // Manejar clic en botones de editar
        if (target.classList.contains('edit-btn')) {
            const row = target.closest('tr');
            const id = target.getAttribute('data-id');
            const title = row.cells[0].textContent;
            const description = row.getAttribute('data-description') || '';
            const date = row.cells[1].textContent;

            eventIdInput.value = id;
            eventTitleInput.value = title;
            eventDescriptionInput.value = description;
            eventDateInput.value = date;
            deleteEventBtn.classList.remove('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Editar Evento';
        } 
        // Manejar clic en botones de eliminar
      else if (target.classList.contains('delete-btn')) {
            const id = target.getAttribute('data-id');
            if (id && confirm('¿Seguro que desea eliminar este evento permanentemente?')) {
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
                        loadAndDisplayEvents(); // Actualizar la vista
                    } else {
                        alert('Error al eliminar: ' + result);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    });

    // Console log para confirmar carga
    console.log("CalendarJS cargado correctamente con las siguientes mejoras:");
    console.log("- Cierre automático del modal al guardar");
    console.log("- Actualización en tiempo real de eventos");
    console.log("- Validación mejorada de formularios");
    console.log("- Manejo de descripciones de eventos");
});
