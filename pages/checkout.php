<?php
// Pastikan session dimulai jika belum ada di koneksi.php untuk mengambil user_id
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/koneksi.php';

/** @var mysqli $conn */

// Ambil user_id dari session login (default ke 1 jika belum login)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

// --- PROSES SIMPAN ALAMAT (TANPA AJAX) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_alamat'])) {
    $nama_penerima = mysqli_real_escape_string($conn, $_POST['nama_penerima']);
    $no_hp         = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $provinsi      = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $kota          = mysqli_real_escape_string($conn, $_POST['kota']);
    $kecamatan     = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $kelurahan     = mysqli_real_escape_string($conn, $_POST['kelurahan']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (!empty($nama_penerima) && !empty($no_hp) && !empty($kelurahan) && !empty($alamat)) {
        // Reset default alamat lama milik user ini terlebih dahulu
        mysqli_query($conn, "UPDATE addresses SET is_default = 0 WHERE user_id = '$user_id'");

        // Query simpan alamat baru sebagai default (is_default = 1)
        $query_save = "INSERT INTO addresses (user_id, nama_penerima, no_hp, alamat, kota, kecamatan, kelurahan, provinsi, is_default) 
                       VALUES ('$user_id', '$nama_penerima', '$no_hp', '$alamat', '$kota', '$kecamatan', '$kelurahan', '$provinsi', 1)";
        mysqli_query($conn, $query_save);
    }
}

// --- OTOMATIS LOAD ALAMAT DEFAULT SAAT BUKA HALAMAN ---
$alamat_terpanah = null;
$query_load_alamat = "SELECT * FROM addresses WHERE user_id = '$user_id' AND is_default = 1 LIMIT 1";
$res_alamat = mysqli_query($conn, $query_load_alamat);

if ($res_alamat && mysqli_num_rows($res_alamat) > 0) {
    $data_adr = mysqli_fetch_assoc($res_alamat);
    $alamat_terpanah = [
        'nama' => $data_adr['nama_penerima'],
        'telp' => $data_adr['no_hp'],
        'wilayah' => $data_adr['provinsi'] . ", " . $data_adr['kota'] . ", " . $data_adr['kecamatan'] . ", " . $data_adr['kelurahan'],
        'jalan' => $data_adr['alamat']
    ];
}

// --- AMBIL DATA PRODUK ---
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';
$qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
if ($qty < 1) { $qty = 1; }

$nama_produk = "Produk Sampel Cincin Manik";
$harga_produk = 6160;
$berat_produk = 1;
$satuan_produk = "kg";
$gambar_utama = "";

if (!empty($slug)) {
    $query_p = "SELECT p.*, (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                FROM products p WHERE p.slug = '$slug' LIMIT 1";
    $res_p = mysqli_query($conn, $query_p);
    if (mysqli_num_rows($res_p) > 0) {
        $p_data = mysqli_fetch_assoc($res_p);
        $nama_produk = $p_data['nama_produk'];
        $harga_produk = $p_data['harga'];
        $berat_produk = $p_data['berat'];
        $satuan_produk = $p_data['satuan'] ?? 'kg';
        $gambar_utama = $p_data['gambar_utama'];
    }
}

// Kalkulasi Biaya Awal
$biaya_layanan = 2000;
$biaya_pengiriman = 20000;
$subtotal = $harga_produk * $qty;
$total_pembayaran = $subtotal + $biaya_layanan + $biaya_pengiriman;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .modal-active { overflow: hidden; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="javascript:history.back()" class="p-2 hover:bg-gray-100 rounded-full transition text-blue-950">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <h1 class="text-xl font-bold text-blue-950">Checkout</h1>
            </div>
            <div></div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6 space-y-4 mb-24">
        
        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-red-500"></div>
            <div class="flex items-center gap-2 mb-3 text-blue-950">
                <i data-lucide="map-pin" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-base">Alamat Pengiriman</h3>
            </div>
            
            <div onclick="openAddressModal()" class="border-2 border-dashed border-slate-200 hover:border-blue-400 p-4 rounded-xl cursor-pointer transition bg-slate-50/50 group">
                <div id="alamat-display" class="space-y-1 text-sm text-gray-600">
                    <?php if ($alamat_terpanah): ?>
                        <p class="font-bold text-blue-950 text-base" id="lbl-nama-telp"><?= htmlspecialchars($alamat_terpanah['nama']) . ' | ' . htmlspecialchars($alamat_terpanah['telp']); ?></p>
                        <p id="lbl-wilayah" class="text-xs text-gray-600 font-semibold"><?= htmlspecialchars($alamat_terpanah['wilayah']); ?></p>
                        <p id="lbl-detail" class="text-gray-800"><?= htmlspecialchars($alamat_terpanah['jalan']); ?></p>
                    <?php else: ?>
                        <p class="font-bold text-blue-950 text-base" id="lbl-nama-telp">Klik di sini untuk mengatur nama dan alamat pengiriman</p>
                        <p id="lbl-wilayah" class="text-xs text-gray-400">Provinsi, Kota, Kecamatan, Kelurahan</p>
                        <p id="lbl-detail" class="text-gray-500 italic">Belum ada detail alamat spesifik.</p>
                    <?php endif; ?>
                </div>
                <div class="mt-3 flex items-center justify-end text-xs text-blue-600 font-bold group-hover:underline">
                    Ubah Alamat <i data-lucide="chevron-right" class="w-4 h-4 ml-0.5"></i>
                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-50 pb-2">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-blue-950">Detail Pesanan</h3>
            </div>
            
            <div class="flex gap-4 items-start py-2">
                <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 flex items-center justify-center">
                    <?php if (!empty($gambar_utama) && file_exists("../assets/img/product-image/" . $gambar_utama)): ?>
                        <img src="../assets/img/product-image/<?= $gambar_utama; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i data-lucide="fish" class="w-8 h-8 text-blue-500/50"></i>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($nama_produk); ?></h4>
                    <p class="text-xs text-slate-400 mt-0.5">Berat: <?= $berat_produk; ?> <?= htmlspecialchars($satuan_produk); ?></p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-blue-600 font-extrabold text-sm">Rp <?= number_format($harga_produk, 0, ',', '.'); ?></span>
                        
                        <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                            <button type="button" onclick="updateQty(-1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold transition text-xs">-</button>
                            <span id="display-qty" class="px-3 py-1 text-xs font-semibold bg-white text-slate-700 min-w-[24px] text-center"><?= $qty; ?></span>
                            <button type="button" onclick="updateQty(1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold transition text-xs">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

                <!-- SECTION METODE PEMBAYARAN -->
        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                    <h3 class="font-bold text-blue-950">Metode Pembayaran</h3>
                </div>
                <!-- Opsi Lain ditaruh diseberang kanan dengan tulisan abu-abu tipis -->
                <button type="button" onclick="openPaymentModal()" class="text-xs font-medium text-gray-400 hover:text-gray-600 transition bg-transparent border-none p-0 outline-none">
                    Opsi Lain <i data-lucide="chevron-right" class="w-3 h-3 inline ml-0.5"></i>
                </button>
            </div>
            
            <div class="flex items-center gap-3 p-1">
                <img id="current-pay-img" src="../assets/img/payment-method/cod.png" class="h-7 w-16 object-contain">
                <div class="text-sm">
                    <p id="current-pay-title" class="font-bold text-blue-950">Cash on Delivery (COD)</p>
                    <p id="current-pay-desc" class="text-xs text-gray-400">Bayar tunai langsung di tempat</p>
                </div>
            </div>
            <input type="hidden" id="selected-payment-value" value="COD">
        </section>

        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-3 text-sm">
            <div class="flex items-center gap-2 border-b border-gray-50 pb-2 mb-1">
                <i data-lucide="receipt" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-blue-950">Rincian Pembayaran</h3>
            </div>
            <div class="flex justify-between text-gray-500">
                <span id="label-rincian-qty">Subtotal Pesanan (<?= $qty; ?> produk)</span>
                <span class="font-semibold text-gray-700" id="display-subtotal">Rp <?= number_format($subtotal, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Biaya Layanan</span>
                <span class="font-semibold text-gray-700">Rp <?= number_format($biaya_layanan, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Biaya Pengiriman</span>
                <span class="font-semibold text-gray-700">Rp <?= number_format($biaya_pengiriman, 0, ',', '.'); ?></span>
            </div>
            <hr class="border-gray-100 my-1">
            <div class="flex justify-between text-base font-bold text-blue-950 pt-1">
                <span>Total Pembayaran</span>
                <span class="text-blue-600 text-lg" id="display-total">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></span>
            </div>
        </section>
    </main>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-xl z-30">
        <div class="max-w-3xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs text-gray-400 font-medium">Total Belanja</span>
                <span class="text-xl font-extrabold text-blue-600" id="display-fixed-total">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></span>
            </div>
            <button type="button" onclick="prosesBuatPesanan()" class="bg-blue-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-blue-700 shadow-md active:scale-[0.98] transition cursor-pointer">
                Buat Pesanan
            </button>
        </div>
    </div>

    <div id="modal-alamat" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all">
        <form method="POST" action="" class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            <input type="hidden" name="action_alamat" value="1">
            <input type="hidden" name="provinsi" id="hidden-provinsi" value="">
            <input type="hidden" name="kota" id="hidden-kota" value="">
            <input type="hidden" name="kecamatan" id="hidden-kecamatan" value="">
            <input type="hidden" name="kelurahan" id="hidden-kelurahan" value="">

            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-blue-950 text-white">
                <h3 class="font-bold text-lg flex items-center gap-2"><i data-lucide="map-pin" class="w-5 h-5"></i> Atur Alamat Lengkap</h3>
                <button type="button" onclick="closeAddressModal()" class="p-1 hover:bg-white/10 rounded-full transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-4 overflow-y-auto space-y-4 hide-scrollbar flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama Penerima</label>
                        <input type="text" name="nama_penerima" id="inp-nama" placeholder="Contoh: Budi Santoso" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">No. HP / Telepon</label>
                        <input type="tel" name="no_hp" id="inp-telp" placeholder="Contoh: 08123456789" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500">Wilayah Pengiriman</label>
                    <div onclick="openRegionPicker()" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm flex items-center justify-between cursor-pointer bg-slate-50 hover:bg-slate-100/70 transition">
                        <span id="txt-region-summary" class="text-gray-400">Pilih Provinsi, Kota, Kecamatan, Kelurahan...</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Alamat Lengkap (Jalan, No. Rumah)</label>
                    <textarea name="alamat" id="inp-detail-jalan" rows="3" placeholder="Contoh: Jl. Ikan Tuna Raya No. 45, Blok C, RT 02/RW 05" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="p-4 bg-slate-50 border-t border-gray-100 flex items-center justify-between gap-3">
                <button type="button" onclick="resetFormAlamat()" class="px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition">Hapus</button>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md shadow-blue-600/10">Simpan Alamat</button>
            </div>
        </form>
    </div>

    <div id="modal-region" class="fixed inset-0 z-60 hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl max-h-[80vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-blue-600 text-white">
                <h3 id="region-modal-title" class="font-bold text-base">Pilih Provinsi</h3>
                <button type="button" onclick="closeRegionPicker()" class="p-1 hover:bg-white/10 rounded-full transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div id="region-list-container" class="overflow-y-auto divide-y divide-gray-50 max-h-[50vh] hide-scrollbar text-sm"></div>
        </div>
    </div>

    <!-- MODAL POP-UP METODE PEMBAYARAN BERKATEGORI -->
<div id="modal-pembayaran" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all">
    <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-blue-950 text-white">
            <h3 class="font-bold text-base flex items-center gap-2"><i data-lucide="credit-card" class="w-5 h-5"></i> Pilih Metode Pembayaran</h3>
            <button type="button" onclick="closePaymentModal()" class="p-1 hover:bg-white/10 rounded-full transition"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        
        <div class="p-4 overflow-y-auto space-y-4 hide-scrollbar flex-1 text-sm bg-slate-50/30">
            
            <!-- KATEGORI 1: TUNAI -->
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase px-2">Metode Tunai</span>
                <div class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-50 overflow-hidden shadow-xs">
                    <div onclick="selectPaymentOpt('cod', 'Cash on Delivery (COD)', 'Bayar tunai langsung di tempat', 'cod.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/cod.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">Cash on Delivery (COD)</span>
                        </div>
                        <div id="check-opt-cod" class="text-blue-600 font-bold"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                </div>
            </div>

            <!-- KATEGORI 2: E-WALLET -->
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase px-2">E-Wallet & QRIS</span>
                <div class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-50 overflow-hidden shadow-xs">
                    <!-- QRIS -->
                    <div onclick="selectPaymentOpt('qris', 'QRIS All Payment', 'E-Wallet / M-Banking', 'qris.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/qris.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">QRIS All Payment</span>
                        </div>
                        <div id="check-opt-qris" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- DANA -->
                    <div onclick="selectPaymentOpt('dana', 'DANA E-Wallet', 'Bayar instan pakai saldo DANA', 'dana.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/dana.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">DANA</span>
                        </div>
                        <div id="check-opt-dana" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- GOPAY -->
                    <div onclick="selectPaymentOpt('gopay', 'GOPAY E-Wallet', 'Bayar cepat pakai saldo GoPay', 'gopay.webp')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/gopay.webp" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">GoPay</span>
                        </div>
                        <div id="check-opt-gopay" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- OVO -->
                    <div onclick="selectPaymentOpt('ovo', 'OVO E-Wallet', 'Bayar mudah pakai saldo OVO', 'ovo.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/ovo.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">OVO</span>
                        </div>
                        <div id="check-opt-ovo" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- SHOPEEPAY -->
                    <div onclick="selectPaymentOpt('shopeepay', 'ShopeePay', 'Integrasi dompet ShopeePay', 'shopeepay.webp')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/shopeepay.webp" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">ShopeePay</span>
                        </div>
                        <div id="check-opt-shopeepay" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                </div>
            </div>

            <!-- KATEGORI 3: TRANSFER BANK -->
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase px-2">Transfer Bank (Virtual Account)</span>
                <div class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-50 overflow-hidden shadow-xs">
                    <!-- BCA -->
                    <div onclick="selectPaymentOpt('bca', 'BCA Virtual Account', 'Transfer otomatis bank BCA', 'bca.jpg')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/bca.jpg" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">BCA Virtual Account</span>
                        </div>
                        <div id="check-opt-bca" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- BNI -->
                    <div onclick="selectPaymentOpt('bni', 'BNI Virtual Account', 'Transfer otomatis bank BNI', 'bni.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/bni.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">BNI Virtual Account</span>
                        </div>
                        <div id="check-opt-bni" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- BRI -->
                    <div onclick="selectPaymentOpt('bri', 'BRI Virtual Account', 'Transfer otomatis bank BRI', 'bri.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/bri.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">BRI Virtual Account</span>
                        </div>
                        <div id="check-opt-bri" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <!-- MANDIRI -->
                    <div onclick="selectPaymentOpt('mandiri', 'Mandiri Virtual Account', 'Transfer otomatis bank Mandiri', 'mandiri.png')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3">
                            <img src="../assets/img/payment-method/mandiri.png" class="h-5 w-12 object-contain">
                            <span class="font-semibold text-blue-950">Mandiri Virtual Account</span>
                        </div>
                        <div id="check-opt-mandiri" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- POP-UP YAKIN PESAN -->
<div id="popup-konfirmasi" class="fixed inset-0 z-[100] hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-4">
    <div class="bg-white w-full sm:max-w-sm rounded-2xl p-6 shadow-2xl text-center space-y-4">
        <div class="mx-auto w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shadow-xs">
            <i data-lucide="shopping-bag" class="w-8 h-8"></i>
        </div>
        <div class="space-y-1">
            <h3 class="text-lg font-bold text-blue-950">Konfirmasi Pesanan</h3>
            <p class="text-xs text-gray-400 px-2">Apakah kamu yakin ingin memproses pesanan ini?</p>
        </div>
        <div class="flex flex-col gap-2 pt-2">
            <button type="button" onclick="pindahKeSukses()" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-sm shadow-md cursor-pointer hover:bg-blue-700">Ya, Buat Pesanan</button>
            <button type="button" onclick="document.getElementById('popup-konfirmasi').classList.add('hidden')" class="w-full text-xs font-semibold text-gray-400 py-2 cursor-pointer hover:text-gray-600">Periksa Kembali</button>
        </div>
    </div>
</div>

<div id="popup-sukses" class="fixed inset-0 z-[100] hidden bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full sm:max-w-xs rounded-2xl p-6 shadow-2xl text-center space-y-4">
        <div class="mx-auto w-14 h-14 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center shadow-xs">
            <i data-lucide="check-circle-2" class="w-8 h-8"></i>
        </div>
        <div class="space-y-1">
            <h3 class="text-base font-bold text-blue-950">Pesanan Berhasil!</h3>
            <p class="text-xs text-gray-400">Kembali ke produk dalam <span id="countdown-text" class="font-bold text-emerald-600">10</span> detik...</p>
        </div>
        <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
            <div id="countdown-bar" class="bg-emerald-500 h-full w-full rounded-full animate-countdown-bar"></div>
        </div>
        <button type="button" onclick="kembaliKeProduk()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-xl text-xs transition cursor-pointer">Selesai</button>
    </div>
</div>

    <script>
    const hargaProduk = <?= $harga_produk; ?>;
    const biayaLayanan = <?= $biaya_layanan; ?>;
    const biayaPengiriman = <?= $biaya_pengiriman; ?>;
    let currentQty = <?= $qty; ?>;

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateQty(change) {
        let newQty = currentQty + change;
        if (newQty < 1) return; 
        
        currentQty = newQty;
        
        const subtotal = hargaProduk * currentQty;
        const total = subtotal + biayaLayanan + biayaPengiriman;

        document.getElementById('display-qty').textContent = currentQty;
        document.getElementById('label-rincian-qty').textContent = `Subtotal Pesanan (${currentQty} produk)`;
        document.getElementById('display-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('display-total').textContent = formatRupiah(total);
        document.getElementById('display-fixed-total').textContent = formatRupiah(total);
    }

    // Seluruh wilayah 5 Provinsi Kalimantan
    const databaseWilayah = {
        "Kalimantan Barat": {
            "Pontianak": {
                "Pontianak Kota": ["Dararat Sekip", "Mariana", "Sungai Bangkong", "Sungai Jawi"],
                "Pontianak Tenggara": ["Bangka Belitung Darat", "Bangka Belitung Laut", "Bansir Darat"]
            },
            "Singkawang": {
                "Singkawang Barat": ["Pasiran", "Melayu", "Kuala", "Singkawang Dua"]
            }
        },
        "Kalimantan Selatan": {
            "Banjarmasin": {
                "Banjarmasin Tengah": ["Antasan Besar", "Gadang", "Kertak Baru Ilir", "Mawar"],
                "Banjarmasin Utara": ["Alalak Utara", "Antasan Kecil Timur", "Pangeran"]
            },
            "Banjarbaru": {
                "Landasan Ulin": ["Guntung Payung", "Guntung Manggis", "Syamsudin Noor"]
            }
        },
        "Kalimantan Tengah": {
            "Palangkaraya": {
                "Jekan Raya": ["Menteng", "Palangka", "Bukit Tunggal"],
                "Pahandut": ["Pahandut Seberang", "Panarung", "Langkai"]
            }
        },
        "Kalimantan Timur": {
            "Samarinda": {
                "Samarinda Kota": ["Bugis", "Karang Mumus", "Pelabuhan"],
                "Samarinda Ulu": ["Air Hitam", "Air Putih", "Sidodadi"]
            },
            "Balikpapan": {
                "Balikpapan Kota": ["Klandasan Ulu", "Klandasan Ilir", "Prapatan"]
            }
        },
        "Kalimantan Utara": {
            "Tarakan": {
                "Tarakan Barat": ["Karang Anyar", "Karang Balik", "Sebabu"],
                "Tarakan Timur": ["Lingkas Ujung", "Mamburungan", "Pamusian"]
            }
        }
    };

    let selectedProvinsi = "";
    let selectedKota = "";
    let selectedKecamatan = "";
    let selectedKelurahan = "";

    function openAddressModal() {
        document.getElementById('modal-alamat').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    function closeAddressModal() {
        document.getElementById('modal-alamat').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }
    function openRegionPicker() {
        document.getElementById('modal-region').classList.remove('hidden');
        renderProvinsiList();
    }
    function closeRegionPicker() {
        document.getElementById('modal-region').classList.add('hidden');
    }

    function renderProvinsiList() {
        document.getElementById('region-modal-title').textContent = "Pilih Provinsi";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        Object.keys(databaseWilayah).forEach(prov => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = prov;
            row.onclick = () => {
                selectedProvinsi = prov;
                document.getElementById('hidden-provinsi').value = prov;
                renderKotaList();
            };
            container.appendChild(row);
        });
    }

    // (Fungsi Region Picker tetap sama untuk mempermudah operasional)
    function renderKotaList() {
        document.getElementById('region-modal-title').textContent = "Pilih Kota/Kabupaten";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        Object.keys(databaseWilayah[selectedProvinsi]).forEach(kota => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = kota;
            row.onclick = () => {
                selectedKota = kota;
                document.getElementById('hidden-kota').value = kota;
                renderKecamatanList();
            };
            container.appendChild(row);
        });
    }

    function renderKecamatanList() {
        document.getElementById('region-modal-title').textContent = "Pilih Kecamatan";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        Object.keys(databaseWilayah[selectedProvinsi][selectedKota]).forEach(kec => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = kec;
            row.onclick = () => {
                selectedKecamatan = kec;
                document.getElementById('hidden-kecamatan').value = kec;
                renderKelurahanList();
            };
            container.appendChild(row);
        });
    }

    function renderKelurahanList() {
        document.getElementById('region-modal-title').textContent = "Pilih Kelurahan";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        databaseWilayah[selectedProvinsi][selectedKota][selectedKecamatan].forEach(kel => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = kel;
            row.onclick = () => {
                selectedKelurahan = kel;
                document.getElementById('hidden-kelurahan').value = kel;
                document.getElementById('txt-region-summary').textContent = `${selectedProvinsi}, ${selectedKota}, ${selectedKecamatan}, ${selectedKelurahan}`;
                document.getElementById('txt-region-summary').className = "text-gray-800 font-semibold";
                closeRegionPicker();
            };
            container.appendChild(row);
        });
    }

    function resetFormAlamat() {
        document.getElementById('inp-nama').value = "";
        document.getElementById('inp-telp').value = "";
        document.getElementById('inp-detail-jalan').value = "";
        document.getElementById('txt-region-summary').textContent = "Pilih Provinsi, Kota, Kecamatan, Kelurahan...";
        document.getElementById('txt-region-summary').className = "text-gray-400";
        document.getElementById('hidden-provinsi').value = "";
        document.getElementById('hidden-kota').value = "";
        document.getElementById('hidden-kecamatan').value = "";
        document.getElementById('hidden-kelurahan').value = "";
    }

    // --- FUNGSI POP-UP MODAL METODE PEMBAYARAN BARU ---
    function openPaymentModal() {
        document.getElementById('modal-pembayaran').classList.remove('hidden');
        document.body.classList.add('modal-active');
    }
    
    function closePaymentModal() {
        document.getElementById('modal-pembayaran').classList.add('hidden');
        document.body.classList.remove('modal-active');
    }

    function selectPaymentOpt(id, title, desc, imgFileName) {
        // Sembunyikan semua check icon di dalam list modal
        document.querySelectorAll('.pay-opt-row svg').forEach(svg => {
            svg.parentElement.classList.add('hidden');
        });
        
        // Tampilkan check icon pada pilihan aktif
        document.getElementById(`check-opt-${id}`).classList.remove('hidden');

        // Update tampilan utama di halaman checkout
        document.getElementById('current-pay-img').src = `../assets/img/payment-method/${imgFileName}`;
        document.getElementById('current-pay-title').textContent = title;
        document.getElementById('current-pay-desc').textContent = desc;
        
        // Set value input hidden untuk diproses pas buat pesanan
        document.getElementById('selected-payment-value').value = id.toUpperCase();
        
        // Tutup modal
        closePaymentModal();
    }

    function prosesBuatPesanan() {
    // Validasi alamat (seperti logika lamamu)
    const checkValidAlamat = document.getElementById('lbl-detail') ? document.getElementById('lbl-detail').classList.contains('italic') : true;
    if (checkValidAlamat) {
        alert("Harap lengkapi Alamat Pengiriman Anda terlebih dahulu!");
        openAddressModal();
        return;
    }
    
    // Tampilkan modal konfirmasi desain baru
    document.getElementById('popup-konfirmasi').classList.remove('hidden');
}

    // Fungsi untuk kembali ke halaman produk yang tersimpan
    function kembaliKeProduk() {
        const lastPage = localStorage.getItem('lastProductPage');
        window.location.href = lastPage || "index.php";
    }
    </script>
    

<script>
function pindahKeSukses() {
    document.getElementById('popup-konfirmasi').classList.add('hidden');
    document.getElementById('popup-sukses').classList.remove('hidden');
    
    let waktuSisa = 10;
    const textTimer = document.getElementById('countdown-text');
    const interval = setInterval(() => {
        waktuSisa--;
        textTimer.textContent = waktuSisa;
        if (waktuSisa <= 0) {
            clearInterval(interval);
            kembaliKeProduk();
        }
    }, 1000);
}
</script>

<?php
  include '../includes/script.php';         
?>
</body>
</html>