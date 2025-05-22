<?php
session_start();
include 'admin/koneksi.php';

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

if (!empty($_GET['id'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_GET['id']); // amankan input
    $username = $_SESSION['username'];

    // Ambil id_user dari username
    $query_user = mysqli_query($koneksi, "SELECT id_user FROM tb_user WHERE username = '$username'");
    if (!$query_user || mysqli_num_rows($query_user) == 0) {
        echo "<script>alert('User tidak ditemukan.'); window.location='cart.php';</script>";
        exit;
    }

    $data_user = mysqli_fetch_assoc($query_user);
    $id_user = $data_user['id_user'];

    // Cek apakah pesanan dengan id dan id_user sesuai
    $cek_pesanan = mysqli_query($koneksi, "SELECT * FROM tb_pesanan WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user'");
    if (mysqli_num_rows($cek_pesanan) > 0) {
        $hapus = mysqli_query($koneksi, "DELETE FROM tb_pesanan WHERE id_pesanan = '$id_pesanan'");
        if ($hapus) {
            echo "<script>alert('Item berhasil dihapus!'); window.location='cart.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus item.'); window.location='cart.php';</script>";
        }
    } else {
        echo "<script>alert('Pesanan tidak ditemukan atau bukan milik Anda.'); window.location='cart.php';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid (id kosong).'); window.location='cart.php';</script>";
}