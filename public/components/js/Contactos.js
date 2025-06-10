const contacts = [];

const contactForm = document.getElementById('contactForm');
const contactList = document.getElementById('contactList');

contactForm.addEventListener('submit', function (e) {
  e.preventDefault();

  const nombre = document.getElementById('nombre').value.trim();
  const telefono = document.getElementById('telefono').value.trim();
  const correo = document.getElementById('correo').value.trim();
  const index = document.getElementById('contactIndex').value;

  if (nombre === '' || telefono === '' || correo === '') return;

  if (index === '') {
    contacts.push({ nombre, telefono, correo });
  } else {
    contacts[index] = { nombre, telefono, correo };
    document.getElementById('contactIndex').value = '';
  }

  contactForm.reset();
  renderContacts();
});

function renderContacts() {
  contactList.innerHTML = '';
  contacts.forEach((contact, i) => {
    contactList.innerHTML += `
      <div class="col">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">${contact.nombre}</h5>
            <p class="card-text"><strong>Tel:</strong> ${contact.telefono}</p>
            <p class="card-text"><strong>Email:</strong> ${contact.correo}</p>
            <button class="btn btn-sm btn-primary me-2" onclick="editContact(${i})"><i class="fa-solid fa-pen"></i> Editar</button>
            <button class="btn btn-sm btn-danger" onclick="deleteContact(${i})"><i class="fa-solid fa-trash"></i> Eliminar</button>
          </div>
        </div>
      </div>
    `;
  });
}

function editContact(index) {
  const c = contacts[index];
  document.getElementById('nombre').value = c.nombre;
  document.getElementById('telefono').value = c.telefono;
  document.getElementById('correo').value = c.correo;
  document.getElementById('contactIndex').value = index;
}

function deleteContact(index) {
  if (confirm('¿Estás seguro de eliminar este contacto?')) {
    contacts.splice(index, 1);
    renderContacts();
  }
}