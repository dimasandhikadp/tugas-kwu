<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Segar - Kategori Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <script src="https://unpkg.com/lucide@latest"></script>
  </head>
  <body class="bg-slate-50 text-slate-700 antialiased font-['Inter'] pb-16">
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
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

        <nav class="hidden md:flex space-x-6 text-sm font-medium text-gray-600">
          <a href="../index.html" class="group relative hover:text-blue-600">
            Beranda
            <span class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"></span>
          </a>
          <a href="../pages/tentang-kami.php" class="group relative hover:text-blue-600">
            Tentang Kami
            <span class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"></span>
          </a>
          <a href="../pages/cara-belanja.php" class="group relative hover:text-blue-600">
            Cara Belanja
            <span class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"></span>
          </a>
          <a href="../pages/kontak.php" class="group relative hover:text-blue-600">
            Kontak
            <span class="absolute left-1/2 -bottom-1 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full group-hover:left-0"></span>
          </a>
        </nav>

        <div class="flex items-center space-x-4">
          <div class="relative hidden sm:block">
            <input
              type="text"
              placeholder="Cari produk laut segar..."
              class="bg-white text-sm px-4 py-2 pr-10 rounded-full w-80 shadow-sm border border-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <i data-lucide="search" class="absolute right-3 top-2.5 text-gray-400 w-4 h-4"></i>
          </div>
          <div class="relative">
            <button class="text-gray-600 hover:text-blue-600">
              <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            </button>
            <span class="absolute -top-1 -right-2 bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">2</span>
          </div>
          <button class="text-gray-600 hover:text-blue-600">
            <i data-lucide="user" class="w-6 h-6"></i>
          </button>
        </div>
      </div>
    </header>

    <main class="max-w-6xl mx-auto mt-8 px-5">
      <section class="max-w-7xl mx-auto px-4 my-12">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-blue-950">Kategori Produk</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
          <div
            id="btn-ikan"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="ikan"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/icon-ikan.png" alt="Ikan" class="w-20 h-20 object-contain" />
            </div>
            <h4 class="font-bold text-sm text-blue-950">Ikan</h4>
            <p class="text-xs text-gray-400">25+ Produk</p>
          </div>

          <div
            id="btn-udang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="udang"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/icon-udang.png" alt="Udang" class="w-20 h-20 object-contain" />
            </div>
            <h4 class="font-bold text-sm text-blue-950">Udang</h4>
            <p class="text-xs text-gray-400">15+ Produk</p>
          </div>

          <div
            id="btn-kepiting"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="kepiting"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/icon-kepiting.png" alt="Kepiting" class="w-20 h-20 object-contain" />
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kepiting</h4>
            <p class="text-xs text-gray-400">10+ Produk</p>
          </div>

          <div
            id="btn-cumi"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="cumi"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/icon-sotong.png" alt="Cumi & Sotong" class="w-20 h-20 object-contain" />
            </div>
            <h4 class="font-bold text-sm text-blue-950">Cumi & Sotong</h4>
            <p class="text-xs text-gray-400">12+ Produk</p>
          </div>

          <div
            id="btn-kerang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="kerang"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/icon-kerang.png" alt="Kerang" class="w-20 h-20 object-contain" />
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kerang</h4>
            <p class="text-xs text-gray-400">8+ Produk</p>
          </div>
        </div>
      </section>

      <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="ikan">
          <div class="w-full h-44 bg-blue-50 rounded-xl mb-4 flex items-center justify-center text-blue-500">
            <i data-lucide="fish" class="w-16 h-16"></i>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Ikan Kakap Merah</h3>
          <p class="text-xs text-gray-400 mb-3">~ 800 gr / ekor</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 85.000</span>
              <span class="text-xs text-gray-400">/ekor</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="ikan">
          <div class="w-full h-44 bg-blue-50 rounded-xl mb-4 flex items-center justify-center text-blue-500">
            <i data-lucide="fish" class="w-16 h-16"></i>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Fillet Salmon Premium</h3>
          <p class="text-xs text-gray-400 mb-3">~ 500 gr / pack</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 145.000</span>
              <span class="text-xs text-gray-400">/pack</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="udang">
          <div class="w-full h-44 bg-amber-50 rounded-xl mb-4 flex items-center justify-center text-amber-600">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Udang Vaname Jumbo</h3>
          <p class="text-xs text-gray-400 mb-3">~ 1 kg / pack</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 95.000</span>
              <span class="text-xs text-gray-400">/kg</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="udang">
          <div class="w-full h-44 bg-amber-50 rounded-xl mb-4 flex items-center justify-center text-amber-600">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Udang Windu Segar</h3>
          <p class="text-xs text-gray-400 mb-3">~ 1 kg / pack</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 110.000</span>
              <span class="text-xs text-gray-400">/kg</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="kepiting">
          <div class="w-full h-44 bg-orange-50 rounded-xl mb-4 flex items-center justify-center text-orange-500">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
            </svg>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Kepiting Bakau</h3>
          <p class="text-xs text-gray-400 mb-3">~ 500 gr / ekor</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 70.000</span>
              <span class="text-xs text-gray-400">/ekor</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="cumi">
          <div class="w-full h-44 bg-purple-50 rounded-xl mb-4 flex items-center justify-center text-purple-500">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Cumi-Cumi Segar</h3>
          <p class="text-xs text-gray-400 mb-3">~ 1 kg / pack</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 75.000</span>
              <span class="text-xs text-gray-400">/kg</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="product-card bg-white border border-slate-100 p-4 rounded-2xl shadow-xs" data-category="kerang">
          <div class="w-full h-44 bg-orange-50 rounded-xl mb-4 flex items-center justify-center text-orange-500">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
          </div>
          <h3 class="font-bold text-blue-950 text-base mb-1">Kerang Hijau Bersih</h3>
          <p class="text-xs text-gray-400 mb-3">~ 1 kg / pack</p>
          <div class="flex justify-between items-center">
            <div>
              <span class="text-blue-600 font-extrabold text-base">Rp 45.000</span>
              <span class="text-xs text-gray-400">/kg</span>
            </div>
            <button class="bg-blue-600 text-white p-2 rounded-xl hover:bg-blue-700">
              <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

      </div>
    </main>

    <script>
      const categoryButtons = document.querySelectorAll(".category-btn");
      const products = document.querySelectorAll(".product-card");

      function filterCategory(selectedCategory) {
        categoryButtons.forEach((btn) => {
          if (btn.dataset.category === selectedCategory) {
            btn.classList.remove("border-gray-100");
            btn.classList.add("border-blue-500", "shadow-lg");
          } else {
            btn.classList.remove("border-blue-500", "shadow-lg");
            btn.classList.add("border-gray-100");
          }
        });

        products.forEach((product) => {
          if (product.dataset.category === selectedCategory) {
            product.classList.remove("hidden");
          } else {
            product.classList.add("hidden");
          }
        });
      }

      categoryButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const selectedCategory = button.dataset.category;
          filterCategory(selectedCategory);
          
          const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?c=' + selectedCategory;
          window.history.pushState({path:newUrl}, '', newUrl);
        });
      });

      window.addEventListener("DOMContentLoaded", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('c');

        if (categoryParam) {
          filterCategory(categoryParam);
        } else {
          filterCategory("ikan"); 
        }
      });

      lucide.createIcons();
    </script>
  </body>
</html>