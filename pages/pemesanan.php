<?php
    include '../config/koneksi.php'; 
    session_start();
    /** @var mysqli $conn */

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
            <h1 class="text-xl font-bold text-blue-950 tracking-tight">Pemesanan</h1>
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
        $user_id    = $_SESSION['user_id'] ?? 0;
        $status_now = mysqli_real_escape_string($conn, $active_tab);

        $sql = "SELECT 
                o.id,
                o.total_harga,
                o.status,
                o.created_at,
                a.nama_penerima, 
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
                    $sql_items = "SELECT oi.qty, oi.harga, p.nama_produk, p.satuan,
                                         (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar
                                  FROM order_items oi
                                  JOIN products p ON p.id = oi.product_id
                                  WHERE oi.order_id = $oid
                                  LIMIT 3";
                    $res_items = mysqli_query($conn, $sql_items);
                ?>

                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-sm transition-shadow duration-200">

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
                                <a href="payment.php?order=<?= $order['id']; ?>"
                                   class="h-9 px-4 bg-blue-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-blue-700 transition shadow-sm shadow-blue-600/10">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Bayar Sekarang
                                </a>
                            <?php elseif ($active_tab === 'dikirim'): ?>
                                <a href="track.php?order=<?= $order['id']; ?>"
                                   class="h-9 px-4 bg-indigo-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-indigo-700 transition">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Lacak
                                </a>
                            <?php elseif ($active_tab === 'selesai'): ?>
                                <a href="kategori.php"
                                   class="h-9 px-4 bg-green-600 text-white text-xs font-semibold rounded-lg flex items-center gap-1.5 hover:bg-green-700 transition">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Beli Lagi
                                </a>
                            <?php endif; ?>

                            <a href="order-detail.php?id=<?= $order['id']; ?>"
                               class="h-9 px-4 bg-white text-slate-600 text-xs font-semibold border border-slate-300 rounded-lg flex items-center gap-1.5 hover:bg-slate-50 transition">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Detail
                            </a>
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

    <script>
        // Init Lucide icons
        lucide.createIcons();
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

</body>
</html>