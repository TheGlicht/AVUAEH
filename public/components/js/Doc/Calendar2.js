// Archivo: Calendar2.js - Docente
console.log("Calendar2.js cargado correctamente");

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let calendar;

    // Modal y formulario
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const eventForm = document.getElementById('eventForm');
    const eventTitleInput = document.getElementById('eventTitle');
    const eventDescriptionInput = document.getElementById('eventDescription');
    const eventDateInput = document.getElementById('eventDate');
    const eventIdInput = document.getElementById('eventId');
    const deleteEventBtn = document.getElementById('deleteEventBtn');
    const eventMateria = document.getElementById('eventMateria');
    const eventSemestre = document.getElementById('eventSemestre');
    const eventGrupo = document.getElementById('eventGrupo');

    // Generar UID temporal (solo si es necesario)
    function generateUID() {
        return 'ev-' + Math.random().toString(36).substr(2, 9);
    }

    // Cargar eventos desde la API
    function loadAndDisplayEvents() {
        fetch('../../../resources/api/Docente/apiEventosDocente.php?action=listar')
        .then(res => res.text())
        .then(html => {
            document.getElementById('eventTableBody').innerHTML = html;

            // Parsear eventos desde la tabla para agregarlos al calendario
            const rows = document.querySelectorAll('#eventTableBody tr');
            calendar.removeAllEvents();

            rows.forEach(row => {
                const title = row.cells[0].textContent;
                const date = row.cells[1].textContent;
                const id = row.querySelector('button')?.getAttribute('data-id');
                const description = row.getAttribute('data-description') || '';

                if (id) {
                    calendar.addEvent({
                        id: id,
                        title: title,
                        start: date,
                        allDay: true,
                        description: description
                    });
                }
            });
        })
        .catch(err => console.error('Error al cargar eventos:', err));
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
            eventIdInput.value = '';
            eventTitleInput.value = '';
            eventDescriptionInput.value = '';
            eventDateInput.value = info.dateStr;
            eventMateria.value = '';
            eventSemestre.value = '';
            eventGrupo.value = '';
            deleteEventBtn.classList.add('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Agregar Evento';
        },
        eventClick(info) {
            const row = document.querySelector(`#eventTableBody button[data-id="${info.event.id}"]`)?.closest('tr');
            if (!row) return;

            eventIdInput.value = info.event.id;
            eventTitleInput.value = info.event.title;
            eventDescriptionInput.value = row.getAttribute('data-description') || '';
            eventDateInput.value = info.event.startStr.split('T')[0];
            eventMateria.value = row.getAttribute('data-materia') || '';
            eventSemestre.value = row.getAttribute('data-semestre') || '';
            eventGrupo.value = row.getAttribute('data-grupo') || '';
            deleteEventBtn.classList.remove('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Editar Evento';
        }
    });

    calendar.render();

    // Guardar evento (agregar o editar)
    eventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = eventIdInput.value;
        const titulo = eventTitleInput.value.trim();
        const descripcion = eventDescriptionInput.value.trim();
        const fecha = eventDateInput.value;
        const materia = eventMateria.value;
        const semestre = eventSemestre.value;
        const grupo = eventGrupo.value;

        if (!titulo || !fecha) {
            alert("Por favor ingresa título y fecha");
            return;
        }

        const action = id ? 'editar' : 'agregar';
        const formData = new FormData();
        formData.append('action', action);
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('fecha', fecha);
        formData.append('materia', materia);
        formData.append('semestre', semestre);
        formData.append('grupo', grupo);

        if (id) formData.append('id_evento', id);

        fetch('../../../resources/api/Docente/apiEventosDocente.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(result => {
            if (result === "OK") {
                loadAndDisplayEvents();
                eventModal.hide();
                eventForm.reset();
                eventIdInput.value = '';

                const docenteId = document.getElementById('docenteId').value;

                const notifyData = new FormData();
                notifyData.append('titulo', titulo);
                notifyData.append('descripcion', descripcion);
                notifyData.append('fecha', fecha);
                notifyData.append('materia', materia);
                notifyData.append('semestre', semestre);
                notifyData.append('grupo', grupo);
                notifyData.append('docente', docenteId); 
                
                fetch('../../../resources/api/Soporte/apiNotificaciones2.php', {
                    method: 'POST',
                    body: notifyData
                })
                .then(res => res.text())
                .then(resp => {
                    if (resp !== "OK") {
                        console.warn(" Error al enviar correos:", resp);
                    }
                })
                .catch(err => console.error("Error al enviar correos:", err));

            } else {
                alert('Error: ' + result);
            }
        })
        .catch(err => console.error('Error al guardar evento:', err));
    });

    // Eliminar evento
    deleteEventBtn.addEventListener('click', function() {
        const id = eventIdInput.value;
        if (!id || !confirm("¿Seguro que quieres eliminar este evento?")) return;

        const formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id_evento', id);

        fetch('../../../resources/api/Docente/apiEventosDocente.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(result => {
            if (result === "OK") {
                loadAndDisplayEvents();
                eventModal.hide();
            } else {
                alert('Error al eliminar: ' + result);
            }
        })
        .catch(err => console.error('Error al eliminar evento:', err));
    });

    // Delegación de eventos en la tabla
    document.getElementById('eventTableBody').addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        const id = btn.getAttribute('data-id');

        if (btn.classList.contains('edit-btn')) {
            const row = btn.closest('tr');
            eventIdInput.value = id;
            eventTitleInput.value = row.cells[0].textContent;
            eventDescriptionInput.value = row.getAttribute('data-description') || '';
            eventDateInput.value = row.cells[1].textContent;
            eventMateria.value = row.getAttribute('data-materia') || '';
            eventSemestre.value = row.getAttribute('data-semestre') || '';
            eventGrupo.value = row.getAttribute('data-grupo') || '';
            deleteEventBtn.classList.remove('d-none');
            eventModal.show();
            document.getElementById('eventModalLabel').textContent = 'Editar Evento';
        }

        if (btn.classList.contains('delete-btn')) {
            if (!confirm('¿Seguro que quieres eliminar este evento?')) return;
            const formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id_evento', id);

            fetch('../../../resources/api/Docente/apiEventosDocente.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(result => {
                if (result === "OK") loadAndDisplayEvents();
                else alert('Error al eliminar: ' + result);
            });
        }
    });

    // Cargar eventos iniciales
    loadAndDisplayEvents();
});
