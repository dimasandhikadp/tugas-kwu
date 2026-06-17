<?php
session_start();
require_once '../../config/koneksi.php';

/** @var mysqli $conn */

// Validasi Role Akun
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'seller') {
    header('Location: ../../auth/auth.php'); 
    exit();
}

if (isset($_GET['id'])) {
    $id_produk = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id_seller = $_SESSION['user_id'];

    $check_produk = mysqli_query($conn, "SELECT id FROM products WHERE id = '$id_produk' AND user_id = '$user_id_seller'");
    
    if (mysqli_num_rows($check_produk) > 0) {
        
        $query_gambar = mysqli_query($conn, "SELECT nama_file FROM product_images WHERE product_id = '$id_produk'");
        while ($gambar = mysqli_fetch_assoc($query_gambar)) {
            $path_file = "../../assets/img/product-image/" . $gambar['nama_file'];
            if (file_exists($path_file) && !empty($gambar['nama_file'])) {
                unlink($path_file); // Menghapus file foto fisik
            }
        }

        mysqli_query($conn, "DELETE FROM product_images WHERE product_id = '$id_produk'");
        mysqli_query($conn, "DELETE FROM products WHERE id = '$id_produk' AND user_id = '$user_id_seller'");

        $_SESSION['success'] = "Produk berhasil dihapus dari katalog.";
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan atau Anda tidak memiliki akses.";
    }
}

header('Location: index.php');
exit();