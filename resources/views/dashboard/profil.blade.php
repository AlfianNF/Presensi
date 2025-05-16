@extends('component.dashboard')

@section('container')
<main class="flex-1 p-6">
    <h1 class="text-2xl font-bold mb-6">Profil Pengguna</h1>

    <div id="profileContainer" class="bg-white p-6 rounded-lg shadow-md w-full max-w-xl">
        <p class="text-gray-500">Memuat data profil...</p>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const user = window.data; // ✅ AMBIL DARI GLOBAL YANG SUDAH ADA DI DASHBOARD
    const profileContainer = document.getElementById('profileContainer');

    if (user && profileContainer) {
        profileContainer.innerHTML = `
            <h1 class="text-xl font-semibold mb-4">${user.name || '-'}</h1>
            <p class="text-gray-700 mb-2">Email: ${user.email || '-'}</p>
            <p class="text-gray-700 mb-2">Username: ${user.username || '-'}</p>
            <p class="text-gray-700">Role: ${user.role || '-'}</p>
        `;
    } else {
        profileContainer.innerHTML = `<p class="text-red-500">Gagal memuat data pengguna.</p>`;
    }
});
</script>
@endsection
