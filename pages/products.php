<?php
include '../config/koneksi.php'; 

/** @var mysqli $conn */

$default_limit = 10;
// Kita ambil limit saat ini dari URL, jika tidak ada pakai default 10
$current_limit = isset($_GET['limit']) ? (int)$_GET['limit'] : $default_limit;

$query = "SELECT p.*, 
          (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
          FROM products p 
          WHERE p.status = 'aktif' 
          ORDER BY p.id DESC LIMIT $current_limit";
$result = mysqli_query($conn, $query);

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE status = 'aktif'");
$total_data = mysqli_fetch_assoc($total_query)['total'];
?>
<!DOCTYPE html>
<html lang="id">
    <?php include '../includes/head.php'; ?>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php include '../includes/header.php'; ?>
    
    <main class="max-w-7xl mx-auto px-4 my-12">
        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $badge_text = !empty($row['badge']) ? htmlspecialchars($row['badge']) : 'SEGAR';
                    $badge_class = ($badge_text === 'TERLARIS') ? 'bg-blue-50 text-blue-600 border-blue-100' : (($badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
                    $icon = str_contains(strtolower($row['kategori'] ?? ''), 'udang') ? 'shrimp' : (str_contains(strtolower($row['kategori'] ?? ''), 'kepiting') ? 'crab' : 'fish');
            ?>
            <a href="product-details.php?produk=<?= $row['slug']; ?>" class="w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-sm hover:-translate-y-[2px] transition-all duration-200 relative flex flex-col group">
                <div class="absolute top-3 left-3 z-10">
                    <span class="<?= $badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border"><?= $badge_text; ?></span>
                </div>
                <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50">
                    <?php if (!empty($row['gambar_utama']) && file_exists("../assets/img/product-image/" . $row['gambar_utama'])): ?>
                        <img src="../assets/img/product-image/<?= $row['gambar_utama']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i data-lucide="<?= $icon; ?>" class="w-14 h-14 text-blue-500/70"></i>
                    <?php endif; ?>
                </div>
                <div class="space-y-0.5 mb-2.5 flex-1">
                    <h4 class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($row['nama_produk']); ?></h4>
                    <p class="text-[11px] text-slate-400"><?= number_format($row['berat'], 0); ?> <?= htmlspecialchars($row['satuan'] ?? 'kg'); ?></p>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-slate-50">
                    <span class="text-blue-600 font-extrabold text-sm">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded"><?= htmlspecialchars($row['asal_produk'] ?? 'Lokal'); ?></span>
                </div>
            </a>
            <?php 
                }
            }
            ?>
        </div>

        <?php if ($current_limit < $total_data): ?>
            <div id="load-more-trigger" class="py-10 text-center">
                <p class="text-slate-400 font-medium">Sedang memuat produk lainnya...</p>
            </div>
            
            <script>
            // Intersection Observer untuk mendeteksi saat div 'load-more-trigger' masuk ke viewport
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    // Redirect ke URL dengan limit bertambah +10
                    window.location.href = "?limit=<?= $current_limit + 10; ?>";
                }
            }, { threshold: 1.0 });

            observer.observe(document.querySelector('#load-more-trigger'));
            </script>
        <?php endif; ?>
    </main>

    <?php 
        include '../includes/footer.php';
        include '../includes/script.php'; 
    ?>
</body>
</html>