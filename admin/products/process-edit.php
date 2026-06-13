<?php
session_start();
require_once '../../config/koneksi.php';

/** @var mysqli $conn */

// Proteksi Halaman
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'seller') {
    header('Location: index.php');
    exit();
}

$user_id_seller = $_SESSION['user_id'];
$id_produk = mysqli_real_escape_string($conn, $_POST['id']);
$nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
$slug = mysqli_real_escape_string($conn, $_POST['slug']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$harga = floatval($_POST['harga']);
$stok = intval($_POST['stok']);
$satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
$berat = floatval($_POST['berat']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

// Atur string asal_produk
if (isset($_POST['asal_produk']) && is_array($_POST['asal_produk'])) {
    $asal_produk = mysqli_real_escape_string($conn, implode(', ', $_POST['asal_produk']));
} else {
    $asal_produk = ''; 
}

mysqli_begin_transaction($conn);

try {
    // 1. Update data pada tabel utama 'products'
    $query_update = "UPDATE products SET 
        nama_produk = '$nama_produk',
        slug = '$slug',
        kategori = '$kategori',
        asal_produk = '$asal_produk',
        harga = $harga,
        stok = $stok,
        satuan = '$satuan',
        berat = $berat,
        status = '$status'
        WHERE id = '$id_produk' AND user_id = '$user_id_seller'";

    if (!mysqli_query($conn, $query_update)) {
        throw new Exception("Gagal memperbarui data informasi produk.");
    }

    // LOGIKA BARU: PROSES PENGHAPUSAN ASLI DI SINI
    if (isset($_POST['hapus_gambar_id']) && is_array($_POST['hapus_gambar_id'])) {
        foreach ($_POST['hapus_gambar_id'] as $img_id) {
            $clean_img_id = mysqli_real_escape_string($conn, $img_id);

            // Cari nama file fisik terlebih dahulu untuk dihapus dari storage local
            $query_find = mysqli_query($conn, "SELECT pi.nama_file FROM product_images pi 
                                               JOIN products p ON pi.product_id = p.id 
                                               WHERE pi.id = '$clean_img_id' AND p.user_id = '$user_id_seller'");
            
            if ($img_data = mysqli_fetch_assoc($query_find)) {
                $file_name = $img_data['nama_file'];
                $path_file = "../../assets/img/product-image/" . $file_name;

                // Hapus file fisik dari direktori local
                if (!empty($file_name) && file_exists($path_file)) {
                    unlink($path_file);
                }

                // Hapus baris data di database
                $query_delete_row = "DELETE FROM product_images WHERE id = '$clean_img_id'";
                if (!mysqli_query($conn, $query_delete_row)) {
                    throw new Exception("Gagal menghapus entri arsip gambar di database.");
                }
            }
        }
    }

    // 3. Cek dan proses jika user menambahkan banyak gambar baru sekaligus (Sama dengan alur Create)
    if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['name'][0])) {
        $target_dir = "../../assets/img/product-image/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $uploaded_counter = time(); // Penanda timestamp unik
        foreach ($_FILES['gambar']['name'] as $i => $name) {
            if ($_FILES['gambar']['error'][$i] === 0) {
                $file_tmp = $_FILES['gambar']['tmp_name'][$i];
                $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($file_ext, $allowed_extensions)) {
                    $new_file_name = $slug . '-' . $uploaded_counter . '-' . $i . '.' . $file_ext;
                    $target_file = $target_dir . $new_file_name;

                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $query_image = "INSERT INTO product_images (product_id, nama_file) VALUES ($id_produk, '$new_file_name')";
                        if (!mysqli_query($conn, $query_image)) {
                            throw new Exception("Gagal menyimpan data gambar baru ke database.");
                        }
                    }
                }
            }
        }
    }

    mysqli_commit($conn);
    $_SESSION['success'] = "Katalog produk dan aset media berhasil diperbarui.";
    header('Location: index.php?tab=produk&status=edit_success');
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
    header('Location: index.php?tab=produk');
    exit();
}