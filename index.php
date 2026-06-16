<?php
  include 'config/koneksi.php';
  session_start();

  $total_items = 0;
  if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      
      // Query untuk mengambil total qty
      $query_count = "SELECT SUM(ci.qty) AS total 
                      FROM cart_items ci 
                      JOIN cart c ON ci.cart_id = c.id 
                      WHERE c.user_id = '$user_id'";
      $res_count = mysqli_query($conn, $query_count);
      $data_count = mysqli_fetch_assoc($res_count);
      
      // Jika ada isinya, ambil angkanya
      if ($data_count['total'] > 0) {
          $total_items = $data_count['total'];
      }
}

// Logika untuk menampilkan "99+"
$display_count = ($total_items > 99) ? "99+" : $total_items;
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Segar | Hasil Laut Segar Langsung dari Laut</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Section Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div
        class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between"
      >
        <div class="flex items-center space-x-1">
          <img
            src="assets/img/logo.png"
            alt="Logo Segar"
            class="w-15 h-10 object-contain"
            style="transform: scaleX(1.5)"
          />
          <div>
            <span class="text-xl font-bold text-blue-600 block leading-none"
              >Se<span class="text-blue-950">gar</span></span
            >
            <span class="text-xs text-gray-400 leading-none"
              >Hasil Laut Segar, Dari Laut Ke Meja Anda</span
            >
          </div>
        </div>

        <!-- Section Navigation -->
        <nav class="hidden md:flex space-x-6 text-sm font-medium text-gray-600">
          <a href="#" class="group relative hover:text-blue-600">
            Beranda
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a
            href="pages/tentang-kami.php"
            class="group relative hover:text-blue-600"
          >
            Tentang Kami
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a
            href="pages/cara-belanja.php"
            class="group relative hover:text-blue-600"
          >
            Cara Belanja
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a href="pages/kontak.php" class="group relative hover:text-blue-600">
            Kontak
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
        </nav>

        <div class="flex items-center space-x-4">
          <!-- Search -->
          <form action="pages/search.php" method="GET" class="relative hidden sm:block">
              <input
                  type="text"
                  name="q"
                  value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                  placeholder="Cari produk laut segar..."
                  class="bg-white text-sm px-4 py-2 pr-10 rounded-full w-80 shadow-sm border border-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-blue-600 transition cursor-pointer">
                  <i data-lucide="search" class="w-4 h-4"></i>
              </button>
          </form>

          <!-- Keranjang -->
          <a href="../pages/cart.php" class="relative text-gray-600 hover:text-blue-600 transition p-2 rounded-full hover:bg-blue-50">
              <i data-lucide="shopping-cart" class="w-6 h-6"></i>

              <?php if ($total_items > 0): ?>
              <span class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full">
                  <?= $display_count; ?>
              </span>
              <?php endif; ?>
          </a>

  <!-- User Menu -->
  <div class="relative group">
    <!-- Tombol User -->
    <button
      class="text-gray-600 hover:text-blue-600 transition p-2 rounded-full hover:bg-blue-50"
      aria-label="Akun"
    >
      <i data-lucide="user" class="w-6 h-6"></i>
    </button>

    <!-- Dropdown -->
    <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
      <div class="py-2">

        <?php if(isset($_SESSION['user_id'])): ?>
          <!-- Profile -->
          <a
            href="profile.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"
          >
            <i data-lucide="user-circle" class="w-4 h-4"></i>
            <span>Profile</span>
          </a>
        <?php endif; ?>

        <?php if(isset($_SESSION['user_id'])): ?>
          <!-- Pesanan -->
          <a
            href="Pesanan.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"
          >
            <i data-lucide="package" class="w-4 h-4"></i>
            <span>Pesanan</span>
          </a>
        <?php endif; ?>

        <!-- Dark / Light Mode -->
        <button
          class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-50 text-left cursor-pointer"
        >
          <i data-lucide="moon" class="w-4 h-4"></i>
          <span>Mode Gelap</span>
        </button>

        <!-- Pengaturan -->
        <button
          class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-50 text-left cursor-pointer"
        >
          <i data-lucide="settings" class="w-4 h-4"></i>
          <span>Pengaturan</span>
        </button>

        <!-- Dashboard Penjualan -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
          <a
            href="admin/products/index.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"
          >
            <i data-lucide="store" class="w-4 h-4"></i>
            <span>Dashboard Penjualan</span>
          </a>
        <?php endif; ?>

          <!-- Pembatas sebelum Login / Logout -->
          <div class="h-0.5 bg-gray-300 mx-4 my-2 rounded-full"></div>

        <?php if(isset($_SESSION['user_id'])): ?>

          <!-- Logout -->
          <a
            href="auth/logout.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 text-red-600"
          >
            <i data-lucide="log-out" class="w-4 h-4"></i>
            <span>Logout</span>
          </a>

        <?php else: ?>

          <!-- Login -->
          <a
            href="auth/auth.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"
          >
            <i data-lucide="log-in" class="w-4 h-4"></i>
            <span>Login</span>
          </a>

        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

    </header>

    <!-- Section Slide  -->
    <section class="max-w-[1200px] mx-auto px-4 my-6 relative z-0">
      <div
        id="hero-slider"
        class="relative rounded-3xl overflow-hidden min-h-[460px]"
      >
        <!-- Slide 1 -->
        <div
          class="hero-slide absolute inset-0 bg-cover bg-center opacity-100 transition-opacity duration-1000"
          style="background-image: url(assets/img/sliders/slide1.jpeg)"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/30"
          ></div>

          <div
            class="relative z-10 h-full flex items-center px-8 md:px-12 py-12"
          >
            <div class="space-y-6 text-white max-w-xl">
              <span
                class="bg-white text-blue-600 font-semibold text-xs px-3 py-1.5 rounded-full inline-flex items-center gap-1"
              >
                <i data-lucide="snowflake" class="w-4 h-4"></i>
                100% SEGAR
              </span>

              <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                Hasil Laut Segar
                <span class="block text-blue-600">Langsung dari Laut</span>
              </h1>

              <p class="text-blue-100">
                Hasil tangkapan nelayan terbaik dengan kualitas premium dan
                kesegaran terjamin.
              </p>

              <div class="flex gap-4">
                <button
                  class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition cursor-pointer"
                >
                  Belanja Sekarang
                </button>

                <a
                  class="bg-white/10 backdrop-blur text-white px-6 py-3 rounded-xl border border-white/20 cursor-pointer"
                  href="pages/kategori.php"
                >
                  Lihat Kategori
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div
          class="hero-slide absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-1000"
          style="background-image: url(assets/img/sliders/slide-fish.jpg)"

        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/30"
          ></div>

          <div
            class="relative z-10 h-full flex items-center px-8 md:px-12 py-12"
          >
            <div class="space-y-6 text-white max-w-xl">
              <h1 class="text-4xl md:text-6xl font-extrabold">
                Ikan Pilihan
                <span class="block text-blue-600">Kualitas Ekspor</span>
              </h1>

              <p class="text-blue-100">
                Dipilih langsung dari nelayan dan diproses dengan standar
                terbaik.
              </p>
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div
          class="hero-slide absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-1000"
          style="background-image: url(assets/img/sliders/slide3.jpeg)"

        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-blue-950/80 to-blue-900/30"
          ></div>

          <div
            class="relative z-10 h-full flex items-center px-8 md:px-12 py-12"
          >
            <div class="space-y-6 text-white max-w-xl">
              <h1 class="text-4xl md:text-6xl font-extrabold">
                Pengiriman Cepat
                <span class="block text-blue-600">Ke Seluruh Indonesia</span>
              </h1>

              <p class="text-blue-100">
                Produk dikirim setiap hari dengan kemasan yang menjaga
                kesegaran.
              </p>
            </div>
          </div>
        </div>

        <!-- Indicator -->
        <div
          class="absolute bottom-16 left-1/2 -translate-x-1/2 flex gap-2 z-20 cursor-pointer"
        >
          <div class="slider-dot w-3 h-3 rounded-full bg-white"></div>
          <div class="slider-dot w-3 h-3 rounded-full bg-white/40"></div>
          <div class="slider-dot w-3 h-3 rounded-full bg-white/40"></div>
        </div>
      </div>

      <div class="-mb-16"></div>
    </section>

    <!-- Section Content -->
    <section class="max-w-6xl mx-auto px-4 relative z-20 -mt-16">
      <div
        class="bg-white border border-gray-100 rounded-2xl p-6 grid grid-cols-2 lg:grid-cols-4 gap-6 shadow-sm"
      >
        <div class="flex items-center space-x-3">
          <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
            <i data-lucide="snowflake"></i>
          </div>
          <div>
            <h3 class="font-bold text-sm text-blue-950">Kesegaran Terjamin</h3>
            <p class="text-xs text-gray-500">
              Hasil laut segar setiap hari langsung dari nelayan
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
            <i data-lucide="truck"></i>
          </div>
          <div>
            <h3 class="font-bold text-sm text-blue-950">Pengiriman Cepat</h3>
            <p class="text-xs text-gray-500">
              Dikirim setiap hari ke seluruh Indonesia
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
            <i data-lucide="shield-check"></i>
          </div>
          <div>
            <h3 class="font-bold text-sm text-blue-950">Bergaransi</h3>
            <p class="text-xs text-gray-500">
              Uang kembali jika produk tidak sesuai
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
            <i data-lucide="headphones"></i>
          </div>
          <div>
            <h3 class="font-bold text-sm text-blue-950">Layanan Pelanggan</h3>
            <p class="text-xs text-gray-500">Siap membantu Anda setiap hari</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Kategori -->
    <section class="max-w-7xl mx-auto px-4 my-12">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-950">Kategori Produk</h2>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-10">
        <a
          href="pages/kategori.php?c=ikan"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center"
          >
            <img
              src="assets/img/kategori/ikan.png"
              alt="Ikan"
              class="w-20 h-20 object-contain"
            />
          </div>
          <h4 class="font-bold text-sm text-blue-950">Ikan</h4>
          <p class="text-xs text-gray-400">25+ Produk</p>
        </a>

        <a
          href="pages/kategori.php?c=udang"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-22 h-18 bg-blue-50 rounded-lg mb-3 flex items-center justify-center"
          >
            <img
              src="assets/img/kategori/udang.png"
              alt="Udang"
              class="w-15 h-15 object-contain"
            />
          </div>
          <h4 class="font-bold text-sm text-blue-950">Udang</h4>
          <p class="text-xs text-gray-400">15+ Produk</p>
        </a>

        <a
          href="pages/kategori.php?c=kepiting"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-22 h-18 bg-blue-50 rounded-lg mb-3 flex items-center justify-center"
          >
            <img
              src="assets/img/kategori/kepiting.png"
              alt="Kepiting"
              class="w-15 h-15 object-contain"
            />
          </div>
          <h4 class="font-bold text-sm text-blue-950">Kepiting</h4>
          <p class="text-xs text-gray-400">10+ Produk</p>
        </a>

        <a
          href="pages/kategori.php?c=cumi"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-22 h-18 bg-blue-50 rounded-lg mb-3 flex items-center justify-center"
          >
            <img
              src="assets/img/kategori/cumi.png"
              alt="Cumi & Sotong"
              class="w-15 h-15 object-contain"
            />
          </div>
          <h4 class="font-bold text-sm text-blue-950">Cumi & Sotong</h4>
          <p class="text-xs text-gray-400">12+ Produk</p>
        </a>

        <a
          href="pages/kategori.php?c=kerang"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-22 h-18 bg-blue-50 rounded-lg mb-3 flex items-center justify-center"
          >
            <img
              src="assets/img/kategori/kerang.png"
              alt="Kerang"
              class="w-15 h-15 object-contain"
            />
          </div>
          <h4 class="font-bold text-sm text-blue-950">Kerang</h4>
          <p class="text-xs text-gray-400">8+ Produk</p>
        </a>

        <a
          href="pages/kategori.php"
          class="bg-white border border-slate-200 p-4 rounded-2xl text-center shadow-xs hover:shadow-md transition flex flex-col justify-between items-center cursor-pointer"
        >
          <div
            class="w-20 h-18 bg-gray-50 rounded-lg mb-3 flex items-center justify-center text-blue-600"
          >
            <i data-lucide="layout-grid" class="w-10 h-10"></i>
          </div>
          <h4 class="font-bold text-sm text-blue-950">Lainnya</h4>
          <p class="text-xs text-gray-400">10+ Produk</p>
        </a>
      </div>
    </section>

<section class="max-w-7xl mx-auto px-4 my-12">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Produk Terlaris</h2>

    <a href="pages/kategori.php" class="text-blue-600 font-semibold text-sm hover:underline flex items-center gap-1">
      Lihat Semua Produk
      <i data-lucide="arrow-right" class="w-4 h-4"></i>
    </a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">

    <?php
    /** @var mysqli $conn */
    // Pastikan kolom p.slug ikut terpanggil (p.* sudah mencakup kolom slug)
    $query_terlaris = "SELECT p.*, 
                      (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                      FROM products p 
                      WHERE p.status = 'aktif' 
                      ORDER BY (p.badge = 'TERLARIS') DESC, p.id DESC 
                      LIMIT 5";
    
    $result_terlaris = mysqli_query($conn, $query_terlaris);

    if (mysqli_num_rows($result_terlaris) > 0) {
        while ($row = mysqli_fetch_assoc($result_terlaris)) {
            $badge_text = !empty($row['badge']) ? htmlspecialchars($row['badge']) : 'SEGAR';
            $badge_class = ($badge_text === 'TERLARIS') 
                ? 'bg-blue-50 text-blue-600 border-blue-100' 
                : (($badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
            
            $icon_kategori = 'fish';
            if (isset($row['kategori'])) {
                $kat = strtolower($row['kategori']);
                if (str_contains($kat, 'udang')) $icon_kategori = 'shrimp';
                if (str_contains($kat, 'kepiting')) $icon_kategori = 'crab';
            }
    ?>
            <a href="pages/product-details.php?produk=<?= $row['slug']; ?>" class="w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-sm hover:-translate-y-[2px] transition-all duration-200 relative flex flex-col group">
              
              <div class="absolute top-3 left-3 z-10">
                <span class="<?= $badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border">
                  <?= $badge_text; ?>
                </span>
              </div>

              <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50 relative">
                <?php if (!empty($row['gambar_utama']) && file_exists("assets/img/product-image/" . $row['gambar_utama'])): ?>
                    <img src="assets/img/product-image/<?= $row['gambar_utama']; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1">
                        <i data-lucide="<?= $icon_kategori; ?>" class="w-14 h-14"></i>
                    </div>
                <?php endif; ?>
              </div>

              <div class="space-y-0.5 mb-2.5 flex-1 flex flex-col">
                <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2" title="<?= htmlspecialchars($row['nama_produk']); ?>">
                    <?= htmlspecialchars($row['nama_produk']); ?>
                </h4>
                
              </div>

              <div class="flex items-center justify-between pt-1 border-t border-slate-50 mt-1">
                <div>
                  <span class="text-blue-600 font-extrabold text-sm md:text-base block leading-tight">
                      Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                  </span>
                  <span class="text-[10px] text-slate-400">/<?= htmlspecialchars($row['satuan'] ?? 'kg'); ?></span>
                </div>
                
                <div class="text-right max-w-[50%]">
                  <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded truncate block" title="<?= htmlspecialchars($row['asal_produk'] ?? 'Lokal'); ?>">
                    <?= !empty($row['asal_produk']) ? htmlspecialchars($row['asal_produk']) : 'Lokal'; ?>
                  </span>
                </div>
              </div>

            </a> <?php
        }
    } else {
    ?>
        <div class="col-span-full border-2 border-dashed border-slate-200 rounded-2xl py-12 text-center text-slate-400">
            <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
            <p class="text-sm font-medium">Belum ada produk terlaris yang tersedia.</p>
        </div>
    <?php
    }
    ?>

  </div>
</section>

    <!-- Section Content -->
    <section class="max-w-7xl mx-auto px-4 my-12">
      <div
        class="bg-gradient-to-r from-blue-700 to-blue-500 text-white rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between shadow-md"
      >
        <div class="flex items-center space-x-4 mb-4 sm:mb-0">
          <div class="p-4 bg-blue-600/50 rounded-xl">
            <i data-lucide="truck" class="w-8 h-8"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold">Gratis Ongkir</h3>
            <p class="text-blue-100 text-sm">
              Untuk pembelian minimal Rp 200.000
            </p>
          </div>
        </div>
        <button
          class="bg-white text-blue-600 font-bold px-6 py-3 rounded-xl shadow-sm hover:bg-blue-50 transition flex items-center gap-2 text-sm"
        >
          Belanja Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
      </div>
    </section>

    <!-- Section Content -->
    <section class="max-w-7xl mx-auto px-4 my-16 text-center">
      <h2 class="text-3xl font-bold text-blue-950 mb-10">
        Mengapa Memilih Kami?
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="space-y-3">
          <div
            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto"
          >
            <i data-lucide="anchor" class="w-8 h-8"></i>
          </div>
          <h4 class="font-bold text-blue-950 text-base">
            Langsung dari Nelayan
          </h4>
          <p class="text-xs text-gray-500 max-w-xs mx-auto">
            Kami bekerja sama langsung dengan nelayan lokal untuk menjaga
            kualitas terbaik.
          </p>
        </div>
        <div class="space-y-3">
          <div
            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto"
          >
            <i data-lucide="thermometer-snowflake" class="w-8 h-8"></i>
          </div>
          <h4 class="font-bold text-blue-950 text-base">
            Rantai Dingin Terjaga
          </h4>
          <p class="text-xs text-gray-500 max-w-xs mx-auto">
            Dikemas dengan standar cold chain untuk menjaga kesegaran hingga ke
            tangan Anda.
          </p>
        </div>
        <div class="space-y-3">
          <div
            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto"
          >
            <i data-lucide="award" class="w-8 h-8"></i>
          </div>
          <h4 class="font-bold text-blue-950 text-base">Kualitas Premium</h4>
          <p class="text-xs text-gray-500 max-w-xs mx-auto">
            Dipilih dengan teliti untuk memastikan hanya produk terbaik untuk
            Anda.
          </p>
        </div>
        <div class="space-y-3">
          <div
            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto"
          >
            <i data-lucide="heart-handshake" class="w-8 h-8"></i>
          </div>
          <h4 class="font-bold text-blue-950 text-base">Kepuasan Terjamin</h4>
          <p class="text-xs text-gray-500 max-w-xs mx-auto">
            Kepuasan Anda adalah prioritas kami. Garansi uang kembali jika tidak
            puas.
          </p>
        </div>
      </div>
    </section>

<!-- Section Content -->
    <section id="metode-pembayaran" class="max-w-7xl mx-auto px-4 my-16">
      <h2 class="text-3xl font-bold text-blue-950 text-center mb-8">
          Metode Pembayaran
      </h2>

      <!-- Baris 1 -->
      <div class="overflow-hidden payment-section mb-15">
          <div class="flex w-max items-center gap-15 marquee-right">
              <!-- Logo -->
              <img src="assets/img/payment-method/bca.jpg" class="h-12 w-32 object-contain scale-175">
              <img src="assets/img/payment-method/bni.png" class="h-12 w-32 object-contain">
              <img src="assets/img/payment-method/bri2.png" class="h-12 w-32 mr-4 object-contain scale-250">
              <img src="assets/img/payment-method/dana.png" class="h-12 w-32 mr-4 object-contain scale-200">
              <img src="assets/img/payment-method/ovo.png" class="h-12 w-32 object-contain scale-125">

              <!-- Duplikat -->
              <img src="assets/img/payment-method/bca.jpg" class="h-12 w-32 object-contain scale-175">
              <img src="assets/img/payment-method/bni.png" class="h-12 w-32 object-contain">
              <img src="assets/img/payment-method/bri2.png" class="h-12 w-32 mr-4 object-contain scale-250">
              <img src="assets/img/payment-method/dana.png" class="h-12 w-32 mr-4 object-contain scale-200">
              <img src="assets/img/payment-method/ovo.png" class="h-12 w-32 object-contain scale-125">
          </div>
      </div>

      <!-- Baris 2 -->
      <div class="overflow-hidden payment-section">
          <div class="flex w-max items-center gap-15 marquee-left">
              <img src="assets/img/payment-method/mandiri.png" class="h-12 w-32 mr-8 object-contain scale-125">
              <img src="assets/img/payment-method/gopay.webp" class="h-12 w-32 object-contain scale-125">
              <img src="assets/img/payment-method/shopeepay.webp" class="h-12 w-32 object-contain scale-125">
              <img src="assets/img/payment-method/qris.png" class="h-12 w-32 object-contain scale-250">
              <img src="assets/img/payment-method/cod.png" class="h-12 w-32 object-contain scale-125">

              <!-- Duplikat -->
              <img src="assets/img/payment-method/mandiri.png" class="h-12 w-32 mr-8 object-contain scale-125">
              <img src="assets/img/payment-method/gopay.webp" class="h-12 w-32 object-contain scale-125">
              <img src="assets/img/payment-method/shopeepay.webp" class="h-12 w-32 object-contain scale-125">
              <img src="assets/img/payment-method/qris.png" class="h-12 w-32 object-contain scale-250">
              <img src="assets/img/payment-method/cod.png" class="h-12 w-32 object-contain scale-125">
          </div>
      </div>
    </section>

    <!-- Section Footer -->
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
                <a href="pages/products.php" class="hover:text-blue-400">Produk</a>
              </li>
              <li>
                <a href="pages/kategori.php" class="hover:text-blue-400">Kategori</a>
              </li>
              <li>
                <a href="pages/tentang-kami.php" class="hover:text-blue-400">Tentang Kami</a>
              </li>
              <li>
                <a href="pages/kontak.php" class="hover:text-blue-400"
                  >Kontak</a
                >
              </li>
            </ul>
          </div>

          <!-- Bantuan -->
          <div>
            <h4 class="font-bold mb-4 text-lg">Bantuan</h4>

            <ul class="space-y-2 text-sm text-gray-300">
              <li>
                <a href="pages/cara-belanja.php" class="hover:text-blue-400">Cara Belanja</a>
              </li>
              <!-- <li>
                <a href="#" class="hover:text-blue-400">Pengiriman</a>
              </li> -->
              <li>
                <a href="pages/kebijakan-privasi.php" class="hover:text-blue-400">Kebijakan Privasi</a>
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
            <!-- <a href="#" class="hover:text-blue-400">Privasi</a> -->
          </div>
        </div>
      </div>
    </footer>

    <script>
      lucide.createIcons();
    </script>

    <script src="assets/js/slider.js"></script>
  </body>
</html>
