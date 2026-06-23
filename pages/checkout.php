<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';
/** @var mysqli $conn */

// Ambil data barang belanja dari cart.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_checkout'])) {
    $_SESSION['checkout_data'] = [
        'source'    => 'cart',
        'ids'       => implode(',', array_map('intval', $_POST['selected_items'])),
        'qty'       => $_POST['qty'] 
    ];
} elseif (isset($_GET['slug']) && isset($_GET['qty'])) {
    $_SESSION['checkout_data'] = null; 
}

$user_id = $_SESSION['user_id'] ?? 1;

// Simpan Alamat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_alamat'])) {
    $nama_penerima = mysqli_real_escape_string($conn, $_POST['nama_penerima']);
    $no_hp         = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $provinsi      = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $kota          = mysqli_real_escape_string($conn, $_POST['kota']);
    $kecamatan     = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $kelurahan     = mysqli_real_escape_string($conn, $_POST['kelurahan']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (!empty($nama_penerima) && !empty($no_hp) && !empty($kelurahan) && !empty($alamat)) {
        mysqli_query($conn, "UPDATE addresses SET is_default = 0 WHERE user_id = '$user_id'");
        $query_save = "INSERT INTO addresses (user_id, nama_penerima, no_hp, alamat, kota, kecamatan, kelurahan, provinsi, is_default) 
                       VALUES ('$user_id', '$nama_penerima', '$no_hp', '$alamat', '$kota', '$kecamatan', '$kelurahan', '$provinsi', 1)";
        mysqli_query($conn, $query_save);
    }
}

// Load Alamat yang tersimpan
$alamat_terpanah = null;
$res_alamat = mysqli_query($conn, "SELECT * FROM addresses WHERE user_id = '$user_id' AND is_default = 1 LIMIT 1");
if ($res_alamat && mysqli_num_rows($res_alamat) > 0) {
    $data_adr = mysqli_fetch_assoc($res_alamat);
    $alamat_terpanah = [
        'id'       => $data_adr['id'],
        'nama'     => $data_adr['nama_penerima'], 
        'telp'     => $data_adr['no_hp'],
        'wilayah'  => $data_adr['provinsi'] . ", " . $data_adr['kota'] . ", " . $data_adr['kecamatan'] . ", " . $data_adr['kelurahan'],
        'jalan'    => $data_adr['alamat'],
        'provinsi' => $data_adr['provinsi'],
        'kota'     => $data_adr['kota'],
        'kecamatan'=> $data_adr['kecamatan'],
        'kelurahan'=> $data_adr['kelurahan']
    ];
}

$items_checkout = [];
$subtotal_pesanan = 0;
$biaya_layanan = 2000;
$biaya_pengiriman = 20000;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $selected_ids = array_map('intval', $_POST['selected_items']);
    $qty_arr = $_POST['qty']; 
    $ids_string = implode(',', $selected_ids);
    
    $query_p = "SELECT ci.id as cart_item_id, p.*, 
            (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            JOIN cart c ON ci.cart_id = c.id
            WHERE ci.id IN ($ids_string) 
            AND c.user_id = '$user_id'";
            
    $res_p = mysqli_query($conn, $query_p);
    while ($row = mysqli_fetch_assoc($res_p)) {
        $qty = (int)($qty_arr[$row['cart_item_id']] ?? 1);
        $row['qty'] = $qty;
        $subtotal_pesanan += ($row['harga'] * $qty);
        $items_checkout[] = $row;
    }
    
    $cart_to_product = [];
    foreach ($items_checkout as $item) {
        $cart_to_product[$item['cart_item_id']] = $item['id']; 
    }
    $_SESSION['checkout_data']['cart_to_product'] = $cart_to_product;

} elseif (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    $qty = max(1, (int)($_GET['qty'] ?? 1));
    $query_p = "SELECT p.*, (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                FROM products p WHERE p.slug = '$slug' AND p.status = 'aktif' LIMIT 1";
    $res_p = mysqli_query($conn, $query_p);
    if ($row_direct = mysqli_fetch_assoc($res_p)) {
        $row_direct['qty'] = $qty;
        $subtotal_pesanan = $row_direct['harga'] * $qty;
        $items_checkout[] = $row_direct;
        
        $_SESSION['checkout_data'] = [
            'source'     => 'direct',
            'product_id' => (int)$row_direct['id'],
            'qty'        => $qty
        ];
    }
}

// AJAX: Buat Pesanan (simpan ke DB) 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_pembayaran'])) {
    if (isset($_SESSION['checkout_data'])) {
        $cd = $_SESSION['checkout_data'];
        $metode       = mysqli_real_escape_string($conn, $_POST['metode_pembayaran'] ?? 'COD');
        $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type'] ?? 'va'); // cod | va | ewallet
        $total_ajax   = (float)($_POST['total_pembayaran'] ?? 0);
        $alamat_id    = (int)($_POST['alamat_id'] ?? 0);

        // Ambil alamat_id default jika tidak dikirim
        if ($alamat_id === 0) {
            $ra = mysqli_query($conn, "SELECT id FROM addresses WHERE user_id='$user_id' AND is_default=1 LIMIT 1");
            if ($ra && $row_a = mysqli_fetch_assoc($ra)) {
                $alamat_id = (int)$row_a['id'];
            }
        }

        // Buat nomor virtual account unik (bank_kode + timestamp + user_id)
        $va_number = strtoupper(substr($metode, 0, 3)) . date('ymdHis') . $user_id;

        $status_awal = ($payment_type === 'cod') ? 'diproses' : 'pending';

        // Simpan order ke DB
        $q_order = "INSERT INTO orders (user_id, alamat_id, total_harga, status, metode_pembayaran, va_number, created_at) 
                    VALUES ('$user_id', '$alamat_id', '$total_ajax', '$status_awal', '$metode', '$va_number', NOW())";
        mysqli_query($conn, $q_order);
        $order_id = mysqli_insert_id($conn);

        if (!$order_id) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal membuat pesanan.']);
            exit;
        }

        // Log status awal
        mysqli_query($conn, "INSERT INTO order_status_logs (order_id, status) VALUES ('$order_id', '$status_awal')");

        // Simpan order_items & update stok
        if ($cd['source'] === 'direct') {
            $pid        = (int)$cd['product_id'];
            $qty_dibeli = (int)$cd['qty'];
            $res_p2 = mysqli_query($conn, "SELECT harga, stok FROM products WHERE id='$pid' LIMIT 1");
            if ($res_p2 && $p2 = mysqli_fetch_assoc($res_p2)) {
                $harga_sat = (float)$p2['harga'];
                $subtotal_item = $harga_sat * $qty_dibeli;
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, harga, qty, subtotal) VALUES ('$order_id','$pid','$harga_sat','$qty_dibeli','$subtotal_item')");                
                if ($p2['stok'] >= $qty_dibeli) {
                    mysqli_query($conn, "UPDATE products SET stok = stok - $qty_dibeli WHERE id='$pid'");
                }
            }
        } elseif ($cd['source'] === 'cart') {
            $cart_to_product = $cd['cart_to_product'] ?? [];
            $qty_arr_s       = $cd['qty'];
            $ids_string_s    = $cd['ids'];

            $res_ci = mysqli_query($conn, "SELECT ci.id as cart_item_id, ci.product_id, p.harga, p.stok 
                                           FROM cart_items ci 
                                           JOIN products p ON ci.product_id = p.id
                                           WHERE ci.id IN ($ids_string_s)");
            while ($ci = mysqli_fetch_assoc($res_ci)) {
                $cid        = $ci['cart_item_id'];
                $pid        = $ci['product_id'];
                $qty_dibeli = (int)($qty_arr_s[$cid] ?? 1);
                $harga_sat  = (float)$ci['harga'];
                $subtotal_item = $harga_sat * $qty_dibeli;
                mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, harga, qty, subtotal) VALUES ('$order_id','$pid','$harga_sat','$qty_dibeli','$subtotal_item')");                
                if ($ci['stok'] >= $qty_dibeli) {
                    mysqli_query($conn, "UPDATE products SET stok = stok - $qty_dibeli WHERE id='$pid'");
                }
            }
            mysqli_query($conn, "DELETE FROM cart_items WHERE id IN ($ids_string_s)");
        }

        unset($_SESSION['checkout_data']);

        echo json_encode([
            'status'     => 'success',
            'order_id'   => $order_id,
            'va_number'  => $va_number,
            'metode'     => $metode,
            'total'      => $total_ajax
        ]);
        exit;
    }
    echo json_encode(['status' => 'error', 'message' => 'Session habis.']);
    exit;
}

// AJAX: Cek status pembayaran 
if (isset($_GET['cek_status']) && isset($_GET['order_id'])) {
    $oid = (int)$_GET['order_id'];
    $res_s = mysqli_query($conn, "SELECT status FROM orders WHERE id='$oid' AND user_id='$user_id' LIMIT 1");
    if ($res_s && $row_s = mysqli_fetch_assoc($res_s)) {
        echo json_encode(['status' => $row_s['status']]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
    exit;
}

// AJAX: Konfirmasi pembayaran (simulasi berhasil, untuk VA & E-wallet)
if (isset($_GET['konfirmasi_bayar']) && isset($_GET['order_id'])) {
    $oid = (int)$_GET['order_id'];
    mysqli_query($conn, "UPDATE orders SET status='diproses' WHERE id='$oid' AND user_id='$user_id' AND status='pending'");
    mysqli_query($conn, "INSERT INTO order_status_logs (order_id, status) VALUES ('$oid', 'diproses')");
    echo json_encode(['ok' => true]);
    exit;
}

// AJAX: Batalkan order (ubah status jadi dibatalkan) 
if (isset($_GET['batalkan_order']) && isset($_GET['order_id'])) {
    $oid = (int)$_GET['order_id'];
    mysqli_query($conn, "UPDATE orders SET status='dibatalkan' WHERE id='$oid' AND user_id='$user_id' AND status='pending'");
    mysqli_query($conn, "INSERT INTO order_status_logs (order_id, status) VALUES ('$oid', 'dibatalkan')");
    echo json_encode(['ok' => true]);
    exit;
}

$total_pembayaran = $subtotal_pesanan + $biaya_layanan + $biaya_pengiriman;
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/head.php'; ?>
    
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
        
    <!-- Section Alamat -->
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

        <!-- Section List Barang yang dibeli -->
        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-50 pb-2">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-blue-950">Detail Pesanan</h3>
            </div>
            
            <?php foreach ($items_checkout as $item): ?>
            <div class="flex gap-4 items-start py-2">
                <div class="w-20 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100 flex items-center justify-center">
                    <?php if (!empty($item['gambar_utama']) && file_exists("../assets/img/product-image/" . $item['gambar_utama'])): ?>
                        <img src="../assets/img/product-image/<?= $item['gambar_utama']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i data-lucide="fish" class="w-8 h-8 text-blue-500/50"></i>
                    <?php endif; ?>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-slate-800 text-sm truncate"><?= htmlspecialchars($item['nama_produk']); ?></h4>
                    <p class="text-xs text-slate-400 mt-0.5">Asal Tangkapan: <?= $item['asal_produk']; ?></p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-blue-600 font-extrabold text-sm">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></span>
                        <span class="text-xs font-semibold text-slate-600 bg-gray-100 px-2 py-1 rounded">Qty: 
                            <span id="qty-<?= $item['id']; ?>"><?= $item['qty']; ?></span>
                        </span>
                    </div>
                    <div class="mt-1 text-xs text-slate-500">Subtotal: <span id="subtotal-<?= $item['id']; ?>" class="font-semibold text-slate-700">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?></span></div>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

        <!-- Section Metode Pembayaran -->
        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                    <h3 class="font-bold text-blue-950">Metode Pembayaran</h3>
                </div>
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
            <input type="hidden" id="selected-payment-type" value="cod">
        </section>

        <!-- Section Rincian Belanja -->
        <section class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm space-y-3 text-sm">
            <div class="flex items-center gap-2 border-b border-gray-50 pb-2 mb-1">
                <i data-lucide="receipt" class="w-5 h-5 text-blue-600"></i>
                <h3 class="font-bold text-blue-950">Rincian Pembayaran</h3>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Subtotal Pesanan</span>
                <span id="display-subtotal" class="font-semibold text-gray-700">Rp <?= number_format($subtotal_pesanan, 0, ',', '.'); ?></span>
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
                <span id="display-total" class="text-blue-600 text-lg">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></span>
            </div>
        </section>
    </main>

    <!-- Section Checkout -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-xl z-30">
        <div class="max-w-3xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs text-gray-400 font-medium">Total Tagihan</span>
                <span id="display-fixed-total" class="text-xl font-extrabold text-blue-600">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></span>
            </div>
            <button type="button" 
                    onclick="checkAlamatDanBukaPopup()" 
                    class="bg-blue-600 text-white font-bold px-8 py-3.5 rounded-xl hover:bg-blue-700 shadow-md active:scale-[0.98] transition cursor-pointer">
                Buat Pesanan
            </button>
        </div>
    </div>

    <!-- POP UP Alamat -->
    <div id="modal-alamat" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all">
        <form method="POST" action="" class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
            <input type="hidden" name="action_alamat" value="1">
            <input type="hidden" name="provinsi" id="hidden-provinsi" value="<?= $alamat_terpanah['provinsi'] ?? '' ?>">
            <input type="hidden" name="kota" id="hidden-kota" value="<?= $alamat_terpanah['kota'] ?? '' ?>">
            <input type="hidden" name="kecamatan" id="hidden-kecamatan" value="<?= $alamat_terpanah['kecamatan'] ?? '' ?>">
            <input type="hidden" name="kelurahan" id="hidden-kelurahan" value="<?= $alamat_terpanah['kelurahan'] ?? '' ?>">

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
                <button type="button" onclick="resetFormAlamat()" class="px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">Hapus</button>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md shadow-blue-600/10 cursor-pointer">Simpan Alamat</button>
            </div>
        </form>
    </div>

    <div id="modal-region" class="fixed inset-0 z-[60] hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl max-h-[80vh] flex flex-col shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-blue-600 text-white">
                <h3 id="region-modal-title" class="font-bold text-base">Pilih Provinsi</h3>
                <button type="button" onclick="closeRegionPicker()" class="p-1 hover:bg-white/10 rounded-full transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div id="region-list-container" class="overflow-y-auto divide-y divide-gray-50 max-h-[50vh] hide-scrollbar text-sm"></div>
        </div>
    </div>

    <!-- POP UP Opsi Pembayaran -->
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
                        <div onclick="selectPaymentOpt('cod', 'Cash on Delivery (COD)', 'Bayar tunai langsung di tempat', 'cod.png', 'cod')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
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
                        <div onclick="selectPaymentOpt('qris', 'QRIS All Payment', 'E-Wallet / M-Banking', 'qris.png', 'ewallet')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/qris.png" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">QRIS All Payment</span>
                            </div>
                            <div id="check-opt-qris" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('dana', 'DANA E-Wallet', 'Bayar instan pakai saldo DANA', 'dana.png', 'ewallet')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/dana.png" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">DANA</span>
                            </div>
                            <div id="check-opt-dana" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('gopay', 'GOPAY E-Wallet', 'Bayar cepat pakai saldo GoPay', 'gopay.webp', 'ewallet')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/gopay.webp" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">GoPay</span>
                            </div>
                            <div id="check-opt-gopay" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('ovo', 'OVO E-Wallet', 'Bayar mudah pakai saldo OVO', 'ovo.png', 'ewallet')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/ovo.png" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">OVO</span>
                            </div>
                            <div id="check-opt-ovo" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('shopeepay', 'ShopeePay', 'Integrasi dompet ShopeePay', 'shopeepay.webp', 'ewallet')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/shopeepay.webp" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">ShopeePay</span>
                            </div>
                            <div id="check-opt-shopeepay" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI 3: TRANSFER BANK VA -->
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase px-2">Transfer Bank (Virtual Account)</span>
                    <div class="bg-white border border-gray-100 rounded-xl divide-y divide-gray-50 overflow-hidden shadow-xs">
                        <div onclick="selectPaymentOpt('bca', 'BCA Virtual Account', 'Transfer otomatis bank BCA', 'bca.jpg', 'va')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/bca.jpg" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">BCA Virtual Account</span>
                            </div>
                            <div id="check-opt-bca" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('bni', 'BNI Virtual Account', 'Transfer otomatis bank BNI', 'bni.png', 'va')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/bni.png" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">BNI Virtual Account</span>
                            </div>
                            <div id="check-opt-bni" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('bri', 'BRI Virtual Account', 'Transfer otomatis bank BRI', 'bri.png', 'va')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <img src="../assets/img/payment-method/bri.png" class="h-5 w-12 object-contain">
                                <span class="font-semibold text-blue-950">BRI Virtual Account</span>
                            </div>
                            <div id="check-opt-bri" class="text-blue-600 font-bold hidden"><i data-lucide="check" class="w-4 h-4"></i></div>
                        </div>
                        <div onclick="selectPaymentOpt('mandiri', 'Mandiri Virtual Account', 'Transfer otomatis bank Mandiri', 'mandiri.png', 'va')" class="pay-opt-row flex items-center justify-between py-3 px-3 cursor-pointer hover:bg-slate-50 transition">
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

    <!-- POP-UP Konfirmasi Pemesanan -->
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
                <button type="button" onclick="prosesAJAX()" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-sm shadow-md cursor-pointer hover:bg-blue-700">Ya, Buat Pesanan</button>
                <button type="button" onclick="document.getElementById('popup-konfirmasi').classList.add('hidden')" class="w-full text-xs font-semibold text-gray-400 py-2 cursor-pointer hover:text-gray-600">Periksa Kembali</button>
            </div>
        </div>
    </div>

        <!-- POP-UP STATUS PEMBAYARAN  (waiting / success / failed) -->
    <div id="popup-transfer" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[95vh]">

            <!-- KONDISI 1: MENUNGGU PEMBAYARAN (Bank VA & E-Wallet/QRIS) -->
            <div id="va-state-waiting" class="flex flex-col overflow-hidden">
                <!-- Header visual -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-400 px-6 pt-8 pb-6 text-center text-white relative flex-shrink-0">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold">Menunggu Pembayaran</h2>
                    <p class="text-xs text-white/80 mt-1">Selesaikan pembayaran sebelum waktu habis</p>
                    <!-- Timer -->
                    <div class="mt-4 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                        <i data-lucide="alarm-clock" class="w-4 h-4"></i>
                        <span class="font-mono font-bold text-lg tracking-widest" id="va-timer">60:00</span>
                    </div>
                    <!-- Timer bar -->
                    <div class="mt-3 w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                        <div id="va-timer-bar" class="bg-white h-full rounded-full transition-all duration-1000" style="width:100%"></div>
                    </div>
                </div>

                <!-- Body scrollable -->
                <div class="overflow-y-auto flex-1 px-5 py-4 space-y-4 hide-scrollbar">

                    <!-- Total Bayar (selalu tampil) -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-400 font-medium mb-1">Total yang harus dibayar</p>
                        <p class="text-2xl font-extrabold text-blue-600" id="va-total-display">Rp 0</p>
                        <p id="info-kode-unik" class="text-[11px] text-amber-500 font-semibold mt-1 hidden"><span id="kode-unik-display" class="font-bold hidden"></span></p>
                    </div>

                    <!-- Nomor Virtual Account (khusus Bank) -->
                    <div id="box-va-number" class="hidden bg-white border border-slate-200 rounded-2xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <img id="va-bank-img" src="" class="h-6 w-14 object-contain">
                            <div>
                                <p class="text-xs text-slate-400">Nomor Virtual Account</p>
                                <p class="text-xs font-bold text-slate-600" id="va-bank-name">–</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            <span class="font-mono font-bold text-slate-800 text-base tracking-wider" id="va-number-display">–</span>
                            <button onclick="copyVA()" class="flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition cursor-pointer">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                            </button>
                        </div>
                       
                    </div>

                    <!-- QR Code (khusus QRIS) -->
                    <div id="box-qr-code" class="hidden bg-white border border-slate-200 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-400 mb-3">Pindai kode QR berikut menggunakan aplikasi pembayaran kamu</p>
                        <img id="qr-code-img" src="" alt="QRIS Code" class="w-40 h-40 mx-auto rounded-lg border border-slate-100 p-1">
                    </div>

                    <!-- Info instruksi E-Wallet (non-QRIS & QRIS) -->
                    <div id="box-ewallet-info" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-500">
                        Klik tombol <strong>Bayar</strong> di bawah untuk menyelesaikan pembayaran melalui aplikasi <span id="ewallet-app-name" class="font-semibold text-slate-700">e-wallet</span> kamu.
                    </div>

                    <!-- Cara Transfer (Accordion) khusus Bank -->
                    <div id="box-cara-transfer" class="hidden border border-slate-200 rounded-2xl overflow-hidden">
                        <button onclick="toggleCara()" class="w-full flex items-center justify-between px-4 py-3.5 bg-white hover:bg-slate-50 transition cursor-pointer text-left">
                            <div class="flex items-center gap-2.5 text-sm font-bold text-slate-800">
                                <i data-lucide="list-ordered" class="w-4 h-4 text-blue-600"></i>
                                Cara Transfer
                            </div>
                            <i data-lucide="chevron-down" id="cara-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200"></i>
                        </button>
                        <div id="cara-transfer-body" class="hidden px-4 pb-4 pt-1 bg-slate-50/60 border-t border-slate-100">
                            <div id="cara-transfer-steps" class="space-y-2 text-sm text-slate-600"></div>
                        </div>
                    </div>

                </div>

                <!-- Footer button -->
                <div class="px-5 py-4 border-t border-slate-100 bg-white space-y-2 flex-shrink-0">
                    <!-- Baris tombol untuk E-Wallet / QRIS: Tutup + Bayar berdampingan -->
                    <div id="btn-row-ewallet" class="hidden flex gap-2">
                        <button type="button" onclick="tutupPopupTransfer()" class="flex-1 h-11 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="button" id="btn-bayar-ewallet" onclick="bayarEwallet()" class="flex-1 h-11 bg-blue-600 text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition cursor-pointer">
                            <i data-lucide="wallet" class="w-4 h-4"></i> Bayar
                        </button>
                    </div>
                    <!-- Tombol Tutup saja untuk Bank VA -->
                    <button type="button" id="btn-tutup-bank" onclick="tutupPopupTransfer()" class="w-full h-11 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                        Tutup
                    </button>
                    <!-- Tombol kecil simulasi selesai bayar (khusus Bank) -->
                    <div class="text-center">
                        <button type="button" id="btn-selesai-bank" onclick="selesaikanBayarBank()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition cursor-pointer bg-transparent border-none p-0">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>

            <!-- KONDISI 2: BERHASIL (COD instan / simulasi bayar berhasil -->
            <div id="va-state-success" class="hidden flex flex-col overflow-hidden">
                <div class="bg-gradient-to-br from-emerald-400 to-teal-500 px-6 pt-8 pb-6 text-center text-white flex-shrink-0">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="circle-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold">Pesanan Berhasil!</h2>
                    <p class="text-xs text-white/80 mt-1">Pesananmu sedang dikemas oleh tim kami</p>
                </div>
                <!-- Detail info pesanan (scrollable) -->
                <div class="overflow-y-auto flex-1 px-5 py-4 space-y-3 hide-scrollbar">
                    <!-- Nama Pemesan -->
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Nama Pemesan</span>
                        <span class="text-xs font-bold text-slate-700" id="success-nama-pemesan">–</span>
                    </div>
                    <!-- No Pesanan -->
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Pesanan</span>
                        <span class="text-xs font-bold text-slate-700" id="success-no-pesanan">–</span>
                    </div>
                    <!-- Waktu Pemesanan -->
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Waktu Pemesanan</span>
                        <span class="text-xs font-bold text-slate-700" id="success-waktu">–</span>
                    </div>
                    <!-- No VA (khusus Bank) -->
                    <div id="success-row-va" class="hidden flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Virtual Account</span>
                        <span class="text-xs font-bold text-slate-700 font-mono" id="success-va-number">–</span>
                    </div>
                    <!-- No HP (khusus E-Money & QRIS) -->
                    <div id="success-row-hp" class="hidden flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Pembayar</span>
                        <span class="text-xs font-bold text-slate-700" id="success-no-hp">–</span>
                    </div>
                    <!-- Metode Pembayaran -->
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Metode Pembayaran</span>
                        <span class="text-xs font-bold text-slate-700" id="success-metode">–</span>
                    </div>
                    <!-- No Kartu / Nama Bank (khusus Bank) -->
                    <div id="success-row-bank" class="hidden flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium hidden">Bank</span>
                        <span class="text-xs font-bold text-slate-700 hidden" id="success-bank-name">–</span>
                    </div>
                    <!-- Total Pembayaran (paling bawah) -->
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center mt-2">
                        <p class="text-xs text-emerald-600 font-semibold mb-1">Total Pembayaran</p>
                        <p class="text-xl font-extrabold text-emerald-700" id="va-success-total">Rp 0</p>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed text-center">Pantau status pesanan di <strong class="text-slate-600">Halaman Pemesanan</strong></p>
                </div>
                <!-- Footer tombol (tidak scrollable) -->
                <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex gap-2 flex-shrink-0">
                    <button type="button" onclick="selesaiKonfirmasi()" class="flex-1 h-11 bg-slate-100 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                        Tutup
                    </button>
                    <a href="pemesanan.php?tab=diproses" class="flex-1 h-11 bg-emerald-600 text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-emerald-700 transition">
                        <i data-lucide="package" class="w-4 h-4"></i> Lihat Pesanan
                    </a>
                </div>
                <!-- Tombol Selesai (hanya tampil untuk COD & Bank VA, tidak untuk E-Money) -->
                <div id="success-btn-selesai-wrap" class="px-5 pb-5 flex-shrink-0 hidden">
                    <button type="button" onclick="selesaiKonfirmasi()" class="w-full h-11 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition cursor-pointer hidden">
                        Selesai
                    </button>
                </div>
            </div>

            <!-- KONDISI 3: GAGAL / KEDALUWARSA -->
            <div id="va-state-failed" class="hidden flex flex-col">
                <div class="bg-gradient-to-br from-red-400 to-rose-500 px-6 pt-8 pb-6 text-center text-white">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="circle-x" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold" id="va-failed-title">Pembayaran Gagal</h2>
                    <p class="text-xs text-white/80 mt-1" id="va-failed-desc">Waktu pembayaran telah habis</p>
                </div>
                <div class="px-6 py-5 text-center space-y-3">
                    <p class="text-sm text-slate-500 leading-relaxed">Pesanan otomatis dibatalkan. Silakan buat pesanan baru jika ingin melanjutkan.</p>
                </div>
                <div class="px-5 pb-6 space-y-2">
                    <a href="kategori.php" class="w-full h-11 bg-blue-600 text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i> Belanja Lagi
                    </a>
                    <a href="pemesanan.php?tab=dibatalkan" class="w-full h-9 text-xs font-semibold text-slate-400 hover:text-slate-600 transition flex items-center justify-center">
                        Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- end popup-transfer -->

    <script>
    const biayaLayanan    = <?= $biaya_layanan; ?>;
    const biayaPengiriman = <?= $biaya_pengiriman; ?>;
    const alamatId        = <?= (int)($alamat_terpanah['id'] ?? 0); ?>;

    function formatRupiah(angka) {
        return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateQty(id, change, hargaSatuan) {
        let qtyElement = document.getElementById('qty-' + id);
        let subtotalElement = document.getElementById('subtotal-' + id);
        let currentQty = parseInt(qtyElement.textContent);
        let newQty = currentQty + change;
        if (newQty < 1) return;
        qtyElement.textContent = newQty;
        let newSubtotal = newQty * hargaSatuan;
        subtotalElement.textContent = formatRupiah(newSubtotal);
        hitungTotalKeseluruhan();
    }

    function hitungTotalKeseluruhan() {
        let subtotals = document.querySelectorAll('[id^="subtotal-"]');
        let total = 0;
        subtotals.forEach(el => {
            let val = parseInt(el.textContent.replace(/[^0-9]/g, ''));
            total += val;
        });
        const grandTotal = total + biayaLayanan + biayaPengiriman;
        document.getElementById('display-subtotal').textContent = formatRupiah(total);
        document.getElementById('display-total').textContent = formatRupiah(grandTotal);
        document.getElementById('display-fixed-total').textContent = formatRupiah(grandTotal);
    }

    // Wilayah
    const databaseWilayah = {
        "Kalimantan Barat": { "Pontianak": { "Pontianak Kota": ["Dararat Sekip", "Mariana"], "Pontianak Tenggara": ["Bangka Belitung"] }, "Singkawang": { "Singkawang Barat": ["Pasiran", "Melayu"] } },
        "Kalimantan Selatan": { "Banjarmasin": { "Banjarmasin Tengah": ["Antasan Besar"], "Banjarmasin Utara": ["Alalak Utara"] }, "Banjarbaru": { "Landasan Ulin": ["Guntung Payung"] } },
        "Kalimantan Tengah": { "Palangkaraya": { "Jekan Raya": ["Menteng"], "Pahandut": ["Pahandut Seberang"] } },
        "Kalimantan Timur": { "Samarinda": { "Samarinda Kota": ["Bugis"], "Samarinda Ulu": ["Air Hitam"] }, "Balikpapan": { "Balikpapan Kota": ["Klandasan Ulu"] } },
        "Kalimantan Utara": { "Tarakan": { "Tarakan Barat": ["Karang Anyar"], "Tarakan Timur": ["Lingkas Ujung"] } }
    };

    let selectedProvinsi = "", selectedKota = "", selectedKecamatan = "", selectedKelurahan = "";

    function openAddressModal()  { document.getElementById('modal-alamat').classList.remove('hidden'); }
    function closeAddressModal() { document.getElementById('modal-alamat').classList.add('hidden'); }
    function openRegionPicker()  { document.getElementById('modal-region').classList.remove('hidden'); renderProvinsiList(); }
    function closeRegionPicker() { document.getElementById('modal-region').classList.add('hidden'); }

    function renderProvinsiList() {
        document.getElementById('region-modal-title').textContent = "Pilih Provinsi";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        Object.keys(databaseWilayah).forEach(prov => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = prov;
            row.onclick = () => { selectedProvinsi = prov; document.getElementById('hidden-provinsi').value = prov; renderKotaList(); };
            container.appendChild(row);
        });
    }

    function renderKotaList() {
        document.getElementById('region-modal-title').textContent = "Pilih Kota/Kabupaten";
        const container = document.getElementById('region-list-container');
        container.innerHTML = "";
        Object.keys(databaseWilayah[selectedProvinsi]).forEach(kota => {
            const row = document.createElement('div');
            row.className = "p-4 hover:bg-slate-50 cursor-pointer text-gray-700 font-medium transition";
            row.textContent = kota;
            row.onclick = () => { selectedKota = kota; document.getElementById('hidden-kota').value = kota; renderKecamatanList(); };
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
            row.onclick = () => { selectedKecamatan = kec; document.getElementById('hidden-kecamatan').value = kec; renderKelurahanList(); };
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
    }

    // Pembayaran
    function openPaymentModal()  { document.getElementById('modal-pembayaran').classList.remove('hidden'); }
    function closePaymentModal() { document.getElementById('modal-pembayaran').classList.add('hidden'); }

    function selectPaymentOpt(id, title, desc, imgFileName, type) {
        document.querySelectorAll('.pay-opt-row [id^="check-opt-"]').forEach(el => el.classList.add('hidden'));
        document.getElementById(`check-opt-${id}`).classList.remove('hidden');
        document.getElementById('current-pay-img').src = `../assets/img/payment-method/${imgFileName}`;
        document.getElementById('current-pay-title').textContent = title;
        document.getElementById('current-pay-desc').textContent = desc;
        document.getElementById('selected-payment-value').value = id.toUpperCase();
        document.getElementById('selected-payment-type').value  = type;
        closePaymentModal();
    }

    function checkAlamatDanBukaPopup() {
        const alamatValid = document.getElementById('hidden-provinsi').value !== "";
        if (!alamatValid) {
            alert("Harap lengkapi Alamat Pengiriman Anda!");
            openAddressModal();
            return;
        }
        document.getElementById('popup-konfirmasi').classList.remove('hidden');
    }

    // Cara Transfer per Bank
    const caraBayar = {
        bca: [
            "Buka aplikasi <strong>BCA Mobile / myBCA</strong> di smartphone.",
            "Pilih menu <strong>m-Transfer</strong> → <strong>BCA Virtual Account</strong>.",
            "Masukkan nomor VA yang tertera, lalu klik <strong>Kirim</strong>.",
            "Pastikan nama tujuan dan nominal sesuai, kemudian <strong>konfirmasi</strong>.",
            "Simpan bukti transfer sebagai referensi."
        ],
        bni: [
            "Buka aplikasi <strong>BNI Mobile Banking</strong>.",
            "Pilih <strong>Transfer</strong> → <strong>Virtual Account Billing</strong>.",
            "Masukkan nomor VA, lalu pilih <strong>Lanjut</strong>.",
            "Periksa detail tagihan dan masukkan <strong>password transaksi</strong>.",
            "Transaksi berhasil, simpan notifikasi pembayaran."
        ],
        bri: [
            "Buka aplikasi <strong>BRImo</strong> di smartphone.",
            "Pilih menu <strong>Pembayaran</strong> → <strong>BRIVA</strong>.",
            "Masukkan nomor BRIVA yang tertera lalu klik <strong>Lanjut</strong>.",
            "Cek nominal dan nama merchant, lalu masukkan <strong>PIN BRImo</strong>.",
            "Pembayaran selesai, cek riwayat transaksi untuk konfirmasi."
        ],
        mandiri: [
            "Buka aplikasi <strong>Livin' by Mandiri</strong>.",
            "Pilih <strong>Bayar</strong> → <strong>Multipayment</strong>.",
            "Cari penyedia layanan dan masukkan <strong>nomor VA</strong>.",
            "Masukkan nominal sesuai tagihan, lalu klik <strong>Konfirmasi</strong>.",
            "Masukkan <strong>MPIN</strong> untuk menyelesaikan transaksi."
        ]
    };

    // State popup
    let vaTimerInterval    = null;
    let currentOrderId     = null;
    let currentOrderTotal  = 0;
    let currentOrderDetails = {};
    const VA_DURATION      = 60 * 60; // 1 jam dalam detik

    function showVAState(state) {
        ['waiting', 'success', 'failed'].forEach(s => {
            document.getElementById(`va-state-${s}`).classList.add('hidden');
        });
        document.getElementById(`va-state-${state}`).classList.remove('hidden');
    }

    // Buka popup "menunggu pembayaran" — dipakai untuk Bank VA & E-Wallet/QRIS
    function bukaPopupTransfer(data, payType) {
        const paymentId = document.getElementById('selected-payment-value').value.toLowerCase();
        currentOrderTotal = data.total;

        document.getElementById('va-total-display').textContent  = formatRupiah(data.total);
        document.getElementById('va-number-display').textContent = data.va_number;
        document.getElementById('va-bank-name').textContent      = document.getElementById('current-pay-title').textContent;
        document.getElementById('va-bank-img').src = document.getElementById('current-pay-img').src;
        document.getElementById('ewallet-app-name').textContent  = document.getElementById('current-pay-title').textContent;

        const boxVA           = document.getElementById('box-va-number');
        const boxQR            = document.getElementById('box-qr-code');
        const boxCara           = document.getElementById('box-cara-transfer');
        const boxEwalletInfo      = document.getElementById('box-ewallet-info');
        const btnBayarEwallet = document.getElementById('btn-bayar-ewallet');
        const btnRowEwallet   = document.getElementById('btn-row-ewallet');
        const btnTutupBank    = document.getElementById('btn-tutup-bank');
        const btnSelesaiBank  = document.getElementById('btn-selesai-bank');
        const infoKodeUnik    = document.getElementById('info-kode-unik');
        const kodeUnikDisplay = document.getElementById('kode-unik-display');

        if (payType === 'va') {
            // Tampilan Bank Virtual Account
            boxVA.classList.remove('hidden');
            boxCara.classList.remove('hidden');
            boxQR.classList.add('hidden');
            boxEwalletInfo.classList.add('hidden');
            btnRowEwallet.classList.add('hidden');
            btnTutupBank.classList.remove('hidden');
            btnSelesaiBank.closest('div').classList.remove('hidden');
            btnSelesaiBank.disabled = false;
            btnSelesaiBank.textContent = 'Selesai';
            // Bank: tidak tampilkan info kode unik
            infoKodeUnik.classList.add('hidden');

            const steps = caraBayar[paymentId] || [
                "Buka aplikasi mobile banking kamu.",
                "Pilih menu Transfer / Bayar Virtual Account.",
                "Masukkan nomor Virtual Account yang tertera.",
                "Konfirmasi nominal dan selesaikan transaksi."
            ];
            const stepsHTML = steps.map((s, i) =>
                `<div class="flex gap-3 items-start">
                    <span class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5">${i+1}</span>
                    <span>${s}</span>
                </div>`
            ).join('');
            document.getElementById('cara-transfer-steps').innerHTML = stepsHTML;

        } else {
            // Tampilan E-Wallet / QRIS
            boxVA.classList.add('hidden');
            boxCara.classList.add('hidden');
            boxEwalletInfo.classList.remove('hidden');
            btnRowEwallet.classList.remove('hidden');
            btnTutupBank.classList.add('hidden');
            btnSelesaiBank.closest('div').classList.add('hidden');
            btnBayarEwallet.disabled = false;
            btnBayarEwallet.innerHTML = '<i data-lucide="wallet" class="w-4 h-4"></i> Bayar';

            // Generate 3 digit kode unik untuk e-money & QRIS
            const kodeUnik = String(Math.floor(100 + Math.random() * 900));
            kodeUnikDisplay.textContent = kodeUnik;
            infoKodeUnik.classList.remove('hidden');

            if (paymentId === 'qris') {
                boxQR.classList.remove('hidden');
                document.getElementById('qr-code-img').src =
                    `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent('QRIS|ORDER:' + data.order_id + '|TOTAL:' + data.total + '|KODE:' + kodeUnik)}`;
            } else {
                boxQR.classList.add('hidden');
            }
        }

        // Reset accordion
        document.getElementById('cara-transfer-body').classList.add('hidden');
        document.getElementById('cara-chevron').style.transform = '';

        showVAState('waiting');
        document.getElementById('popup-transfer').classList.remove('hidden');

        startVATimer();

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // Buka popup "berhasil" — dipakai untuk COD (instan) & simulasi bayar VA/E-wallet
    function bukaPopupSukses(totalAmount, orderDetails) {
        document.getElementById('va-success-total').textContent = formatRupiah(totalAmount);

        // Isi detail pesanan
        const d = orderDetails || {};
        document.getElementById('success-nama-pemesan').textContent = d.nama || '–';
        document.getElementById('success-no-pesanan').textContent   = d.orderId ? '#' + String(d.orderId).padStart(6, '0') : '–';
        document.getElementById('success-waktu').textContent        = d.waktu || new Date().toLocaleString('id-ID', {dateStyle:'short', timeStyle:'short'});
        document.getElementById('success-metode').textContent       = d.metodeLabel || '–';

        const payType = d.payType || 'cod';

        // Tampilkan baris VA (khusus Bank)
        const rowVA   = document.getElementById('success-row-va');
        const rowBank = document.getElementById('success-row-bank');
        const rowHP   = document.getElementById('success-row-hp');
        const btnSelesasiWrap = document.getElementById('success-btn-selesai-wrap');

        rowVA.classList.add('hidden');
        rowBank.classList.add('hidden');
        rowHP.classList.add('hidden');

        if (payType === 'va') {
            rowVA.classList.remove('hidden');
            document.getElementById('success-va-number').textContent = d.vaNumber || '–';
            rowBank.classList.remove('hidden');
            document.getElementById('success-bank-name').textContent = d.metodeLabel || '–';
            // Tombol Selesai tampil untuk Bank
            btnSelesasiWrap.classList.remove('hidden');
        } else if (payType === 'ewallet') {
            rowHP.classList.remove('hidden');
            document.getElementById('success-no-hp').textContent = d.noHp || '–';
            // Tombol Selesai TIDAK tampil untuk E-Money
            btnSelesasiWrap.classList.add('hidden');
        } else {
            // COD: tampil tombol Selesai
            btnSelesasiWrap.classList.remove('hidden');
        }

        showVAState('success');
        document.getElementById('popup-transfer').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function startVATimer() {
        let sisa = VA_DURATION;
        clearInterval(vaTimerInterval);
        document.getElementById('va-timer-bar').style.width = '100%';
        vaTimerInterval = setInterval(() => {
            sisa--;
            const mnt = String(Math.floor(sisa / 60)).padStart(2, '0');
            const dtk = String(sisa % 60).padStart(2, '0');
            document.getElementById('va-timer').textContent = `${mnt}:${dtk}`;
            document.getElementById('va-timer-bar').style.width = `${(sisa / VA_DURATION) * 100}%`;

            if (sisa <= 0) {
                clearInterval(vaTimerInterval);
                // Mark order dibatalkan via fetch
                fetch('checkout.php?batalkan_order=1&order_id=' + currentOrderId).catch(() => {});
                document.getElementById('va-failed-title').textContent = 'Waktu Habis';
                document.getElementById('va-failed-desc').textContent  = 'Waktu pembayaran 1 jam telah habis';
                showVAState('failed');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }, 1000);
    }

    // Simulasi: tombol kecil "Selesai" di bawah nomor VA (khusus Bank)
    function selesaikanBayarBank() {
        const btn = document.getElementById('btn-selesai-bank');
        btn.textContent = 'Memproses...';
        btn.disabled = true;

        fetch(`checkout.php?konfirmasi_bayar=1&order_id=${currentOrderId}`)
            .then(() => {
                clearInterval(vaTimerInterval);
                bukaPopupSukses(currentOrderTotal, currentOrderDetails);
            })
            .catch(() => {
                btn.textContent = 'Selesai';
                btn.disabled = false;
                showToast('Gagal memproses, coba lagi.');
            });
    }

    // Simulasi: tombol "Bayar" untuk E-Wallet / QRIS
    function bayarEwallet() {
        const btn = document.getElementById('btn-bayar-ewallet');
        btn.innerHTML = '<i data-lucide="loader-circle" class="w-4 h-4 animate-spin"></i> Memproses...';
        btn.disabled = true;
        if (typeof lucide !== 'undefined') lucide.createIcons();

        fetch(`checkout.php?konfirmasi_bayar=1&order_id=${currentOrderId}`)
            .then(() => {
                clearInterval(vaTimerInterval);
                bukaPopupSukses(currentOrderTotal, currentOrderDetails);
            })
            .catch(() => {
                btn.innerHTML = '<i data-lucide="wallet" class="w-4 h-4"></i> Bayar';
                btn.disabled = false;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                showToast('Gagal memproses, coba lagi.');
            });
    }

    // Tombol "Tutup" pada popup menunggu pembayaran (Bank & E-wallet) → balik ke index.php
    function tutupPopupTransfer() {
        clearInterval(vaTimerInterval);
        window.location.href = '../index.php';
    }

    // Tombol "Selesai" pada popup sukses → balik ke index.php
    function selesaiKonfirmasi() {
        window.location.href = '../index.php';
    }

    function copyVA() {
        const va = document.getElementById('va-number-display').textContent;
        navigator.clipboard.writeText(va).then(() => showToast('Nomor VA berhasil disalin!'));
    }

    function toggleCara() {
        const body    = document.getElementById('cara-transfer-body');
        const chevron = document.getElementById('cara-chevron');
        const isHidden = body.classList.contains('hidden');
        body.classList.toggle('hidden', !isHidden);
        chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-xl z-[200] transition-opacity duration-300';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 2500);
    }

    // AJAX utama: buat pesanan
    function prosesAJAX() {
        const totalEl   = document.getElementById('display-total');
        const totalText = totalEl.textContent.replace(/[^0-9]/g, '');
        const metode    = document.getElementById('selected-payment-value').value;
        const payType   = document.getElementById('selected-payment-type').value; // 'cod' | 'va' | 'ewallet'

        fetch('checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `proses_pembayaran=1&metode_pembayaran=${encodeURIComponent(metode)}&payment_type=${encodeURIComponent(payType)}&total_pembayaran=${totalText}&alamat_id=${alamatId}`
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('popup-konfirmasi').classList.add('hidden');
            if (data.status === 'success') {
                currentOrderId    = data.order_id;
                currentOrderTotal = data.total;

                // Ambil nama pemesan dari halaman (label alamat)
                const namaLabel = document.getElementById('lbl-nama-telp').textContent.split('|')[0].trim();
                const noHpLabel = document.getElementById('lbl-nama-telp').textContent.split('|')[1]?.trim() || '–';
                const waktuNow  = new Date().toLocaleString('id-ID', {dateStyle:'short', timeStyle:'short'});

                currentOrderDetails = {
                    orderId     : data.order_id,
                    vaNumber    : data.va_number,
                    metodeLabel : document.getElementById('current-pay-title').textContent,
                    payType     : payType,
                    nama        : namaLabel,
                    noHp        : noHpLabel,
                    waktu       : waktuNow
                };

                if (payType === 'cod') {
                    // COD: bayar saat barang diterima, tidak perlu menunggu pembayaran
                    bukaPopupSukses(currentOrderTotal, currentOrderDetails);
                } else {
                    // Bank VA & E-Wallet/QRIS: tampilkan popup menunggu pembayaran
                    bukaPopupTransfer(data, payType);
                }
            } else {
                alert('Gagal memproses pesanan: ' + (data.message || 'Coba lagi.'));
            }
        })
        .catch(() => alert("Terjadi kesalahan koneksi. Silakan coba lagi."));
    }

    lucide.createIcons();
    </script>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

<?php include '../includes/script.php'; ?>
</body>
</html>