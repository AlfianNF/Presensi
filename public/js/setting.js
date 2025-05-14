const baseUrl = 'http://presensi.test';

// Fungsi untuk mengambil cookie berdasarkan nama
function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

document.getElementById('addPresensiButton').addEventListener('click', () => {
  document.getElementById('presensiModal').classList.remove('hidden');
});

document.getElementById('closeModal').addEventListener('click', () => {
  document.getElementById('presensiModal').classList.add('hidden');
});

document.getElementById('presensiForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const hari = document.getElementById('hari').value;
  const waktu = document.getElementById('waktu').value;
  const token = getCookie('access_token'); // Ambil token dari cookie

  if (!token) {
    alert('Token tidak ditemukan. Silakan login ulang.');
    return;
  }

  fetch(`${baseUrl}/api/setting-presensi`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    credentials: 'include',
    body: JSON.stringify({ hari, waktu })
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      window.location.reload();
    })
    .catch(err => {
      alert('Gagal menyimpan presensi');
      console.error(err);
    });
});
