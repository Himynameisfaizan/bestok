<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Central database connection include
include_once('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// 1. GET ROUTE SLUG PARAMETER FROM HTACCESS RULE (Fixed from 'subcat' to 'slug')
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: " . $site . "404.php");
    exit();
}

$subcat_slug = mysqli_real_escape_string($conn, $_GET['slug']);

// 2. FETCH SUBCATEGORY RECORD DATA ROWS BY SLUG
$sub_master_query = "SELECT * FROM `sub_categories` WHERE `slug_url` = '$subcat_slug' AND `status` = 1 LIMIT 1";
$sub_master_res   = mysqli_query($conn, $sub_master_query);

if (!$sub_master_res || mysqli_num_rows($sub_master_res) == 0) {
    header("Location: " . $site . "404.php");
    exit();
}

$sub_row           = mysqli_fetch_assoc($sub_master_res);

// CRITICAL RELATIONAL FIX: Mapping sub_categories.id -> products.pro_sub_cate
$target_subcat_pk  = $sub_row['id'];

$subcat_title      = htmlspecialchars($sub_row['categories']);
$meta_title        = htmlspecialchars($sub_row['meta_title'] ?? $sub_row['categories']);
$meta_desc         = htmlspecialchars($sub_row['meta_desc'] ?? '');
$meta_key          = htmlspecialchars($sub_row['meta_key'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $meta_title ?></title>
    <meta name="description" content="<?= $meta_desc ?>">
    <meta name="keywords" content="<?= $meta_key ?>">
    <link rel="icon" type="image/x-icon" href="<?= $site ?>assets/img/fav.png">

    <link rel="stylesheet" href="<?= $site ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/animate.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/nice-select.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
    /* 💎 GRID DESIGN ALIGNMENT & MODERN CARD HOVER STYLING */
    .product-box-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .product-box-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .product-box-img {
        height: 200px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 15px;
        background: #fafafa;
        border-radius: 8px;
    }

    .product-box-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }

    .product-box-card:hover .product-box-img img {
        transform: scale(1.05);
    }

    .product-box-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 44px;
        line-height: 1.4;
        text-transform: uppercase;
    }

    .product-short-text-wrap {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        height: 63px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        margin-bottom: 15px;
    }

    .product-box-btn {
        margin-top: auto;
        display: block;
        width: 100%;
        padding: 10px;
        background: #13345d;
        color: #fff !important;
        font-weight: 600;
        font-size: 13px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: background 0.2s;
    }

    .product-box-btn:hover {
        background: #0d2544;
    }
    </style>
</head>

<body class="home-2">

    <?php include 'inc/header.php'; ?>

    <main class="main">

        <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title"><?= $subcat_title ?></h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"
                            aria-hidden="true"></i></li>
                    <li class="active"><?= $subcat_title ?></li>
                </ul>
            </div>
        </div>

        <div id="rs-blog" class="rs-blog gray-color pt-60 pb-80 md-pt-70 md-pb-80">
            <div class="container">
                <div class="sec-title2 text-center mb-45">
                    <h2 class="title title2">Products and Solutions</h2>
                    <p>Discover powerful and reliable products crafted to deliver smart, efficient, and effective
                        solutions for your business needs in <?= $subcat_title ?>.</p>
                    <div class="heading-divider"></div>
                </div>

                <div class="row g-4">
                    <?php
                    // 🎯 SMART DYNAMIC RELATION FILTER: Checking both 'id' and 'cate_id' for old data compatibility
                    $parent_cate_id = $sub_row['parent_id'];
                    $target_subcat_cate_id = $sub_row['cate_id']; // Purane products ke liye

                    $product_query = "
                    SELECT p.*
                    FROM products p
                    WHERE p.pro_cate = '$parent_cate_id'
                    AND (p.pro_sub_cate = '$target_subcat_pk' OR p.pro_sub_cate = '$target_subcat_cate_id')
                    AND p.status = 1
                    AND p.is_disabled = 0
                    ORDER BY p.display_order ASC, p.pro_id DESC
                    ";

                    $product_res   = mysqli_query($conn, $product_query);

                    if ($product_res && mysqli_num_rows($product_res) > 0) {
                        while ($prod = mysqli_fetch_assoc($product_res)) {
                            $prod_name = htmlspecialchars($prod['pro_name']);
                            $prod_desc = $prod['short_desc'];
                            $prod_slug = htmlspecialchars($prod['slug_url']);
                             $prod_img  = $prod['pro_img'];

                            if (!empty($prod_img)) {
                                $safe_img_name = str_replace(' ', '%20', $prod_img);
                                
                                $image_src = $site . "admin/assets/img/uploads/" . $safe_img_name;
                            } else {
                                $image_src = $site . "assets/img/logo.png";
                            }
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="product-box-card shadow-sm">
                            <div class="product-box-img">
                                <!-- Image seedha exact path se aayegi -->
                                <img src="<?= $image_src ?>" alt="<?= $prod_name ?>" loading="lazy"
                                    onerror="this.src='<?= $site ?>assets/img/logo.png'">
                            </div>
                            <h3><?= $prod_name ?></h3>
                            <div class="product-short-text-wrap">
                                <?php
                                        $clean_text = strip_tags(html_entity_decode($prod_desc));
                                        echo strlen($clean_text) > 95 ? mb_strimwidth($clean_text, 0, 92, '...') : $clean_text;
                                        ?>
                            </div>
                            <a href="<?= $site ?>product/<?= $prod_slug ?>" class="product-box-btn">Read more</a>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center py-5"><div class="alert alert-info w-100">No products found under ' . $subcat_title . ' currently.</div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'inc/footer.php'; ?>

    <script src="<?= $site ?>assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?= $site ?>assets/js/popper.min.js"></script>
    <script src="<?= $site ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $site ?>assets/js/wow.min.js"></script>
    <script src="<?= $site ?>assets/js/main.js"></script>
    <script>
    new WOW().init();
    </script>
</body>

</html>