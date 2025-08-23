console.log("Archivo Perfil.js cargado correctamente");

// Al cargar la página → pedir datos del perfil
window.addEventListener('DOMContentLoaded', () => {
  fetch('../../../resources/api/Alumnos/apiDatosA.php?action=mostrar')
    .then(response => response.json())
    .then(result => {
      if (!result.success) {
        // Si no hay datos, al menos dejamos el username que ya venía precargado en el input
        console.warn(result.message || 'Sin datos de perfil.');
        return;
      }

      const profileData = result.data || {};
      document.getElementById('nombre').value   = profileData.nombreCompleto || "";
      document.getElementById('usuario').value  = profileData.username || document.getElementById('usuario').value;
      document.getElementById('semestre').value = profileData.semestre || "";
      document.getElementById('grupo').value    = profileData.grupo || "";
    })
    .catch(error => {
      console.error('Error al cargar el perfil:', error);
      alert("Ocurrió un error al cargar el perfil.");
    });
});


// Al cargar el perfil también cargamos avance de materias
window.addEventListener('DOMContentLoaded', () => {
  // Cargar materias relacionadas con el usuario y sus promedios
  fetch('../../../resources/api/Alumnos/apiRelacion.php?action=listar')
    .then(res => res.json())
    .then(materias => {
      const lista = document.getElementById('avanceMaterias');
      lista.innerHTML = ""; // limpiar contenido anterior

      if (!materias || materias.length === 0) {
        lista.innerHTML = `<li class="list-group-item text-muted">No hay materias registradas.</li>`;
        return;
      }

      materias.forEach(m => {
        // Calcular promedio con tus mismas ponderaciones
        const p1 = parseFloat(m.parcial1) || 0;
        const p2 = parseFloat(m.parcial2) || 0;
        const ord = parseFloat(m.ordinario) || 0;
        const promedio = ((p1 * 0.3 + p2 * 0.3 + ord * 0.4)*10).toFixed(1);

        // El porcentaje es el mismo que el promedio (0 a 100)
        const porcentaje = Math.min(100, Math.max(0, promedio));

        // Color dinámico según el desempeño
        let color = "bg-danger";
        if (porcentaje >= 90) color = "bg-success";
        else if (porcentaje >= 70) color = "bg-primary";
        else if (porcentaje >= 60) color = "bg-warning";

        // Crear el <li> con barra de progreso
        const li = document.createElement('li');
        li.className = "list-group-item";
        li.innerHTML = `
          <strong>${m.nombre}</strong>
          <div class="progress mt-2" style="height: 20px;">
            <div class="progress-bar ${color}" style="width:${porcentaje}%">
              ${porcentaje}%
            </div>
          </div>
        `;
        lista.appendChild(li);
      });
    })
    .catch(err => {
      console.error("Error al cargar avance de materias:", err);
    });
});


// Guardar cambios del perfil (insertar o actualizar según exista)
document.getElementById('formPerfil').addEventListener('submit', function (event) {
  event.preventDefault();

  const formData = new FormData(this);

  fetch('../../../resources/api/Alumnos/apiDatosA.php?action=editar', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        alert(result.message || "Perfil actualizado correctamente");
        // Si el username cambió, el backend ya actualizó la sesión; opcionalmente recargamos:
        // location.reload();
      } else {
        alert("Error: " + (result.message || "No se pudo actualizar el perfil"));
      }
    })
    .catch(error => {
      console.error('Error al actualizar el perfil:', error);
      alert("Ocurrió un error al actualizar el perfil.");
    });
});



