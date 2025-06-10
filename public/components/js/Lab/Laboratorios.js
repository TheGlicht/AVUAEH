document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendarioLaboratorios');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridWeek',
      firstDay: 1,
      locale: 'es',
      events: [
        {
          title: 'Química - Profa. García - Gpo A',
          start: '2025-06-10T10:00:00',
          end: '2025-06-10T12:00:00',
          color: '#0d6efd'
        },
        {
          title: 'Física - Prof. Ramírez - Gpo B',
          start: '2025-06-11T13:00:00',
          end: '2025-06-11T15:00:00',
          color: '#198754'
        }
      ]
    });
    calendar.render();

    // Simulación de guardado
    document.getElementById('formPreprogramar').addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Práctica guardada (simulado)');
      bootstrap.Modal.getInstance(document.getElementById('modalPreprogramar')).hide();
    });
  });