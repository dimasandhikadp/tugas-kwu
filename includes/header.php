

<!-- Section Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div
        class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between"
      >
        <div class="flex items-center space-x-1">
          <img
            src="../assets/img/logo.png"
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
          <a href="../index.php" class="group relative hover:text-blue-600">
            Beranda
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a
            href="../pages/tentang-kami.php"
            class="group relative hover:text-blue-600"
          >
            Tentang Kami
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a
            href="../pages/cara-belanja.php"
            class="group relative hover:text-blue-600"
          >
            Cara Belanja
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
          <a href="../pages/kontak.php" class="group relative hover:text-blue-600">
            Kontak
            <span
              class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"
            ></span>
          </a>
        </nav>

        <div class="flex items-center space-x-4">
          <!-- Search -->
          <form action="search.php" method="GET" class="relative hidden sm:block">
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
          <a
          href="cart.php"
          class="relative text-gray-600 hover:text-blue-600 transition p-2 rounded-full hover:bg-blue-50"
        >
          <i data-lucide="shopping-cart" class="w-6 h-6"></i>

          <span
            class="absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full"
          >
            2
          </span>
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
            href="../admin/products/index.php"
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
            href="../auth/logout.php"
            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 text-red-600"
          >
            <i data-lucide="log-out" class="w-4 h-4"></i>
            <span>Logout</span>
          </a>

        <?php else: ?>

          <!-- Login -->
          <a
            href="../auth/auth.php"
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