document.addEventListener('DOMContentLoaded', () => {
    const grupos = [
      {
        materia: "Química",
        grupo: "4A",
        semestre: "4°",
        alumnos: [
          { nombre: "Juan Pérez", cuenta: "2023001" },
          { nombre: "Ana García", cuenta: "2023002" },
        ]
      },
      {
        materia: "Física",
        grupo: "3B",
        semestre: "3°",
        alumnos: [
          { nombre: "Luis Hernández", cuenta: "2023003" },
          { nombre: "María López", cuenta: "2023004" },
        ]
      }
    ];

    const container = document.getElementById("grupoContainer");

    grupos.forEach((g, index) => {
      const col = document.createElement("div");
      col.className = "col-md-6";

      const card = document.createElement("div");
      card.className = "card shadow-sm h-100";

      card.innerHTML = `
        <div class="card-header bg-secondary text-white">
          <strong>${g.materia}</strong> - Grupo: ${g.grupo} (${g.semestre})
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            ${g.alumnos.map(al => `
              <li class="list-group-item d-flex justify-content-between align-items-center">
                ${al.nombre}
                <a href="detalleAlumno.php?cuenta=${al.cuenta}" class="btn btn-outline-primary btn-sm">
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
  });