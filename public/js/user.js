const addUserButton = document.getElementById('addUserButton');
const addUserModal = document.getElementById('addUserModal');
const closeButtons = document.querySelectorAll('.close-button');
const userTableBody = document.getElementById('userTableBody');
const addForm = document.getElementById('addForm');
const baseUrl = 'http://192.168.88.114:8000'


let userIdToEdit = null;
let userIdToDelete = null;

addUserButton.addEventListener('click', () => {
  addUserModal.classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
});

closeButtons.forEach(button => {
  button.addEventListener('click', () => {
    addUserModal.classList.add('hidden');
    editUserModal.classList.add('hidden');
    deleteUserModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  });
});



window.addEventListener('click', (event) => {
  if (event.target.classList.contains('fixed')) {
    addUserModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }
});

addForm.addEventListener('submit', (event) => {
  event.preventDefault();

  const name = document.getElementById('add_name').value;
  const username = document.getElementById('add_username').value;
  const email = document.getElementById('add_email').value;
  const password = document.getElementById('add_password').value;
  const role = document.getElementById('add_role').value;


  fetch(`${baseUrl}/api/register`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    },
    body: JSON.stringify({ name, username, email, password, role }),
  })
  .then(response => {
    if (!response.ok) {
      return response.json().then(data => {
        throw new Error(data.message || 'Failed to add user');
      });
    }
    return response.json();
  })
  .then(data => {
    const newUserRow = document.createElement('tr');
    newUserRow.classList.add('hover:bg-gray-50');
    newUserRow.dataset.userId = data.data.id;
    newUserRow.innerHTML = `
      <td class="px-6 py-4">${userTableBody.children.length + 1}</td>
      <td class="px-6 py-4">${data.data.name}</td>
      <td class="px-6 py-4">${data.data.username}</td>
      <td class="px-6 py-4">${data.data.email}</td>
      <td class="px-6 py-4 capitalize">${data.data.role}</td>
    `;
    userTableBody.appendChild(newUserRow);

    addForm.reset();
    addUserModal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
        alert(data.message);

  })
  .catch(error => {
    alert('Terjadi kesalahan: ' . error.message);
  });
});
