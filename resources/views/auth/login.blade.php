<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sistem Presensi Online</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    .bg-gradient-custom {
    background: #034289 ;
    }
    .card-shadow {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
      display: none;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md px-4">
    <div class="bg-white rounded-xl card-shadow overflow-hidden">
      <div class="bg-gradient-custom text-white p-6 text-center">
        <div class="flex justify-center mb-4">
          <img src="img/tas2.svg" alt="Logo Sistem Presensi Online" class="h-32" />
        </div>
        <h1 class="text-2xl font-bold">Sistem Presensi Online</h1>
        <p class="text-blue-100 mt-1">Silakan masuk dengan akun Anda</p>
      </div>

      <div class="p-6">
        <form id="loginForm">
          <div class="mb-4">
            <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
              </div>
              <input type="text" id="username" name="username" placeholder="Masukkan username"
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                required />
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input type="password" id="password" name="password" placeholder="Masukkan password"
                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                required />
              <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <i id="eyeIcon" class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center mb-6">
            <input id="remember" name="remember" type="checkbox"
              class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
          </div>

          <button type="submit"
            class="w-full bg-gradient-custom text-white py-2 px-4 rounded-lg font-medium hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Masuk
          </button>
        </form>
      </div>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      eyeIcon.classList.toggle('fa-eye');
      eyeIcon.classList.toggle('fa-eye-slash');
    });

    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const username = document.getElementById('username').value;
      const password = passwordInput.value;
      const remember = document.getElementById('remember').checked;

      const btn = e.target.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
      btn.disabled = true;

      setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (username && password) {
          alert('Login berhasil! Akan diarahkan ke dashboard.');
        //   window.location.href = '/dashboard';
        } else {
          alert('Username dan password harus diisi!');
        }
      }, 1500);
    });
  </script>
</body>
</html>
