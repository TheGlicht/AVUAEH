document.addEventListener('DOMContentLoaded', function () {
    console.log("CalendarLab.js cargado correctamente");
  
    const calendarEl = document.getElementById('calendarioLaboratorios');
  
    // Validar si el elemento existe
    if (!calendarEl) {
      console.error('No se encontró el contenedor con ID "calendarioLaboratorios"');
      return;
    }
  
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      events: [] // Aquí puedes poner eventos estáticos o traerlos desde el servidor
    });
  
    calendar.render();
  });
  