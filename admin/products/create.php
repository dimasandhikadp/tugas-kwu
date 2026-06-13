<?php
session_start();
require_once '../../config/koneksi.php';

// Pastikan user sudah login untuk mendapatkan user_id. 
// Jika sistem Anda belum menerapkan login, ubah $user_id menjadi ID default, misal: 1
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $slug        = mysqli_real_escape_string($conn, $_POST['slug']);
    
    // TANGKAP DATA: Mengambil input kategori dari form
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori']);
    
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $harga       = floatval($_POST['harga']);
    $stok        = intval($_POST['stok']);
    $berat       = floatval($_POST['berat']);
    
    $satuan      = mysqli_real_escape_string($conn, $_POST['satuan']);
    $badge       = mysqli_real_escape_string($conn, $_POST['badge']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    if (isset($_POST['asal_produk']) && is_array($_POST['asal_produk'])) {
        $asal_produk = mysqli_real_escape_string($conn, implode(', ', $_POST['asal_produk']));
    } else {
        $asal_produk = ''; 
    }

    mysqli_begin_transaction($conn);

    try {
        // PERBAIKAN QUERY: Menambahkan kolom `kategori` ke dalam statement INSERT INTO
        $query = "INSERT INTO products (user_id, nama_produk, slug, kategori, deskripsi, harga, stok, berat, satuan, asal_produk, badge, status) 
                  VALUES ($user_id, '$nama_produk', '$slug', '$kategori', '$deskripsi', $harga, $stok, $berat, '$satuan', '$asal_produk', '$badge', '$status')";

        if (!mysqli_query($conn, $query)) {
            throw new Exception("Gagal menambahkan data produk: " . mysqli_error($conn));
        }

        $product_id = mysqli_insert_id($conn);

        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
            $target_dir = "../../assets/img/product-image/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $uploaded_counter = 1;
            foreach ($_FILES['gambar']['name'] as $i => $name) {
                if ($_FILES['gambar']['error'][$i] === 0) {
                    $file_tmp = $_FILES['gambar']['tmp_name'][$i];
                    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

                    if (in_array($file_ext, $allowed_extensions)) {
                        $new_file_name = $slug . '-' . time() . '-' . $uploaded_counter . '.' . $file_ext;
                        $target_file = $target_dir . $new_file_name;

                        if (move_uploaded_file($file_tmp, $target_file)) {
                            $query_image = "INSERT INTO product_images (product_id, nama_file) VALUES ($product_id, '$new_file_name')";
                            if (!mysqli_query($conn, $query_image)) {
                                throw new Exception("Gagal menyimpan data gambar ke database: " . mysqli_error($conn));
                            }
                            $uploaded_counter++;
                        }
                    }
                }
            }
        }

        mysqli_commit($conn);
        
        // REFRESH HALAMAN: Mengarahkan kembali ke diri sendiri dengan query parameter success
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error_msg = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<?php include '../../includes/head.php'; ?>
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
            <h2 class="text-3xl font-bold text-blue-950">Tambah Produk</h2>
            <p class="text-slate-500 mt-1">Tambahkan produk baru ke katalog SeaFresh.</p>

            <?php if (isset($error_msg)): ?>
                <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl"><?= $error_msg; ?></div>
            <?php endif; ?>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Informasi Produk</h3>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" placeholder="Contoh: Ikan Kembung Segar" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kategori Produk</label>
                        <select name="kategori" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 12px center; background-size: 16px;" required>
                            <option value="" disabled selected>- Pilih Kategori -</option>
                            <option value="Ikan">Ikan</option>
                            <option value="Udang">Udang</option>
                            <option value="Kerang">Kerang</option>
                            <option value="Kepiting">Kepiting</option>
                            <option value="Cumi">Cumi / Sotong</option>
                            <option value="Olahan">Olahan Hasil Laut</option>
                        </select>
                    </div>
                    
                    <input type="hidden" id="slug" name="slug">
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea rows="5" name="deskripsi" placeholder="Tuliskan deskripsi lengkap produk hasil laut di sini..." class="w-full border border-slate-300 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"></textarea>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Detail Produk</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" placeholder="0" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Stok</label>
                        <input type="number" name="stok" placeholder="0" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Berat (kg)</label>
                        <input type="number" step="0.01" name="berat" placeholder="0.00" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Satuan</label>
                        <select name="satuan" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition appearance-none bg-no-repeat bg-right pr-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-position: right 12px center; background-size: 16px;">
                            <option value="kg">kg</option>
                            <option value="gram">gram</option>
                            <option value="ekor">ekor</option>
                            <option value="pack">pack</option>
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
                                <input type="radio" name="asal_produk[]" value="Tangkapan Harian" class="sr-only" checked>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Tangkapan Harian</span>
                                <span class="text-xs text-slate-400 mt-1">Nelayan Lokal</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="asal_produk[]" value="Budidaya Air Tawar" class="sr-only">
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Budidaya Lokal</span>
                                <span class="text-xs text-slate-400 mt-1">Kolam/Tambak</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="asal_produk[]" value="Impor" class="sr-only">
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
                                <input type="radio" name="badge" value="" class="sr-only" checked>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Tidak Ada</span>
                                <span class="text-xs text-gray-400 mt-1">Polos</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="TERLARIS" class="sr-only">
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-amber-600 mt-2">TERLARIS</span>
                                <span class="text-xs text-slate-400 mt-1">Produk Populer</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="PROMO" class="sr-only">
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-red-600 mt-2">PROMO</span>
                                <span class="text-xs text-slate-400 mt-1">Harga Potongan</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="badge" value="BARU" class="sr-only">
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
                                <input type="radio" name="status" value="aktif" class="sr-only" checked>
                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center bg-white transition group-has-[:checked]:bg-blue-600 group-has-[:checked]:border-blue-600">
                                    <svg class="w-3 h-3 text-white hidden group-has-[:checked]:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800 mt-2">Aktif</span>
                                <span class="text-xs text-slate-400 mt-1">Tampil di Toko</span>
                            </label>
                            <label class="relative border border-slate-200 rounded-xl p-4 flex flex-col justify-between cursor-pointer select-none hover:bg-slate-50 transition group">
                                <input type="radio" name="status" value="nonaktif" class="sr-only">
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

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">Gambar Produk</h3>
                <div class="flex items-center justify-center w-full mb-4">
                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-slate-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-1 text-sm text-slate-500 font-medium"><span class="text-blue-600 font-semibold">Klik untuk unggah</span> atau seret gambar ke sini</p>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG, WEBP (Bisa pilih beberapa sekaligus)</p>
                        </div>
                        <input type="file" id="gambar-input" name="gambar[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
                <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4"></div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="index.php" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-medium text-sm">Batal</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition font-medium text-sm shadow-md shadow-blue-200">Simpan Produk</button>
            </div>
        </form>
    </main>

    <script src="../../assets/js/create-product.js"></script>
</body>
</html>