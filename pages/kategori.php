<!DOCTYPE html>
<html lang="id">

  <?php
    include '../includes/head.php'
  ?>

  <body class="bg-gray-50 text-gray-800 font-sans">

  <?php
    include '../includes/header.php'
  ?>
    
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
              <img src="../assets/img/kategori/ikan.png" alt="Ikan" class="w-20 h-20 object-contain" />
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
              <img src="../assets/img/kategori/udang.png" alt="Udang" class="w-20 h-20 object-contain" />
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
              <img src="../assets/img/kategori/kepiting.png" alt="Kepiting" class="w-20 h-20 object-contain" />
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
              <img src="../assets/img/kategori/cumi.png" alt="Cumi & Sotong" class="w-20 h-20 object-contain" />
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
              <img src="../assets/img/kategori/kerang.png" alt="Kerang" class="w-20 h-20 object-contain" />
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

    <?php
      include '../includes/footer.php';
      include '../includes/script.php';
    ?>
    
  </body>
</html>