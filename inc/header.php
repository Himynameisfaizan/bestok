<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// ==========================================
// 1. DYNAMIC SEO (META & SCHEMA) LOGIC
// ==========================================
$current_page = basename($_SERVER['PHP_SELF']);

// Default fallback values
$meta_title = "Bestok Industries Pvt Ltd";
$meta_desc = "Discover powerful and reliable products crafted to deliver smart, efficient solutions.";
$meta_key = "Air Compressor, Industrial Equipment";
$schema_markup = "";

// Dynamic Clean URLs handling (e.g., domain.com/product/slug)
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);

    // Apne actual file names ke hisaab se 'product-details.php' ya 'blog-details.php' ko match karein
    if ($current_page == 'product-details.php') {
        $seo_query = "SELECT meta_title, meta_desc, meta_key, schema_markup FROM products WHERE slug_url = '$slug' AND status=1";
    } elseif ($current_page == 'blog-details.php') {
        // Blogs table mein URL ke liye column ka naam 'slug' hai
        $seo_query = "SELECT meta_title, meta_desc, meta_key, schema_markup FROM blogs WHERE slug = '$slug' AND status=1";
    } elseif ($current_page == 'category.php') {
        $seo_query = "SELECT meta_title, meta_desc, meta_key, schema_markup FROM categories WHERE slug_url = '$slug' AND status=1";
    }
} else {
    // Standard Pages (index.php, about.php, contact.php)
    // meta aur page_schemas tables ko join karke data la rahe hain
    $seo_query = "SELECT m.meta_title, m.meta_desc, m.meta_key, s.schema_markup 
                  FROM meta m 
                  LEFT JOIN page_schemas s ON m.page_url = s.page_url 
                  WHERE m.page_url = '$current_page'";
}

if (isset($seo_query)) {
    $seo_res = mysqli_query($conn, $seo_query);
    if ($seo_res && mysqli_num_rows($seo_res) > 0) {
        $seo_data = mysqli_fetch_assoc($seo_res);
        $meta_title = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : $meta_title;
        $meta_desc = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_desc;
        $meta_key = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_key;
        $schema_markup = !empty($seo_data['schema_markup']) ? $seo_data['schema_markup'] : "";
    }
}

// ==========================================
// 2. DYNAMIC CANONICAL URL (FORCE WWW)
// ==========================================
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];

// Live environment mein www enforce karna (localhost ko bypass karega)
if (strpos($host, 'www.') !== 0 && $host !== 'localhost' && $host !== '127.0.0.1') {
    $host = 'www.' . $host;
}

// REQUEST_URI se current path nikalna aur canonical URL ke liye parameters (?id=...) strip karna
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$canonical_url = $protocol . "://" . $host . $request_uri;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic SEO Tags -->
    <title><?= htmlspecialchars($meta_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_key) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>" />
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

    <meta name="google-site-verification" content="GGGzfztUktF_-gsCYU0Mi8gIcXEhBB6Rc9ECNgl1Crg" />


    <!-- Dynamic Schema Markup -->
    <?php if (!empty($schema_markup)): ?>
    <script type="application/ld+json">
    <?= $schema_markup ?>
    </script>
    <?php endif; ?>

    <!-- Aapke existing CSS / Bootstrap styles yahan include hongi -->

</head>

<body>
    <!-- Aapka existing HTML Header Code Yahan Se Start Hoga -->
    <header class="header">
        <!-- ... baaki code ... -->
        <div class="header-top">
            <div class="container pe-lg-0">
                <div class="header-top-wrap">
                    <div class="header-top-left">
                        <div class="header-top-left d-flex align-items-center justify-content-between w-100">
                            <?php
                            // 1. FETCH THE SYSTEM MATRIX ROW FROM CONTACTS TABLE
                            $contact_top_query = "SELECT * FROM `contacts` LIMIT 1";
                            $contact_top_res   = mysqli_query($conn, $contact_top_query);
                            $top_data = ($contact_top_res && mysqli_num_rows($contact_top_res) > 0) ? mysqli_fetch_assoc($contact_top_res) : [];

                            // Extracting sanitized contact elements
                            $top_phone  = !empty($top_data['phone']) ? htmlspecialchars($top_data['phone']) : '+91-9871126707';
                            $top_phone2 = !empty($top_data['phone2']) ? htmlspecialchars($top_data['phone2']) : '';
                            $top_email  = !empty($top_data['email']) ? htmlspecialchars($top_data['email']) : 'bestocompressors@gmail.com';
                            $top_email2 = !empty($top_data['email2']) ? htmlspecialchars($top_data['email2']) : '';
                            $top_email3 = !empty($top_data['email3']) ? htmlspecialchars($top_data['email3']) : '';

                            // Extracting social profiles from updated column schemas
                            $fb_link   = !empty($top_data['facebook']) ? htmlspecialchars($top_data['facebook']) : '';
                            $insta_link = !empty($top_data['instagram']) ? htmlspecialchars($top_data['instagram']) : '';
                            $tw_link   = !empty($top_data['twitter']) ? htmlspecialchars($top_data['twitter']) : '';
                            $link_link = !empty($top_data['linkdin']) ? htmlspecialchars($top_data['linkdin']) : '';
                            $yt_link   = !empty($top_data['youtube']) ? htmlspecialchars($top_data['youtube']) : '';
                            ?>

                            <div class="header-top-list">
                                <ul>
                                    <li>
                                        <a href="mailto:<?= $top_email ?>">
                                            <i class="fa fa-envelope" aria-hidden="true"></i> <?= $top_email ?>
                                        </a>
                                        <?php if (!empty($top_email2)): ?>
                                        <span class="text-white mx-1">|</span>
                                        <a href="mailto:<?= $top_email2 ?>" class="small-email-top"
                                            style="font-size: 12px; opacity:0.8;"><?= $top_email2 ?></a>
                                        <?php endif; ?>
                                        <?php if (!empty($top_email3)): ?>
                                        <span class="text-white mx-1">|</span>
                                        <a href="mailto:<?= $top_email3 ?>" class="small-email-top"
                                            style="font-size: 12px; opacity:0.8;"><?= $top_email3 ?></a>
                                        <?php endif; ?>
                                    </li>

                                    <li>
                                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $top_phone) ?>">
                                            <i class="fa fa-phone" aria-hidden="true"></i> <?= $top_phone ?>
                                        </a>
                                        <?php if (!empty($top_phone2)): ?>
                                        <span class="text-white mx-1">/</span>
                                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $top_phone2) ?>">
                                            <?= $top_phone2 ?>
                                        </a>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>

                            <div class="top-follow">
                                <?php if (!empty($fb_link)): ?>
                                <a href="<?= $fb_link ?>" target="_blank"><i class="bi bi-facebook"></i></a>
                                <?php endif; ?>

                                <?php if (!empty($insta_link)): ?>
                                <a href="<?= $insta_link ?>" target="_blank"><i class="bi bi-instagram"></i></a>
                                <?php endif; ?>

                                <?php if (!empty($tw_link)): ?>
                                <a href="<?= $tw_link ?>" target="_blank"><i class="bi bi-twitter"></i></a>
                                <?php endif; ?>

                                <?php if (!empty($yt_link)): ?>
                                <a href="<?= $yt_link ?>" target="_blank"><i class="bi bi-youtube"></i></a>
                                <?php endif; ?>

                                <?php if (!empty($link_link)): ?>
                                <a href="<?= $link_link ?>" target="_blank"><i class="bi bi-linkedin"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container position-relative">
                    <a class="navbar-brand" href="<?= $site ?>index.php">
                        <?php
                        // 🎯 FETCH ACTIVE HEADER LOGO FROM LOGOS TABLE
                        $logo_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'header' AND `is_active` = 1 LIMIT 1";
                        $logo_res   = mysqli_query($conn, $logo_query);

                        if ($logo_res && mysqli_num_rows($logo_res) > 0) {
                            $logo_row = mysqli_fetch_assoc($logo_res);
                            // DB upload standard pattern path resolution check
                            $logo_src = $site . "admin/uploads/" . htmlspecialchars($logo_row['logo_path']);
                        } else {
                            // Fallback to static asset asset if no active entry found
                            $logo_src = $site . "assets/img/logo.png";
                        }
                        ?>
                        <img src="<?= $logo_src ?>" alt="Bestok Industries"
                            onerror="this.src='<?= $site ?>assets/img/logo.png'">
                    </a>
                    <div class="mobile-menu-right">
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                            aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <a href="<?= $site ?>index.php" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                <img src="<?= $site ?>assets/img/logo.png" alt="Bestok">
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="offcanvas-body gap-xl-1">
                            <ul class="navbar-nav justify-content-end flex-grow-1">

                                <li class="nav-item"><a class="nav-link" href="<?= $site ?>index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= $site ?>about.php">About Us</a></li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:;" data-bs-toggle="dropdown">
                                        Our Products <i class="fa fa-chevron-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        // OPTIMIZATION STEP 1: Sirf EK query mein saari Categories le aao
                                        $parent_cat_query = "SELECT cate_id, categories, slug_url 
                         FROM categories 
                         WHERE status=1 
                         ORDER BY display_order ASC, categories ASC";
                                        $parent_cat_res = mysqli_query($conn, $parent_cat_query);

                                        $categories_list = [];
                                        if ($parent_cat_res && mysqli_num_rows($parent_cat_res) > 0) {
                                            while ($row = mysqli_fetch_assoc($parent_cat_res)) {
                                                $categories_list[] = $row;
                                            }
                                        }

                                        // OPTIMIZATION STEP 2: Sirf EK query mein saari Sub-Categories le aao
                                        $sub_cat_query = "SELECT id, parent_id, categories, slug_url 
                      FROM sub_categories 
                      WHERE status=1 
                      ORDER BY display_order ASC, categories ASC";
                                        $sub_cat_res = mysqli_query($conn, $sub_cat_query);

                                        // Sub-categories ko unke 'parent_id' ke hisaab se array mein set kar rahe hain
                                        $sub_categories_list = [];
                                        if ($sub_cat_res && mysqli_num_rows($sub_cat_res) > 0) {
                                            while ($row = mysqli_fetch_assoc($sub_cat_res)) {
                                                $sub_categories_list[$row['parent_id']][] = $row;
                                            }
                                        }

                                        // STEP 3: Ab bina database ko pareshan kiye, fast HTML render karein
                                        if (!empty($categories_list)) {
                                            foreach ($categories_list as $p_cat) {
                                                $master_cate_id = $p_cat['cate_id']; // categories.cate_id
                                                $p_cat_name     = htmlspecialchars($p_cat['categories']);
                                                $p_cat_slug     = htmlspecialchars($p_cat['slug_url']);

                                                // Check if this category has subcategories in our pre-fetched array
                                                $has_sub_cats = isset($sub_categories_list[$master_cate_id]);
                                                $li_class = $has_sub_cats ? "dropdown-submenu" : "";
                                        ?>
                                        <li class="<?= $li_class ?>">
                                            <a class="dropdown-item" href="<?= $site ?>category/<?= $p_cat_slug ?>">
                                                <?= ucwords($p_cat_name) ?>
                                            </a>

                                            <?php if ($has_sub_cats): ?>
                                            <ul class="dropdown-menu">
                                                <?php
                                                            // Sirf us array ko loop kar rahe hain, database query NAHI kar rahe
                                                            foreach ($sub_categories_list[$master_cate_id] as $s_cat) {
                                                                $s_cat_name = htmlspecialchars($s_cat['categories']);
                                                                $s_cat_slug = htmlspecialchars($s_cat['slug_url']);
                                                            ?>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?= $site ?>subcategory/<?= $s_cat_slug ?>">
                                                        <?= ucwords($s_cat_name) ?>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                            <?php endif; ?>
                                        </li>
                                        <?php
                                            }
                                        } else {
                                            echo '<li><a class="dropdown-item text-muted" href="javascript:void(0);">No Categories Found</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </li>

                                <li class="nav-item"><a class="nav-link"
                                        href="<?= $site ?>certificates.php">Certificates</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= $site ?>blogs.php">Blogs</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= $site ?>gallery.php">Gallery</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= $site ?>contact-us.php">Contact
                                        Us</a></li>
                            </ul>

                            <div class="nav-right">
                                <div class="nav-btn">
                                    <a href="<?= $site ?>contact-us.php" class="theme-btn">Get a Quote<i
                                            class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>