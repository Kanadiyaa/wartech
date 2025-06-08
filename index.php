<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="zxx">

<!-- index28:48-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Beranda || limupa - Digital Products Store eCommerce Bootstrap 4 Template</title>
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
                        <li><a href="index.html">Beranda</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Slider With Banner Area -->
        <div class="slider-with-banner">
            <div class="container">
                <div class="row">
                    <!-- Begin Slider Area -->
                    <div class="col-lg-8 col-md-8">
                        <div class="slider-area">
                            <div class="slider-active owl-carousel">
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left  animation-style-01 bg-1">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5>Dijual <span>-10% Off</span> Di Minggu Ini</h5>
                                        <h2>Samsung Galaxy S9 | S9+</h2>
                                        <h3>Mulai Dari <span>Rp 2.000.000</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="shop-left-sidebar.html">Beli Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left animation-style-02 bg-2">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5>Dijual <span>-5% Off</span> Di Minggu Ini</h5>
                                        <h2>Work Desk Surface Studio 2018</h2>
                                        <h3>Mulai Dari <span>Rp 10.200.000</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="shop-left-sidebar.html">Beli Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left animation-style-01 bg-3">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5>Dijual <span>-10% Off</span> Di Minggu Ini</h5>
                                        <h2>Phantom 4 Pro+ Obsidian</h2>
                                        <h3>Mulai Dari <span>Rp 20.000.000</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="shop-left-sidebar.html">Beli Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                            </div>
                        </div>
                    </div>
                    <!-- Slider Area End Here -->
                    <!-- Begin Li Banner Area -->
                    <div class="col-lg-4 col-md-4 text-center pt-xs-30">
                        <div class="li-banner">
                            <a href="#">
                                <img src="images/banner/1_1.jpg" alt="">
                            </a>
                        </div>
                        <div class="li-banner mt-15 mt-sm-30 mt-xs-30">
                            <a href="#">
                                <img src="images/banner/1_2.jpg" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- Li Banner Area End Here -->
                </div>
            </div>
        </div>
        <!-- Slider With Banner Area End Here -->
        <!-- Begin Static Top Area -->
        <div class="static-top-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="static-top-content mt-sm-30">
                            Dapatkan Promo Spesial tiap Jumat dari wartech - Gunakan kode promo <span>WARTECH30</span> untuk mendapatkan diskon 30% pada produk pilihan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Static Top Area End Here -->
        <!-- product-area start -->
        <div class="product-area pt-55 pb-25 pt-xs-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="li-product-tab">
                            <ul class="nav li-product-menu">
                                <li><a class="active" data-toggle="tab" href="#li-new-product"><span>Produk Kami</span></a></li>
                            </ul>
                        </div>
                        <!-- Begin Li's Tab Menu Content Area -->
                    </div>
                </div>
                <div class="tab-content">
                    <div id="li-new-product" class="tab-pane active show" role="tabpanel">
                        <div class="row">
                            <div class="product-active owl-carousel">
                                <?php
                                include "admin/koneksi.php";

                                $query = mysqli_query($koneksi, "SELECT p.*, k.nm_kategori FROM tb_produk p JOIN tb_kategori k on p.id_kategori = k.id_kategori ORDER BY RAND() LIMIT 5");
                                while ($data = mysqli_fetch_assoc($query)) {
                                ?>

                                    <div class="col-lg-12">
                                        <!-- single-product-wrap start -->
                                        <div class="single-product-wrap">
                                            <div class="product-image">
                                                <a href="detail_product.php?id=<?php echo $data['id_produk']; ?>">
                                                    <img src="admin/produk_img/<?php echo $data['gambar']; ?>" alt="<?php echo $data['nm_produk']; ?>" width="300" height="300">
                                                </a>

                                            </div>
                                            <div class="product_desc">
                                                <div class="product_desc_info">
                                                    <div class="product-review">
                                                        <h5 class="manufacturer">
                                                            <a href="#"><?php echo strtoupper($data['nm_kategori']); ?></a>
                                                        </h5>

                                                    </div>
                                                    <h4><a class="product_name" href="detail_produk.php?id=<?php echo $data['id_produk']; ?>"><?php echo $data['nm_produk']; ?></a></h4>
                                                    <div class="price-box">
                                                        <span class="new-price">Rp<?php echo number_format($data['harga'], 0, ',', '.'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="add-actions">
                                                    <ul class="add-actions-link">
                                                        <li class="add-cart active"><a href="detail_produk.php?id=<?php echo $data['id_produk']; ?>">Beli Sekarang</a></li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- single-product-wrap end -->
                                    </div>
                                <?php } ?>

                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- product-area end -->
        <!-- Begin Li's Static Banner Area -->
        <div class="li-static-banner">
            <div class="container">

            </div>
        </div>
        <!-- Li's Static Banner Area End Here -->
        <!-- Begin Li's Laptop Product Area -->
        <section class="product-area li-laptop-product pt-60 pb-45">

        </section>
        <!-- Li's Laptop Product Area End Here -->
        <!-- Begin Li's TV & Audio Product Area -->
        <section class="product-area li-laptop-product li-tv-audio-product pb-45">

        </section>
        <!-- Li's TV & Audio Product Area End Here -->
        <!-- Begin Li's Static Home Area -->
        <div class="li-static-home">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Begin Li's Static Home Image Area -->
                        <div class="li-static-home-image"></div>
                        <!-- Li's Static Home Image Area End Here -->
                        <!-- Begin Li's Static Home Content Area -->
                        <div class="li-static-home-content">
                            <p>Dijual Sampai <span>-20% Off</span> Di Minggu Ini</p>
                            <h2>Produk Terbaru</h2>
                            <h2>Meito Aksesoris 2025</h2>
                            <p class="schedule">
                                Dijual dengan harga spesial hanya untuk anda, <span>Rp</span>
                                <span>1.200.000</span>
                            </p>
                            <div class="default-btn">
                                <a href="shop-left-sidebar.html" class="links">Shopping Now</a>
                            </div>
                        </div>
                        <!-- Li's Static Home Content Area End Here -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Li's Static Home Area End Here -->
        <!-- Begin Li's Trending Product Area -->
        <section class="product-area li-trending-product pt-60 pb-45">

        </section>
        <!-- Li's Trending Product Area End Here -->
        <!-- Begin Li's Trendding Products Area -->
        <section class="product-area li-laptop-product li-trendding-products best-sellers pb-45">

        </section>
        <!-- Li's Trendding Products Area End Here -->
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

        <!-- Quick View | Modal Area End Here -->
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

<!-- index30:23-->

</html>