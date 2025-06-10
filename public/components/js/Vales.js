document.addEventListener('DOMContentLoaded', () => {
  const materiaSelect = document.getElementById('materia');
  const kitSelect = document.getElementById('kit');
  const fechaInput = document.getElementById('fecha');
  const form = document.getElementById('labForm');
  const pdfContainer = document.getElementById('pdfContainer');
  const downloadBtn = document.getElementById('downloadPDF');

  const materialesPorKit = {
    quimica: {
      'Kit Básico Química': ['Vasos medidores', 'Tubos de ensayo', 'Reactivos básicos'],
      'Kit Avanzado Química': ['Matraz Erlenmeyer', 'Buretas', 'Soluciones titulación']
    },
    fisica: {
      'Kit Básico Física': ['Multímetro', 'Cables cocodrilo', 'Fuente de voltaje'],
      'Kit Avanzado Física': ['Sensor de movimiento', 'Placa Arduino', 'Generador de funciones']
    },
    electronica: {
      'Kit Básico Electrónica': ['Protoboard', 'LEDs', 'Resistencias'],
      'Kit Avanzado Electrónica': ['Microcontrolador', 'Pantalla LCD', 'Módulo WiFi']
    }
  };

  // Establecer fecha mínima y máxima (hoy a 3 días)
  const today = new Date();
  const maxDate = new Date();
  maxDate.setDate(today.getDate() + 3);
  fechaInput.min = today.toISOString().split('T')[0];
  fechaInput.max = maxDate.toISOString().split('T')[0];

  // Actualizar opciones de kits según la materia
  materiaSelect.addEventListener('change', () => {
    const materia = materiaSelect.value;
    kitSelect.innerHTML = '<option disabled selected>Selecciona un kit</option>';
    if (materialesPorKit[materia]) {
      for (const kit in materialesPorKit[materia]) {
        const option = document.createElement('option');
        option.value = kit;
        option.textContent = kit;
        kitSelect.appendChild(option);
      }
    }
  });

  // Envío de formulario
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const fechaSeleccionada = new Date(fechaInput.value);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    const maxFecha = new Date();
    maxFecha.setDate(hoy.getDate() + 3);

    if (fechaSeleccionada < hoy || fechaSeleccionada > maxFecha) {
      alert('La fecha debe estar dentro de los próximos 3 días y no puede ser pasada.');
      return;
    }

    const materia = materiaSelect.options[materiaSelect.selectedIndex].text;
    const profesor = document.getElementById('profesor').value;
    const fecha = fechaInput.value;
    const laboratorio = document.getElementById('laboratorio').value;
    const kit = kitSelect.value;
    const materiales = materialesPorKit[materiaSelect.value][kit];

    document.getElementById('valeMateria').textContent = materia;
    document.getElementById('valeProfesor').textContent = profesor;
    document.getElementById('valeFecha').textContent = fecha;
    document.getElementById('valeLab').textContent = laboratorio;
    document.getElementById('valeKit').textContent = kit;

    const lista = document.getElementById('valeMateriales');
    lista.innerHTML = '';
    materiales.forEach(m => {
      const li = document.createElement('li');
      li.textContent = m;
      lista.appendChild(li);
    });

    document.getElementById('vale').classList.remove('d-none');
    pdfContainer.classList.remove('d-none');
  });

  // Descargar como PDF
  downloadBtn.addEventListener('click', () => {
    const element = document.getElementById('vale');
    const opt = {
      margin: 0.5,
      filename: `vale-${new Date().toISOString().split('T')[0]}.pdf`,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().from(element).set(opt).save();
  });
});
