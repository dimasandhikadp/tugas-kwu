<?php
include '../config/koneksi.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    // Simpan URL asal untuk redirect setelah login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    echo "<script>
            window.location.href = '../auth/auth.php';
          </script>";
    exit;
}

/** @var mysqli $conn */

if (!isset($_GET['produk']) || empty($_GET['produk'])) {
    header("Location: index.php");
    exit;
}

$slug_produk = mysqli_real_escape_string($conn, $_GET['produk']);

$query_produk = "SELECT * FROM products WHERE slug = '$slug_produk' AND status = 'aktif' LIMIT 1";
$result_produk = mysqli_query($conn, $query_produk);

if (mysqli_num_rows($result_produk) == 0) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result_produk);

$query_gambar = "SELECT nama_file FROM product_images WHERE product_id = '{$row['id']}' ORDER BY id ASC";
$result_gambar = mysqli_query($conn, $query_gambar);

$daftar_gambar = [];
while ($img = mysqli_fetch_assoc($result_gambar)) {
    $daftar_gambar[] = $img['nama_file'];
}

$icon_kategori = 'fish';
if (isset($row['kategori'])) {
    $kat = strtolower($row['kategori']);
    if (str_contains($kat, 'udang')) $icon_kategori = 'shrimp';
    if (str_contains($kat, 'kepiting')) $icon_kategori = 'crab';
}

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
                <?php if (!empty($row['asal_produk'])): ?>
                    <span class="text-slate-500 font-medium">Asal: <?= htmlspecialchars($row['asal_produk']); ?></span>
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

            <form action="checkout.php" method="GET" id="form-checkout">
                <input type="hidden" name="product_id" value="<?= $row['id']; ?>"> <input type="hidden" name="slug" value="<?= $row['slug']; ?>">
                
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center mb-4">
                    <div class="flex items-center border border-slate-300 rounded-lg h-11 bg-white justify-between sm:justify-start">
                        <button type="button" class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-l-lg transition cursor-pointer" onclick="updateQty(-1)">-</button>
                        <input type="number" id="product-qty" name="qty" value="1" min="1" max="<?= $row['stok']; ?>" readonly class="w-10 text-center font-semibold text-slate-800 border-none outline-none">
                        <button type="button" class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-r-lg transition cursor-pointer" onclick="updateQty(1)">+</button>
                    </div>
                    
                    <button type="button" id="btn-add-cart" class="flex-1 h-11 bg-blue-600 text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition shadow-sm shadow-blue-600/10 cursor-pointer">
                        <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                    </button>
                </div>
                
                <button type="submit" class="w-full h-11 bg-white text-blue-600 font-semibold border-2 border-blue-600 rounded-lg text-sm hover:bg-blue-50 transition cursor-pointer">Beli Sekarang</button>
            </form>
        </div>
    </main>
    
    <section class="max-w-7xl mx-auto px-4 my-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-950">Produk Serupa</h2>
        <a href="kategori.php" class="text-blue-600 font-semibold text-sm hover:underline flex items-center gap-1">
            Lihat Semua Produk
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        <?php
        $kategori_sekarang = mysqli_real_escape_string($conn, $row['kategori']);
        $query_serupa = "SELECT p.*, (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                         FROM products p 
                         WHERE p.kategori = '$kategori_sekarang' AND p.id != '{$row['id']}' AND p.status = 'aktif'
                         ORDER BY RAND() LIMIT 5";
        $result_serupa = mysqli_query($conn, $query_serupa);

        if (mysqli_num_rows($result_serupa) > 0) {
            while ($row_serupa = mysqli_fetch_assoc($result_serupa)) {
                $s_badge_text = !empty($row_serupa['badge']) ? htmlspecialchars($row_serupa['badge']) : 'SEGAR';
                $s_badge_class = ($s_badge_text === 'TERLARIS') ? 'bg-blue-50 text-blue-600 border-blue-100' : (($s_badge_text === 'PROMO') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100');
        ?>
            <a href="product-details.php?produk=<?= $row_serupa['slug']; ?>" class="w-full bg-white border border-slate-200 rounded-2xl p-3.5 hover:shadow-sm hover:-translate-y-[2px] transition-all duration-200 relative flex flex-col group">
                
                <div class="absolute top-3 left-3 z-10">
                    <span class="<?= $s_badge_class; ?> text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border">
                        <?= $s_badge_text; ?>
                    </span>
                </div>

                <div class="w-full aspect-square flex items-center justify-center overflow-hidden rounded-xl mb-2.5 bg-slate-50 relative">
                    <?php if (!empty($row_serupa['gambar_utama']) && file_exists("../assets/img/product-image/" . $row_serupa['gambar_utama'])): ?>
                        <img src="../assets/img/product-image/<?= $row_serupa['gambar_utama']; ?>" alt="<?= htmlspecialchars($row_serupa['nama_produk']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-blue-500/70 p-1">
                            <i data-lucide="fish" class="w-14 h-14"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-0.5 mb-2.5 flex-1 flex flex-col">
                    <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2" title="<?= htmlspecialchars($row_serupa['nama_produk']); ?>">
                        <?= htmlspecialchars($row_serupa['nama_produk']); ?>
                    </h4>
                    
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
                echo '<div class="col-span-full py-8 text-center text-slate-400">Belum ada produk serupa lainnya.</div>';
            }
            ?>
        </div>
    </section>
    
    <script>

        // Simpan URL halaman ini setiap kali user membuka detail produk
        localStorage.setItem('lastProductPage', window.location.href);

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

   document.getElementById('btn-add-cart').addEventListener('click', function() {
    let form = document.getElementById('form-checkout');
    let formData = new FormData();
    
    formData.append('product_id', form.querySelector('input[name="product_id"]').value);
    formData.append('qty', document.getElementById('product-qty').value);

    fetch('../actions/add-to-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Ambil respon teks dari PHP (added/updated)
    .then(status => {
        let badge = document.querySelector('.absolute.top-0.right-0');
        let qtyInput = parseInt(document.getElementById('product-qty').value);

        if (status.includes("added")) {
            // PRODUK BARU: Tambah ke total badge
            if (badge) {
                let newQty = parseInt(badge.innerText) + qtyInput;
                badge.innerText = (newQty > 99) ? "99+" : newQty;
            } else {
                // Buat badge baru jika belum ada
                let cartIcon = document.querySelector('[data-lucide="shopping-cart"]').parentElement;
                let newBadge = document.createElement('span');
                newBadge.className = 'absolute top-0 right-0 bg-blue-600 text-white text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center rounded-full';
                newBadge.innerText = qtyInput;
                cartIcon.appendChild(newBadge);
            }
            // alert('Berhasil ditambahkan ke keranjang!');
        } else if (status.includes("updated")) {
            // PRODUK UPDATE: Jangan tambah total badge, tapi kasih tau user
            // alert('Jumlah produk di keranjang telah diperbarui!');
        }
    })
    .catch(error => console.error('Error:', error));
});
    </script>

    <?php
        include '../includes/footer.php';
        include '../includes/script.php';         
    ?>
</body>
</html>