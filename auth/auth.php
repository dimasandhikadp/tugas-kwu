<!DOCTYPE html>
<html lang="id">

<?php
  include '../includes/head.php';
?>

<body class="bg-slate-50 min-h-screen flex flex-col">

    <!-- HEADER / NAVBAR -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
  <div
    class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between"
  >
    <!-- Logo -->
    <div class="flex items-center space-x-1">
      <img
        src="../assets/img/logo.png"
        alt="Logo Segar"
        class="w-15 h-10 object-contain"
        style="transform: scaleX(1.5)"
      />
      <div>
        <span class="text-xl font-bold text-blue-600 block leading-none">
          Se<span class="text-blue-950">gar</span>
        </span>
        <span class="text-xs text-gray-400 leading-none">
          Hasil Laut Segar, Dari Laut Ke Meja Anda
        </span>
      </div>
    </div>

    <!-- Tombol kembali -->
    <a
      href="../index.html"
      class="text-sm font-medium text-gray-600 hover:text-blue-600 transition"
    >
      Kembali ke Beranda
    </a>
  </div>
</header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-grow flex items-center justify-center p-6 bg-gradient-to-b from-blue-50/50 to-white">
        
        <!-- ==================== 1. FORM LOGIN ==================== -->
        <div id="loginCard" class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 transition-all duration-300 block">
            <!-- Form Header -->
            <div class="p-8 text-center pb-3">
                <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang Kembali</h2>
                <p class="text-sm text-slate-500">Silakan masuk untuk melanjutkan belanja kuliner laut Anda</p>
            </div>

            <!-- Form Body -->
            <div class="px-8 pb-8 pt-2">
                <form action="#" method="POST" class="space-y-4">
                    <!-- Input Email -->
                    <div>
                        <label for="loginEmail" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Alamat Email</label>
                        <input type="email" id="loginEmail" name="email" required placeholder="nama@email.com" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Input Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="loginPassword" class="block text-xs font-bold uppercase tracking-wider text-slate-600">Kata Sandi</label>
                            <a href="#" class="text-xs font-semibold text-[#0066cc] hover:underline">Lupa Sandi?</a>
                        </div>
                        <input type="password" id="loginPassword" name="password" required placeholder="••••••••" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" 
                        class="w-full bg-[#0066cc] hover:bg-[#0052a3] text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-blue-100 active:scale-[0.99] transform mt-2 cursor-pointer">
                        Masuk Sekarang
                    </button>
                </form>

                <!-- Pembatas Garis -->
                <div class="relative flex py-6 items-center">
                    <div class="flex-grow border-t border-slate-100"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-xs uppercase tracking-wider">Atau</span>
                    <div class="flex-grow border-t border-slate-100"></div>
                </div>

                <!-- Link ke Halaman Daftar -->
                <p class="text-center text-sm text-slate-600">
                    Belum punya akun di Segar? 
                    <button onclick="showRegister()" class="font-bold text-[#0066cc] hover:underline cursor-pointer focus:outline-none">Daftar Sekarang</button>
                </p>
            </div>
        </div>

        <!-- ==================== 2. FORM DAFTAR (Tersembunyi secara default) ==================== -->
        <div id="registerCard" class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 transition-all duration-300 hidden">
            <!-- Form Header -->
            <div class="p-8 text-center pb-3">
                <h2 class="text-2xl font-bold text-slate-800 mb-1">Mulai Belanja</h2>
                <p class="text-sm text-slate-500">Buat akun Segar Anda untuk kemudahan memesan hasil laut terbaik</p>
            </div>

            <!-- Form Body -->
            <div class="px-8 pb-8 pt-2">
                <form action="#" method="POST" class="space-y-4">
                    <!-- Input Nama Lengkap -->
                    <div>
                        <label for="regName" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="regName" name="name" required placeholder="Masukkan nama lengkap Anda" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Input Nomor WhatsApp -->
                    <div>
                        <label for="regPhone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nomor WhatsApp</label>
                        <input type="tel" id="regPhone" name="phone" required placeholder="Contoh: 08123456789" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Input Email -->
                    <div>
                        <label for="regEmail" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Alamat Email</label>
                        <input type="email" id="regEmail" name="email" required placeholder="nama@email.com" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Input Kata Sandi -->
                    <div>
                        <label for="regPassword" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Kata Sandi Baru</label>
                        <input type="password" id="regPassword" name="password" required placeholder="Minimal 8 karakter" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>

                    <!-- Persetujuan Syarat Ketentuan -->
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required
                            class="h-4 w-4 mt-0.5 rounded border-slate-300 text-[#0066cc] focus:ring-[#0066cc]">
                        <label for="terms" class="ml-2 text-xs text-slate-600 select-none leading-normal">
                            Saya menyetujui <a href="#" class="text-[#0066cc] font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#0066cc] font-semibold hover:underline">Kebijakan Privasi</a>.
                        </label>
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit" 
                        class="w-full bg-[#0066cc] hover:bg-[#0052a3] text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-blue-100 active:scale-[0.99] transform mt-2 cursor-pointer">
                        Daftar Akun Baru
                    </button>
                </form>

                <!-- Pembatas Garis -->
                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-slate-100"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-xs uppercase tracking-wider">Atau</span>
                    <div class="flex-grow border-t border-slate-100"></div>
                </div>

                <!-- Link kembali ke Halaman Login -->
                <p class="text-center text-sm text-slate-600">
                    Sudah memiliki akun? 
                    <button onclick="showLogin()" class="font-bold text-[#0066cc] hover:underline cursor-pointer focus:outline-none">Masuk di sini</button>
                </p>
            </div>
        </div>

    </main>

    <footer class="bg-blue-950 text-white mt-20">
  <!-- Bottom Footer -->
  <div class="border-t border-blue-900">
    <div
      class="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row justify-between items-center gap-3"
    >
      <p class="text-sm text-gray-400">
        © 2026 Segar. Semua hak dilindungi.
      </p>

      <div class="flex items-center gap-4 text-sm text-gray-400">
        <a href="#" class="hover:text-blue-400">Syarat & Ketentuan</a>
        <a href="#" class="hover:text-blue-400">Privasi</a>
      </div>
    </div>
  </div>
</footer>


    <!-- INTERAKSI JAVASCRIPT GABUNGAN -->
    <script>
        const loginCard = document.getElementById('loginCard');
        const registerCard = document.getElementById('registerCard');

        function showRegister() {
            loginCard.classList.replace('block', 'hidden');
            registerCard.classList.replace('hidden', 'block');
            document.title = "Daftar Akun - Segar | Hasil Laut Segar";
        }

        function showLogin() {
            registerCard.classList.replace('block', 'hidden');
            loginCard.classList.replace('hidden', 'block');
            document.title = "Login - Segar | Hasil Laut Segar";
        }
    </script>
</body>
</html>