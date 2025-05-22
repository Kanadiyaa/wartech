<?php
include 'admin/koneksi.php';

header('Content-Type: application/json'); // <- ini penting

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = mysqli_query($koneksi, "
        SELECT p.*, k.nm_kategori 
        FROM tb_produk p 
        JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
        WHERE p.id_produk = '$id'
    ");

    if ($data = mysqli_fetch_assoc($query)) {
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Produk tidak ditemukan']);
    }
}
?>