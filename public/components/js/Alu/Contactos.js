// Archivo: Contactos.js
document.addEventListener('DOMContentLoaded', function () {
  // Rutas/usuario inyectadas desde Contactos.php
  const API_CHAT = window.API_CHAT || '../../../resources/api/Alumnos/apiChat.php';
  const API_CONT = window.API_CONT || '../../../resources/api/Alumnos/apiContactos.php';
  const myUser = window.myUser || '';

  // Referencias DOM
  const contactForm = document.getElementById('contactForm');
  const idInput = document.getElementById('id_contacto');
  const nombreInput = document.getElementById('nombre');
  const telefonoInput = document.getElementById('telefono');
  const correoInput = document.getElementById('correo');
  const cancelEditBtn = document.getElementById('cancelEditBtn');
  const tbody = document.getElementById('contactTableBody');
  const sugerenciasList = document.getElementById('sugerenciasList');
  const chatContainer = document.getElementById('chatContainer');

  // Control de intervalos por chat
  const chatIntervals = {};

  // Cargar datos iniciales
  loadAndDisplayContacts();
  loadSugerencias();

  // ---------- Contactos ----------
  function loadAndDisplayContacts() {
    fetch(API_CONT + '?action=listar', { credentials: 'same-origin' })
      .then(resp => resp.text())
      .then(html => { tbody.innerHTML = html; })
      .catch(err => console.error('Error al cargar contactos:', err));
  }

  function loadSugerencias() {
    const fd = new FormData();
    fd.append('action', 'sugerencias');
    fetch(API_CONT, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(r => r.text())
      .then(html => { sugerenciasList.innerHTML = html; })
      .catch(err => console.error('Error al cargar sugerencias:', err));
  }

  // Agregar desde sugerencia
  sugerenciasList.addEventListener('click', function (e) {
    const btn = e.target.closest('.add-suggest-btn');
    if (!btn) return;
    nombreInput.value = btn.getAttribute('data-nombre') || '';
    telefonoInput.value = btn.getAttribute('data-telefono') || '';
    correoInput.value = btn.getAttribute('data-correo') || '';
    document.getElementById('saveContactBtn').focus();
  });

  // Form add/edit
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const id = idInput.value;
    const nombre = nombreInput.value.trim();
    const telefono = telefonoInput.value.trim();
    const correo = correoInput.value.trim();
    if (!nombre || !telefono || !correo) { alert('Completa todos los campos'); return; }
    const action = id ? 'editar' : 'agregar';
    const fd = new FormData();
    fd.append('action', action);
    fd.append('nombre', nombre);
    fd.append('telefono', telefono);
    fd.append('correo', correo);
    if (id) fd.append('id_contacto', id);

    fetch(API_CONT, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(r => r.text())
      .then(res => {
        if (res.trim() === 'OK') {
          loadAndDisplayContacts();
          loadSugerencias();
          resetForm();
        } else {
          alert('Error: ' + res);
        }
      })
      .catch(err => { console.error(err); alert('Error al procesar la solicitud'); });
  });

  // Delegacion botones editar / eliminar y chat en la tabla
  tbody.addEventListener('click', function (e) {
    const btn = e.target.closest('button');
    if (!btn) return;

    if (btn.classList.contains('edit-btn')) {
      idInput.value = btn.getAttribute('data-id') || '';
      nombreInput.value = btn.getAttribute('data-nombre') || '';
      telefonoInput.value = btn.getAttribute('data-telefono') || '';
      correoInput.value = btn.getAttribute('data-correo') || '';
      cancelEditBtn.classList.remove('d-none');
      nombreInput.focus();
    }

    if (btn.classList.contains('delete-btn')) {
      const id = btn.getAttribute('data-id');
      if (!id) return;
      if (!confirm('¿Eliminar?')) return;
      const fd = new FormData();
      fd.append('action', 'eliminar');
      fd.append('id_contacto', id);
      fetch(API_CONT, { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.text())
        .then(res => {
          if (res.trim() === 'OK') {
            loadAndDisplayContacts();
            loadSugerencias();
            if (idInput.value === id) resetForm();
          } else alert('Error al eliminar: ' + res);
        }).catch(err => console.error(err));
    }

    // Chat button
    if (btn.classList.contains('chat-btn')) {
      const user = btn.getAttribute('data-username') || '';
      const nombre = btn.getAttribute('data-nombre') || '';
      if (!user) {
        console.warn('Este contacto no tiene username asociado. No se puede abrir chat.');
        alert('No se puede abrir chat: el contacto no está registrado en el sistema.');
        return;
      }
      openChat(user, nombre);
    }
  });

  cancelEditBtn.addEventListener('click', resetForm);
  function resetForm() {
    contactForm.reset();
    idInput.value = '';
    cancelEditBtn.classList.add('d-none');
  }

  // ---------- CHAT ----------
  function safeId(username) {
    return 'chat-' + String(username).replace(/[^a-z0-9_\-]/gi, '_');
  }

  function openChat(username, nombre) {
    if (!username) return;
    const sid = safeId(username);
    if (document.getElementById(sid)) return; // ya abierto

    const chat = document.createElement('div');
    chat.className = 'chat-window';
    chat.id = sid;
    chat.innerHTML = `
      <div class="chat-header d-flex align-items-center justify-content-between">
        <strong style="font-size:0.95rem">${escapeHtml(nombre)}</strong>
        <div><button class="btn-close btn-close-white btn-sm" title="Cerrar"></button></div>
      </div>
      <div class="chat-body" style="height:260px; overflow:auto; padding:10px;" id="${sid}-body"></div>
      <div class="chat-footer p-2">
        <input type="text" class="form-control form-control-sm me-2" placeholder="Escribe..." id="${sid}-input">
        <button class="btn btn-sm btn-primary" id="${sid}-send"><i class="fa fa-paper-plane"></i></button>
      </div>
    `;
    chatContainer.appendChild(chat);

    // cerrar
    chat.querySelector('.btn-close').onclick = () => {
      // limpiar interval
      if (chatIntervals[username]) { clearInterval(chatIntervals[username]); delete chatIntervals[username]; }
      chat.remove();
    };

    // enviar
    chat.querySelector('#' + sid + '-send').onclick = async () => {
      const input = chat.querySelector('#' + sid + '-input');
      const texto = input.value.trim();
      if (!texto) return;
      try {
        const fd = new FormData();
        fd.append('action', 'enviar');
        fd.append('receptor', username);
        fd.append('mensaje', texto);
        const resp = await fetch(API_CHAT, {
          method: 'POST',
          body: fd,
          credentials: 'same-origin'
        });
        const data = await resp.json();
        if (data.status && data.status === 'OK') {
          input.value = '';
          await loadChat(username); // recargar inmediatamente
        } else {
          const err = data.error || 'Error al enviar';
          console.error('API enviar error:', err);
          alert(err);
        }
      } catch (err) {
        console.error('Error al enviar:', err);
        alert('Error al enviar mensaje (ver consola).');
      }
    };

    // evitar duplicar intervalos
    if (!chatIntervals[username]) {
      chatIntervals[username] = setInterval(() => loadChat(username), 3000);
    }
    loadChat(username);
  }

  async function loadChat(username) {
    if (!username) return;
    const sid = safeId(username);
    const bodyEl = document.getElementById(sid + '-body');
    if (!bodyEl) return;
    try {
      const resp = await fetch(API_CHAT + '?action=cargar&contacto=' + encodeURIComponent(username), { credentials: 'same-origin' });
      if (!resp.ok) {
        console.error('Error cargando chat', resp.status);
        return;
      }
      const msgs = await resp.json();
      bodyEl.innerHTML = '';
      msgs.forEach(m => {
        const div = document.createElement('div');
        div.className = 'chat-bubble ' + (m.emisor === myUser ? 'me' : 'other');
        div.textContent = m.mensaje;
        bodyEl.appendChild(div);
      });
      bodyEl.scrollTop = bodyEl.scrollHeight;
    } catch (err) {
      console.error('Error en loadChat()', err);
    }
  }

  // pequeño helper
  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"'`]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;', "`":'&#96;'}[s]));
  }

  console.log('Contactos.js cargado. Usuario:', myUser);
});
 