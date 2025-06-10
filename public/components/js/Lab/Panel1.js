document.addEventListener('DOMContentLoaded', function(){
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
      locale: 'es',
      height: 'auto',
      events: [
        {
          title: 'Práctica Química 3A',
          start: '2025-06-10'
        },
        {
          title: 'Física Grupo B',
          start: '2025-06-12'
        },
        {
          title: 'Biología 2C',
          start: '2025-06-14'
        }
      ]
    });

    calendar.render();
});