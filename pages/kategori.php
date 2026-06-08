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
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center text-blue-600">
              <i data-lucide="fish" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Ikan</h4>
            <p class="text-xs text-gray-400">25+ Produk</p>
          </div>

          <div
            id="btn-udang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="udang"
          >
            <div class="w-24 h-20 bg-amber-50 rounded-lg mb-3 flex items-center justify-center text-amber-600">
              <i data-lucide="shrimp" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Udang</h4>
            <p class="text-xs text-gray-400">15+ Produk</p>
          </div>

          <div
            id="btn-kepiting"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="kepiting"
          >
            <div class="w-24 h-20 bg-red-50 rounded-lg mb-3 flex items-center justify-center text-red-500">
              <i data-lucide="crab" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kepiting</h4>
            <p class="text-xs text-gray-400">10+ Produk</p>
          </div>

          <div
            id="btn-cumi"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="cumi"
          >
            <div class="w-24 h-20 bg-purple-50 rounded-lg mb-3 flex items-center justify-center text-purple-500">
              <i data-lucide="squid" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Cumi & Sotong</h4>
            <p class="text-xs text-gray-400">12+ Produk</p>
          </div>

          <div
            id="btn-kerang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition flex flex-col justify-between items-center cursor-pointer"
            data-category="kerang"
          >
            <div class="w-24 h-20 bg-orange-50 rounded-lg mb-3 flex items-center justify-center text-orange-500">
              <i data-lucide="shell" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kerang</h4>
            <p class="text-xs text-gray-400">8+ Produk</p>
          </div>
        </div>
      </section>

      <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="ikan">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=300&q=80" alt="Kakap" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Ikan Kakap Merah Segar</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 85.000 <span class="text-xs text-slate-400 font-normal">/ekor</span></div>
        </div>
        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="ikan">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=300&q=80" alt="Salmon" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Fillet Salmon Premium</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 145.000 <span class="text-xs text-slate-400 font-normal">/500g</span></div>
        </div>

        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="udang">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&w=300&q=80" alt="Udang" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Udang Vaname Jumbo</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 95.000 <span class="text-xs text-slate-400 font-normal">/kg</span></div>
        </div>
        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="udang">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1551248429-40975aa4de74?auto=format&fit=crop&w=300&q=80" alt="Udang Windu" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Udang Windu Segar</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 110.000 <span class="text-xs text-slate-400 font-normal">/kg</span></div>
        </div>

        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="kepiting">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1559737558-2f5a35f4523b?auto=format&fit=crop&w=300&q=80" alt="Kepiting" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Kepiting Bakau Hidup</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 120.000 <span class="text-xs text-slate-400 font-normal">/kg</span></div>
        </div>

        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="cumi">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1510130387422-82bed34b37e9?auto=format&fit=crop&w=300&q=80" alt="Cumi" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Cumi-Cumi Serokan Nelayan</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 75.000 <span class="text-xs text-slate-400 font-normal">/kg</span></div>
        </div>

        <div class="product-card bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-xs" data-category="kerang">
          <div class="aspect-[4/3] overflow-hidden rounded-lg mb-3">
            <img src="https://images.unsplash.com/photo-1559847844-5315695dadae?auto=format&fit=crop&w=300&q=80" alt="Kerang" class="w-full h-full object-cover" />
          </div>
          <div class="text-sm font-semibold text-slate-800">Kerang Hijau Cuci Bersih</div>
          <div class="text-base font-bold text-slate-900 mt-2">Rp 45.000 <span class="text-xs text-slate-400 font-normal">/kg</span></div>
        </div>

      </div>
    </main>

    <script>
      const categoryButtons = document.querySelectorAll(".category-btn");
      const products = document.querySelectorAll(".product-card");

      // Fungsi Inti Filter Kategori Dinamis
      function filterCategory(selectedCategory) {
        // Atur style border & shadow tombol kategori aktif
        categoryButtons.forEach((btn) => {
          if (btn.dataset.category === selectedCategory) {
            btn.classList.remove("border-gray-100");
            btn.classList.add("border-blue-500", "shadow-lg");
          } else {
            btn.classList.remove("border-blue-500", "shadow-lg");
            btn.classList.add("border-gray-100");
          }
        });

        // Tampilkan/sembunyikan produk berdasarkan kecocokan data attribute
        products.forEach((product) => {
          if (product.dataset.category === selectedCategory) {
            product.classList.remove("hidden");
          } else {
            product.classList.add("hidden");
          }
        });
      }

      // 1. Event listener ketika tombol kategori diklik manual di halaman ini
      categoryButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const selectedCategory = button.dataset.category;
          filterCategory(selectedCategory);
          
          // Opsional: Perbarui URL agar selaras tanpa memuat ulang halaman
          const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?c=' + selectedCategory;
          window.history.pushState({path:newUrl}, '', newUrl);
        });
      });

      // 2. Membaca parameter Query URL "?c=..." saat halaman diakses pertama kali
      window.addEventListener("DOMContentLoaded", () => {
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('c');

        // Jika ada parameter valid gunakan itu, jika tidak ada, default ke 'ikan'
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