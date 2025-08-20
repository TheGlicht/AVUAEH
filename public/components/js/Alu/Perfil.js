console.log("Archivo Perfil.js cargado correctamente");

// Cargar datos del perfil al cargar la página
// Cargar datos del perfil al cargar la página
window.addEventListener('DOMContentLoaded', () => {
    fetch('../../../resources/api/Alumnos/apiDatosA.php?action=mostrar')
    .then(response => response.json())
    .then(result => {
        if (!result.success) {
            alert(result.message);
            return;
        }

        const profileData = result.data;

        document.getElementById('nombre').value = profileData.nombreCompleto || "";
        document.getElementById('usuario').value = profileData.username || "";
        document.getElementById('semestre').value = profileData.semestre || "";
        document.getElementById('grupo').value = profileData.grupo || "";
    })
    .catch(error => {
        console.error('Error al cargar el perfil:', error);
        alert("Ocurrió un error al cargar el perfil.");
    });

});


// Función para guardar los datos del perfil
document.getElementById('profileForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío del formulario por defecto

    const formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

    fetch('../../../resources/api/Alumnos/apiDatosA.php?action=editar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(result => {
        if (result.success) {
            alert(result.message);
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Ocurrió un error al actualizar el perfil.");
    });
});

// Función para mostrar los datos (si es necesario)
function loadData() {
    fetch('../../../resources/api/Alumnos/apiDatosA.php?action=mostrar')
    .then(response => response.text())
    .then(html => {
        // Actualizar los datos en el DOM
        document.getElementById('avanceMaterias').innerHTML = html;

        // En caso de no encontrar ninguno, poner que los datos no se han registrado aun
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
