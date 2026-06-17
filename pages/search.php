<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../config/koneksi.php';

/** @var mysqli $conn */

$search_query = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$query = "SELECT p.*, 
          (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
          FROM products p 
          WHERE p.status = 'aktif' 
          AND (p.nama_produk LIKE '%$search_query%' OR p.slug LIKE '%$search_query%')
          ORDER BY p.id DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
    <?php include '../includes/head.php'; ?>
<body class="bg-gray-50 text-gray-800 font-sans">
    <?php include '../includes/header.php'; ?>
    
    <main class="max-w-7xl mx-auto px-4 my-12">
        <h2 class="text-2xl font-bold text-blue-950 mb-6">
            Hasil Pencarian: <span class="text-blue-600">"<?= htmlspecialchars($search_query); ?>"</span>
        </h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $badge_text = !empty($row['badge']) ? htmlspecialchars($row['badge']) : 'SEGAR';
                    $badge_class = ($badge_text === 'TERLARIS') ? 'bg-blue-50 text-blue-600 border-blue-100' : (($badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
                    $icon = str_contains(strtolower($row['kategori'] ?? ''), 'udang') ? 'shrimp' : (str_contains(strtolower($row['kategori'] ?? ''), 'kepiting') ? 'crab' : 'fish');
                ?>
                <a href="product-details.php?produk=<?= $row['slug']; ?>" class="w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-sm hover:-translate-y-[2px] transition-all duration-200 relative flex flex-col group">
                    
                    <div class="absolute top-3 left-3 z-10">
                        <span class="<?= $badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border">
                            <?= $badge_text; ?>
                        </span>
                    </div>

                    <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50 relative">
                        <?php if (!empty($row['gambar_utama']) && file_exists("../assets/img/product-image/" . $row['gambar_utama'])): ?>
                            <img src="../assets/img/product-image/<?= $row['gambar_utama']; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1">
                                <i data-lucide="<?= $icon; ?>" class="w-14 h-14"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-0.5 mb-2.5 flex-1 flex flex-col">
                        <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2" title="<?= htmlspecialchars($row['nama_produk']); ?>">
                            <?= htmlspecialchars($row['nama_produk']); ?>
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-auto font-medium">
                            <?= number_format($row['berat'], 0); ?> <?= htmlspecialchars($row['satuan'] ?? 'kg'); ?>
                        </p>
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
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <i data-lucide="search-x" class="w-16 h-16 text-slate-300 mx-auto mb-4"></i>
                <h3 class="text-xl font-bold text-slate-700">Produk tidak ditemukan</h3>
                <p class="text-slate-500">Coba kata kunci lain atau periksa kembali ejaan Anda.</p>
                <a href="../index.php" class="mt-6 inline-block text-blue-600 font-semibold hover:underline">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include '../includes/footer.php'; include '../includes/script.php'; ?>
</body>
</html>