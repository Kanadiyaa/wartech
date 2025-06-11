<?php
session_start();
include "koneksi.php";

// Cek apakah sudah login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Cek apakah status tersedia dan pastikan user adalah admin
if (!isset($_SESSION["status"]) || $_SESSION["status"] !== "admin") {
    echo "<script>
    alert('Akses ditolak! Halaman ini hanya untuk Admin.');
    window.location.href='login.php';
    </script>";
    exit;
}
?>


<?php
include "koneksi.php";

// Mendapatkan kode produk otomatis
$auto = mysqli_query($koneksi, "SELECT MAX(id_produk) AS max_code FROM tb_produk");
$hasil = mysqli_fetch_array($auto);
$code = $hasil['max_code'];
// Pastikan $code tidak null jika tabel masih kosong
$urutan = (int)substr($code, 1, 3);
$urutan++;
$huruf = "P";
$id_produk_otomatis = $huruf . sprintf("%03s", $urutan); // Ubah nama variabel agar tidak ambigu

if (isset($_POST['simpan'])) {
    // Ambil input dan escape untuk keamanan
    // $id_produk = mysqli_real_escape_string($koneksi, $_POST['id_produk']); // HAPUS BARIS INI
    $id_produk = mysqli_real_escape_string($koneksi, $id_produk_otomatis); // Gunakan ID yang dihasilkan otomatis

    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']); // Tambahkan escape untuk id_kategori
    $nm_produk = mysqli_real_escape_string($koneksi, $_POST['nm_produk']);

    // Validasi dan sanitize harga
    $harga_mentah = $_POST['harga'];
    if (!is_numeric($harga_mentah)) {
        echo "<script>alert('Harga harus berupa angka!');</script>";
        header("refresh:0, t_produk.php");
        exit;
    }
    $harga = mysqli_real_escape_string($koneksi, $harga_mentah); // Escape harga setelah validasi

    // Validasi dan sanitize stok
    $stok_mentah = $_POST['stok'];
    if (!is_numeric($stok_mentah)) {
        echo "<script>alert('Stok harus berupa angka!');</script>";
        header("refresh:0, t_produk.php");
        exit;
    }
    $stok = mysqli_real_escape_string($koneksi, $stok_mentah); // Escape stok setelah validasi

    $desk = mysqli_real_escape_string($koneksi, $_POST['desk']);


    // Upload gambar
    $imgfile = $_FILES['gambar']['name'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $extension = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));

    $dir = "produk_img/";
    $allowed_extensions = array("jpg", "jpeg", "png", "webp");

    if (in_array($extension, $allowed_extensions)) {
        // Rename file agar unik
        $imgnewfile = md5(time() . $imgfile) . "." . $extension;
        move_uploaded_file($tmp_file, $dir . $imgnewfile);
        $imgnewfile = mysqli_real_escape_string($koneksi, $imgnewfile); // Escape nama file baru

        // Simpan data ke database
        $query = mysqli_query($koneksi, "INSERT INTO tb_produk (id_produk, nm_produk, harga, stok, desk, id_kategori, gambar)
            VALUES ('$id_produk', '$nm_produk', '$harga', '$stok', '$desk', '$id_kategori', '$imgnewfile')");

        if ($query) {
            echo "<script>alert('Produk berhasil ditambahkan!');</script>";
            header("refresh:0, produk.php");
        } else {
            // Menampilkan error MySQL untuk debugging
            error_log("MySQL Error: " . mysqli_error($koneksi));
            echo "<script>alert('Gagal menambahkan produk! Silakan coba lagi. Error: " . mysqli_error($koneksi) . "');</script>";
            header("refresh:0, produk.php");
        }
    } else {
        echo "<script>alert('Format tidak valid. Hanya jpg, jpeg, png, dan webp yang diperbolehkan.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Tambah Produk - wartech Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">wartech</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/gambarrr.jpg" alt="Profile" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h6>
                            <span>Admin</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link collapsed" href="index.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="kategori.php">
                    <i class="bi bi-tags-fill"></i>
                    <span>Kategori Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="produk.php">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="keranjang.php">
                    <i class="bi bi-basket-fill"></i>
                    <span>Keranjang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="transaksi.php">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan.php">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="pengguna.php">
                    <i class="bi bi-person-fill"></i>
                    <span>Pengguna</span>
                </a>
            </li>
        </ul>

    </aside>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Produk</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item">Produk</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">

                            <form class="row g-3 mt-2" method="post" enctype="multipart/form-data">
                                <div class="col-12">
                                    <label for="nm_produk" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="nm_produk" name="nm_produk" placeholder="Masukkan Nama Produk" required>
                                </div>
                                <div class="col-12">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga Produk" required>
                                </div>
                                <div class="12">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" placeholder="Masukkan Stok Produk" required>
                                </div>
                                <div class="col-12">
                                    <label for="desk" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="desk" name="desk" placeholder="Masukkan Deskripsi Produk" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-control" id="id_kategori" name="id_kategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        // Pastikan koneksi.php hanya di-include sekali di awal script
                                        // include 'koneksi.php'; // HAPUS INI, sudah di atas
                                        $query = mysqli_query($koneksi, "SELECT * FROM tb_kategori");
                                        while ($kategori = mysqli_fetch_array($query)) {
                                            echo "<option value='" . htmlspecialchars($kategori['id_kategori']) . "'>" . htmlspecialchars($kategori['nm_kategori']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="gambar" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                </div>
                                <div class="text-center">
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>wartech</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://instagram.com/abdzn_/" target="_blank">kanadiyaa</a>
        </div>
    </footer><a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <script src="assets/js/main.js"></script>

</body>

</html>