<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="zxx">


<!doctype html>
<html class="no-js" lang="zxx">

<!-- shopping-cart31:32-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Cart || limupa - Digital Products Store eCommerce Bootstrap 4 Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <!-- Material Design Iconic Font-V2.2.0 -->
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Font Awesome Stars-->
    <link rel="stylesheet" href="css/fontawesome-stars.css">
    <!-- Meanmenu CSS -->
    <link rel="stylesheet" href="css/meanmenu.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="css/slick.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Jquery-ui CSS -->
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <!-- Venobox CSS -->
    <link rel="stylesheet" href="css/venobox.css">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/nice-select.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Bootstrap V4.1.3 Fremwork CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Helper CSS -->
    <link rel="stylesheet" href="css/helper.css">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Modernizr js -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <!-- Begin Header Area -->
        <header>
            <!-- Begin Header Middle Area -->
            <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
                <div class="container">
                    <div class="row">
                        <!-- Begin Header Logo Area -->
                        <div class="col-lg-3">
                            <div class="logo pb-sm-30 pb-xs-30">
                                <a href="index.html">
                                    <h1>Wartech</h1>
                                </a>
                            </div>
                        </div>
                        <!-- Header Logo Area End Here -->
                        <!-- Begin Header Middle Right Area -->
                        <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">
                            <!-- Begin Header Middle Right Area -->
                            <div class="header-middle-right">
                                <ul class="hm-menu">
                                    <?php

                                    if (!isset($_SESSION['id_user'])) {
                                    ?>
                                        <!-- Begin Header Middle Wishlist Area -->
                                        <li class="hm-wishlist">
                                            <a href="login.php" title="Login">
                                                <i class="fa fa-user-o"></i>
                                            </a>
                                        </li>
                                    <?php
                                    } else {
                                        // Ambil nama user dari session atau database jika mau
                                        $nama_user = $_SESSION['username']; // pastikan diset saat login

                                    ?>
                                        <!-- User Icon with Dropdown -->
                                        <li class="hm-wishlist dropdown">
                                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-user"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="padding: 10px; min-width: 150px; text-align: center;">
                                                <li style="padding: 5px 10px; font-weight: bold;">
                                                    <?= htmlspecialchars($nama_user) ?>
                                                </li>
                                                <li>
                                                    <hr style="margin: 5px 0;">
                                                </li> <!-- Garis pembatas -->
                                                <li>
                                                    <a href="logout.php" style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                                                        <i class="fa fa-sign-out"></i> Logout
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                        <!-- Header Middle Wishlist Area End Here -->
                                        <?php
                                        if (session_status() === PHP_SESSION_NONE) {
                                            session_start();
                                        }

                                        include "admin/koneksi.php";

                                        $total_keranjang = 0;
                                        $jumlah_item = 0;
                                        $total_bayar = 0;

                                        if (isset($_SESSION['username'])) {
                                            $username = mysqli_real_escape_string($koneksi, $_SESSION['username']);

                                            $query_user = mysqli_query($koneksi, "SELECT id_user FROM tb_user WHERE username = '$username'");
                                            $data_user = mysqli_fetch_assoc($query_user);

                                            if ($data_user) {
                                                $id_user = $data_user['id_user'];

                                                $pesanan = [];
                                                $query_pesanan = mysqli_query($koneksi, "
            SELECT p.id_pesanan, p.qty, pr.id_produk, pr.nm_produk, pr.gambar, pr.harga
            FROM tb_pesanan p
            JOIN tb_produk pr ON p.id_produk = pr.id_produk
            WHERE p.id_user = '$id_user'
        ");

                                                while ($row = mysqli_fetch_assoc($query_pesanan)) {
                                                    $pesanan[] = $row;
                                                    $jumlah_item += $row['qty'];
                                                    $total_keranjang += $row['qty'] * $row['harga'];
                                                }

                                                $diskon = 0;
                                                if ($total_keranjang > 3000000) {
                                                    $diskon = 0.07 * $total_keranjang;
                                                } elseif ($total_keranjang > 1500000) {
                                                    $diskon = 0.05 * $total_keranjang;
                                                }
                                                $total_bayar = $total_keranjang - $diskon;
                                            }
                                        }
                                        ?>

                                        <!-- Begin Header Mini Cart Area -->
                                        <li class="hm-minicart">
                                            <div class="hm-minicart-trigger">
                                                <span class="item-icon"></span>
                                                <span class="item-text">
                                                    Rp<?= number_format($total_bayar, 0, ',', '.') ?>
                                                    <span class="cart-item-count"><?= $jumlah_item ?></span>
                                                </span>
                                            </div>
                                            <div class="minicart">
                                                <ul class="minicart-product-list">
                                                    <?php if (!empty($pesanan)): ?>
                                                        <?php foreach ($pesanan as $row): ?>
                                                            <li>
                                                                <a href='single-product.php?id=<?= $row['id_produk'] ?>' class='minicart-product-image'>
                                                                    <img src='admin/produk_img/<?= htmlspecialchars($row['gambar']) ?>' alt='<?= htmlspecialchars($row['nm_produk']) ?>' width='70'>
                                                                </a>
                                                                <div class='minicart-product-details'>
                                                                    <h6><a href='single-product.php?id=<?= $row['id_produk'] ?>'><?= htmlspecialchars($row['nm_produk']) ?></a></h6>
                                                                    <span>Rp<?= number_format($row['harga'], 0, ',', '.') ?> x <?= $row['qty'] ?></span>
                                                                </div>
                                                                <a href='hapus_pesanan.php?id=<?= $row['id_pesanan'] ?>' onclick='return confirm("Hapus item ini?")' class='close'>
                                                                    <i class='fa fa-close'></i>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li>
                                                            <div class='minicart-product-details'>Keranjang Anda kosong.</div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>

                                                <p class="minicart-total">TOTAL: <span>Rp<?= number_format($total_bayar, 0, ',', '.') ?></span></p>
                                                <div class="minicart-button">
                                                    <a href="cart.php" class="li-button li-button-fullwidth li-button-sm btn btn-warning">
                                                        <span>KERANJANG</span>
                                                    </a>
                                                    <form action="checkout.php" method="POST" style="margin: 0;">
                                                        <button type="submit" name="checkout" class="li-button li-button-fullwidth li-button-sm btn btn-warning" style="display: flex; align-items: center; justify-content: center;">
                                                            CHECKOUT
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Middle Area End Here -->
            <!-- Begin Header Bottom Area -->
            <div class="header-bottom mb-0 header-sticky stick d-none d-lg-block d-xl-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Header Bottom Menu Area -->
                            <div class="hb-menu">
                                <nav>
                                    <ul>
                                        <li class="dropdown-holder"><a href="index.php">Beranda</a>
                                        </li>
                                        <li class="megamenu-holder"><a href="belanja.php">Belanja</a>
                                        </li>
                                        </li>
                                        <li><a href="contact.php">Hubungi Kami</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <!-- Header Bottom Menu Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Bottom Area End Here -->
            <!-- Begin Mobile Menu Area -->
            <div class="mobile-menu-area d-lg-none d-xl-none col-12">
                <div class="container">
                    <div class="row">
                        <div class="mobile-menu">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu Area End Here -->
        </header>
        <!-- Header Area End Here -->
        <!-- Begin Li's Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.html">Beranda</a></li>
                        <li class="active">Keranjang</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!--Shopping Cart Area Strat-->
        <div class="Shopping-cart-area pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php
                        include "admin/koneksi.php";

                        if (isset($_POST['update_cart'])) {
                            if (!isset($_SESSION['id_user'])) {
                                echo "<script>alert('User tidak ditemukan!'); window.location='cart.php';</script>";
                                exit;
                            }

                            $id_user = $_SESSION['id_user'];

                            // Pastikan data qty tersedia
                            if (isset($_POST['qty']) && is_array($_POST['qty'])) {
                                foreach ($_POST['qty'] as $id_pesanan => $qty) {
                                    $qty = (int)$qty;
                                    if ($qty < 1) $qty = 1;

                                    // Ambil harga produk terkait dari join tabel
                                    $query = mysqli_query($koneksi, "
                SELECT pr.harga 
                FROM tb_pesanan p 
                JOIN tb_produk pr ON p.id_produk = pr.id_produk 
                WHERE p.id_pesanan = '$id_pesanan' AND p.id_user = '$id_user'
            ");
                                    $data = mysqli_fetch_assoc($query);
                                    $harga = $data['harga'];
                                    $total = $qty * $harga;

                                    // Update qty dan total
                                    mysqli_query($koneksi, "
                UPDATE tb_pesanan 
                SET qty = '$qty', total = '$total' 
                WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user'
            ");
                                }
                            }

                            echo "<script>alert('Keranjang berhasil diperbarui.'); window.location='cart.php';</script>";
                            exit;
                        }


                        if (isset($_POST['checkout'])) {
                            if (!isset($_SESSION['id_user'])) {
                                echo "<script>alert('User tidak ditemukan'); window.location= 'cart.php';</script>";
                                exit;
                            }

                            $id_user = $_SESSION['id_user'];

                            $query_pesanan = mysqli_query($koneksi, "SELECT p.*, pr.harga FROM tb_pesanan p JOIN tb_produk pr ON p.id_produk = pr.id_produk WHERE p.id_user = '$id_user'");

                            if (!$query_pesanan || mysqli_num_rows($query_pesanan) == 0) {
                                echo "<script>alert('Keranjang kosong'); window.location= 'cart.php';</script>";
                                exit;
                            }

                            $subtotal = 0;
                            $items = [];
                            while ($row = mysqli_fetch_assoc($query_pesanan)) {
                                $total = $row['qty'] * $row['harga'];
                                $subtotal += $total;
                                $items[] = [
                                    'id_produk' => $row['id_produk'],
                                    'qty' => $row['qty'],
                                    'harga' => $total
                                ];
                            }

                            $diskon = 0;
                            if ($subtotal > 3000000) {
                                $diskon = 0.07 * $subtotal;
                            } else if ($subtotal > 1500000) {
                                $diskon = 0.05 * $subtotal;
                            }
                            $total_bayar = $subtotal - $diskon;

                            $result = mysqli_query($koneksi, "SELECT MAX(RIGHT(id_jual, 3)) AS max_id FROM tb_jual");
                            $row = mysqli_fetch_assoc($result);
                            $last_id = $row['max_id'];
                            $next_id = 'T' . str_pad((int)$last_id + 1, 3, '0', STR_PAD_LEFT);

                            $tgl = date('Y-m-d H:i:s');
                            $query_insert_jual = mysqli_query($koneksi, "INSERT INTO tb_jual (id_jual, id_user, tgl_jual, total, diskon) VALUES ('$next_id', '$id_user', '$tgl', '$total_bayar', '$diskon')");
                            if (!$query_insert_jual) {
                                echo "<script>alert('Gagal menyimpan data penjualan'); window.location= 'cart.php';</script>";
                                exit;
                            }

                            foreach ($items as $item) {
                                $query_dtl = mysqli_query($koneksi, "INSERT INTO tb_jualdtl (id_jual, id_produk, qty, harga) VALUES ('$next_id', '{$item['id_produk']}', '{$item['qty']}', '{$item['harga']}')");
                                if (!$query_dtl) {
                                    echo "<script>alert('Gagal menyimpan data detail penjualan'); window.location= 'cart.php';</script>";
                                    exit;
                                }
                            }

                            $hapus = mysqli_query($koneksi, "DELETE FROM tb_pesanan WHERE id_user = '$id_user'");
                            if (!$hapus) {
                                echo "<script>alert('Gagal menghapus data keranjang'); window.location= 'cart.php';</script>";
                                exit;
                            }

                            echo "<script>alert('Pesanan berhasil dibuat'); window.location= 'index.php';</script>";
                        }
                        ?>
                        <form method="post" action="">
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="li-product-remove">remove</th>
                                            <th class="li-product-thumbnail">images</th>
                                            <th class="cart-product-name">Product</th>
                                            <th class="li-product-price">Unit Price</th>
                                            <th class="li-product-quantity">Quantity</th>
                                            <th class="li-product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include "admin/koneksi.php";

                                        if (!isset($_SESSION['username'])) {
                                            echo "<tr><td colspan='6'>Silakan login terlebih dahulu.</td></tr>";
                                            exit;
                                        }
                                        $username = $_SESSION['username'];

                                        $query_user = mysqli_query($koneksi, "SELECT id_user FROM tb_user WHERE username = '$username'");
                                        $data_user = mysqli_fetch_assoc($query_user);
                                        $id_user = $data_user['id_user'];

                                        $query_pesanan = mysqli_query($koneksi, "SELECT p.*, pr.nm_produk, pr.gambar, pr.harga FROM tb_pesanan p JOIN tb_produk pr ON p.id_produk = pr.id_produk WHERE p.id_user = '$id_user'");
                                        if (mysqli_num_rows($query_pesanan) > 0) {
                                            while ($row = mysqli_fetch_assoc($query_pesanan)) {
                                                $total = $row['qty'] * $row['harga'];
                                                echo "<tr>
                                                <td class='li-product-remove'><a href='hapus_pesanan.php?id={$row['id_pesanan']}' onclick='return confirm(\"Yakin hapus item ini\")'><i class='fa fa-times'></i></a></td>
                                                <td class='li-product-thumbnail'><a href='#'><img src='admin/produk_img/{$row['gambar']}' alt='{$row['nm_produk']}' width='70'></a></td>
                                                <td class='li-product-name'><a href='#'>{$row['nm_produk']}</a></td>
                                                <td class='li-product-price'><span class='amount'>Rp" . number_format($row['harga'], 0, ',', '.') . "</span></td>
                                                <td class='quantity'>
                                                <label>Quantity</label>
                                                    <div class='cart-plus-minus'>
                                                        <input name='qty[{$row['id_pesanan']}]' class='cart-plus-minus-box' value='{$row['qty']}' type='number' min='1'>
                                                        <div class='dec qtybutton'><i class='fa fa-angle-down'></i></div>
                                                        <div class='inc qtybutton'><i class='fa fa-angle-up'></i></div>
                                                    </div>
                                                </td>
                                            <td class='product-subtotal'><span class='amount'>Rp" . number_format($total, 0, ',', '.') . "</span></td>
                                        </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>Keranjang kosong.</td></tr>";
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="coupon-all">
                                        <div class="coupon2">
                                            <input class="button" name="update_cart" value="Update cart" type="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 ml-auto">
                                    <div class="cart-page-total">
                                        <h2>Total Pesanan</h2>
                                        <?php
                                        $subtotal = 0;
                                        mysqli_data_seek($query_pesanan, 0);
                                        while ($row = mysqli_fetch_assoc($query_pesanan)) {
                                            $subtotal += $row['qty'] * $row['harga'];
                                        }

                                        $diskon = 0;
                                        if ($subtotal > 3000000) {
                                            $diskon = 0.07 * $subtotal;
                                        } elseif ($subtotal > 1500000) {
                                            $diskon = 0.05 * $subtotal;
                                        }

                                        $total_bayar = $subtotal - $diskon;
                                        ?>

                                        <ul>
                                            <li>Subtotal <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span></li>
                                            <li>Diskon <span>Rp <?= number_format($diskon, 0, ',', '.') ?></span></li>
                                            <li>Total <span>Rp <?= number_format($total_bayar, 0, ',', '.') ?></span></li>
                                        </ul>
                                        <button type="submit" name="checkout" class="btn btn-dark mt-3">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Shopping Cart Area End-->
        <!-- Begin Footer Area -->
        <div class="footer">
            <!-- Begin Footer Static Top Area -->
            <div class="footer-static-top">
                <div class="container">
                    <!-- Begin Footer Shipping Area -->
                    <div class="footer-shipping pt-60 pb-55 pb-xs-25">
                        <div class="row">
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/1.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Pengiriman Gratis</h2>
                                        <p>
                                            Untuk semua pesanan di atas Rp 500.000>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/2.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Pembayaran Aman</h2>
                                        <p>Bayar dengan metode pembayaran paling populer dan aman di dunia.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/3.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Belanja dengan percaya diri</h2>
                                        <p>Perlindungan pembeli kami melindungi pembelian anda dari klik hinggan pengiriman.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/4.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Pusat Bantuan 24/7</h2>
                                        <p>Hubung kami jika anda mengalami kendala.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                        </div>
                    </div>
                    <!-- Footer Shipping Area End Here -->
                </div>
            </div>
            <!-- Footer Static Top Area End Here -->
            <!-- Begin Footer Static Middle Area -->
            <div class="footer-static-middle">
                <div class="container">
                    <div class="footer-logo-wrap pt-50 pb-35">
                        <div class="row">
                            <!-- Begin Footer Logo Area -->
                            <div class="col-lg-4 col-md-6">
                                <div class="footer-logo">
                                    <h1>Wartech</h1>
                                    <p class="info">
                                        Selamat data di toko kami Wartech, toko kami menyediakan berbagai macam barang elektronik. Selamat Berbelanja.
                                    </p>
                                </div>
                                <ul class="des">
                                    <li>
                                        <span>Alamat: </span>
                                        Jl.Randublatung Lorong 4, RT 3, RW 1, Tambakromo, Cepu
                                    </li>
                                    <li>
                                        <span>Phone: </span>
                                        <a href="#">(+62) 877 2890 8035</a>
                                    </li>
                                    <li>
                                        <span>Email: </span>
                                        <a href="mailto://kabdzn.@gmail.com">kabdzn.@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Footer Logo Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-2 col-md-3 col-sm-6">
                            </div>
                            <!-- Footer Block Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-2 col-md-3 col-sm-6">
                            </div>
                            <!-- Footer Block Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-4">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Ikuti Kami</h3>
                                    <ul class="social-link">
                                        <li class="instagram">
                                            <a href="https://instagram.com/abdzn_/" data-toggle="tooltip" target="_blank" title="Instagram">
                                                <i class="fa fa-instagram"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Footer Block Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Middle Area End Here -->
            <!-- Begin Footer Static Bottom Area -->
            <div class="footer-static-bottom pt-55 pb-55">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">>
                            <!-- Begin Footer Payment Area -->
                            <div class="copyright text-center">
                                <a href="#">
                                    <img src="images/payment/1.png" alt="">
                                </a>
                            </div>
                            <!-- Footer Payment Area End Here -->
                            <!-- Begin Copyright Area -->
                            <div class="copyright text-center pt-25">
                                <span><a target="_blank" href="https://instagram.com/abdzn_/">Desaign by Kanadiyaa</a></span>
                            </div>
                            <!-- Copyright Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Bottom Area End Here -->
        </div>
        <!-- Footer Area End Here -->
    </div>
    <!-- Body Wrapper End Here -->
    <!-- jQuery-V1.12.4 -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/vendor/popper.min.js"></script>
    <!-- Bootstrap V4.1.3 Fremwork js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Ajax Mail js -->
    <script src="js/ajax-mail.js"></script>
    <!-- Meanmenu js -->
    <script src="js/jquery.meanmenu.min.js"></script>
    <!-- Wow.min js -->
    <script src="js/wow.min.js"></script>
    <!-- Slick Carousel js -->
    <script src="js/slick.min.js"></script>
    <!-- Owl Carousel-2 js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- Magnific popup js -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope js -->
    <script src="js/isotope.pkgd.min.js"></script>
    <!-- Imagesloaded js -->
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <!-- Mixitup js -->
    <script src="js/jquery.mixitup.min.js"></script>
    <!-- Countdown -->
    <script src="js/jquery.countdown.min.js"></script>
    <!-- Counterup -->
    <script src="js/jquery.counterup.min.js"></script>
    <!-- Waypoints -->
    <script src="js/waypoints.min.js"></script>
    <!-- Barrating -->
    <script src="js/jquery.barrating.min.js"></script>
    <!-- Jquery-ui -->
    <script src="js/jquery-ui.min.js"></script>
    <!-- Venobox -->
    <script src="js/venobox.min.js"></script>
    <!-- Nice Select js -->
    <script src="js/jquery.nice-select.min.js"></script>
    <!-- ScrollUp js -->
    <script src="js/scrollUp.min.js"></script>
    <!-- Main/Activator js -->
    <script src="js/main.js"></script>
</body>

<!-- shopping-cart31:32-->

</html>