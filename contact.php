<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="zxx">

<!-- contact32:04-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Hubungi Kami || wartech - Toko Produk Elektronik</title>
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
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Helper CSS -->
    <link rel="stylesheet" href="css/helper.css">
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
                                                <form action="cart.php" method="POST" style="margin: 0;">
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
                        <li><a href="index.html">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Contact Main Page Area -->
        <div class="contact-main-page mt-60 mb-40 mb-md-40 mb-sm-40 mb-xs-40">
            <div class="container mb-60">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d400.3916768171092!2d111.57705308475668!3d-7.15661402970677!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1749373759146!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        width="600"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>

                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 offset-lg-1 col-md-12 order-1 order-lg-2">
                            <div class="contact-page-side-content">
                                <h3 class="contact-page-title">wartech</h3>
                                <p class="contact-page-message mb-25">Selamat datang di toko kami Wartech, toko kami menyediakan berbagai macam barang elektronik. Selamat Berbelanja.
                                </p>
                                <div class="single-contact-block">
                                    <h4><i class="fa fa-fax"></i> Alamat</h4>
                                    <p>Jl.Randublatung Lorong 4, RT 3, RW 1, Tambakromo, Cepu</p>
                                </div>
                                <div class="single-contact-block">
                                    <h4><i class="fa fa-phone"></i> Telepon</h4>
                                    <p>(+62) 877 2890 8035</p>
                                </div>
                                <div class="single-contact-block last-child">
                                    <h4><i class="fa fa-envelope-o"></i> Email</h4>
                                    <p>kabdz@gmail.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 order-2 order-lg-1">
                            <div class="contact-form-content pt-sm-55 pt-xs-55">
                                <h3 class="contact-page-title">Tell Us Your Message</h3>
                                <div class="contact-form">
                                    <form id="contact-form" action="http://demo.hasthemes.com/limupa-v3/limupa/mail.php" method="post">
                                        <div class="form-group">
                                            <label>Nama Anda <span class="required">*</span></label>
                                            <input type="text" name="customerName" id="customername" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Anda <span class="required">*</span></label>
                                            <input type="email" name="customerEmail" id="customerEmail" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Masukkan Subjek</label>
                                            <input type="text" name="contactSubject" id="contactSubject">
                                        </div>
                                        <div class="form-group mb-30">
                                            <label>Pesan Anda</label>
                                            <textarea name="contactMessage" id="contactMessage"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" value="submit" id="submit" class="li-btn-3" name="submit">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                                <p class="form-messege"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact Main Page Area End Here -->
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
                                        <h1>wartech</h1>
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
            <!-- Begin Quick View | Modal Area -->
            <div class="modal fade modal-wrapper" id="exampleModalCenter">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="modal-inner-area row">
                                <div class="col-lg-5 col-md-6 col-sm-6">
                                    <!-- Product Details Left -->
                                    <div class="product-details-left">
                                        <div class="product-details-images slider-navigation-1">
                                            <div class="lg-image">
                                                <img src="admin/produk_img/" alt="product image" id="modal-gambar">
                                            </div>
                                        </div>
                                    </div>
                                    <!--// Product Details Left -->
                                </div>

                                <div class="col-lg-7 col-md-6 col-sm-6">
                                    <div class="product-details-view-content pt-60">
                                        <div class="product-info">
                                            <h2 id="modal-nama-produk"></h2>
                                            <span class="product-details-ref" id="modal-kategori">kategori</span>
                                            <div class="price-box pt-20">
                                                <span class="new-price new-price-2" id="modal-harga">Rp0</span>
                                            </div>
                                            <div class="product-decs">
                                                <p id="modal-desk"></p>
                                                <p><strong>Stok tersedia:</strong> <span id="modal-stok">0</span> unit</p>
                                            </div>

                                            <div class="single-add-to-cart">
                                                <form action="tambah_ke_keranjang.php" method="POST" class="cart-quantity">
                                                    <input type="hidden" name="id_produk" id="input-id-produk">
                                                    <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                                                    <input type="hidden" name="harga" id="input-harga">
                                                    <input type="hidden" name="redirect_url" value="belanja.php">

                                                    <div class="quantity">
                                                        <label>Jumlah</label>
                                                        <div class="cart-plus-minus">
                                                            <input class="cart-plus-minus-box" name="jumlah" id="input-jumlah" value="1" type="text">
                                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                                            <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                                        </div>
                                                    </div>

                                                    <button class="add-to-cart" type="submit">Beli Sekarang</button>
                                                </form>
                                            </div>

                                            <div class="product-additional-info pt-25">
                                                <div class="product-social-sharing pt-25">
                                                    <ul>
                                                        <li class="facebook"><a href="#"><i class="fa fa-facebook"></i> Facebook</a></li>
                                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i> Twitter</a></li>
                                                        <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i> Google +</a></li>
                                                        <li class="instagram"><a href="#"><i class="fa fa-instagram"></i> Instagram</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <!-- Google Map -->
            <script src="https://maps.google.com/maps/api/js?sensor=false&amp;libraries=geometry&amp;v=3.22&amp;key=AIzaSyChs2QWiAhnzz0a4OEhzqCXwx_qA9ST_lE"></script>

            <script>
                // When the window has finished loading create our google map below
                google.maps.event.addDomListener(window, 'load', init);

                function init() {
                    // Basic options for a simple Google Map
                    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                    var mapOptions = {
                        // How zoomed in you want the map to start at (always required)
                        zoom: 12,
                        scrollwheel: false,
                        // The latitude and longitude to center the map (always required)
                        center: new google.maps.LatLng(40.740610, -73.935242), // New York
                        // How you would like to style the map. 
                        // This is where you would paste any style found on
                        styles: [{
                                "featureType": "water",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#e9e9e9"
                                    },
                                    {
                                        "lightness": 17
                                    }
                                ]
                            },
                            {
                                "featureType": "landscape",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#f5f5f5"
                                    },
                                    {
                                        "lightness": 20
                                    }
                                ]
                            },
                            {
                                "featureType": "road.highway",
                                "elementType": "geometry.fill",
                                "stylers": [{
                                        "color": "#ffffff"
                                    },
                                    {
                                        "lightness": 17
                                    }
                                ]
                            },
                            {
                                "featureType": "road.highway",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                        "color": "#ffffff"
                                    },
                                    {
                                        "lightness": 29
                                    },
                                    {
                                        "weight": 0.2
                                    }
                                ]
                            },
                            {
                                "featureType": "road.arterial",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#ffffff"
                                    },
                                    {
                                        "lightness": 18
                                    }
                                ]
                            },
                            {
                                "featureType": "road.local",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#ffffff"
                                    },
                                    {
                                        "lightness": 16
                                    }
                                ]
                            },
                            {
                                "featureType": "poi",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#f5f5f5"
                                    },
                                    {
                                        "lightness": 21
                                    }
                                ]
                            },
                            {
                                "featureType": "poi.park",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#dedede"
                                    },
                                    {
                                        "lightness": 21
                                    }
                                ]
                            },
                            {
                                "elementType": "labels.text.stroke",
                                "stylers": [{
                                        "visibility": "on"
                                    },
                                    {
                                        "color": "#ffffff"
                                    },
                                    {
                                        "lightness": 16
                                    }
                                ]
                            },
                            {
                                "elementType": "labels.text.fill",
                                "stylers": [{
                                        "saturation": 36
                                    },
                                    {
                                        "color": "#333333"
                                    },
                                    {
                                        "lightness": 40
                                    }
                                ]
                            },
                            {
                                "elementType": "labels.icon",
                                "stylers": [{
                                    "visibility": "off"
                                }]
                            },
                            {
                                "featureType": "transit",
                                "elementType": "geometry",
                                "stylers": [{
                                        "color": "#f2f2f2"
                                    },
                                    {
                                        "lightness": 19
                                    }
                                ]
                            },
                            {
                                "featureType": "administrative",
                                "elementType": "geometry.fill",
                                "stylers": [{
                                        "color": "#fefefe"
                                    },
                                    {
                                        "lightness": 20
                                    }
                                ]
                            },
                            {
                                "featureType": "administrative",
                                "elementType": "geometry.stroke",
                                "stylers": [{
                                        "color": "#fefefe"
                                    },
                                    {
                                        "lightness": 17
                                    },
                                    {
                                        "weight": 1.2
                                    }
                                ]
                            }
                        ]
                    };

                    // Get the HTML DOM element that will contain your map 
                    // We are using a div with id="map" seen below in the <body>
                    var mapElement = document.getElementById('google-map');

                    // Create the Google Map using our element and options defined above
                    var map = new google.maps.Map(mapElement, mapOptions);

                    // Let's also add a marker while we're at it
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(40.740610, -73.935242),
                        map: map,
                        title: 'Limupa',
                        animation: google.maps.Animation.BOUNCE
                    });
                }
            </script>
</body>

<!-- contact32:04-->

</html>