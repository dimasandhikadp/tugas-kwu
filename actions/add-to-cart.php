<?php
session_start();
include '../config/koneksi.php';

/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $qty_requested = intval($_POST['qty']);

    // 1. Ambil stok produk
    $query_product = mysqli_query($conn, "SELECT stok FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($query_product);

    // 2. Cari/Buat Keranjang
    $query_cart = mysqli_query($conn, "SELECT id FROM cart WHERE user_id = '$user_id'");
    if (mysqli_num_rows($query_cart) == 0) {
        mysqli_query($conn, "INSERT INTO cart (user_id) VALUES ('$user_id')");
        $cart_id = mysqli_insert_id($conn);
    } else {
        $cart_id = mysqli_fetch_assoc($query_cart)['id'];
    }

    // 3. Cek apakah produk sudah ada
    $check_item = mysqli_query($conn, "SELECT id, qty FROM cart_items WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
    
    if (mysqli_num_rows($check_item) > 0) {
        // PRODUK ADA: Update jumlahnya
        $row = mysqli_fetch_assoc($check_item);
        $new_qty = $qty_requested; // Jika ingin menimpa (set sesuai input baru)
        // $new_qty = $row['qty'] + $qty_requested; // GUNAKAN INI JIKA INGIN DITAMBAHKAN (akumulasi)

        // Validasi stok akhir
        if ($new_qty > $product['stok']) {
            echo "error:stok_kurang"; 
            exit;
        }

        mysqli_query($conn, "UPDATE cart_items SET qty = '$new_qty' WHERE id = '" . $row['id'] . "'");
        echo "success:updated";
    } else {
        // PRODUK BARU: Tambah baris baru
        mysqli_query($conn, "INSERT INTO cart_items (cart_id, product_id, qty) VALUES ('$cart_id', '$product_id', '$qty_requested')");
        echo "success:added";
    }
}
?>