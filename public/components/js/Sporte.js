document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('supportForm');
    const responseMsg = document.getElementById('responseMsg');
  
    form.addEventListener('submit', (e) => {
      e.preventDefault();
  
      const formData = new FormData(form);
  
      fetch('../../resources/api/Soporte/apiSoporte.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === "OK") {
          responseMsg.innerHTML = '<div class="alert alert-success">✅ Mensaje enviado correctamente</div>';
          form.reset();
        } else {
          responseMsg.innerHTML = '<div class="alert alert-danger">❌ Error: ' + data + '</div>';
        }
      })
      .catch(err => {
        responseMsg.innerHTML = '<div class="alert alert-danger">⚠️ Error de red</div>';
      });
    });
  });
  