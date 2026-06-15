<?php
include '../../config/koneksi.php';
session_start();

/** @var mysqli $conn */

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'seller') {
    header('Location: ../../auth/auth.php'); 
    exit();
}

$user_id_seller = $_SESSION['user_id'];
$username_seller = $_SESSION['username'] ?? 'Penjual';

$words = explode(" ", $username_seller);
$initials = "";
foreach ($words as $w) {
    $initials .= mb_substr($w, 0, 1);
}
$initials = strtoupper(substr($initials, 0, 2));

$query_produk = "SELECT p.*, 
                 (SELECT pi.nama_file FROM product_images pi WHERE pi.product_id = p.id LIMIT 1) as gambar_utama 
                 FROM products p 
                 WHERE p.user_id = '$user_id_seller' 
                 ORDER BY p.id DESC";
$result_produk = mysqli_query($conn, $query_produk);
$total_produk_aktif = mysqli_num_rows($result_produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Seller - Segar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen flex overflow-hidden">

    <aside class="w-64 bg-white border-r border-slate-200 text-slate-700 flex flex-col flex-shrink-0 z-20 shadow-md">
        <div class="p-5 border-b border-slate-100 flex items-center space-x-3">
            <img src="../../assets/img/logo.png" alt="Logo" class="w-10 h-8 object-contain bg-slate-50 border border-slate-200 rounded p-1" style="transform: scaleX(1.2)">
            <div>
                <span class="text-xl font-bold text-blue-600 block leading-none">Se<span class="text-slate-800">gar</span></span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Seller Panel</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="../../index.php" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-500 hover:bg-slate-100 hover:text-slate-900 mb-4 border border-dashed border-slate-200">
                <i class="fa-solid fa-arrow-left-long w-5 text-center text-slate-400"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <button onclick="switchTab('dashboard')" id="menu-dashboard" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm bg-blue-600 text-white shadow-sm shadow-blue-200">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span>Dashboard</span>
            </button>

            <button onclick="switchTab('produk')" id="menu-produk" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-box w-5 text-center text-slate-400"></i>
                <span>Produk Anda</span>
            </button>

            <button onclick="switchTab('pengiriman')" id="menu-pengiriman" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-truck-fast w-5 text-center text-slate-400"></i>
                <span>Pengiriman</span>
            </button>

            <button onclick="switchTab('pesan')" id="menu-pesan" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-comments w-5 text-center text-slate-400"></i>
                <span>Pesan Pelanggan</span>
            </button>

            <button onclick="switchTab('finance')" id="menu-finance" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-wallet w-5 text-center text-slate-400"></i>
                <span>Finance</span>
            </button>
        </nav>

        <div class="p-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shadow-inner">
                    <?= $initials; ?>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-800 block leading-none max-w-[140px] truncate"><?= htmlspecialchars($username_seller); ?></span>
                    <span class="text-[10px] text-emerald-600 font-medium flex items-center mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full inline-block mr-1"></span>Penjual (Seller)
                    </span>
                </div>
            </div>
            <a href="../../logout.php" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg transition" title="Keluar">
                <i class="fa-solid fa-right-from-bracket text-sm"></i>
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between flex-shrink-0">
            <h1 id="page-title" class="text-lg font-bold text-slate-900">Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full font-medium">
                    <i class="fa-regular fa-calendar mr-1"></i> <?= date('d M Y'); ?>
                </span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">

            <!-- ALERT NOTIFIKASI SYSTEM (Tambahkan Bagian Ini) -->
            <?php if (isset($_SESSION['success']) || isset($_GET['status'])): 
                $msg = $_SESSION['success'] ?? 'Aksi berhasil dieksekusi!';
                if(isset($_GET['status'])) {
                    if($_GET['status'] === 'edit_success') $msg = "Informasi katalog produk berhasil diperbarui!";
                    if($_GET['status'] === 'delete_success') $msg = "Produk hasil laut berhasil dihapus dari katalog.";
                }
            ?>
                <div id="toast-success" class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center space-x-3 text-emerald-800 transition-all duration-500 shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold">Aksi Berhasil</p>
                        <p class="text-xs text-emerald-600 font-medium"><?= $msg; ?></p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-700 p-1 rounded-lg transition"><i class="fa-solid fa-xmark text-xs"></i></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center space-x-3 text-red-800 animate-fade-in shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-red-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold">Terjadi Kesalahan</p>
                        <p class="text-xs text-red-600 font-medium"><?= $_SESSION['error']; ?></p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700 p-1 rounded-lg transition"><i class="fa-solid fa-xmark text-xs"></i></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <!-- AKHIR ALERT NOTIFIKASI -->

            <div id="tab-dashboard" class="tab-content space-y-6">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-md relative overflow-hidden">
                    <div class="relative z-10 max-w-xl">
                        <h2 class="text-2xl font-bold">Selamat Datang Kembali, <?= htmlspecialchars($username_seller); ?>!</h2>
                        <p class="text-slate-400 text-sm mt-1">Pantau perkembangan performa tokomu, kelola stok hasil laut yang kamu upload, dan pantau pengiriman pesanan hari ini.</p>
                    </div>
                    <i class="fa-solid fa-shrimp absolute right-6 bottom-[-20px] text-8xl text-slate-800/50 transform -rotate-12 pointer-events-none"></i>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Produk Anda (Aktif)</span>
                            <span class="text-2xl font-bold text-slate-900 mt-1 block"><?= $total_produk_aktif; ?></span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600"><i class="fa-solid fa-box text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pengiriman Berjalan</span>
                            <span class="text-2xl font-bold text-slate-900 mt-1 block">3</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600"><i class="fa-solid fa-truck text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pesan Belum Dibaca</span>
                            <span class="text-2xl font-bold text-slate-900 mt-1 block">0</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600"><i class="fa-solid fa-comment-dots text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pendapatan Siap Cair</span>
                            <span class="text-2xl font-bold text-slate-900 mt-1 block">Rp 48.9M</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600"><i class="fa-solid fa-coins text-lg"></i></div>
                    </div>
                </div>
            </div>

            <div id="tab-produk" class="tab-content hidden space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Daftar Katalog Produk Anda</h3>
                        <p class="text-xs text-slate-500">Menampilkan hasil laut segar yang Anda upload ke sistem.</p>
                    </div>
                    <a href="create.php" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs flex items-center space-x-2 transition shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Produk</span>
                    </a>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-5">Produk</th>
                                    <th class="py-3.5 px-5">Kategori</th>
                                    <th class="py-3.5 px-5">Harga</th>
                                    <th class="py-3.5 px-5">Stok / Berat</th>
                                    <th class="py-3.5 px-5">Asal Produk</th>
                                    <th class="py-3.5 px-5">Status</th>
                                    <th class="py-3.5 px-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php if ($total_produk_aktif > 0): ?>
                                    <?php while($produk = mysqli_fetch_assoc($result_produk)): ?>
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-4 px-5">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                    <?php if(!empty($produk['gambar_utama']) && file_exists("../../assets/img/product-image/" . $produk['gambar_utama'])): ?>
                                                        <img src="../../assets/img/product-image/<?= $produk['gambar_utama']; ?>" alt="Foto Produk" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-fish text-xl text-slate-300"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-slate-900 block"><?= htmlspecialchars($produk['nama_produk']); ?></span>
                                                    <span class="text-[11px] text-slate-400 font-mono block max-w-[180px] truncate"><?= htmlspecialchars($produk['slug']); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-5">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60 inline-block">
                                                <?= htmlspecialchars($produk['kategori'] ?? 'Lainnya'); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-5 font-semibold text-slate-700">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                                        <td class="py-4 px-5">
                                            <span class="text-slate-700 block font-medium"><?= $produk['stok']; ?> <span class="text-xs text-slate-400 font-normal"><?= htmlspecialchars($produk['satuan'] ?? 'kg'); ?></span></span>
                                            <span class="text-[11px] text-slate-400 block mt-0.5"><?= number_format($produk['berat'], 2); ?> kg</span>
                                        </td>
                                        <td class="py-4 px-5">
                                            <span class="text-xs text-slate-600 block"><?= htmlspecialchars($produk['asal_produk'] ?? 'Tangkapan Harian'); ?></span>
                                            <?php if(!empty($produk['badge'])): ?>
                                                <span class="inline-block mt-1 text-[9px] font-extrabold bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded border border-amber-200/50 uppercase tracking-wide"><?= htmlspecialchars($produk['badge']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-5">
                                            <?php if($produk['status'] === 'aktif'): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50">Aktif</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">Arsip</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-5">
                                            <div class="flex items-center justify-center space-x-1.5">
                                                <!-- Tombol Edit: Mengarah ke halaman form edit dengan parameter ID -->
                                                <a href="edit.php?id=<?= $produk['id']; ?>" 
                                                class="p-2 border border-slate-200 hover:border-blue-600 hover:bg-blue-50 text-slate-500 hover:text-blue-600 rounded-xl transition" 
                                                title="Edit Produk">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </a>
                                                
                                                <!-- Tombol Hapus: Mengarah ke delete.php dengan konfirmasi pop-up browser -->
                                                <a href="delete.php?id=<?= $produk['id']; ?>" 
                                                onclick="return confirm('Apakah Anda benar-benar yakin ingin menghapus produk \'<?= addslashes($produk['nama_produk']); ?>\' dari katalog Anda? Semua gambar produk terkait juga akan terhapus fisik.')" 
                                                class="p-2 border border-slate-200 hover:border-red-600 hover:bg-red-50 text-slate-500 hover:text-red-600 rounded-xl transition" 
                                                title="Hapus Produk">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-slate-400 bg-slate-50/50">
                                            <i class="fa-solid fa-box-open text-4xl block mb-3 text-slate-300"></i>
                                            <span class="text-sm">Belum ada produk hasil laut yang Anda upload.</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-pengiriman" class="tab-content hidden space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Manajemen Pengiriman Pesanan</h3>
                    <p class="text-xs text-slate-500">Kelola siklus status pengantaran komoditas hasil laut ke pembeli.</p>
                </div>

                <div class="flex border-b border-slate-200 bg-white px-4 pt-2.5 rounded-2xl shadow-sm border border-slate-200/60">
                    <button onclick="switchSubTab('dikemas')" id="sub-btn-dikemas" class="sub-nav-btn pb-3 px-4 font-semibold text-xs border-b-2 border-blue-600 text-blue-600 transition flex items-center space-x-2">
                        <i class="fa-solid fa-cubes-stacked"></i>
                        <span>Dikemas</span>
                        <span class="bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">2</span>
                    </button>
                    <button onclick="switchSubTab('dikirim')" id="sub-btn-dikirim" class="sub-nav-btn pb-3 px-4 font-medium text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center space-x-2">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <span>Dikirim</span>
                        <span class="bg-slate-100 text-slate-600 text-[10px] px-1.5 py-0.2 rounded-full font-bold">1</span>
                    </button>
                    <button onclick="switchSubTab('diterima')" id="sub-btn-diterima" class="sub-nav-btn pb-3 px-4 font-medium text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Diterima</span>
                    </button>
                    <button onclick="switchSubTab('gagal')" id="sub-btn-gagal" class="sub-nav-btn pb-3 px-4 font-medium text-xs border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center space-x-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <span>Gagal</span>
                    </button>
                </div>

                <div id="sub-tab-dikemas" class="sub-tab-content space-y-4">
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3 text-xs text-slate-400">
                            <span>No. Invoice: <strong class="text-slate-700">INV/2026/0612-01</strong></span>
                            <span>Waktu Order: 12 Jun 2026, 14:20 WITA</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-slate-50 border rounded-lg flex items-center justify-center text-blue-600"><i class="fa-solid fa-shrimp text-lg"></i></div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Udang Vaname Premium Windu (1.5 kg)</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Penerima: <span class="text-slate-700 font-medium">Andi Wijaya</span> — Denpasar, Bali</p>
                                </div>
                            </div>
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">Atur Resi Pengiriman</button>
                        </div>
                    </div>
                </div>

                <div id="sub-tab-dikirim" class="sub-tab-content hidden space-y-4">
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3 text-xs text-slate-400">
                            <span>No. Invoice: <strong class="text-slate-700">INV/2026/0610-88</strong></span>
                            <span>Kurir: <strong class="text-slate-700">JNE Yes (Resi: 88201920122)</strong></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-slate-50 border rounded-lg flex items-center justify-center text-blue-600"><i class="fa-solid fa-fish text-lg"></i></div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Ikan Kembung Segar Hasil Tangkapan Harian (3.0 kg)</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Status: <span class="text-amber-600 font-medium bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/40">Sedang Kurir Antar ke Lokasi</span></p>
                                </div>
                            </div>
                            <button class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition">Lacak Posisi</button>
                        </div>
                    </div>
                </div>

                <div id="sub-tab-diterima" class="sub-tab-content hidden text-center py-10 bg-white rounded-2xl border border-slate-200/60">
                    <i class="fa-solid fa-boxes-packing text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-sm text-slate-400 italic">Belum ada komoditas pesanan selesai di termin ini.</p>
                </div>

                <div id="sub-tab-gagal" class="sub-tab-content hidden text-center py-10 bg-white rounded-2xl border border-slate-200/60">
                    <i class="fa-solid fa-triangle-exclamation text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-sm text-slate-400 italic">Tidak ada catatan pengantaran barang yang dibatalkan / gagal.</p>
                </div>
            </div>

            <div id="tab-pesan" class="tab-content hidden text-center py-12 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <i class="fa-regular fa-comment-dots text-5xl text-slate-300 block mb-3"></i>
                <h4 class="text-slate-800 font-semibold text-sm">Belum Ada Chat Baru</h4>
                <p class="text-xs text-slate-400 mt-1">Pertanyaan pelanggan seputar spesifikasi hasil laut akan muncul di sini.</p>
            </div>

            <div id="tab-finance" class="tab-content hidden text-center py-12 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <i class="fa-solid fa-wallet text-5xl text-slate-300 block mb-3"></i>
                <h4 class="text-slate-800 font-semibold text-sm">Laporan Saldo Finansial Toko</h4>
                <p class="text-xs text-slate-400 mt-1">Histori penarikan uang dan dana transaksi escrow order Anda.</p>
            </div>

        </main>
    </div>

    <script src="../../assets/js/dashboard.js">
        
    </script>
</body>
</html> 