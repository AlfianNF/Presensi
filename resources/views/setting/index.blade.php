<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans">

  <!-- Sidebar -->
  <div class="flex min-h-screen">
    <aside class="w-64 bg-orange-600 text-white flex flex-col">
      <div class="px-6 py-4 text-2xl font-bold border-b border-orange-500">
        <i class="fas fa-user-shield mr-2"></i>Admin Panel
      </div>
      <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="#user" class="flex items-center px-3 py-2 rounded hover:bg-orange-700">
          <i class="fas fa-users mr-2"></i> User
        </a>
        <a href="#setting-presensi" class="flex items-center px-3 py-2 rounded hover:bg-orange-700">
          <i class="fas fa-cogs mr-2"></i> Setting Presensi
        </a>
        <a href="#presensi" class="flex items-center px-3 py-2 rounded hover:bg-orange-700">
          <i class="fas fa-calendar-check mr-2"></i> Presensi
        </a>
        <a href="#profil" class="flex items-center px-3 py-2 rounded hover:bg-orange-700">
          <i class="fas fa-user-circle mr-2"></i> Profil Admin
        </a>
      </nav>
      <div class="px-6 py-4 border-t border-orange-500">
        <button class="w-full text-left text-sm hover:underline">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <h1 class="text-2xl font-semibold mb-4">Selamat Datang, Admin!</h1>

      <div id="user" class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-bold mb-2">Manajemen User</h2>
        <p class="text-sm text-gray-600">Data user akan ditampilkan di sini...</p>
      </div>

      <div id="setting-presensi" class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-bold mb-2">Setting Presensi</h2>
        <p class="text-sm text-gray-600">Pengaturan presensi tersedia di sini...</p>
      </div>

      <div id="presensi" class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-bold mb-2">Presensi Harian</h2>
        <p class="text-sm text-gray-600">Laporan presensi harian akan muncul di sini...</p>
      </div>

      <div id="profil" class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-bold mb-2">Profil Admin</h2>
        <p class="text-sm text-gray-600">Informasi akun admin.</p>
      </div>
    </main>
  </div>

</body>
</html>
