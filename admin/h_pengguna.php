<?php
include 'koneksi.php';

//periksa apakah pamameter id telah dikirimkan
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    //query untuk menghapus data berdasarkan id_user
    $query = mysqli_query($koneksi, "DELETE FROM tb_user WHERE id_user='$id_user'");

    //notifakiasi dan redirect 
    if ($query) {
        echo "<script>alert('Data pengguna berhasil dihapus'); window.location='pengguna.php';</script>";
        header("refresh:0; pengguna.php");
    } else {
        echo "<script>alert('Data pengguna gagal dihapus'); window.location='pengguna.php';</script>";
        header("refresh:0; pengguna.php");
    }
} else {
    echo "<script>alert('ID pengguna tidak ditemukan'); window.location='pengguna.php';</script>";
    header("refresh:0; pengguna.php");
}
?>