 // Simulación de almacenamiento temporal
 const profileForm = document.getElementById('profileForm');

 profileForm.addEventListener('submit', function (e) {
   e.preventDefault();

   const data = {
     nombre: document.getElementById('nombre').value,
     usuario: document.getElementById('usuario').value,
     semestre: document.getElementById('semestre').value,
     grupo: document.getElementById('grupo').value,
     materias: document.getElementById('materias').value
   };

   localStorage.setItem('perfilAlumno', JSON.stringify(data));
   alert('Perfil guardado temporalmente.');
 });

 // Cargar datos si existen
 window.addEventListener('DOMContentLoaded', () => {
   const saved = localStorage.getItem('perfilAlumno');
   if (saved) {
     const data = JSON.parse(saved);
     document.getElementById('nombre').value = data.nombre;
     document.getElementById('usuario').value = data.usuario;
     document.getElementById('semestre').value = data.semestre;
     document.getElementById('grupo').value = data.grupo;
     document.getElementById('materias').value = data.materias;
   }
 });