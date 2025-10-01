// vales.js

document.addEventListener('DOMContentLoaded', function () {
    const fechaInput = document.getElementById('fecha');
    const tablaVales = document.getElementById('tablaVales');
    const form = document.getElementById('labForm');
  
    // -------- Restricción de fechas --------
    const hoy = new Date();
    const manana = new Date(hoy);
    manana.setDate(hoy.getDate() + 1); // desde mañana
    const max = new Date(hoy);
    max.setDate(hoy.getDate() + 3); // hasta 3 días adelante
  
    const toDateStr = (d) => d.toISOString().split('T')[0];
    fechaInput.min = toDateStr(manana);
    fechaInput.max = toDateStr(max);
  
    // -------- Interceptar submit para no recargar --------
    if (form) {
      form.addEventListener('submit', async function (e) {
        e.preventDefault(); // evitar recarga
  
        const formData = new FormData(form);
  
        try {
          const resp = await fetch('Vales.php', {
            method: 'POST',
            body: formData,
          });
  
          if (resp.ok) {
            alert("Vale generado correctamente");
            // refrescar tabla sin recargar página
            cargarVales();
            form.reset(); //  limpia el formulario

            // volver a poner la opción por defecto en selects
            docente.innerHTML = '<option value="" disabled selected>Selecciona un docente</option>';
            kit.innerHTML = '<option value="" disabled selected>Selecciona un kit</option>';

          } else {
            alert("Error al guardar el vale");
          }
        } catch (err) {
          console.error("Error en fetch:", err);
        }
      });
    }
  
    // -------- Botón para generar PDF --------
    const btnPdf = document.createElement('button');
    btnPdf.textContent = 'Descargar PDF';
    btnPdf.className = 'btn btn-success my-3';
    tablaVales.parentElement.insertBefore(btnPdf, tablaVales);
  
    btnPdf.addEventListener('click', function () {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
  
      // Encabezado
      doc.setFontSize(16);
      doc.text('Vales de Laboratorio', 14, 20);
  
      // Extraer datos de la tabla
      const headers = [];
      tablaVales.querySelectorAll('thead th').forEach((th) => {
        if (th.innerText.trim() !== 'Acciones') {
          headers.push(th.innerText.trim());
        }
      });
  
      const rows = [];
      tablaVales.querySelectorAll('tbody tr').forEach((tr) => {
        const row = [];
        tr.querySelectorAll('td').forEach((td, i) => {
          if (i < headers.length) row.push(td.innerText.trim());
        });
        if (row.length) rows.push(row);
      });
  
      // Dibujar tabla
      if (rows.length > 0) {
        doc.autoTable({
          head: [headers],
          body: rows,
          startY: 30,
        });
      } else {
        doc.setFontSize(12);
        doc.text('No hay vales registrados.', 14, 30);
      }
  
      // Guardar automáticamente
      doc.save('vales.pdf');
    });
  
    // -------- Recargar tabla por AJAX --------
    async function cargarVales() {
      try {
        const resp = await fetch("Vales.php?action=vales");
        const data = await resp.json();
        const tbody = tablaVales.querySelector("tbody");
        tbody.innerHTML = "";
  
        if (data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="7" class="text-center">No hay vales registrados</td></tr>`;
          return;
        }
  
        data.forEach(v => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${v.materia}</td>
            <td>${v.docente}</td>
            <td>${v.diaLab}</td>
            <td>${v.horaLab}</td>
            <td>${v.id_lab}</td>
            <td>${v.kit}</td>
            <td>
              <form method="POST" action="Vales.php" style="display:inline;">
                <input type="hidden" name="delete_id" value="${v.id_vales}">
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error("Error cargando vales:", err);
      }
    }

     // -------- Evento cambio de materia (docentes + kits) --------
     if (materia) {
      materia.addEventListener("change", async function () {
        const id = this.value;

        // // Cargar docentes
        // try {
        //   const respDoc = await fetch(`Vales.php?action=docentes&id_materia=${encodeURIComponent(id)}`);
        //   const dataDoc = await respDoc.json();
        //   docente.innerHTML = '<option value="" disabled selected>Selecciona un docente</option>';
        //   dataDoc.forEach(d => {
        //     const o = document.createElement('option');
        //     o.value = d.id_docente;
        //     o.textContent = d.nombre;
        //     docente.appendChild(o);
        //   });
        // } catch (err) {
        //   console.error("Error cargando docentes:", err);
        // }

        // Cargar kits
        try {
          const respKits = await fetch(`Vales.php?action=kits&id_materia=${encodeURIComponent(id)}`);
          const dataKits = await respKits.json();
          kit.innerHTML = '<option value="" disabled selected>Selecciona un kit</option>';
          dataKits.forEach(k => {
            const o = document.createElement('option');
            o.value = k.id_kit;
            o.textContent = k.nombre;
            kit.appendChild(o);
          });
        } catch (err) {
          console.error("Error cargando kits:", err);
        }
      });
    }
  });
  