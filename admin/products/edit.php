<?php
session_start();
require_once '../../config/koneksi.php';

// Proteksi akses seller
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'seller') {
    header('Location: ../../auth/auth.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: index.php?tab=produk");
    exit();
}

/** @var mysqli $conn */
$id_produk = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data produk berdasarkan ID dan user_id seller
$query_produk = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id_produk' AND user_id = '$user_id'");
$produk = mysqli_fetch_assoc($query_produk);

if (!$produk) {
    header("Location: index.php?tab=produk");
    exit();
}

// Ambil SEMUA gambar terkait produk ini
$images_list = [];
$query_images = mysqli_query($conn, "SELECT id, nama_file FROM product_images WHERE product_id = '$id_produk'");
while ($img_row = mysqli_fetch_assoc($query_images)) {
    $images_list[] = $img_row;
}

// Ekstrak string asal_produk menjadi array untuk kebutuhan check input radio
$asal_array = array_map('trim', explode(',', $produk['asal_produk']));
?>
<!DOCTYPE html>
<html lang="id">
<?php include '../../includes/head.php'; ?>
<!-- Hubungkan FontAwesome secara eksplisit jika belum ada di file head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-1">
          <img src="../../assets/img/logo.png" alt="Logo Segar" class="w-15 h-10 object-contain" style="transform: scaleX(1.5)" />
          <div>
            <span class="text-xl font-bold text-blue-600 block leading-none">Se<span class="text-blue-950">gar</span></span>
            <span class="text-xs text-gray-400 leading-none">Hasil Laut Segar, Dari Laut Ke Meja Anda</span>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 py-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-blue-950">Edit Produk</h2>
            <p class="text-slate-500 mt-1">Perbarui informasi katalog produk hasil laut Anda.</p>
        </div>

        <form id="form-edit-produk" action="process-edit.php" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <input type="hidden" name="id" value="<?= $produk['id']; ?>">
            
            <!-- CONTAINER UNTUK MENAMPUNG ID GAMBAR YANG AKAN DIHAPUS (DI-GENERATED LEWAT JAVASCRIPT) -->
            <div id="container-input-hapus"></div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Informasi Produk</h3>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']); ?>" placeholder="Contoh: Ikan Kembung Segar" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kategori Produk</label>
                        <select name="kategori" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 12px center; background-size: 16px;" required>
                            <option value="Ikan" <?= $produk['kategori'] === 'Ikan' ? 'selected' : ''; ?>>Ikan</option>
                            <option value="Udang" <?= $produk['kategori'] === 'Udang' ? 'selected' : ''; ?>>Udang</option>
                            <option value="Kerang" <?= $produk['kategori'] === 'Kerang' ? 'selected' : ''; ?>>Kerang</option>
                            <option value="Kepiting" <?= $produk['kategori'] === 'Kepiting' ? 'selected' : ''; ?>>Kepiting</option>
                            <option value="Cumi" <?= $produk['kategori'] === 'Cumi' ? 'selected' : ''; ?>>Cumi / Sotong</option>
                            <option value="Olahan" <?= $produk['kategori'] === 'Olahan' ? 'selected' : ''; ?>>Olahan Hasil Laut</option>
                        </select>
                    </div>
                    
                    <input type="hidden" id="slug" name="slug" value="<?= htmlspecialchars($produk['slug']); ?>">
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea rows="5" name="deskripsi" placeholder="Tuliskan deskripsi lengkap produk hasil laut..." class="w-full border border-slate-300 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"><?= htmlspecialchars($produk['deskripsi']); ?></textarea>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Detail Produk</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" value="<?= $produk['harga']; ?>" placeholder="0" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Stok</label>
                        <input type="number" name="stok" value="<?= $produk['stok']; ?>" placeholder="0" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Berat (kg)</label>
                        <input type="number" step="0.01" name="berat" value="<?= $produk['berat']; ?>" placeholder="0.00" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Satuan</label>
                        <select name="satuan" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 12px center; background-size: 16px;">
                            <option value="kg" <?= $produk['satuan'] === 'kg' ? 'selected' : ''; ?>>kg</option>
                            <option value="gram" <?= $produk['satuan'] === 'gram' ? 'selected' : ''; ?>>gram</option>
                            <option value="ekor" <?= $produk['satuan'] === 'ekor' ? 'selected' : ''; ?>>ekor</option>
                            <option value="pack" <?= $produk['satuan'] === 'pack' ? 'selected' : ''; ?>>pack</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Informasi Tambahan</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Asal Produk</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="asal_produk[]" value="Tangkapan Harian" class="sr-only" <?= in_array('Tangkapan Harian', $asal_array) ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Tangkapan Harian</span>
                                <span class="text-xs text-slate-400 mt-1">Nelayan Lokal</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="asal_produk[]" value="Budidaya Air Tawar" class="sr-only" <?= in_array('Budidaya Air Tawar', $asal_array) ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Budidaya Lokal</span>
                                <span class="text-xs text-slate-400 mt-1">Kolam/Tambak</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="asal_produk[]" value="Impor" class="sr-only" <?= in_array('Impor', $asal_array) ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Premium Impor</span>
                                <span class="text-xs text-slate-400 mt-1">Luar Negeri</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-slate-700 mb-3">Badge Produk</span>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="" class="sr-only" <?= empty($produk['badge']) ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Tidak Ada</span>
                                <span class="text-xs text-gray-400 mt-1">Polos</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="TERLARIS" class="sr-only" <?= $produk['badge'] === 'TERLARIS' ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-amber-600 mt-2">TERLARIS</span>
                                <span class="text-xs text-slate-400 mt-1">Produk Populer</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="PROMO" class="sr-only" <?= $produk['badge'] === 'PROMO' ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-red-600 mt-2">PROMO</span>
                                <span class="text-xs text-slate-400 mt-1">Harga Potongan</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="BARU" class="sr-only" <?= $produk['badge'] === 'BARU' ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-emerald-600 mt-2">BARU</span>
                                <span class="text-xs text-slate-400 mt-1">Katalog Terbaru</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-slate-700 mb-3">Status Visibilitas</span>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="status" value="aktif" class="sr-only" <?= $produk['status'] === 'aktif' ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Aktif</span>
                                <span class="text-xs text-slate-400 mt-1">Tampil di Toko</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="status" value="nonaktif" class="sr-only" <?= $produk['status'] === 'nonaktif' ? 'checked' : ''; ?>>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Nonaktif</span>
                                <span class="text-xs text-slate-400 mt-1">Arsipkan Produk</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN MEDIA: MENDUKUNG MENGHAPUS STATE SECARA LOKAL -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Gambar Produk</h3>
                
                <div class="mb-4">
                    <span class="block text-sm font-medium text-slate-700 mb-3">Gambar Tersimpan Saat Ini:</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <?php if (count($images_list) > 0): ?>
                            <?php foreach ($images_list as $img): ?>
                                <!-- Ditambahkan id unik di level card untuk proses manipulasi DOM -->
                                <div id="saved-img-card-<?= $img['id']; ?>" class="relative group border border-slate-200 rounded-xl overflow-hidden aspect-square bg-slate-50 shadow-sm transition duration-300">
                                    <?php if (file_exists("../../assets/img/product-image/" . $img['nama_file'])): ?>
                                        <img src="../../assets/img/product-image/<?= $img['nama_file']; ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300"><i class="fa-solid fa-fish text-3xl"></i></div>
                                    <?php endif; ?>
                                    
                                    <!-- HOVER OVERLAY & ICON SAMPAH KUSTOM -->
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center">
                                        <!-- Fungsi diubah agar memicu antrean hapus lokal -->
                                        <button type="button" onclick="antrikanHapusGambar(<?= $img['id']; ?>)" class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 active:scale-95 transition shadow-md" title="Hapus Gambar Ini">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full border border-dashed border-slate-300 rounded-xl p-4 text-center text-sm text-slate-400">
                                Belum ada gambar tersimpan untuk produk ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full">
                    <span class="block text-sm font-medium text-slate-700 mb-2">Tambahkan Gambar Baru (Opsional)</span>
                    <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition group">
                        <div class="flex flex-col items-center justify-center pt-4 pb-4">
                            <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-1 text-sm text-slate-500 font-medium"><span class="text-blue-600 font-semibold">Klik untuk menambah gambar baru</span> atau seret ke sini</p>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG, WEBP (Bisa pilih banyak sekaligus)</p>
                        </div>
                        <input type="file" id="gambar-input" name="gambar[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
                
                <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4"></div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="index.php?tab=produk" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-medium text-sm">Batal</a>
                <button type="button" id="btn-trigger-edit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition font-medium text-sm shadow-md shadow-blue-200">Simpan Perubahan</button>
            </div>
        </form>
    </main>

    <div id="custom-confirm-modal" class="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl max-w-sm w-full mx-4 p-6 shadow-xl border border-slate-100 scale-95 transform transition-transform duration-300">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-900 mb-1">Konfirmasi Perubahan</h4>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">Apakah Anda yakin ingin memperbarui informasi data komoditas produk hasil laut ini?</p>
            <div class="flex items-center justify-end gap-3">
                <button type="button" id="modal-cancel-btn" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition text-xs font-semibold">Kembali</button>
                <button type="button" id="modal-confirm-btn" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition text-xs font-semibold shadow-md shadow-blue-100">Ya, Simpan</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/create-product.js"></script>
    <script>
        const formEdit = document.getElementById('form-edit-produk');
        const triggerBtn = document.getElementById('btn-trigger-edit');
        const modalContainer = document.getElementById('custom-confirm-modal');
        const modalCancel = document.getElementById('modal-cancel-btn');
        const modalConfirm = document.getElementById('modal-confirm-btn');
        const containerInputHapus = document.getElementById('container-input-hapus');

        // ==========================================
        // LOGIKA BARU: ANTREKAN HAPUS GAMBAR LOKAL
        // ==========================================
        function antrikanHapusGambar(imgId) {
            const cardElement = document.getElementById('saved-img-card-' + imgId);
            if (cardElement) {
                // 1. Sembunyikan gambar dari antarmuka secara instan
                cardElement.style.opacity = '0';
                setTimeout(() => {
                    cardElement.remove();
                }, 250);

                // 2. Buat input hidden baru yang menampung ID gambar untuk disubmit nanti
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'hapus_gambar_id[]';
                hiddenInput.value = imgId;
                containerInputHapus.appendChild(hiddenInput);
            }
        }

        if(triggerBtn && modalContainer) {
            triggerBtn.addEventListener('click', () => {
                if(formEdit.checkValidity()) {
                    modalContainer.classList.remove('opacity-0', 'pointer-events-none');
                    modalContainer.querySelector('.scale-95').classList.remove('scale-95');
                } else {
                    formEdit.reportValidity();
                }
            });

            modalCancel.addEventListener('click', () => {
                modalContainer.classList.add('opacity-0', 'pointer-events-none');
                modalContainer.querySelector('.transform').classList.add('scale-95');
            });

            modalConfirm.addEventListener('click', () => {
                if(typeof syncInputFiles === 'function') syncInputFiles();
                formEdit.submit();
            });
        }
    </script>
</body>
</html>