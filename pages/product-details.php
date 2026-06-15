<?php
// 1. Hubungkan ke database Anda
include '../config/koneksi.php'; // Sesuaikan dengan jalur file koneksi Anda

/** @var mysqli $conn */

// 2. Ambil parameter 'produk' (slug) dari URL (Contoh: detail.php?produk=ikan-kakap-merah)
if (!isset($_GET['produk']) || empty($_GET['produk'])) {
    header("Location: index.php");
    exit;
}

$slug_produk = mysqli_real_escape_string($conn, $_GET['produk']);

// 3. Ambil data produk utama berdasarkan slug yang aktif
$query_produk = "SELECT * FROM products WHERE slug = '$slug_produk' AND status = 'aktif' LIMIT 1";
$result_produk = mysqli_query($conn, $query_produk);

// Jika slug salah atau produk tidak aktif, kembalikan ke halaman depan
if (mysqli_num_rows($result_produk) == 0) {
    echo "<script>alert('Produk tidak ditemukan atau sudah tidak tersedia!'); window.location='index.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result_produk);

// 4. Ambil semua koleksi foto produk untuk fitur galeri dari tabel product_images
$query_gambar = "SELECT nama_file FROM product_images WHERE product_id = '{$row['id']}' ORDER BY id ASC";
$result_gambar = mysqli_query($conn, $query_gambar);

$daftar_gambar = [];
while ($img = mysqli_fetch_assoc($result_gambar)) {
    $daftar_gambar[] = $img['nama_file'];
}

// Setel fallback ikon bawaan jika data gambar kosong di database
$icon_kategori = 'fish';
if (isset($row['kategori'])) {
    $kat = strtolower($row['kategori']);
    if (str_contains($kat, 'udang')) $icon_kategori = 'shrimp';
    if (str_contains($kat, 'kepiting')) $icon_kategori = 'crab';
}

// 5. Konfigurasi warna & teks badge
$badge_text = !empty($row['badge']) ? htmlspecialchars($row['badge']) : 'SEGAR';
$badge_class = ($badge_text === 'TERLARIS') 
    ? 'bg-blue-600 text-white' 
    : (($badge_text === 'PROMO') ? 'bg-red-600 text-white' : 'bg-green-600 text-white');
?>
<!DOCTYPE html>
<html lang="id">
    <?php include '../includes/head.php'; ?>
<body class="bg-gray-50 text-gray-800 font-sans">
        
    <?php include '../includes/header.php'; ?>
    
    <main class="max-w-6xl mx-auto mt-5 px-5 grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        <div class="flex flex-col gap-4">
            <div class="relative bg-white border border-slate-200 rounded-2xl overflow-hidden aspect-[4/3] flex items-center justify-center">
                <?php if (!empty($daftar_gambar) && file_exists("../assets/img/product-image/" . $daftar_gambar[0])): ?>
                    <img id="main-product-img" src="../assets/img/product-image/<?= $daftar_gambar[0]; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1 bg-slate-50">
                        <i data-lucide="<?= $icon_kategori; ?>" class="w-24 h-24"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (count($daftar_gambar) > 1): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($daftar_gambar as $index => $nama_file): 
                    $active_border = ($index === 0) ? 'border-blue-500 ring-2 ring-blue-500/10' : 'border-slate-200 hover:border-blue-500';
                    if (file_exists("../assets/img/product-image/" . $nama_file)):
                ?>
                    <div class="bg-white border <?= $active_border; ?> rounded-xl aspect-square cursor-pointer overflow-hidden flex items-center justify-center transition thumb-box" 
                         onclick="changeImage('../assets/img/product-image/<?= $nama_file; ?>', this)">
                        <img src="../assets/img/product-image/<?= $nama_file; ?>" class="w-full h-full object-cover">
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col">
            
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="<?= $badge_class; ?> text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm"><?= $badge_text; ?></span>
                <span class="bg-blue-50 text-blue-700 text-[10px] font-semibold px-2.5 py-1 rounded-md flex items-center gap-1 border border-blue-100">
                    <i class="fa-solid fa-snowflake"></i> 100% SEGAR
                </span>
            </div>

            <h1 class="text-3xl font-bold text-slate-900 leading-tight mb-1"><?= htmlspecialchars($row['nama_produk']); ?></h1>
            
            <p class="text-sm text-slate-400 mb-5">
                ± <?= number_format($row['berat'], 0); ?> <?= htmlspecialchars($row['satuan'] ?? 'kg'); ?> • Tangkapan Harian
                <?php if (!empty($row['asal_produk'])): ?>
                    • <span class="text-slate-500 font-medium">Asal: <?= htmlspecialchars($row['asal_produk']); ?></span>
                <?php endif; ?>
            </p>
            
            <div class="text-3xl font-bold text-slate-900 pb-4 mb-5 border-b border-slate-100">
                Rp <?= number_format($row['harga'], 0, ',', '.'); ?> <span class="text-base text-slate-400 font-normal">/ <?= htmlspecialchars($row['satuan'] ?? 'kg'); ?></span>
            </div>
            
            <div class="text-sm font-semibold text-slate-800 mb-2">Deskripsi Produk</div>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                <?= nl2br(htmlspecialchars($row['deskripsi'])); ?>
            </p>

            <div class="flex gap-8 pb-5 mb-6 border-b border-slate-100">
                <div class="flex items-center gap-3 text-sm font-semibold text-slate-800">
                    <i class="fa-solid fa-truck-fast text-xl text-blue-600"></i>
                    <div>
                        Pengiriman Cepat
                        <span class="block text-xs text-slate-400 font-normal mt-0.5">Sameday / Nextday</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm font-semibold text-slate-800">
                    <i class="fa-solid fa-shield-halved text-xl text-blue-600"></i>
                    <div>
                        Garansi Segar
                        <span class="block text-xs text-slate-400 font-normal mt-0.5">Uang kembali jika rusak</span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center mb-4">
                <div class="flex items-center border border-slate-300 rounded-lg h-11 bg-white justify-between sm:justify-start">
                    <button class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-l-lg transition" onclick="updateQty(-1)">-</button>
                    <input type="number" id="product-qty" value="1" min="1" max="<?= $row['stok']; ?>" readonly class="w-10 text-center font-semibold text-slate-800 border-none outline-none">
                    <button class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-r-lg transition" onclick="updateQty(1)">+</button>
                </div>
                <button class="flex-1 h-11 bg-blue-600 text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition shadow-sm shadow-blue-600/10">
                    <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                </button>
            </div>
            
            <button class="w-full h-11 bg-white text-blue-600 font-semibold border-2 border-blue-600 rounded-lg text-sm hover:bg-blue-50 transition">Beli Sekarang</button>
        </div>
    </main>
    
    <section class="max-w-7xl mx-auto px-4 my-12">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-950">Produk Serupa</h2>
        <a href="produk.php" class="text-blue-600 font-semibold text-sm hover:underline flex items-center gap-1">
          Lihat Semua Produk
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        <?php
        $kategori_sekarang = mysqli_real_escape_string($conn, $row['kategori']);
        // Query memastikan p.id != produk saat ini agar tidak terjadi duplikasi tampilan
        $query_serupa = "SELECT p.*, 
                        (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                        FROM products p 
                        WHERE p.kategori = '$kategori_sekarang' AND p.id != '{$row['id']}' AND p.status = 'aktif'
                        ORDER BY RAND() LIMIT 5";
        $result_serupa = mysqli_query($conn, $query_serupa);

        if (mysqli_num_rows($result_serupa) > 0) {
            while ($row_serupa = mysqli_fetch_assoc($result_serupa)) {
                $s_badge_text = !empty($row_serupa['badge']) ? htmlspecialchars($row_serupa['badge']) : 'SEGAR';
                $s_badge_class = ($s_badge_text === 'TERLARIS') 
                    ? 'bg-blue-50 text-blue-600 border-blue-100' 
                    : (($s_badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
                
                $s_icon = 'fish';
                if (isset($row_serupa['kategori'])) {
                    $s_kat = strtolower($row_serupa['kategori']);
                    if (str_contains($s_kat, 'udang')) $s_icon = 'shrimp';
                    if (str_contains($s_kat, 'kepiting')) $s_icon = 'crab';
                }
        ?>
                <a href="product-details.php?produk=<?= $row_serupa['slug']; ?>" class="w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-sm hover:-translate-y-[2px] transition-all duration-200 relative flex flex-col group">
                  <div class="absolute top-3 left-3 z-10">
                    <span class="<?= $s_badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border">
                      <?= $s_badge_text; ?>
                    </span>
                  </div>

                  <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50">
                    <?php if (!empty($row_serupa['gambar_utama']) && file_exists("../assets/img/product-image/" . $row_serupa['gambar_utama'])): ?>
                        <img src="../assets/img/product-image/<?= $row_serupa['gambar_utama']; ?>" alt="<?= htmlspecialchars($row_serupa['nama_produk']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1">
                            <i data-lucide="<?= $s_icon; ?>" class="w-14 h-14"></i>
                        </div>
                    <?php endif; ?>
                  </div>

                  <div class="space-y-0.5 mb-2.5 flex-1 flex flex-col">
                    <h4 class="font-bold text-slate-800 text-sm leading-snug truncate" title="<?= htmlspecialchars($row_serupa['nama_produk']); ?>">
                        <?= htmlspecialchars($row_serupa['nama_produk']); ?>
                    </h4>
                    <p class="text-[11px] text-slate-400 mt-auto font-medium">
                        <?= number_format($row_serupa['berat'], 0); ?> <?= htmlspecialchars($row_serupa['satuan'] ?? 'kg'); ?>
                    </p>
                  </div>

                  <div class="flex items-center justify-between pt-1 border-t border-slate-50 mt-1">
                    <div>
                      <span class="text-blue-600 font-extrabold text-sm md:text-base block leading-tight">
                          Rp <?= number_format($row_serupa['harga'], 0, ',', '.'); ?>
                      </span>
                      <span class="text-[10px] text-slate-400">/<?= htmlspecialchars($row_serupa['satuan'] ?? 'kg'); ?></span>
                    </div>
                    
                    <div class="text-right max-w-[50%]">
                      <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded truncate block" title="<?= htmlspecialchars($row_serupa['asal_produk'] ?? 'Lokal'); ?>">
                        <?= !empty($row_serupa['asal_produk']) ? htmlspecialchars($row_serupa['asal_produk']) : 'Lokal'; ?>
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
                <p class="text-sm font-medium">Belum ada produk serupa lainnya yang tersedia.</p>
            </div>
        <?php
        }
        ?>
      </div>
    </section>
    
    <script>
        function changeImage(imgUrl, element) {
            document.getElementById('main-product-img').src = imgUrl;
            
            const thumbs = document.querySelectorAll('.thumb-box');
            thumbs.forEach(thumb => {
                thumb.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500/10');
                thumb.classList.add('border-slate-200');
            });
            
            element.classList.remove('border-slate-200');
            element.classList.add('border-blue-500', 'ring-2', 'ring-blue-500/10');
        }

        function updateQty(change) {
            const qtyInput = document.getElementById('product-qty');
            let currentQty = parseInt(qtyInput.value);
            const maxStok = parseInt(qtyInput.getAttribute('max')) || 1;
            
            currentQty += change;
            
            if (currentQty < 1) currentQty = 1;
            if (currentQty > maxStok) currentQty = maxStok;
            
            qtyInput.value = currentQty;
        }
    </script>

    <?php
        include '../includes/footer.php';
        include '../includes/script.php';         
    ?>
</body>
</html>