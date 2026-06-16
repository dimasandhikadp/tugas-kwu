<?php
session_start();
include '../config/koneksi.php';

/** @var mysqli $conn */

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang + JOIN tabel gambar (mengambil 1 gambar pertama per produk)
$query = "SELECT ci.id AS cart_item_id, p.nama_produk, p.harga, ci.qty, p.berat, p.satuan, pi.nama_file
          FROM cart_items ci
          JOIN cart c ON ci.cart_id = c.id
          JOIN products p ON ci.product_id = p.id
          LEFT JOIN (
              SELECT product_id, MIN(nama_file) AS nama_file 
              FROM product_images 
              GROUP BY product_id
          ) pi ON p.id = pi.product_id
          WHERE c.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="../index.php" class="p-2 hover:bg-gray-100 rounded-full transition text-blue-950">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <h1 class="text-xl font-bold text-blue-950">Keranjang Saya</h1>
            </div>
        </div>
    </header>

    <form action="checkout.php" method="POST" id="form-cart">
        <main class="max-w-3xl mx-auto px-4 py-6 space-y-4 mb-24">
            
            <?php if(mysqli_num_rows($result) == 0): ?>
                <div class="text-center py-10">
                    <i data-lucide="shopping-cart" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <p class="text-gray-500 font-medium">Keranjang Anda masih kosong.</p>
                </div>
            <?php endif; ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex gap-4 items-center cart-item" data-price="<?= $row['harga']; ?>">
                
                <input type="checkbox" name="selected_items[]" value="<?= $row['cart_item_id']; ?>" 
                       class="w-5 h-5 accent-blue-600 cursor-pointer item-checkbox" onchange="calculateTotal()">
                
                <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 flex items-center justify-center">
                    <?php if (!empty($row['nama_file']) && file_exists("../assets/img/product-image/" . $row['nama_file'])): ?>
                        <img src="../assets/img/product-image/<?= $row['nama_file']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i data-lucide="fish" class="w-8 h-8 text-blue-500/50"></i>
                    <?php endif; ?>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($row['nama_produk']); ?></h4>
                    <p class="text-xs text-slate-400 mt-0.5">Berat: <?= $row['berat']; ?> <?= htmlspecialchars($row['satuan']); ?></p>
                    
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-blue-600 font-extrabold text-sm">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                        
                        <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                            <button type="button" onclick="updateQty(this, -1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold transition text-xs cursor-pointer">-</button>
                            
                            <span class="px-3 py-1 text-xs font-semibold bg-white text-slate-700 min-w-[24px] text-center qty-text"><?= $row['qty']; ?></span>
                            
                            <input type="hidden" name="qty[<?= $row['cart_item_id']; ?>]" value="<?= $row['qty']; ?>" class="qty-input">
                            
                            <button type="button" onclick="updateQty(this, 1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold transition text-xs cursor-pointer">+</button>
                        </div>
                    </div>
                </div>
            </section>
            <?php endwhile; ?>
            
        </main>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-xl z-30">
            <div class="max-w-3xl mx-auto px-4 h-20 flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-xs text-gray-400 font-medium">Total Belanja</span>
                    <span class="text-xl font-extrabold text-blue-600" id="totalDisplay">Rp 0</span>
                </div>
                <button type="submit" class="bg-blue-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-blue-700 shadow-md active:scale-[0.98] transition cursor-pointer">
                    Checkout
                </button>
            </div>
        </div>
    </form>

    <script>
        // Mengubah kuantitas item
        function updateQty(btn, delta) {
            let container = btn.parentElement;
            let qtyEl = container.querySelector('.qty-text');
            let hiddenInput = container.querySelector('.qty-input');
            
            let qty = parseInt(qtyEl.innerText) + delta;
            
            if (qty >= 1) {
                qtyEl.innerText = qty;
                hiddenInput.value = qty; // Perbarui value input agar terbaca di checkout.php
                calculateTotal();
            }
        }

        // Menghitung akumulasi total harga dari item yang dicentang saja
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                let checkbox = item.querySelector('.item-checkbox');
                
                if (checkbox.checked) {
                    let price = parseInt(item.getAttribute('data-price'));
                    let qty = parseInt(item.querySelector('.qty-text').innerText);
                    total += (price * qty);
                }
            });
            document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Proteksi: Mencegah submit kosong ke halaman checkout
        document.getElementById('form-cart').addEventListener('submit', function(e) {
            let checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault(); // Batalkan pengiriman form
                alert('Pilih minimal satu produk terlebih dahulu untuk checkout!');
            }
        });

        // Load awal ikon dan setup kalkulasi awal
        document.addEventListener('DOMContentLoaded', () => {
            calculateTotal();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>