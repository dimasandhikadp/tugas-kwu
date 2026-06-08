<!DOCTYPE html>
<html lang="id">
  <!-- <?php include '../includes/head.php'; ?> -->
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Segar - Hasil Laut Segar Langsung dari Laut</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
  </head>
  <body class="bg-slate-50 text-slate-700">
    <?php
     include '../includes/header.php'
    ?> 
    
    <main class="max-w-7xl mx-auto px-5 py-12">

  <!-- Judul -->
  <section class="text-center mb-12">
    <h1 class="text-4xl font-bold text-slate-900 mb-4">
      Tentang Segar
    </h1>

    <p class="max-w-4xl mx-auto text-lg text-slate-600 leading-8">
      Berawal dari kepedulian terhadap kualitas pangan laut Indonesia,
      Segar berkomitmen menghadirkan hasil laut segar langsung dari nelayan
      lokal kepada masyarakat Indonesia dengan kualitas terbaik.
    </p>
  </section>

  <!-- Logo -->
  <div class="flex justify-center mb-12">
    <img
      src="../assets/img/full-logo.JPEG"
      alt="Segar"
      class="rounded-2xl shadow-lg max-w-full md:max-w-3xl"
    />
  </div>

  <!-- Visi & Misi -->
  <section class="grid md:grid-cols-2 gap-8 mb-12">

    <!-- Visi -->
    <div class="bg-white rounded-2xl shadow-md p-10">
      <h2 class="text-3xl font-bold text-center text-slate-900 mb-6">
        Visi Kami
      </h2>

      <p class="text-center text-slate-600 text-lg leading-9">
        Menjadi platform hasil laut terpercaya dan terdepan di Indonesia yang
        menghadirkan produk laut segar, berkualitas, dan berkelanjutan
        langsung dari nelayan Nusantara ke seluruh masyarakat Indonesia.
      </p>
    </div>

    <!-- Misi -->
    <div class="bg-white rounded-2xl shadow-md p-10">
      <h2 class="text-3xl font-bold text-center text-slate-900 mb-6">
        Misi Kami
      </h2>

      <p class="text-center text-slate-600 text-lg leading-9">
        Menyediakan hasil laut segar berkualitas tinggi yang aman dan sehat,
        membangun kemitraan yang kuat dengan nelayan lokal, menghadirkan
        layanan yang mudah dan terpercaya, serta mendukung pertumbuhan
        ekonomi pesisir Indonesia.
      </p>
    </div>

  </section>

  <!-- Cara Kami Beroperasi -->
  <section class="bg-white rounded-2xl shadow-md p-12">

    <h2 class="text-4xl font-bold text-center text-slate-900 mb-6">
      Cara Kami Beroperasi
    </h2>

    <p class="max-w-5xl mx-auto text-center text-slate-600 text-xl leading-10">
      Segar menghubungkan nelayan lokal dengan pelanggan melalui sistem
      distribusi yang cepat, transparan, dan terjaga kualitasnya. Dengan
      pengelolaan rantai dingin (cold chain) yang baik, kami memastikan
      hasil laut tetap segar sejak ditangkap hingga sampai ke tangan
      pelanggan.
    </p>

    <!-- 3 Nilai Utama -->
    <div class="grid md:grid-cols-3 gap-8 mt-12">

      <div class="text-center">
        <div
          class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center"
        >
          <i data-lucide="fish" class="w-10 h-10 text-blue-600"></i>
        </div>

        <h3 class="text-xl font-semibold mb-3">Kualitas Terbaik</h3>

        <p class="text-slate-600">
          Produk laut dipilih langsung dari sumber terpercaya untuk menjaga
          kualitas dan kesegaran.
        </p>
      </div>

      <div class="text-center">
        <div
          class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center"
        >
          <i data-lucide="truck" class="w-10 h-10 text-blue-600"></i>
        </div>

        <h3 class="text-xl font-semibold mb-3">Pengiriman Cepat</h3>

        <p class="text-slate-600">
          Sistem distribusi modern memastikan produk sampai dengan kondisi
          segar dan aman.
        </p>
      </div>

      <div class="text-center">
        <div
          class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center"
        >
          <i data-lucide="users" class="w-10 h-10 text-blue-600"></i>
        </div>

        <h3 class="text-xl font-semibold mb-3">Mendukung Nelayan</h3>

        <p class="text-slate-600">
          Memberikan akses pasar yang lebih luas bagi nelayan lokal untuk
          meningkatkan kesejahteraan mereka.
        </p>
      </div>

    </div>

  </section>

</main>
    <!-- <?php
        include '../includes/footer.php';
        // include '../includes/script.php';
    ?> -->
    <footer class="bg-blue-950 text-white mt-20">
      <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          <!-- Brand -->
          <div>
            <p class="text-gray-300 text-sm leading-relaxed">
              Menyediakan hasil laut segar berkualitas tinggi langsung dari
              nelayan lokal untuk keluarga Indonesia.
            </p>

            <div class="flex space-x-3 mt-5">
              <a
                href="#"
                class="bg-blue-900 hover:bg-blue-800 p-2 rounded-lg transition"
              >
                <i data-lucide="facebook" class="w-5 h-5"></i>
              </a>

              <a
                href="#"
                class="bg-blue-900 hover:bg-blue-800 p-2 rounded-lg transition"
              >
                <i data-lucide="instagram" class="w-5 h-5"></i>
              </a>

              <a
                href="#"
                class="bg-blue-900 hover:bg-blue-800 p-2 rounded-lg transition"
              >
                <i data-lucide="twitter" class="w-5 h-5"></i>
              </a>
            </div>
          </div>

          <!-- Menu -->
          <div>
            <h4 class="font-bold mb-4 text-lg">Menu</h4>

            <ul class="space-y-2 text-sm text-gray-300">
              <li>
                <a href="#" class="hover:text-blue-400">Beranda</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Produk</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Kategori</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Tentang Kami</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Kontak</a>
              </li>
            </ul>
          </div>

          <!-- Bantuan -->
          <div>
            <h4 class="font-bold mb-4 text-lg">Bantuan</h4>

            <ul class="space-y-2 text-sm text-gray-300">
              <li>
                <a href="#" class="hover:text-blue-400">Cara Belanja</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Metode Pembayaran</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Pengiriman</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">FAQ</a>
              </li>
              <li>
                <a href="#" class="hover:text-blue-400">Kebijakan Privasi</a>
              </li>
            </ul>
          </div>

          <!-- Kontak -->
          <div>
            <h4 class="font-bold mb-4 text-lg">Hubungi Kami</h4>

            <div class="space-y-3 text-sm text-gray-300">
              <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="w-5 h-5 text-blue-400"></i>
                <span>Tarakan, Kalimantan Utara</span>
              </div>

              <div class="flex items-center gap-3">
                <i data-lucide="phone" class="w-5 h-5 text-blue-400"></i>
                <span>+62 812-3456-7890</span>
              </div>

              <div class="flex items-center gap-3">
                <i data-lucide="mail" class="w-5 h-5 text-blue-400"></i>
                <span>info@segar.id</span>
              </div>
            </div>
          </div>
        </div>
      </div>

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

    <script>
      lucide.createIcons();
    </script>
  </body>
</html>
