<?php
// 1. Hubungkan ke database Anda
include '../config/koneksi.php'; // Sesuaikan dengan jalur file koneksi Anda
session_start();

/** @var mysqli $conn */

// Ambil kategori dari URL jika ada untuk digunakan sebagai default state di PHP (opsional)
$kategori_aktif = isset($_GET['c']) ? strtolower(trim($_GET['c'])) : 'semua';
?>
<!DOCTYPE html>
<html lang="id">
  <?php include '../includes/head.php' ?>
  <style>
    /* Style pembantu untuk memastikan transisi filter berjalan mulus dan instan */
    .filter-hidden {
        display: none !important;
    }
  </style>

  <body class="bg-gray-50 text-gray-800 font-sans">

  <?php include '../includes/header.php' ?>
    
    <main class="max-w-7xl mx-auto mt-8 px-5 my-12">
      
      <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-blue-950">Kategori Produk</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
          <div
            id="btn-semua"
            data-target-cat="semua"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center text-blue-600">
              <i data-lucide="grid" class="w-10 h-10"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Semua</h4>
            <p class="text-xs text-gray-500 font-semibold">Tampilkan Semua</p>
          </div>

          <div
            id="btn-ikan"
            data-target-cat="ikan"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/kategori/ikan.png" alt="Ikan" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
              <i data-lucide="fish" class="w-10 h-10 text-blue-600 hidden"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Ikan</h4>
            <p class="text-xs text-gray-400">Produk Pilihan</p>
          </div>

          <div
            id="btn-udang"
            data-target-cat="udang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/kategori/udang.png" alt="Udang" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
              <i data-lucide="shrimp" class="w-10 h-10 text-blue-600 hidden"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Udang</h4>
            <p class="text-xs text-gray-400">Produk Pilihan</p>
          </div>

          <div
            id="btn-kepiting"
            data-target-cat="kepiting"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/kategori/kepiting.png" alt="Kepiting" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
              <i data-lucide="crab" class="w-10 h-10 text-blue-600 hidden"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kepiting</h4>
            <p class="text-xs text-gray-400">Produk Pilihan</p>
          </div>

          <div
            id="btn-cumi"
            data-target-cat="cumi"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/kategori/cumi.png" alt="Cumi & Sotong" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
              <i data-lucide="squid" class="w-10 h-10 text-blue-600 hidden"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Cumi & Sotong</h4>
            <p class="text-xs text-gray-400">Produk Pilihan</p>
          </div>

          <div
            id="btn-kerang"
            data-target-cat="kerang"
            class="category-btn bg-white border border-gray-100 p-4 rounded-2xl text-center transition-all duration-200 flex flex-col justify-between items-center cursor-pointer select-none hover:shadow-md hover:border-blue-200"
            onclick="filterCategory(this)"
          >
            <div class="w-24 h-20 bg-blue-50 rounded-lg mb-3 flex items-center justify-center">
              <img src="../assets/img/kategori/kerang.png" alt="Kerang" class="w-20 h-20 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
              <i data-lucide="shell" class="w-10 h-10 text-blue-600 hidden"></i>
            </div>
            <h4 class="font-bold text-sm text-blue-950">Kerang</h4>
            <p class="text-xs text-gray-400">Produk Pilihan</p>
          </div>
        </div>
      </section>

      <div class="flex justify-between items-center mb-6">
        <h2 id="section-title" class="text-2xl font-bold text-blue-950">Daftar Produk</h2>
      </div>

      <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        <?php
        $query_produk = "SELECT p.*, 
                         (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                         FROM products p 
                         WHERE p.status = 'aktif' 
                         ORDER BY p.id DESC";
        $result_produk = mysqli_query($conn, $query_produk);

        if (mysqli_num_rows($result_produk) > 0) {
            while ($row = mysqli_fetch_assoc($result_produk)) {

                // --- PEMETAAN KATEGORI DATA ---
                $kategori_katalog = 'lainnya';
                
                if (!empty($row['kategori'])) {
                    $kat_lower = strtolower(trim($row['kategori']));
                    
                    if (strpos($kat_lower, 'udang') !== false) {
                        $kategori_katalog = 'udang';
                    } elseif (strpos($kat_lower, 'kepiting') !== false || strpos($kat_lower, 'rajungan') !== false) {
                        $kategori_katalog = 'kepiting';
                    } elseif (strpos($kat_lower, 'cumi') !== false || strpos($kat_lower, 'sotong') !== false || strpos($kat_lower, 'gurita') !== false) {
                        $kategori_katalog = 'cumi';
                    } elseif (strpos($kat_lower, 'kerang') !== false || strpos($kat_lower, 'tiram') !== false) {
                        $kategori_katalog = 'kerang';
                    } elseif (strpos($kat_lower, 'ikan') !== false) {
                        $kategori_katalog = 'ikan';
                    } else {
                        $kategori_katalog = 'lainnya';
                    }
                }

                $badge_text = !empty($row['badge']) ? htmlspecialchars($row['badge']) : 'SEGAR';
                $badge_class = ($badge_text === 'TERLARIS') 
                    ? 'bg-blue-50 text-blue-600 border-blue-100' 
                    : (($badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
                
                $icon = 'fish';
                if ($kategori_katalog === 'udang') $icon = 'shrimp';
                if ($kategori_katalog === 'kepiting') $icon = 'crab';
                if ($kategori_katalog === 'cumi') $icon = 'squid';
                if ($kategori_katalog === 'kerang') $icon = 'shell';
        ?>
            <a href="product-details.php?produk=<?= $row['slug']; ?>" class="product-card w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-md hover:-translate-y-[2px] active:translate-y-0 active:scale-[0.99] transition-all duration-150 relative flex flex-col group select-none outline-none" data-cat-name="<?= $kategori_katalog; ?>">
              
              <div class="absolute top-3 left-3 z-10">
                <span class="<?= $badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border">
                  <?= $badge_text; ?>
                </span>
              </div>

              <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50 group-hover:opacity-90 transition-opacity">
                <?php if (!empty($row['gambar_utama']) && file_exists("../assets/img/product-image/" . $row['gambar_utama'])): ?>
                    <img src="../assets/img/product-image/<?= $row['gambar_utama']; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1">
                        <i data-lucide="<?= $icon; ?>" class="w-14 h-14"></i>
                    </div>
                <?php endif; ?>
              </div>

              <div class="space-y-0.5 mb-2.5 flex-1 flex flex-col">
                <h4 class="font-bold text-slate-800 text-sm leading-snug truncate group-hover:text-blue-600 transition-colors" title="<?= htmlspecialchars($row['nama_produk']); ?>">
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
            </a>
        <?php 
            }
        } else {
        ?>
            <div class="col-span-full border-2 border-dashed border-slate-200 rounded-2xl py-12 text-center text-slate-400">
                <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
                <p class="text-sm font-medium">Belum ada produk yang tersedia saat ini.</p>
            </div>
        <?php
        }
        ?>
      </div>
    </main>

    <?php
      include '../includes/footer.php';
      // include '../includes/script.php';
    ?>

    <script>
    function filterCategory(elementOrString) {
        let targetCategory = 'semua';
        let targetElement = null;

        // Mendeteksi apakah fungsi dipanggil dengan mengklik elemen (this) atau string langsung saat loading
        if (typeof elementOrString === 'string') {
            targetCategory = elementOrString;
            targetElement = document.querySelector(`.category-btn[data-target-cat="${targetCategory}"]`);
        } else if (elementOrString && elementOrString.getAttribute) {
            targetCategory = elementOrString.getAttribute('data-target-cat');
            targetElement = elementOrString;
        }

        // 1. Sinkronisasi parameter ke URL
        const url = new URL(window.location);
        if (targetCategory === 'semua') {
            url.searchParams.delete('c'); 
        } else {
            url.searchParams.set('c', targetCategory); 
        }
        window.history.pushState({}, '', url);

        // 2. Reset visual seluruh tombol kategori
        const buttons = document.querySelectorAll('.category-btn');
        buttons.forEach(btn => {
            // Hapus style aktif yang tebal
            btn.classList.remove('bg-blue-50', 'border-blue-400', 'ring-4', 'ring-blue-100', 'shadow-md');
            // Kembalikan ke style normal
            btn.classList.add('bg-white', 'border-gray-100');
        });

        // 3. Set visual tombol AKTIF (yang sedang dipilih)
        if (targetElement) {
            targetElement.classList.remove('bg-white', 'border-gray-100');
            // Tambahkan style aktif yang sangat menonjol menggunakan Tailwind
            targetElement.classList.add('bg-blue-50', 'border-blue-400', 'ring-4', 'ring-blue-100', 'shadow-md');
        }

        // 4. Saring Kartu Produk Berdasarkan Atribut Data
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            const cardCategory = card.getAttribute('data-cat-name');
            if (targetCategory === 'semua' || cardCategory === targetCategory) {
                card.classList.remove('filter-hidden');
            } else {
                card.classList.add('filter-hidden');
            }
        });

        // 5. Update Judul Komponen Secara Dinamis
        const sectionTitle = document.getElementById('section-title');
        if (targetCategory === 'semua') {
            sectionTitle.textContent = 'Daftar Produk';
        } else {
            const displayTitle = targetCategory.charAt(0).toUpperCase() + targetCategory.slice(1);
            sectionTitle.textContent = 'Daftar Produk ' + displayTitle;
        }

        // 6. Re-render Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // --- INITIAL LOAD ---
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        let categoryParam = urlParams.get('c');
        
        // Membersihkan potensi string 'undefined' yang macet di cache/URL sebelumnya
        if (!categoryParam || categoryParam === 'undefined' || categoryParam.trim() === '') {
            filterCategory('semua');
        } else {
            // Validasi apakah kategori di URL benar-benar ada tombolnya
            const categoryClean = categoryParam.toLowerCase().trim();
            const exists = document.querySelector(`.category-btn[data-target-cat="${categoryClean}"]`);
            
            if (exists) {
                filterCategory(categoryClean);
            } else {
                filterCategory('semua'); // Default aman
            }
        }
    });
    </script>
  </body>
</html>