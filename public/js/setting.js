const baseUrl = document.querySelector('meta[name="app-url"]').getAttribute('content');

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
    const token = localStorage.getItem('token');

    if (!token) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Token tidak ditemukan. Silakan login ulang.',
        });
        return;
    }

    fetch(`${baseUrl}/api/me`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Gagal mengambil data pengguna');
            });
        }
        return response.json();
    })
    .then(userData => {
        const userId = userData.id; 
        document.getElementById('id_user').value = userId;
        fetch(`${baseUrl}/api/setting-presensi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ hari, waktu, id_user: userId })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Gagal menyimpan presensi');
                });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Terjadi kesalahan saat menyimpan data.'
            });
            console.error(error);
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal mengambil data pengguna: ' + error.message
        });
        console.error(error);
    });
});
