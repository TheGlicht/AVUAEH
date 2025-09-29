// Archivo: Contactos.js
document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del DOM
    const contactForm = document.getElementById('contactForm');
    const idInput = document.getElementById('id_contacto');
    const nombreInput = document.getElementById('nombre');
    const telefonoInput = document.getElementById('telefono');
    const correoInput = document.getElementById('correo');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const tbody = document.getElementById('contactTableBody');
    const sugerenciasList = document.getElementById('sugerenciasList');
  
    // Cargar contactos al iniciar
    loadAndDisplayContacts();
    loadSugerencias();
  
    // Función principal para cargar y mostrar contactos
    function loadAndDisplayContacts() {
      fetch('../../../resources/api/Alumnos/apiContactos.php?action=listar')
        .then((response) => response.text())
        .then((html) => {
          tbody.innerHTML = html;
        })
        .catch((err) => console.error('Error al cargar contactos:', err));
    }

    // Función para cargar las sugerencias de contactos
    function loadSugerencias() {
      const formData = new FormData();
      formData.append('action', 'sugerencias');
    
      fetch('../../../resources/api/Alumnos/apiContactos.php', {
        method: 'POST',
        body: formData
      })
        .then(res => res.text())
        .then(html => {
          document.getElementById('sugerenciasList').innerHTML = html;
        })
        .catch(err => console.error('Error al cargar sugerencias:', err));
    }
    
    // Funcion para agregar el contactos desde la sugerencia.   
      sugerenciasList.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-suggest-btn');
        if (!btn) return;

        // Extraer datos desde atributos data-*
        const nombre   = btn.getAttribute('data-nombre') || '';
        const telefono = btn.getAttribute('data-telefono') || '';
        const correo   = btn.getAttribute('data-correo') || '';

        // Pasarlos a los inputs del formulario
        nombreInput.value = nombre;
        telefonoInput.value = telefono;
        correoInput.value = correo;

        // Poner el foco en el botón guardar
        document.getElementById('saveContactBtn').focus();
      });
    
  
    // Envío de formulario (agregar/editar)
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
  
      const id = idInput.value;
      const nombre = nombreInput.value.trim();
      const telefono = telefonoInput.value.trim();
      const correo = correoInput.value.trim();
  
      if (!nombre || !telefono || !correo) {
        alert('Por favor, completa todos los campos.');
        return;
      }
  
      const action = id ? 'editar' : 'agregar';
      const formData = new FormData();
      formData.append('action', action);
      formData.append('nombre', nombre);
      formData.append('telefono', telefono);
      formData.append('correo', correo);
      if (id) formData.append('id_contacto', id);
  
      fetch('../../../resources/api/Alumnos/apiContactos.php', {
        method: 'POST',
        body: formData,
      })
        .then((res) => res.text())
        .then((result) => {
          if (result === 'OK') {
            loadAndDisplayContacts(); // Actualiza lista
            loadSugerencias()         // Actualiza sugerencias
            resetForm();               // Limpia formulario y estado
          } else {
            alert('Error: ' + result);
          }
        })
        .catch((err) => {
          console.error('Error:', err);
          alert('Error al procesar la solicitud');
        });
    });
  
    // Delegación de eventos para botones Editar/Eliminar dentro de la tabla
    tbody.addEventListener('click', function (e) {
      const target = e.target.closest('button');
      if (!target) return;
  
      // Editar
      if (target.classList.contains('edit-btn')) {
        const id = target.getAttribute('data-id');
        const nombre = target.getAttribute('data-nombre') || '';
        const telefono = target.getAttribute('data-telefono') || '';
        const correo = target.getAttribute('data-correo') || '';
  
        idInput.value = id;
        nombreInput.value = nombre;
        telefonoInput.value = telefono;
        correoInput.value = correo;
  
        cancelEditBtn.classList.remove('d-none');
        nombreInput.focus();
      }
  
      // Eliminar
      if (target.classList.contains('delete-btn')) {
        const id = target.getAttribute('data-id');
        if (!id) return;
        if (!confirm('¿Seguro que deseas eliminar este contacto?')) return;
  
        const formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id_contacto', id);
  
        fetch('../../../resources/api/Alumnos/apiContactos.php', {
          method: 'POST',
          body: formData,
        })
          .then((res) => res.text())
          .then((result) => {
            if (result === 'OK') {
              loadAndDisplayContacts();
              loadSugerencias();
              if (idInput.value === id) resetForm(); // Si estaba editando este mismo
            } else {
              alert('Error al eliminar: ' + result);
            }
          })
          .catch((err) => console.error('Error:', err));
      }
    });
  
    // Cancelar edición
    cancelEditBtn.addEventListener('click', function () {
      resetForm();
    });
  
    // Utilidad: resetear formulario/estado
    function resetForm() {
      contactForm.reset();
      idInput.value = '';
      cancelEditBtn.classList.add('d-none');
    }
  
    // Console log para confirmar carga
    console.log('Contactos.js cargado. Flujo similar a CalendarJS.js (listar/agregar/editar/eliminar).');
  });
  