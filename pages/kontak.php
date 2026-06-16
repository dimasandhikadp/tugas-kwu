<?php
  include '../config/koneksi.php';
  session_start();
?>

<!DOCTYPE html>
<html lang="id">
   <?php
    include '../includes/head.php'
    ?>
  <body class="bg-gray-50 text-gray-800 font-sans">

    <?php
    include '../includes/header.php'
    ?> 

  <main class="max-w-7xl mx-auto py-16 px-6">

  <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

    <div class="grid md:grid-cols-2">

      <!-- BAGIAN KIRI -->
      <div class="relative">

      <div
        class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/30"
      ></div>

        <img
          src="../assets/img/gambar-kontak.jpg"
          alt="Office"
          class="w-full h-200 object-cover"
        >

        <!-- CARD INFO -->
        <div class="absolute left-8 top-1/2 -translate-y-1/2">

          <div class="bg-white p-6 rounded-2xl shadow-xl w-72">

            <div class="flex items-start gap-4 mb-6">
              <div class="bg-blue-100 p-3 rounded-full">
                <i data-lucide="map-pin" class="text-blue-600 w-5 h-5"></i>
              </div>

              <div>
                <h4 class="font-bold text-slate-800">Lokasi</h4>
                <p class="text-sm text-gray-500">
                  Tarakan, Kalimantan Utara
                </p>
              </div>
            </div>

            <div class="flex items-start gap-4 mb-6">
              <div class="bg-blue-100 p-3 rounded-full">
                <i data-lucide="phone" class="text-blue-600 w-5 h-5"></i>
              </div>

              <div>
                <h4 class="font-bold text-slate-800">Telepon</h4>
                <p class="text-sm text-gray-500">
                  +62 812-3456-7890
                </p>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="bg-blue-100 p-3 rounded-full">
                <i data-lucide="clock-3" class="text-blue-600 w-5 h-5"></i>
              </div>

              <div>
                <h4 class="font-bold text-slate-800">
                  Jam Operasional
                </h4>
                <p class="text-sm text-gray-500">
                  Senin - Minggu
                </p>
                <p class="text-sm text-gray-500">
                  08:00 - 21:00 WIB
                </p>
              </div>
            </div>

          </div>

        </div>

      </div>

      <!-- BAGIAN KANAN -->
      <div class="p-10 lg:p-14">

        <h2 class="text-4xl font-bold text-slate-800 mb-2">
          Hubungi Kami
        </h2>

        <p class="text-gray-500 mb-8">
          Hubungi tim Segar untuk pertanyaan,
          pemesanan, atau kerja sama.
        </p>

        <form class="space-y-5">

          <div>
            <label class="block text-sm font-medium mb-2">
              Nama Lengkap
            </label>

            <input
              type="text"
              placeholder="Masukkan nama lengkap"
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            >
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">
              Email
            </label>

            <input
              type="email"
              placeholder="Masukkan email"
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            >
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">
              Nomor Telepon
            </label>

            <input
              type="text"
              placeholder="08xxxxxxxxxx"
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            >
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">
              Pesan
            </label>

            <textarea
              rows="5"
              placeholder="Tulis pesan Anda..."
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            ></textarea>
          </div>

          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold transition"
          >
            Kirim Pesan
          </button>

        </form>

      </div>

    </div>

  </div>

</main>
    
  <?php
    include '../includes/footer.php'; 
    include '../includes/script.php'; 
  ?>
  </body>
</html>
