document.addEventListener('DOMContentLoaded', async () => {
  const container = document.getElementById("grupoContainer");

  try {
    const resp = await fetch("../../../resources/api/Docente/apiGrupos.php");
    const alumnos = await resp.json();

    // Agrupar por materia + semestre + grupo
    const gruposMap = {};
    alumnos.forEach(al => {
      const key = `${al.materiaNombre}::${al.semestre}::${al.grupo}`;
      if (!gruposMap[key]) {
        gruposMap[key] = {
          materia: al.materiaNombre,
          semestre: al.semestre,
          grupo: al.grupo,
          alumnos: []
        };
      }
      gruposMap[key].alumnos.push(al);
    });

    Object.values(gruposMap).forEach(g => {
      const col = document.createElement("div");
      col.className = "col-md-6";

      const card = document.createElement("div");
      card.className = "card shadow-sm h-100";

      card.innerHTML = `
        <div class="card-header bg-secondary text-white">
          <strong>${g.materia}</strong> - Semestre ${g.semestre} - Grupo ${g.grupo}
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            ${g.alumnos.map(al => `
              <li class="list-group-item d-flex justify-content-between align-items-center">
                ${al.nombreCompleto}
                <a href="detalleAlumno.php?id=${al.id_alumno}" class="btn btn-outline-primary btn-sm">
                  Ver <i class="fa-solid fa-arrow-right"></i>
                </a>
              </li>
            `).join("")}
          </ul>
        </div>
      `;      

      col.appendChild(card);
      container.appendChild(col);
    });

  } catch (error) {
    console.error("Error cargando grupos:", error);
    container.innerHTML = `<div class="alert alert-danger">No se pudieron cargar los grupos</div>`;
  }
});
