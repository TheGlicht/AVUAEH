// Cambio a tema oscuro
const toggleBtn = document.getElementById('themeToggle');
const body = document.getElementById('body');

toggleBtn.addEventListener('click', () =>{
    body.classList.toggle('dark-mode');
    toggleBtn.textContent = body.classList.contains('dark-mode')
    ? 'Cambiar a tema claro'
    : 'Cambiar a tema oscuro';
});