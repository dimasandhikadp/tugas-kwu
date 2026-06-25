<?php
    include '../config/koneksi.php'; 
    session_start();
    /** @var mysqli $conn */

    $user_id = $_SESSION['user_id'] ?? 0;

    // ===== AJAX: Konfirmasi Pembayaran (pending -> diproses) =====
    if (isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'konfirmasi_bayar' && isset($_GET['order_id'])) {
        header('Content-Type: application/json');
        $oid = (int) $_GET['order_id'];
        mysqli_query($conn, "UPDATE orders SET status='diproses' WHERE id='$oid' AND user_id='$user_id' AND status='pending'");
        if (mysqli_affected_rows($conn) > 0) {
            mysqli_query($conn, "INSERT INTO order_status_logs (order_id, status) VALUES ('$oid', 'diproses')");
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan atau sudah diproses.']);
        }
        exit;
    }

    // ===== AJAX: Batalkan Pesanan (pending / diproses -> dibatalkan) =====
    if (isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'batalkan_pesanan' && isset($_GET['order_id'])) {
        header('Content-Type: application/json');
        $oid = (int) $_GET['order_id'];
        mysqli_query($conn, "UPDATE orders SET status='dibatalkan' WHERE id='$oid' AND user_id='$user_id' AND status IN ('pending','diproses')");
        if (mysqli_affected_rows($conn) > 0) {
            mysqli_query($conn, "INSERT INTO order_status_logs (order_id, status) VALUES ('$oid', 'dibatalkan')");
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak dapat dibatalkan.']);
        }
        exit;
    }
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/head.php'; ?>

<body class="bg-gray-50 text-gray-800 font-sans min-h-screen">

    <!-- Navbar Pemesanan -->
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center gap-3">
            <a href="../index.php" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 transition text-slate-600">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-xl font-bold text-blue-950 tracking-tight">Pesanan</h1>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-6">

        <!-- Tab Navigation -->
        <div class="flex gap-1 bg-slate-100 p-1 rounded-xl mb-6 overflow-x-auto no-scrollbar">
            <?php
            $tabs = [
                ['key' => 'pending',    'label' => 'Menunggu',  'icon' => 'clock',         'color' => 'amber'],
                ['key' => 'diproses',   'label' => 'Dikemas',   'icon' => 'package',       'color' => 'blue'],
                ['key' => 'dikirim',    'label' => 'Dikirim',   'icon' => 'truck',         'color' => 'indigo'],
                ['key' => 'selesai',    'label' => 'Selesai',   'icon' => 'circle-check',  'color' => 'green'],
                ['key' => 'dibatalkan', 'label' => 'Gagal',     'icon' => 'circle-x',      'color' => 'red'],
            ];

            $active_tab = $_GET['tab'] ?? 'pending';
            ?>
            <?php foreach ($tabs as $tab): ?>
                <a href="?tab=<?= $tab['key']; ?>"
                   class="flex-1 min-w-[80px] flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-all duration-150
                          <?= $active_tab === $tab['key']
                              ? 'bg-white text-blue-600 shadow-sm shadow-slate-200/80'
                              : 'text-slate-500 hover:text-slate-700'; ?>">
                    <i data-lucide="<?= $tab['icon']; ?>" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    <?= $tab['label']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php
        // ── Database query ──────────────────────────────────────────────
        // Assumes $conn is available from includes and session user_id is set
        $status_now = mysqli_real_escape_string($conn, $active_tab);

        $sql = "SELECT 
                o.id,
                o.total_harga,
                o.status,
                o.created_at,
                o.metode_pembayaran,
                o.va_number,
                a.nama_penerima, 
                a.no_hp,
                a.alamat,
                a.kota,
                (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS jumlah_item,
                (SELECT osl.created_at FROM order_status_logs osl 
                WHERE osl.order_id = o.id 
                ORDER BY osl.created_at DESC LIMIT 1) AS last_update
            FROM orders o
            LEFT JOIN addresses a ON a.id = o.alamat_id
            WHERE o.user_id = '$user_id' AND o.status = '$status_now'
            ORDER BY o.created_at DESC";

        $result = mysqli_query($conn, $sql);

        // Badge config per status
        $badge_cfg = [
            'pending'    => ['bg' => 'bg-amber-50 text-amber-600 border-amber-100',  'dot' => 'bg-amber-400',  'label' => 'Menunggu Pembayaran'],
            'diproses'   => ['bg' => 'bg-blue-50 text-blue-600 border-blue-100',     'dot' => 'bg-blue-400',   'label' => 'Sedang Dikemas'],
            'dikirim'    => ['bg' => 'bg-indigo-50 text-indigo-600 border-indigo-100','dot'=> 'bg-indigo-400', 'label' => 'Dalam Pengiriman'],
            'selesai'    => ['bg' => 'bg-green-50 text-green-600 border-green-100',  'dot' => 'bg-green-400',  'label' => 'Selesai'],
            'dibatalkan' => ['bg' => 'bg-red-50 text-red-600 border-red-100',        'dot' => 'bg-red-400',    'label' => 'Dibatalkan'],
        ];

        $cfg = $badge_cfg[$active_tab];
        ?>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="flex flex-col gap-4">
            <?php while ($order = mysqli_fetch_assoc($result)): ?>

                <?php
                    // Fetch order items for this order
                    $oid  = (int) $order['id'];
                    $sql_items = "SELECT oi.qty, oi.harga, p.id as product_id, p.slug, p.nama_produk, p.satuan,
                                         (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar
                                  FROM order_items oi
                                  JOIN products p ON p.id = oi.product_id
                                  WHERE oi.order_id = $oid
                                  LIMIT 3";
                    $res_items = mysqli_query($conn, $sql_items);
                    $first_product_slug = null;
                ?>

                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-sm transition-shadow duration-200 order-card"
                     data-order='<?= htmlspecialchars(json_encode([
                         'id'       => (int) $order['id'],
                         'nama'     => $order['nama_penerima'] ?? '-',
                         'noHp'     => $order['no_hp'] ?? '-',
                         'waktu'    => date('d/m/y, H:i', strtotime($order['created_at'])),
                         'metode'   => $order['metode_pembayaran'] ?? 'COD',
                         'vaNumber' => $order['va_number'] ?? '',
                         'total'    => (float) $order['total_harga'],
                         'status'   => $order['status'],
                     ]), ENT_QUOTES, 'UTF-8'); ?>'>

                    <!-- Card Header -->
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50/60">
                        <div class="flex items-center gap-2.5">
                            <span class="<?= $cfg['bg']; ?> border text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full <?= $cfg['dot']; ?> inline-block"></span>
                                <?= $cfg['label']; ?>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">
                            #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                            &nbsp;·&nbsp;
                            <?= date('d M Y, H:i', strtotime($order['created_at'])); ?>
                        </span>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="px-5 py-4 flex flex-col gap-3">
                        <?php if (mysqli_num_rows($res_items) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($res_items)): ?>
                            <?php if ($first_product_slug === null && !empty($item['slug'])) { $first_product_slug = $item['slug']; } ?>
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    <?php if (!empty($item['gambar']) && file_exists("../assets/img/product-image/" . $item['gambar'])): ?>
                                        <img src="../assets/img/product-image/<?= $item['gambar']; ?>" 
                                             alt="<?= htmlspecialchars($item['nama_produk']); ?>"
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i data-lucide="fish" class="w-6 h-6 text-blue-400/70"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 leading-snug truncate"><?= htmlspecialchars($item['nama_produk']); ?></p>
                                    <p class="text-xs text-slate-400 mt-0.5"><?= $item['qty']; ?> <?= htmlspecialchars($item['satuan'] ?? 'kg'); ?> &nbsp;×&nbsp; Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="text-sm font-bold text-slate-800 flex-shrink-0">
                                    Rp <?= number_format($item['qty'] * $item['harga'], 0, ',', '.'); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>

                            <?php if ($order['jumlah_item'] > 3): ?>
                                <p class="text-xs text-slate-400 text-center pt-1">+<?= $order['jumlah_item'] - 3; ?> produk lainnya</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-5 py-4 border-t border-slate-100 bg-slate-50/40">
                        <div>
                            <?php if (!empty($order['alamat'])): ?>
                            <p class="text-xs text-slate-500 flex items-center gap-1.5 mb-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-blue-500"></i>
                                <strong><?= htmlspecialchars($order['nama_penerima']); ?></strong> - 
                                <?= htmlspecialchars($order['alamat']); ?>, <?= htmlspecialchars($order['kota']); ?>
                            </p>
                            <?php endif; ?>
                            
                            <p class="text-sm font-semibold text-slate-800">
                                Total &nbsp;
                                <span class="text-blue-600 font-extrabold">
                                    Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?>
                                </span>
                            </p>
                            
                            <?php if (!empty($order['last_update'])): ?>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Diperbarui: <?= date('d M Y, H:i', strtotime($order['last_update'])); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                    
                        <div class="flex gap-2 flex-shrink-0">
                            <?php if ($active_tab === 'pending'): ?>
                                <button type="button" onclick="prosesBayarSekarang(this)"
                                   class="h-9 px-4 bg-blue-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-blue-700 transition shadow-sm shadow-blue-600/10 cursor-pointer">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Bayar Sekarang
                                </button>
                            <?php elseif ($active_tab === 'diproses'): ?>
                                <button type="button" onclick="batalkanPesanan(this)"
                                   class="h-9 px-4 bg-white text-red-500 text-xs font-semibold border border-red-200 rounded-lg flex items-center gap-1.5 hover:bg-red-50 transition cursor-pointer">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Batalkan
                                </button>
                            <?php elseif ($active_tab === 'dikirim'): ?>
                                <a href="track.php?order=<?= $order['id']; ?>"
                                   class="h-9 px-4 bg-indigo-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-indigo-700 transition">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Lacak
                                </a>
                            <?php elseif ($active_tab === 'selesai'): ?>
                                <a href="<?= $first_product_slug ? 'product-details.php?slug=' . urlencode($first_product_slug) : 'kategori.php'; ?>"
                                   class="h-9 px-4 bg-green-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-green-700 transition">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Beli Lagi
                                </a>
                            <?php endif; ?>

                            <button type="button" onclick="bukaDetailPesanan(this)"
                               class="h-9 px-4 bg-white text-slate-600 text-xs font-semibold border border-slate-300 rounded-lg flex items-center gap-1.5 hover:bg-slate-50 transition cursor-pointer">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
            </div>

        <?php else: ?>
            <!-- Empty State -->
            <?php
            $empty_icons  = ['pending'=>'clock','diproses'=>'package','dikirim'=>'truck','selesai'=>'circle-check','dibatalkan'=>'circle-x'];
            $empty_labels = ['pending'=>'Belum ada pesanan yang menunggu pembayaran.','diproses'=>'Tidak ada pesanan yang sedang dikemas.','dikirim'=>'Tidak ada pesanan dalam pengiriman.','selesai'=>'Belum ada pesanan yang selesai.','dibatalkan'=>'Tidak ada pesanan yang gagal atau dibatalkan.'];
            ?>
            <div class="flex flex-col items-center justify-center py-20 text-center gap-4">
                <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300">
                    <i data-lucide="<?= $empty_icons[$active_tab]; ?>" class="w-9 h-9"></i>
                </div>
                <div>
                    <p class="text-slate-500 font-medium text-sm"><?= $empty_labels[$active_tab]; ?></p>
                    <a href="kategori.php" class="inline-flex items-center gap-1.5 mt-3 text-blue-600 font-semibold text-sm hover:underline">
                        Mulai Belanja
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <!-- ============================================================ -->
    <!-- MODAL: DETAIL PESANAN                                        -->
    <!-- ============================================================ -->
    <div id="modal-detail-pesanan" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="h-1.5 bg-gradient-to-r from-emerald-400 to-teal-500 flex-shrink-0"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 flex-shrink-0">
                <h3 class="font-bold text-blue-950 text-base flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5 text-emerald-600"></i> Detail Pesanan
                </h3>
                <button type="button" onclick="tutupDetailPesanan()" class="p-1.5 hover:bg-slate-100 rounded-full transition cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-5 py-4 hide-scrollbar">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">Nama Pemesan</span>
                    <span class="text-xs font-bold text-slate-700" id="detail-nama-pemesan">–</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">No. Pesanan</span>
                    <span class="text-xs font-bold text-slate-700" id="detail-no-pesanan">–</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">Waktu Pemesanan</span>
                    <span class="text-xs font-bold text-slate-700" id="detail-waktu">–</span>
                </div>
                <div id="detail-row-va" class="hidden flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">No. Virtual Account</span>
                    <span class="text-xs font-bold text-slate-700 font-mono" id="detail-va-number">–</span>
                </div>
                <div id="detail-row-hp" class="hidden flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">No. Pembayar</span>
                    <span class="text-xs font-bold text-slate-700" id="detail-no-hp">–</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                    <span class="text-xs text-slate-400 font-medium">Metode Pembayaran</span>
                    <span class="text-xs font-bold text-slate-700" id="detail-metode">–</span>
                </div>

                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center mt-4">
                    <p class="text-xs text-emerald-600 font-semibold mb-1">Total Pembayaran</p>
                    <p class="text-xl font-extrabold text-emerald-700" id="detail-total">Rp 0</p>
                </div>
            </div>
            <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="tutupDetailPesanan()" class="w-full h-11 bg-slate-100 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: KONFIRMASI BATALKAN PESANAN                            -->
    <!-- ============================================================ -->
    <div id="modal-konfirmasi-batal" class="fixed inset-0 z-[100] hidden bg-black/40 backdrop-blur-xs flex items-end sm:items-center justify-center p-4">
        <div class="bg-white w-full sm:max-w-sm rounded-2xl p-6 shadow-2xl text-center space-y-4">
            <div class="mx-auto w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center shadow-xs">
                <i data-lucide="circle-x" class="w-8 h-8"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-blue-950">Batalkan Pesanan?</h3>
                <p class="text-xs text-gray-400 px-2">Pesanan yang dibatalkan tidak dapat diproses kembali.</p>
            </div>
            <div class="flex flex-col gap-2 pt-2">
                <button type="button" id="btn-ya-batalkan" onclick="konfirmasiBatalkanPesanan()" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl text-sm shadow-md cursor-pointer hover:bg-red-700">Ya, Batalkan</button>
                <button type="button" onclick="tutupKonfirmasiBatal()" class="w-full text-xs font-semibold text-gray-400 py-2 cursor-pointer hover:text-gray-600">Kembali</button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- POPUP: STATUS PEMBAYARAN (waiting / success / failed)         -->
    <!-- ============================================================ -->
    <div id="popup-transfer-pesanan" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden max-h-[95vh]">

            <!-- KONDISI 1: MENUNGGU PEMBAYARAN -->
            <div id="pt-state-waiting" class="flex flex-col overflow-hidden">
                <div class="bg-gradient-to-br from-amber-400 to-orange-400 px-6 pt-8 pb-6 text-center text-white relative flex-shrink-0">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold">Menunggu Pembayaran</h2>
                    <p class="text-xs text-white/80 mt-1">Selesaikan pembayaran sebelum waktu habis</p>
                    <div class="mt-4 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                        <i data-lucide="alarm-clock" class="w-4 h-4"></i>
                        <span class="font-mono font-bold text-lg tracking-widest" id="pt-timer">60:00</span>
                    </div>
                    <div class="mt-3 w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                        <div id="pt-timer-bar" class="bg-white h-full rounded-full transition-all duration-1000" style="width:100%"></div>
                    </div>
                </div>

                <div class="overflow-y-auto flex-1 px-5 py-4 space-y-4 hide-scrollbar">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-400 font-medium mb-1">Total yang harus dibayar</p>
                        <p class="text-2xl font-extrabold text-blue-600" id="pt-total-display">Rp 0</p>
                        <p id="pt-info-kode-unik" class="text-[11px] text-amber-500 font-semibold mt-1 hidden">Kode unik: <span id="pt-kode-unik-display" class="font-bold"></span></p>
                    </div>

                    <div id="pt-box-va-number" class="hidden bg-white border border-slate-200 rounded-2xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <img id="pt-bank-img" src="" class="h-6 w-14 object-contain">
                            <div>
                                <p class="text-xs text-slate-400">Nomor Virtual Account</p>
                                <p class="text-xs font-bold text-slate-600" id="pt-bank-name">–</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            <span class="font-mono font-bold text-slate-800 text-base tracking-wider" id="pt-number-display">–</span>
                            <button type="button" onclick="copyVAPesanan()" class="flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition cursor-pointer">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                            </button>
                        </div>
                    </div>

                    <div id="pt-box-qr-code" class="hidden bg-white border border-slate-200 rounded-2xl p-4 text-center">
                        <p class="text-xs text-slate-400 mb-3">Pindai kode QR berikut menggunakan aplikasi pembayaran kamu</p>
                        <img id="pt-qr-code-img" src="" alt="QRIS Code" class="w-40 h-40 mx-auto rounded-lg border border-slate-100 p-1">
                    </div>

                    <div id="pt-box-ewallet-info" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-500">
                        Klik tombol <strong>Bayar</strong> di bawah untuk menyelesaikan pembayaran melalui aplikasi <span id="pt-ewallet-app-name" class="font-semibold text-slate-700">e-wallet</span> kamu.
                    </div>

                    <div id="pt-box-cara-transfer" class="hidden border border-slate-200 rounded-2xl overflow-hidden">
                        <button type="button" onclick="toggleCaraPesanan()" class="w-full flex items-center justify-between px-4 py-3.5 bg-white hover:bg-slate-50 transition cursor-pointer text-left">
                            <div class="flex items-center gap-2.5 text-sm font-bold text-slate-800">
                                <i data-lucide="list-ordered" class="w-4 h-4 text-blue-600"></i>
                                Cara Transfer
                            </div>
                            <i data-lucide="chevron-down" id="pt-cara-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200"></i>
                        </button>
                        <div id="pt-cara-transfer-body" class="hidden px-4 pb-4 pt-1 bg-slate-50/60 border-t border-slate-100">
                            <div id="pt-cara-transfer-steps" class="space-y-2 text-sm text-slate-600"></div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 bg-white space-y-2 flex-shrink-0">
                    <div id="pt-btn-row-ewallet" class="hidden flex gap-2">
                        <button type="button" onclick="tutupPopupTransferPesanan()" class="flex-1 h-11 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="button" id="pt-btn-bayar-ewallet" onclick="bayarEwalletPesanan()" class="flex-1 h-11 bg-blue-600 text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition cursor-pointer">
                            <i data-lucide="wallet" class="w-4 h-4"></i> Bayar
                        </button>
                    </div>
                    <button type="button" id="pt-btn-tutup-bank" onclick="tutupPopupTransferPesanan()" class="w-full h-11 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition cursor-pointer">
                        Tutup
                    </button>
                    <div class="text-center">
                        <button type="button" id="pt-btn-selesai-bank" onclick="selesaikanBayarBankPesanan()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition cursor-pointer bg-transparent border-none p-0">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>

            <!-- KONDISI 2: BERHASIL -->
            <div id="pt-state-success" class="hidden flex flex-col overflow-hidden">
                <div class="bg-gradient-to-br from-emerald-400 to-teal-500 px-6 pt-8 pb-6 text-center text-white flex-shrink-0">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="circle-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold">Pembayaran Berhasil!</h2>
                    <p class="text-xs text-white/80 mt-1">Pesananmu sedang dikemas oleh tim kami</p>
                </div>
                <div class="overflow-y-auto flex-1 px-5 py-4 space-y-1 hide-scrollbar">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Nama Pemesan</span>
                        <span class="text-xs font-bold text-slate-700" id="pt-success-nama-pemesan">–</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Pesanan</span>
                        <span class="text-xs font-bold text-slate-700" id="pt-success-no-pesanan">–</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Waktu Pemesanan</span>
                        <span class="text-xs font-bold text-slate-700" id="pt-success-waktu">–</span>
                    </div>
                    <div id="pt-success-row-va" class="hidden flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Virtual Account</span>
                        <span class="text-xs font-bold text-slate-700 font-mono" id="pt-success-va-number">–</span>
                    </div>
                    <div id="pt-success-row-hp" class="hidden flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">No. Pembayar</span>
                        <span class="text-xs font-bold text-slate-700" id="pt-success-no-hp">–</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-xs text-slate-400 font-medium">Metode Pembayaran</span>
                        <span class="text-xs font-bold text-slate-700" id="pt-success-metode">–</span>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center mt-3">
                        <p class="text-xs text-emerald-600 font-semibold mb-1">Total Pembayaran</p>
                        <p class="text-xl font-extrabold text-emerald-700" id="pt-success-total">Rp 0</p>
                    </div>
                </div>
                <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex-shrink-0">
                    <button type="button" onclick="selesaiKonfirmasiPesanan()" class="w-full h-11 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition cursor-pointer">
                        Selesai
                    </button>
                </div>
            </div>

            <!-- KONDISI 3: GAGAL / KEDALUWARSA -->
            <div id="pt-state-failed" class="hidden flex flex-col">
                <div class="bg-gradient-to-br from-red-400 to-rose-500 px-6 pt-8 pb-6 text-center text-white">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-3 backdrop-blur-sm">
                        <i data-lucide="circle-x" class="w-8 h-8 text-white"></i>
                    </div>
                    <h2 class="text-lg font-extrabold" id="pt-failed-title">Pembayaran Gagal</h2>
                    <p class="text-xs text-white/80 mt-1" id="pt-failed-desc">Waktu pembayaran telah habis</p>
                </div>
                <div class="px-6 py-5 text-center space-y-3">
                    <p class="text-sm text-slate-500 leading-relaxed">Pesanan otomatis dibatalkan. Silakan buat pesanan baru jika ingin melanjutkan.</p>
                </div>
                <div class="px-5 pb-6 space-y-2">
                    <button type="button" onclick="window.location.href='pemesanan.php?tab=pending'" class="w-full h-11 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition cursor-pointer">
                        Tutup
                    </button>
                    <a href="pemesanan.php?tab=dibatalkan" class="w-full h-9 text-xs font-semibold text-slate-400 hover:text-slate-600 transition flex items-center justify-center">
                        Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Init Lucide icons
        lucide.createIcons();

        // ===== Data referensi metode pembayaran (samakan dengan checkout.php) =====
        const PAYMENT_INFO = {
            cod:       { label: 'Cash on Delivery (COD)', img: 'cod.png',       type: 'cod' },
            qris:      { label: 'QRIS All Payment',       img: 'qris.png',      type: 'ewallet' },
            dana:      { label: 'DANA E-Wallet',          img: 'dana.png',      type: 'ewallet' },
            gopay:     { label: 'GOPAY E-Wallet',         img: 'gopay.webp',    type: 'ewallet' },
            ovo:       { label: 'OVO E-Wallet',           img: 'ovo.png',       type: 'ewallet' },
            shopeepay: { label: 'ShopeePay',              img: 'shopeepay.webp',type: 'ewallet' },
            bca:       { label: 'BCA Virtual Account',    img: 'bca.jpg',       type: 'va' },
            bni:       { label: 'BNI Virtual Account',    img: 'bni.png',       type: 'va' },
            bri:       { label: 'BRI Virtual Account',    img: 'bri.png',       type: 'va' },
            mandiri:   { label: 'Mandiri Virtual Account',img: 'mandiri.png',   type: 'va' },
        };

        const caraBayarPesanan = {
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

        let vaTimerInterval         = null;
        let currentOrderIdGlobal    = null;
        let currentOrderTotalGlobal = 0;
        let currentOrderDetailsGlobal = {};
        let orderIdToCancel         = null;
        const VA_DURATION = 60 * 60; // 1 jam dalam detik

        function formatRupiah(angka) {
            return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function getOrderData(el) {
            const card = el.closest('.order-card');
            return JSON.parse(card.dataset.order);
        }

        function showToastPesanan(msg) {
            const t = document.createElement('div');
            t.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-xl z-[200] transition-opacity duration-300';
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 2500);
        }

        // ===================== DETAIL PESANAN =====================
        function bukaDetailPesanan(btn) {
            const d    = getOrderData(btn);
            const info = PAYMENT_INFO[(d.metode || 'cod').toLowerCase()] || PAYMENT_INFO.cod;

            document.getElementById('detail-nama-pemesan').textContent = d.nama || '–';
            document.getElementById('detail-no-pesanan').textContent   = '#' + String(d.id).padStart(6, '0');
            document.getElementById('detail-waktu').textContent        = d.waktu || '–';
            document.getElementById('detail-metode').textContent       = info.label;
            document.getElementById('detail-total').textContent        = formatRupiah(d.total);

            const rowVA = document.getElementById('detail-row-va');
            const rowHP = document.getElementById('detail-row-hp');
            rowVA.classList.add('hidden');
            rowHP.classList.add('hidden');

            if (info.type === 'va') {
                rowVA.classList.remove('hidden');
                document.getElementById('detail-va-number').textContent = d.vaNumber || '–';
            } else if (info.type === 'ewallet') {
                rowHP.classList.remove('hidden');
                document.getElementById('detail-no-hp').textContent = d.noHp || '–';
            }

            document.getElementById('modal-detail-pesanan').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function tutupDetailPesanan() {
            document.getElementById('modal-detail-pesanan').classList.add('hidden');
        }

        // ===================== BAYAR SEKARANG =====================
        function prosesBayarSekarang(btn) {
            const d    = getOrderData(btn);
            const info = PAYMENT_INFO[(d.metode || 'cod').toLowerCase()] || PAYMENT_INFO.cod;

            currentOrderIdGlobal      = d.id;
            currentOrderTotalGlobal   = d.total;
            currentOrderDetailsGlobal = {
                orderId: d.id, vaNumber: d.vaNumber, metodeLabel: info.label,
                payType: info.type, nama: d.nama, noHp: d.noHp
            };

            if (info.type === 'cod') {
                // COD seharusnya sudah berstatus 'diproses' sejak checkout, jaga-jaga saja
                konfirmasiBayarPesanan();
                return;
            }
            bukaPopupTransferPesanan(d, info);
        }

        function bukaPopupTransferPesanan(d, info) {
            document.getElementById('pt-total-display').textContent  = formatRupiah(d.total);
            document.getElementById('pt-number-display').textContent = d.vaNumber || '–';
            document.getElementById('pt-bank-name').textContent       = info.label;
            document.getElementById('pt-bank-img').src                = `../assets/img/payment-method/${info.img}`;
            document.getElementById('pt-ewallet-app-name').textContent= info.label;

            const boxVA            = document.getElementById('pt-box-va-number');
            const boxQR            = document.getElementById('pt-box-qr-code');
            const boxCara          = document.getElementById('pt-box-cara-transfer');
            const boxEwallet       = document.getElementById('pt-box-ewallet-info');
            const btnRowEwallet    = document.getElementById('pt-btn-row-ewallet');
            const btnTutupBank     = document.getElementById('pt-btn-tutup-bank');
            const btnSelesaiBank   = document.getElementById('pt-btn-selesai-bank');
            const btnBayarEwallet  = document.getElementById('pt-btn-bayar-ewallet');
            const infoKodeUnik     = document.getElementById('pt-info-kode-unik');
            const kodeUnikDisplay  = document.getElementById('pt-kode-unik-display');
            const paymentId        = (d.metode || '').toLowerCase();

            if (info.type === 'va') {
                boxVA.classList.remove('hidden');
                boxCara.classList.remove('hidden');
                boxQR.classList.add('hidden');
                boxEwallet.classList.add('hidden');
                btnRowEwallet.classList.add('hidden');
                btnTutupBank.classList.remove('hidden');
                btnSelesaiBank.closest('div').classList.remove('hidden');
                btnSelesaiBank.disabled = false;
                btnSelesaiBank.textContent = 'Selesai';
                infoKodeUnik.classList.add('hidden');

                const steps = caraBayarPesanan[paymentId] || [
                    "Buka aplikasi mobile banking kamu.",
                    "Pilih menu Transfer / Bayar Virtual Account.",
                    "Masukkan nomor Virtual Account yang tertera.",
                    "Konfirmasi nominal dan selesaikan transaksi."
                ];
                document.getElementById('pt-cara-transfer-steps').innerHTML = steps.map((s, i) =>
                    `<div class="flex gap-3 items-start">
                        <span class="flex-shrink-0 w-5 h-5 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5">${i+1}</span>
                        <span>${s}</span>
                    </div>`
                ).join('');
            } else {
                boxVA.classList.add('hidden');
                boxCara.classList.add('hidden');
                boxEwallet.classList.remove('hidden');
                btnRowEwallet.classList.remove('hidden');
                btnTutupBank.classList.add('hidden');
                btnSelesaiBank.closest('div').classList.add('hidden');
                btnBayarEwallet.disabled = false;
                btnBayarEwallet.innerHTML = '<i data-lucide="wallet" class="w-4 h-4"></i> Bayar';

                const kodeUnik = String(Math.floor(100 + Math.random() * 900));
                kodeUnikDisplay.textContent = kodeUnik;
                infoKodeUnik.classList.remove('hidden');

                if (paymentId === 'qris') {
                    boxQR.classList.remove('hidden');
                    document.getElementById('pt-qr-code-img').src =
                        `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent('QRIS|ORDER:' + d.id + '|TOTAL:' + d.total + '|KODE:' + kodeUnik)}`;
                } else {
                    boxQR.classList.add('hidden');
                }
            }

            document.getElementById('pt-cara-transfer-body').classList.add('hidden');
            document.getElementById('pt-cara-chevron').style.transform = '';

            showPTState('waiting');
            document.getElementById('popup-transfer-pesanan').classList.remove('hidden');
            startVATimerPesanan();
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function showPTState(state) {
            ['waiting', 'success', 'failed'].forEach(s => {
                document.getElementById(`pt-state-${s}`).classList.add('hidden');
            });
            document.getElementById(`pt-state-${state}`).classList.remove('hidden');
        }

        function startVATimerPesanan() {
            let sisa = VA_DURATION;
            clearInterval(vaTimerInterval);
            document.getElementById('pt-timer-bar').style.width = '100%';
            vaTimerInterval = setInterval(() => {
                sisa--;
                const mnt = String(Math.floor(sisa / 60)).padStart(2, '0');
                const dtk = String(sisa % 60).padStart(2, '0');
                document.getElementById('pt-timer').textContent = `${mnt}:${dtk}`;
                document.getElementById('pt-timer-bar').style.width = `${(sisa / VA_DURATION) * 100}%`;

                if (sisa <= 0) {
                    clearInterval(vaTimerInterval);
                    fetch(`pemesanan.php?ajax_action=batalkan_pesanan&order_id=${currentOrderIdGlobal}`).catch(() => {});
                    document.getElementById('pt-failed-title').textContent = 'Waktu Habis';
                    document.getElementById('pt-failed-desc').textContent  = 'Waktu pembayaran 1 jam telah habis';
                    showPTState('failed');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }, 1000);
        }

        function selesaikanBayarBankPesanan() {
            const btn = document.getElementById('pt-btn-selesai-bank');
            btn.textContent = 'Memproses...';
            btn.disabled = true;
            konfirmasiBayarPesanan();
        }

        function bayarEwalletPesanan() {
            const btn = document.getElementById('pt-btn-bayar-ewallet');
            btn.innerHTML = '<i data-lucide="loader-circle" class="w-4 h-4 animate-spin"></i> Memproses...';
            btn.disabled = true;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            konfirmasiBayarPesanan();
        }

        function konfirmasiBayarPesanan() {
            fetch(`pemesanan.php?ajax_action=konfirmasi_bayar&order_id=${currentOrderIdGlobal}`)
                .then(r => r.json())
                .then(data => {
                    clearInterval(vaTimerInterval);
                    if (data.status === 'success') {
                        bukaPopupSuksesPesanan();
                    } else {
                        showToastPesanan(data.message || 'Gagal memproses pembayaran.');
                        resetTombolBayar();
                    }
                })
                .catch(() => {
                    showToastPesanan('Terjadi kesalahan koneksi.');
                    resetTombolBayar();
                });
        }

        function resetTombolBayar() {
            const btnBank = document.getElementById('pt-btn-selesai-bank');
            const btnEw   = document.getElementById('pt-btn-bayar-ewallet');
            if (btnBank) { btnBank.textContent = 'Selesai'; btnBank.disabled = false; }
            if (btnEw)   {
                btnEw.innerHTML = '<i data-lucide="wallet" class="w-4 h-4"></i> Bayar';
                btnEw.disabled = false;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        function bukaPopupSuksesPesanan() {
            const d = currentOrderDetailsGlobal;
            document.getElementById('pt-success-total').textContent        = formatRupiah(currentOrderTotalGlobal);
            document.getElementById('pt-success-nama-pemesan').textContent = d.nama || '–';
            document.getElementById('pt-success-no-pesanan').textContent   = '#' + String(d.orderId).padStart(6, '0');
            document.getElementById('pt-success-waktu').textContent        = new Date().toLocaleString('id-ID', {dateStyle: 'short', timeStyle: 'short'});
            document.getElementById('pt-success-metode').textContent       = d.metodeLabel || '–';

            const rowVA = document.getElementById('pt-success-row-va');
            const rowHP = document.getElementById('pt-success-row-hp');
            rowVA.classList.add('hidden');
            rowHP.classList.add('hidden');

            if (d.payType === 'va') {
                rowVA.classList.remove('hidden');
                document.getElementById('pt-success-va-number').textContent = d.vaNumber || '–';
            } else if (d.payType === 'ewallet') {
                rowHP.classList.remove('hidden');
                document.getElementById('pt-success-no-hp').textContent = d.noHp || '–';
            }

            showPTState('success');
            document.getElementById('popup-transfer-pesanan').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function tutupPopupTransferPesanan() {
            clearInterval(vaTimerInterval);
            document.getElementById('popup-transfer-pesanan').classList.add('hidden');
        }

        function selesaiKonfirmasiPesanan() {
            document.getElementById('popup-transfer-pesanan').classList.add('hidden');
            window.location.href = 'pemesanan.php?tab=diproses';
        }

        function copyVAPesanan() {
            const va = document.getElementById('pt-number-display').textContent;
            navigator.clipboard.writeText(va).then(() => showToastPesanan('Nomor VA berhasil disalin!'));
        }

        function toggleCaraPesanan() {
            const body    = document.getElementById('pt-cara-transfer-body');
            const chevron = document.getElementById('pt-cara-chevron');
            const isHidden = body.classList.contains('hidden');
            body.classList.toggle('hidden', !isHidden);
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        }

        // ===================== BATALKAN PESANAN (tab Dikemas) =====================
        function batalkanPesanan(btn) {
            const d = getOrderData(btn);
            orderIdToCancel = d.id;
            document.getElementById('modal-konfirmasi-batal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function tutupKonfirmasiBatal() {
            orderIdToCancel = null;
            document.getElementById('modal-konfirmasi-batal').classList.add('hidden');
        }
        function konfirmasiBatalkanPesanan() {
            if (!orderIdToCancel) return;
            const btn = document.getElementById('btn-ya-batalkan');
            btn.textContent = 'Memproses...';
            btn.disabled = true;
            fetch(`pemesanan.php?ajax_action=batalkan_pesanan&order_id=${orderIdToCancel}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = 'pemesanan.php?tab=dibatalkan';
                    } else {
                        showToastPesanan(data.message || 'Gagal membatalkan pesanan.');
                        btn.textContent = 'Ya, Batalkan';
                        btn.disabled = false;
                    }
                })
                .catch(() => {
                    showToastPesanan('Terjadi kesalahan koneksi.');
                    btn.textContent = 'Ya, Batalkan';
                    btn.disabled = false;
                });
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

</body>
</html>