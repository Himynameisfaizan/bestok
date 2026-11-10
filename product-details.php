<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Central database connection config pipeline map rules
include('config/connect.php');
include 'inc/header.php';

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// 1. GET ROUTE SLUG PARAMETER FROM HTACCESS RULE
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: " . $site . "index.php");
    exit();
}

$product_slug = mysqli_real_escape_string($conn, $_GET['slug']);

// 2. FETCH SINGLE PRODUCT RECORD ROWS FROM DATABASE
$prod_master_query = "SELECT * FROM `products` WHERE `slug_url` = '$product_slug' AND `status` = 1 LIMIT 1";
$prod_master_res = mysqli_query($conn, $prod_master_query);

if (!$prod_master_res || mysqli_num_rows($prod_master_res) == 0) {
    header("Location: " . $site . "index.php");
    exit();
}

$product_row = mysqli_fetch_assoc($prod_master_res);
$current_pro_id = $product_row['pro_id'];
$pro_cate_id = $product_row['pro_cate']; // Relational link fields to track specs matrix
$pro_name = htmlspecialchars($product_row['pro_name']);
$brand_name = htmlspecialchars($product_row['brand_name'] ?? 'Bestok Industries');
$description = $product_row['description']; // Contains rich structural texts fields
$prod_img = htmlspecialchars($product_row['pro_img']);
$stock_status = (intval($product_row['stock'] ?? 0) > 0) ? "In Stock" : "Out of Stock";

// Dynamic Meta SEO tags setup
$meta_title = htmlspecialchars($product_row['meta_title'] ?? $product_row['pro_name']);
$meta_desc = htmlspecialchars($product_row['meta_desc'] ?? '');
$meta_key = htmlspecialchars($product_row['meta_key'] ?? '');

// Image path configuration matching administrative uploads architecture
$image_src = (!empty($prod_img) && file_exists("admin/assets/img/uploads/" . $prod_img)) ?
    $site . "admin/assets/img/uploads/" . $prod_img : $site . "assets/img/p1.jpg";

// 3. FETCH CATEGORY TITLE FOR METADATA MAPPING
$cat_name_title = "Air Compressor";
$cat_check = mysqli_query($conn, "SELECT `categories` FROM `categories` WHERE `cate_id` = '$pro_cate_id' LIMIT 1");
if ($cat_check && mysqli_num_rows($cat_check) > 0) {
    $c_row = mysqli_fetch_assoc($cat_check);
    $cat_name_title = htmlspecialchars($c_row['categories']);
}
?>

<body class="home-2">

    <main class="main">

        <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title"><?= $pro_name ?></h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"
                            aria-hidden="true"></i></li>
                    <li class="active"><?= $pro_name ?></li>
                </ul>
            </div>
        </div>
        <div class="service-detail-section" style="margin: 60px 0px;">
            <div class="container">
                <div class="row">

                    <div class="content-side col-lg-8 col-md-12 col-sm-12">
                        <article class="service-detail" itemscope itemtype="https://schema.org/Product">
                            <div class="image-box">
                                <div class="image wow fadeIn">
                                    <img src="<?= $image_src ?>" alt="<?= $pro_name ?>" itemprop="image"
                                        onerror="this.src='<?= $site ?>assets/img/p1.jpg'">
                                </div>
                            </div>

                            <div class="content-box mt-4">
                                <h2 itemprop="name"><?= $pro_name ?></h2>
                                <div class="product-description mt-3" itemprop="description">
                                    <?= $description ?>
                                </div>

                                <div class="product-meta"
                                    style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 5px;">
                                    <h3>Product Specifications</h3>
                                    <ul class="mt-3">
                                        <li class="mb-2"><strong>Category:</strong> <span
                                                itemprop="category"><?= $cat_name_title ?></span></li>
                                        <li class="mb-2"><strong>Availability:</strong> <span
                                                class="badge bg-success text-white px-2 py-1"><?= $stock_status ?></span>
                                        </li>
                                        <li class="mb-2"><strong>Brand/Manufacturer:</strong>
                                            <span><?= $brand_name ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                        <aside class="sidebar services-sidebar" aria-labelledby="sidebar-products-title">
                            <div class="sidebar-widget categories">
                                <div class="sidebar-title">
                                    <h3 id="sidebar-products-title">Featured Products</h3>
                                </div>
                                <ul class="category-list">
                                    <?php
                                    // Fetch active sidebar related products dynamically except the current product page
                                    $side_prod_query = "SELECT `pro_name`, `slug_url` FROM `products` WHERE `status` = 1 AND `pro_id` != '$current_pro_id' AND `is_disabled` = 0 ORDER BY `id` DESC LIMIT 12";
                                    $side_prod_res   = mysqli_query($conn, $side_prod_query);

                                    if ($side_prod_res && mysqli_num_rows($side_prod_res) > 0) {
                                        while ($s_prod = mysqli_fetch_assoc($side_prod_res)) {
                                            $s_name = htmlspecialchars($s_prod['pro_name']);
                                            $s_slug = htmlspecialchars($s_prod['slug_url']);
                                    ?>
                                            <li>
                                                <a href="<?= $site ?>product/<?= $s_slug ?>" aria-label="View <?= $s_name ?>">
                                                    <?= $s_name ?>
                                                </a>
                                            </li>
                                    <?php
                                        }
                                    } else {
                                        echo '<li><a href="javascript:void(0);" class="text-muted">No other products logged</a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>

                            <div class="sidebar-widget brochure">
                                <div class="brochure-box">
                                    <h6>Need Help<span> ?</span></h6>
                                    <ul class="info-box">
                                        <?php
                                        // Ensure global environment variable exists for deep routing safety
                                        if (!isset($site)) {
                                            $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
                                        }

                                        // 1. FETCH THE SYSTEM MATRIX ROW FROM CONTACTS TABLE
                                        $info_box_query = "SELECT * FROM `contacts` LIMIT 1";
                                        $info_box_res   = mysqli_query($conn, $info_box_query);
                                        $ib_data = ($info_box_res && mysqli_num_rows($info_box_res) > 0) ? mysqli_fetch_assoc($info_box_res) : [];

                                        // Extracting fields or mapping standard fallback defaults
                                        $ib_email  = !empty($ib_data['email']) ? htmlspecialchars($ib_data['email']) : 'bestocompressors@gmail.com';
                                        $ib_email2 = !empty($ib_data['email2']) ? htmlspecialchars($ib_data['email2']) : '';
                                        $ib_email3 = !empty($ib_data['email3']) ? htmlspecialchars($ib_data['email3']) : '';

                                        $ib_phone  = !empty($ib_data['phone']) ? htmlspecialchars($ib_data['phone']) : '+91-9871255334';
                                        $ib_phone2 = !empty($ib_data['phone2']) ? htmlspecialchars($ib_data['phone2']) : '';
                                        $ib_tele   = !empty($ib_data['telephone']) ? htmlspecialchars($ib_data['telephone']) : '';
                                        ?>

                                        <li>
                                            <span class="icon fa fa-envelope" aria-hidden="true"></span>
                                            <a href="mailto:<?= $ib_email ?>"><?= $ib_email ?></a>

                                            <?php if (!empty($ib_email2)): ?>
                                                <br><span class="icon fa fa-envelope" aria-hidden="true"
                                                    style="visibility: hidden;"></span>
                                                <a href="mailto:<?= $ib_email2 ?>"><?= $ib_email2 ?></a>
                                            <?php endif; ?>

                                            <?php if (!empty($ib_email3)): ?>
                                                <br><span class="icon fa fa-envelope" aria-hidden="true"
                                                    style="visibility: hidden;"></span>
                                                <a href="mailto:<?= $ib_email3 ?>"><?= $ib_email3 ?></a>
                                            <?php endif; ?>
                                        </li>

                                        <li>
                                            <span class="icon fa fa-phone" aria-hidden="true"></span>
                                            <a
                                                href="tel:<?= preg_replace('/[^0-9+]/', '', $ib_phone) ?>"><?= $ib_phone ?></a>

                                            <?php if (!empty($ib_phone2)): ?>
                                                <br><span class="icon fa fa-phone" aria-hidden="true"
                                                    style="visibility: hidden;"></span>
                                                <a
                                                    href="tel:<?= preg_replace('/[^0-9+]/', '', $ib_phone2) ?>"><?= $ib_phone2 ?></a>
                                            <?php endif; ?>

                                            <?php if (!empty($ib_tele)): ?>
                                                <br><span class="icon fa fa-phone" aria-hidden="true"
                                                    style="visibility: hidden;"></span>
                                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $ib_tele) ?>"><?= $ib_tele ?>
                                                    (Tel)</a>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                    <a href="<?= $site ?>contact-us.php"
                                        class="theme-btn btn-style-one w-100 text-center mt-3 d-block btn btn-dark py-2"
                                        aria-label="Contact Bestok Industries">
                                        Contact Us
                                    </a>
                                </div>
                            </div>
                        </aside>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <?php include 'inc/footer.php'; ?>

    <script src="<?= $site ?>assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?= $site ?>assets/js/modernizr.min.js"></script>
    <script src="<?= $site ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $site ?>assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="<?= $site ?>assets/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= $site ?>assets/js/isotope.pkgd.min.js"></script>
    <script src="<?= $site ?>assets/js/jquery.appear.min.js"></script>
    <script src="<?= $site ?>assets/js/jquery.easing.min.js"></script>
    <script src="<?= $site ?>assets/js/owl.carousel.min.js"></script>
    <script src="<?= $site ?>assets/js/counter-up.js"></script>
    <script src="<?= $site ?>assets/js/jquery.nice-select.min.js"></script>
    <script src="<?= $site ?>assets/js/wow.min.js"></script>
    <script src="<?= $site ?>assets/js/parsley.js"></script>
    <script src="<?= $site ?>assets/js/main.js"></script>

    <script>
        new WOW().init();
    </script>
</body>

</html>