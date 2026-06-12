<?php
session_start();
require_once '../../config/koneksi.php';

// Proteksi halaman admin (opsional, sesuaikan dengan sistem session-mu)
// if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Segar</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-950 text-white flex flex-col flex-shrink-0 z-20 shadow-xl">
        <!-- Logo & Brand -->
        <div class="p-5 border-b border-blue-900 flex items-center space-x-3">
            <img src="../../assets/img/logo.png" alt="Logo" class="w-10 h-8 object-contain bg-white rounded p-1" style="transform: scaleX(1.2)">
            <div>
                <span class="text-lg font-bold text-blue-400 block leading-none">Se<span class="text-white">gar</span></span>
                <span class="text-[10px] text-blue-300 block mt-0.5">Admin Panel</span>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <!-- Link Kembali ke Beranda Utama Website -->
            <a href="../../index.php" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-slate-400 hover:bg-slate-900/40 hover:text-white mb-4 border border-dashed border-slate-700/60">
                <i class="fa-solid fa-arrow-left-long w-5 text-center text-slate-500 group-hover:text-white"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <button onclick="switchTab('dashboard')" id="menu-dashboard" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm bg-blue-600 text-white">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span>Dashboard</span>
            </button>

            <button onclick="switchTab('produk')" id="menu-produk" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-blue-200 hover:bg-blue-900/50 hover:text-white">
                <i class="fa-solid fa-box w-5 text-center"></i>
                <span>Produk</span>
            </button>

            <button onclick="switchTab('pengiriman')" id="menu-pengiriman" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-blue-200 hover:bg-blue-900/50 hover:text-white">
                <i class="fa-solid fa-truck-fast w-5 text-center"></i>
                <span>Pengiriman</span>
            </button>

            <button onclick="switchTab('pesan')" id="menu-pesan" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-blue-200 hover:bg-blue-900/50 hover:text-white">
                <i class="fa-solid fa-comments w-5 text-center"></i>
                <span>Pesan Pelanggan</span>
            </button>

            <button onclick="switchTab('finance')" id="menu-finance" class="nav-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition font-medium text-sm text-blue-200 hover:bg-blue-900/50 hover:text-white">
                <i class="fa-solid fa-wallet w-5 text-center"></i>
                <span>Finance</span>
            </button>
        </nav>

        <!-- Bagian Bawah / User Info -->
        <div class="p-4 border-t border-blue-900 bg-blue-950/80 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white shadow-inner">
                    AD
                </div>
                <div>
                    <span class="text-xs font-semibold block leading-none">Admin Segar</span>
                    <span class="text-[10px] text-blue-400">Owner Akun</span>
                </div>
            </div>
            <a href="logout.php" class="text-blue-300 hover:text-red-400 p-1.5 rounded-lg transition" title="Keluar">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between flex-shrink-0">
            <h1 id="page-title" class="text-lg font-bold text-blue-950">Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full font-medium">
                    <i class="fa-regular fa-calendar mr-1"></i> <?= date('d M Y'); ?>
                </span>
            </div>
        </header>

        <!-- Konten Dinamis -->
        <main class="flex-1 overflow-y-auto p-6 bg-slate-50">

            <!-- Dashboard -->
            <div id="tab-dashboard" class="tab-content space-y-6">
                <!-- Banner Selamat Datang -->
                <div class="bg-gradient-to-r refinement bg-blue-950 text-white p-6 rounded-2xl shadow-md relative overflow-hidden">
                    <div class="relative z-10 max-w-xl">
                        <h2 class="text-2xl font-bold">Selamat Datang Kembali, Admin!</h2>
                        <p class="text-blue-200 text-sm mt-1">Pantau perkembangan performa toko, kelola stok produk hasil laut, dan selesaikan pengiriman pelanggan hari ini dalam satu dashboard.</p>
                    </div>
                    <i class="fa-solid fa-shrimp absolute right-6 bottom-[-20px] text-8xl text-blue-900/40 transform -rotate-12"></i>
                </div>

                <!-- Ringkasan Statistik Singkat -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Total Produk Aktif</span>
                            <span class="text-2xl font-bold text-blue-950 mt-1 block">124</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600"><i class="fa-solid fa-box text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pengiriman Berjalan</span>
                            <span class="text-2xl font-bold text-blue-950 mt-1 block">18</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600"><i class="fa-solid fa-truck text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pesan Belum Dibaca</span>
                            <span class="text-2xl font-bold text-blue-950 mt-1 block">0</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600"><i class="fa-solid fa-comment-dots text-lg"></i></div>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block">Pendapatan Bulan Ini</span>
                            <span class="text-2xl font-bold text-blue-950 mt-1 block">Rp 14.2M</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600"><i class="fa-solid fa-coins text-lg"></i></div>
                    </div>
                </div>
            </div>

            <!-- Produk Section -->
            <div id="tab-produk" class="tab-content hidden space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-blue-950">Daftar Katalog Produk Anda</h3>
                        <p class="text-xs text-slate-500">Menampilkan produk hasil laut segar yang Anda kelola.</p>
                    </div>
                    <a href="create.php" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs flex items-center space-x-2 transition shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Produk</span>
                    </a>
                </div>

                <!-- Tabel Data Produk -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-5">Produk</th>
                                    <th class="py-3.5 px-5">Harga</th>
                                    <th class="py-3.5 px-5">Stok / Berat</th>
                                    <th class="py-3.5 px-5">Asal & Badge</th>
                                    <th class="py-3.5 px-5">Status</th>
                                    <th class="py-3.5 px-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-11 h-11 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                                                <img src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?w=150&auto=format&fit=crop&q=60" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <span class="font-semibold text-blue-950 block">Ikan Kembung Segar</span>
                                                <span class="text-xs text-slate-400 block max-w-[180px] truncate">ikan-kembung-segar</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 font-semibold text-slate-700">Rp 35.000</td>
                                    <td class="py-4 px-5">
                                        <span class="text-slate-700 block">45 <span class="text-xs text-slate-400">pack</span></span>
                                        <span class="text-xs text-slate-400 block">1.00 kg</span>
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="text-xs text-slate-600 block">Tangkapan Harian</span>
                                        <span class="inline-block mt-0.5 text-[9px] font-extrabold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded">TERLARIS</span>
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Aktif</span>
                                    </td>
                                    <td class="py-4 px-5">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button class="p-1.5 border border-slate-200 hover:border-blue-600 hover:bg-blue-50 text-slate-500 hover:text-blue-600 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                            <button class="p-1.5 border border-slate-200 hover:border-red-600 hover:bg-red-50 text-slate-500 hover:text-red-600 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash text-xs"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pengiriman Section -->
            <div id="tab-pengiriman" class="tab-content hidden space-y-6">
                <div>
                    <h3 class="text-base font-bold text-blue-950">Status Logistik Pengiriman</h3>
                    <p class="text-xs text-slate-500">Kelola pemrosesan pesanan berdasarkan tahap operasional pengiriman barang.</p>
                </div>

                <!-- Kontrol Navigasi Sub-Tab -->
                <div class="flex border-b border-slate-200 max-w-md">
                    <button onclick="switchShipmentSubTab('dikemas')" id="sub-btn-dikemas" class="shipment-sub-btn flex-1 text-center py-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition">
                        Dikemas <span class="ml-1 text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full font-extrabold">3</span>
                    </button>
                    <button onclick="switchShipmentSubTab('dikirim')" id="sub-btn-dikirim" class="shipment-sub-btn flex-1 text-center py-3 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition">
                        Dikirim <span class="ml-1 text-xs bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded-full font-extrabold">2</span>
                    </button>
                    <button onclick="switchShipmentSubTab('diterima')" id="sub-btn-diterima" class="shipment-sub-btn flex-1 text-center py-3 text-sm font-semibold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition">
                        Diterima <span class="ml-1 text-xs bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded-full font-extrabold">12</span>
                    </button>
                </div>

                <!-- AREA DAFTAR KONTEN SUB-TAB (3 KOLOM KE BAWAH) -->
                
                <!-- SUB-KONTEN: DIKEMAS -->
                <div id="sub-tab-dikemas" class="shipment-sub-content grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-mono text-slate-400">#TRX-90112</span>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Gojek Instan</span>
                            </div>
                            <h4 class="text-sm font-bold text-blue-950">Andi Wijaya (3.5 kg)</h4>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">Jl. Danau Sunter Utara No. 12, Tanjung Priok, Jakarta Utara</p>
                        </div>
                        <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Atur Pickup Kurir</button>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-mono text-slate-400">#TRX-90115</span>
                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Grab Express</span>
                            </div>
                            <h4 class="text-sm font-bold text-blue-950">Rian Hidayat (1.8 kg)</h4>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">Komp. Permata Buana Blok C4/10, Kembangan, Jakarta Barat</p>
                        </div>
                        <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Atur Pickup Kurir</button>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-mono text-slate-400">#TRX-90120</span>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Kurir Toko</span>
                            </div>
                            <h4 class="text-sm font-bold text-blue-950">CV. Boga Utama (15.0 kg)</h4>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">Ruko Paramount Dotcom Blok B No.3, Gading Serpong, Tangerang</p>
                        </div>
                        <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">Serahkan ke Driver</button>
                    </div>
                </div>

                <!-- SUB-KONTEN: DIKIRIM -->
                <div id="sub-tab-dikirim" class="shipment-sub-content hidden grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3 border-l-4 border-l-amber-500">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">#TRX-88902</span>
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Kurir Internal</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-blue-950">Siti Rahma (1.2 kg)</h4>
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">Ruko Pasar Segar Blok KB/5, Graha Raya, Tangerang</p>
                        </div>
                        <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                            <span class="text-[11px] text-slate-500"><i class="fa-solid fa-user-biking mr-1 text-slate-400"></i>Driver: Budi</span>
                            <span class="text-[11px] text-amber-600 font-semibold animate-pulse"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Di Jalan</span>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3 border-l-4 border-l-amber-500">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">#TRX-88940</span>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Gojek Instan</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-blue-950">Hendrik Setiawan (4.0 kg)</h4>
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">Jl. Kemang Raya No. 45, Mampang, Jakarta Selatan</p>
                        </div>
                        <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                            <span class="text-[11px] text-slate-500"><i class="fa-solid fa-link mr-1 text-slate-400"></i><a href="#" class="text-blue-600 underline">Live Tracking</a></span>
                            <span class="text-[11px] text-amber-600 font-semibold animate-pulse"><i class="fa-solid fa-truck-moving mr-1"></i>Menuju Lokasi</span>
                        </div>
                    </div>
                </div>

                <!-- SUB-KONTEN: DITERIMA -->
                <div id="sub-tab-diterima" class="shipment-sub-content hidden grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-2 opacity-80">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">#TRX-87611</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Grab Express</span>
                        </div>
                        <h4 class="text-sm font-bold text-blue-950">Bambang Hartono</h4>
                        <p class="text-xs text-slate-400 line-clamp-1">Apartemen Medit Gajah Mada Tower B, Jakarta Barat</p>
                        <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                            <span class="text-[10px] text-slate-400">Diterima pukul 14:20</span>
                            <span class="text-xs text-emerald-600 font-bold flex items-center"><i class="fa-solid fa-circle-check mr-1"></i> Selesai</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesan -->
            <div id="tab-pesan" class="tab-content hidden h-full">
                <!-- Dikosongkan sesuai instruksi user -->
            </div>

            <!-- Finance Section -->
            <div id="tab-finance" class="tab-content hidden space-y-6">
                <div>
                    <h3 class="text-base font-bold text-blue-950">Laporan Pendapatan Keuangan</h3>
                    <p class="text-xs text-slate-500">Transparansi neraca laba kotor, dana tertahan, dan penarikan kas digital.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-gradient-to-br refinement bg-blue-600 text-white p-5 rounded-2xl shadow-md">
                        <span class="text-xs text-blue-100 font-medium opacity-80">Saldo Utama Siap Cair</span>
                        <h4 class="text-3xl font-extrabold mt-1">Rp 48.910.000</h4>
                        <button class="mt-4 px-4 py-2 bg-white text-blue-600 hover:bg-blue-50 transition rounded-xl font-bold text-xs shadow-sm">Tarik Dana Ke Rekening</button>
                    </div>
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
                        <span class="text-xs text-slate-400 font-medium block">Pendapatan Kotor Hari Ini</span>
                        <h4 class="text-2xl font-bold text-blue-950 mt-1">Rp 3.420.000</h4>
                        <span class="text-[10px] text-emerald-600 font-semibold block mt-1"><i class="fa-solid fa-arrow-trend-up mr-0.5"></i> +12.4% dibanding kemarin</span>
                    </div>
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
                        <span class="text-xs text-slate-400 font-medium block">Dana Transaksi Tertahan (Escrow)</span>
                        <h4 class="text-2xl font-bold text-blue-950 mt-1">Rp 8.150.000</h4>
                        <span class="text-[10px] text-slate-400 block mt-1">Akan cair otomatis setelah kurir konfirmasi tiba</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-blue-950">Riwayat Transaksi Terbaru</h4>
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm divide-y divide-slate-100">
                        <div class="p-4 flex items-center justify-between text-sm hover:bg-slate-50/40 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-arrow-down-long"></i></div>
                                <div>
                                    <span class="font-semibold text-blue-950 block">Penjualan #TRX-87611</span>
                                    <span class="text-xs text-slate-400 block">Metode: QRIS Otomatis</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-emerald-600 block">+ Rp 215.000</span>
                                <span class="text-[10px] text-slate-400 block">Hari Ini, 14:22</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Fungsi utama pengendali Menu Utama Sidebar
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            document.getElementById('tab-' + tabId).classList.remove('hidden');

            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('text-blue-200', 'hover:bg-blue-900/50', 'hover:text-white');
            });

            const activeBtn = document.getElementById('menu-' + tabId);
            activeBtn.classList.remove('text-blue-200', 'hover:bg-blue-900/50', 'hover:text-white');
            activeBtn.classList.add('bg-blue-600', 'text-white');

            const titleMap = {
                'dashboard': 'Dashboard',
                'produk': 'Katalog Produk',
                'pengiriman': 'Manajemen Pengiriman',
                'pesan': 'Ruang Chat Pelanggan',
                'finance': 'Laporan Keuangan & Omset'
            };
            document.getElementById('page-title').innerText = titleMap[tabId];
        }

        // Fungsi khusus pengendali Sub-Tab Internal di Menu Pengiriman
        function switchShipmentSubTab(subTabId) {
            // Sembunyikan seluruh isi daftar sub-tab pengiriman
            document.querySelectorAll('.shipment-sub-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Tampilkan sub-tab tujuan
            document.getElementById('sub-tab-' + subTabId).classList.remove('hidden');

            // Reset desain tombol sub-tab pasif
            document.querySelectorAll('.shipment-sub-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
                btn.classList.add('border-transparent', 'text-slate-400', 'font-semibold');
            });

            // Set desain tombol aktif yang sedang dipilih
            const activeSubBtn = document.getElementById('sub-btn-' + subTabId);
            activeSubBtn.classList.remove('border-transparent', 'text-slate-400', 'font-semibold');
            activeSubBtn.classList.add('border-blue-600', 'text-blue-600', 'font-bold');
        }
    </script>
</body>
</html>