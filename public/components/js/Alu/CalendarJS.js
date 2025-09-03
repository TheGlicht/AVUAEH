// Archivo: CalendarJS.js (reemplazar)
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

    // Carga y pinta eventos (robusto ante respuestas no-JSON)
    function loadAndDisplayEvents() {
        fetch('../../../resources/api/Alumnos/apiEventos.php?action=listar')
            .then(response => response.text())
            .then(text => {
                let events;
                try {
                    events = JSON.parse(text);
                } catch (err) {
                    console.error('Respuesta del servidor no es JSON:', text);
                    return; // no procesamos más
                }

                if (!Array.isArray(events)) {
                    if (events && events.error) {
                        console.error('API error:', events.error);
                    } else {
                        console.error('Respuesta inesperada de la API:', events);
                    }
                    return;
                }

                // Limpiar calendario antes de añadir
                calendar.getEvents().forEach(ev => ev.remove());

                events.forEach(ev => {
                    // Normalizar start (en tu API restos devuelven fechaEvento)
                    const start = ev.fechaEvento || ev.fecha || ev.start || '';

                    calendar.addEvent({
                        id: ev.id_evento,
                        title: ev.tituloEvento,
                        start: start,
                        allDay: true,
                        description: ev.descripcion || '',
                        tipo: ev.tipo || 'alumno',
                        color: ev.tipo === 'docente' ? 'yellow' : (ev.tipo === 'laboratorio' ? 'green' : 'blue')
                    });
                });
            })
            .catch(error => console.error('Error al cargar eventos:', error));
    }

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        dateClick(info) {
            // Solo permitir agregar eventos propios
            eventIdInput.value = '';
            eventTitleInput.value = '';
            eventDescriptionInput.value = '';
            eventDateInput.value = info.dateStr;
            deleteEventBtn.classList.add('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Agregar Evento';
        },
        eventClick(info) {
            const event = info.event;
            eventIdInput.value = event.id;
            eventTitleInput.value = event.title;
            eventDescriptionInput.value = event.extendedProps.description || '';
            eventDateInput.value = event.startStr.split('T')[0];

            // TRATAMIENTO: docente o laboratorio => SOLO LECTURA
            if (event.extendedProps.tipo === 'docente' || event.extendedProps.tipo === 'laboratorio') {
                deleteEventBtn.classList.add('d-none');
                eventTitleInput.setAttribute('readonly', true);
                eventDescriptionInput.setAttribute('readonly', true);
                eventDateInput.setAttribute('readonly', true);
                document.getElementById('saveEvent').style.display = 'none';
                eventModal.show();
                document.getElementById('eventModalLabel').textContent = 
                    event.extendedProps.tipo === 'laboratorio' ? 'Evento Laboratorio (Solo lectura)' : 'Evento Docente (Solo lectura)';
            } else {
                // Evento alumno: permitir editar y eliminar
                deleteEventBtn.classList.remove('d-none');
                eventTitleInput.removeAttribute('readonly');
                eventDescriptionInput.removeAttribute('readonly');
                eventDateInput.removeAttribute('readonly');
                document.getElementById('saveEvent').style.display = 'inline-block';
                eventModal.show();
                document.getElementById('eventModalLabel').textContent = 'Editar Evento';
            }
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

    // Delegación de eventos para la tabla (solo si existe la tabla en el DOM)
    const eventTableBody = document.getElementById('eventTableBody');
    if (eventTableBody) {
        eventTableBody.addEventListener('click', function(e) {
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
    }

    // Console log para confirmar carga
    console.log("CalendarJS cargado correctamente con las siguientes mejoras:");
    console.log("- Cierre automático del modal al guardar");
    console.log("- Actualización en tiempo real de eventos");
    console.log("- Validación mejorada de formularios");
    console.log("- Manejo de descripciones de eventos");
});
