<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="zxx">


<!doctype html>
<html class="no-js" lang="zxx">

<!-- single-product31:30-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Detail Produk || wartech - Toko Produk Elektronik</title>
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
                    <li class="active">Detail Produk</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Li's Breadcrumb Area End Here -->
    <!-- content-wraper start -->
    <?php
    include 'admin/koneksi.php';

    $id = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['id_produk']) ? $_GET['id_produk'] : null);

    if (!$id) {
        echo "<script>alert('ID produk tidak ditemukan!'); window.location.href = 'index.html';</script>";
        exit;
    }

    $query = mysqli_query($koneksi, "SELECT p.*, k.nm_kategori FROM tb_produk p LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori WHERE id_produk = '$id'");
    $data = mysqli_fetch_array($query);
    ?>

    <div class="content-wraper">
        <div class="container">
            <div class="row single-product-area">
                <div class="col-lg-5 col-md-6">
                    <!-- Product Details Left -->
                    <div class="product-details-left">
                        <div class="product-details-images slider-navigation-1">
                            <div class="lg-image">
                                <a class="popup-img venobox vbox-item" href="admin/produk_img/<?= $data['gambar'] ?>" data-gall="myGallery">
                                    <img src="admin/produk_img/<?= $data['gambar'] ?>" alt="<?= $data['nm_produk'] ?>" width="300" height="300">
                                </a>
                            </div>
                        </div>
                    </div>


                    <!--// Product Details Left -->
                </div>

                <?php if ($data['stok'] == 0) { ?>
                    <script>
                        alert('Stok produk habis');
                        window.location.href = 'belanja.php';
                    </script>
                <?php } ?>

                <div class="col-lg-7 col-md-6">
                    <div class="product-details-view-content pt-2">
                        <div class="product-info">
                            <h2><?= $data['nm_produk'] ?></h2>
                            <span class="product-details-ref">Kategori: <?= $data['nm_kategori'] ?></span>
                            <div class="price-box pt-20">
                                <sapn class="new-price new-price-2">Rp<?= number_format($data['harga'], 0, ',', '.') ?></sapn>
                            </div>
                            <div class="product-desc">
                                <P>
                                    <span class><?= nl2br($data['desk']) ?></span>
                                </P>
                                <P><strong>Stok tersedia:</strong> <?= $data['stok'] ?> unit</P>
                            </div>

                            <div class="single-add-to-cart">
                                <form action="tambah_ke_keranjang.php" method="post" class="cart-quantity">
                                    <input type="hidden" name="id_produk" value="<?= $data['id_produk'] ?>">
                                    <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">
                                    <input type="hidden" name="harga" value="<?= $data['harga'] ?>">
                                    <input type="hidden" name="redirect_url" value="<?= $_SERVER['REQUEST_URI'] ?>">
                                    <div class="quantity">
                                        <label>Jumlah</label>
                                        <div class="cart-plus-minus">
                                            <input name="jumlah" class="cart-plus-minus-box" value="1" type="number" min="1" max="<?= $data['stok'] ?>">
                                            <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                        </div>
                                    </div>
                                    <button class="add-to-cart" type="submit">Tambah Ke Keranjang</button>
                                </form>

                                <div class="product-additional-info pt-25">
                                    <div class="product-social-sharing pt-25">
                                        <ul>
                                            <li class="instagram"><a href="https://instagram.com/abdzn_/" target="_blank"><i class="fa fa-instagram"></i>Instagram</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="block-reassurance">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wraper end -->
        <!-- Begin Product Area -->
        <div class="product-area pt-35">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="li-product-tab">
                            <ul class="nav li-product-menu">
                                <li><a class="active" data-toggle="tab" href="#description"><span>Deskripsi</span></a></li>
                            </ul>
                        </div>
                        <!-- Begin Li's Tab Menu Content Area -->
                    </div>
                </div>
                <div class="tab-content">
                    <div id="description" class="tab-pane active show" role="tabpanel">
                        <div class="product-description">
                            <span><?= nl2br($data['desk']) ?></span>
                        </div>
                    </div>
                    <div id="product-details" class="tab-pane" role="tabpanel">
                        <div class="product-details-manufacturer">
                            <a href="#">
                                <img src="images/product-details/1.jpg" alt="Product Manufacturer Image">
                            </a>
                            <p><span>Reference</span> demo_7</p>
                            <p><span>Reference</span> demo_7</p>
                        </div>
                    </div>
                    <div id="reviews" class="tab-pane" role="tabpanel">
                        <div class="product-reviews">
                            <div class="product-details-comment-block">
                                <div class="comment-review">
                                    <span>Grade</span>
                                    <ul class="rating">
                                        <li><i class="fa fa-star-o"></i></li>
                                        <li><i class="fa fa-star-o"></i></li>
                                        <li><i class="fa fa-star-o"></i></li>
                                        <li class="no-star"><i class="fa fa-star-o"></i></li>
                                        <li class="no-star"><i class="fa fa-star-o"></i></li>
                                    </ul>
                                </div>
                                <div class="comment-author-infos pt-25">
                                    <span>HTML 5</span>
                                    <em>01-12-18</em>
                                </div>
                                <div class="comment-details">
                                    <h4 class="title-block">Demo</h4>
                                    <p>Plaza</p>
                                </div>
                                <div class="review-btn">
                                    <a class="review-links" href="#" data-toggle="modal" data-target="#mymodal">Write Your Review!</a>
                                </div>
                                <!-- Begin Quick View | Modal Area -->
                                <div class="modal fade modal-wrapper" id="mymodal">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h3 class="review-page-title">Write Your Review</h3>
                                                <div class="modal-inner-area row">
                                                    <div class="col-lg-6">
                                                        <div class="li-review-product">
                                                            <img src="images/product/large-size/3.jpg" alt="Li's Product">
                                                            <div class="li-review-product-desc">
                                                                <p class="li-product-name">Today is a good day Framed poster</p>
                                                                <p>
                                                                    <span>Beach Camera Exclusive Bundle - Includes Two Samsung Radiant 360 R3 Wi-Fi Bluetooth Speakers. Fill The Entire Room With Exquisite Sound via Ring Radiator Technology. Stream And Control R3 Speakers Wirelessly With Your Smartphone. Sophisticated, Modern Design </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="li-review-content">
                                                            <!-- Begin Feedback Area -->
                                                            <div class="feedback-area">
                                                                <div class="feedback">
                                                                    <h3 class="feedback-title">Our Feedback</h3>
                                                                    <form action="#">
                                                                        <p class="your-opinion">
                                                                            <label>Your Rating</label>
                                                                            <span>
                                                                                <select class="star-rating">
                                                                                    <option value="1">1</option>
                                                                                    <option value="2">2</option>
                                                                                    <option value="3">3</option>
                                                                                    <option value="4">4</option>
                                                                                    <option value="5">5</option>
                                                                                </select>
                                                                            </span>
                                                                        </p>
                                                                        <p class="feedback-form">
                                                                            <label for="feedback">Your Review</label>
                                                                            <textarea id="feedback" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                                                        </p>
                                                                        <div class="feedback-input">
                                                                            <p class="feedback-form-author">
                                                                                <label for="author">Name<span class="required">*</span>
                                                                                </label>
                                                                                <input id="author" name="author" value="" size="30" aria-required="true" type="text">
                                                                            </p>
                                                                            <p class="feedback-form-author feedback-form-email">
                                                                                <label for="email">Email<span class="required">*</span>
                                                                                </label>
                                                                                <input id="email" name="email" value="" size="30" aria-required="true" type="text">
                                                                                <span class="required"><sub>*</sub> Required fields</span>
                                                                            </p>
                                                                            <div class="feedback-btn pb-15">
                                                                                <a href="#" class="close" data-dismiss="modal" aria-label="Close">Close</a>
                                                                                <a href="#">Submit</a>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <!-- Feedback Area End Here -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Quick View | Modal Area End Here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Area End Here -->
        <!-- Begin Li's Laptop Product Area -->
        <section class="product-area li-laptop-product pt-30 pb-50">
            <div class="container">
                <div class="row">
                    <!-- Begin Li's Section Area -->
                    <div class="col-lg-12">
                        <div class="li-section-title">
                            <h2>
                                <span>Produk Lainnya:</span>
                            </h2>
                        </div>
                        <div class="row">
                            <div class="product-active owl-carousel">
                                <?php
                                include 'admin/koneksi.php';
                                $id_produk = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['id_produk']) ? $_GET['id_produk'] : null);

                                if (!$id_produk) {
                                    echo "<script>alert('ID produk tidak ditemukan!'); window.location.href = 'index.html';</script>";
                                    exit;
                                }

                                $query_produk_lain = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk != '$id_produk' ORDER BY RAND() LIMIT 6");
                                while ($p = mysqli_fetch_array($query_produk_lain)) {
                                ?>
                                    <div class="col-lg-12">
                                        <!-- single-product-wrap start -->
                                        <div class="single-product-wrap">
                                            <div class="product-image">
                                                <a href="detail_produk.php?id_produk=<?= $p['id_produk'] ?>">
                                                    <img src="admin/produk_img/<?= $p['gambar'] ?>" alt="<?= $p['nm_produk'] ?>" width="300" height="300">
                                                </a>
                                            </div>
                                            <div class="product_desc">
                                                <div class="product_desc_info">
                                                    <div class="product-review">
                                                        <h5 class="manufacturer">
                                                            <a href="#"><?= $p['id_kategori'] ?></a>
                                                        </h5>
                                                    </div>
                                                    <h4>
                                                        <a class="product_name" href="detail_produk.php?id_produk=<?= $p['id_produk'] ?>">
                                                            <?= $p['nm_produk'] ?>
                                                        </a>
                                                    </h4>
                                                    <div class="price-box">
                                                        <span class="new-price">Rp<?= number_format($p['harga'], 0, ',', '.') ?></span>
                                                    </div>
                                                </div>
                                                <div class="add-actions">
                                                    <ul class="add-actions-link">
                                                        <li class="add-cart active">
                                                            <a href="detail_produk.php?id_produk=<?= $p['id_produk'] ?>">Beli Sekarang</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- single-product-wrap end -->
                            </div>
                        </div>
                    </div>
                    <!-- Li's Section Area End Here -->
                </div>
            </div>
        </section>
        <!-- Li's Laptop Product Area End Here -->
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

<!-- single-product31:32-->

</html>