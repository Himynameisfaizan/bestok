<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('config/connect.php');

// =========================================================
// 1. AUTO-DETECT BASE URL ($site) FOR LOCALHOST & LIVE SERVER
// =========================================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Automatically detects '/projects/bestok/' on XAMPP or '/' on Live Domain
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = rtrim($script_dir, '/') . '/';
$site = $protocol . "://" . $host . $base_path;

// =========================================================
// 2. FETCH DYNAMIC CONTACTS & LOGOS FROM DATABASE
// =========================================================
// A. Fetch Contacts (Emails, Phones, Social Media)
$contact_top_query = "SELECT * FROM `contacts` LIMIT 1";
$contact_top_res   = mysqli_query($conn, $contact_top_query);
$top_data = ($contact_top_res && mysqli_num_rows($contact_top_res) > 0) ? mysqli_fetch_assoc($contact_top_res) : [];

$top_phone  = !empty($top_data['phone']) ? htmlspecialchars($top_data['phone']) : '+91-9871255334';
$top_phone2 = !empty($top_data['phone2']) ? htmlspecialchars($top_data['phone2']) : '+91-9871126707';
$top_tele   = !empty($top_data['telephone']) ? htmlspecialchars($top_data['telephone']) : '';

$top_email  = !empty($top_data['email']) ? htmlspecialchars($top_data['email']) : 'bestocompressors@gmail.com';
$top_email2 = !empty($top_data['email2']) ? htmlspecialchars($top_data['email2']) : '';
$top_email3 = !empty($top_data['email3']) ? htmlspecialchars($top_data['email3']) : '';

$fb_link    = !empty($top_data['facebook']) ? htmlspecialchars($top_data['facebook']) : '';
$insta_link = !empty($top_data['instagram']) ? htmlspecialchars($top_data['instagram']) : '';
$tw_link    = !empty($top_data['twitter']) ? htmlspecialchars($top_data['twitter']) : '';
$link_link  = !empty($top_data['linkdin']) ? htmlspecialchars($top_data['linkdin']) : '';
$yt_link    = !empty($top_data['youtube']) ? htmlspecialchars($top_data['youtube']) : '';

// B. Fetch Active Logos (Header, Mobile, Favicon) in 1 Query
$header_logo = $site . "assets/img/logo.png";
$mobile_logo = $site . "assets/img/logo.png";
$favicon_src = $site . "assets/img/fav.png";

$logo_query = "SELECT `location`, `logo_path` FROM `logos` WHERE `is_active` = 1";
$logo_res   = mysqli_query($conn, $logo_query);
if ($logo_res && mysqli_num_rows($logo_res) > 0) {
    while ($l_row = mysqli_fetch_assoc($logo_res)) {
        $l_path = htmlspecialchars($l_row['logo_path']);
        $full_logo_url = $site . "admin/uploads/" . $l_path;
        
        if ($l_row['location'] === 'header') {
            $header_logo = $full_logo_url;
        } elseif ($l_row['location'] === 'mobile') {
            $mobile_logo = $full_logo_url;
        } elseif ($l_row['location'] === 'favicon') {
            $favicon_src = $full_logo_url;
        }
    }
}

// =========================================================
// 3. DYNAMIC SEO (META & SCHEMA) LOGIC
// =========================================================
$current_page = basename($_SERVER['PHP_SELF']);

// Fallback defaults (agar page par pehle se define na ho)
$meta_title    = isset($meta_title) && !empty($meta_title) ? $meta_title : "Bestok Industries Pvt Ltd | Compressed Air Solutions";
$meta_desc     = isset($meta_desc) && !empty($meta_desc) ? $meta_desc : "Discover powerful and reliable Rotary Screw Air Compressors, Air Dryers, and Industrial Equipment by Bestok Industries.";
$meta_key      = isset($meta_key) && !empty($meta_key) ? $meta_key : "Air Compressor, Rotary Screw Air Compressor, Air Dryer, Bestok Industries";
$schema_markup = isset($schema_markup) ? $schema_markup : "";

$raw_slug = $_GET['slug'] ?? $_GET['slug_url'] ?? '';
if (!empty($raw_slug)) {
    $slug = mysqli_real_escape_string($conn, trim($raw_slug));

    if ($current_page == 'product-details.php') {
        $seo_query = "SELECT pro_name AS fallback_title, meta_title, meta_desc, meta_key, schema_markup FROM products WHERE slug_url = '$slug' AND status=1 LIMIT 1";
    } elseif ($current_page == 'blog-details.php') {
        $seo_query = "SELECT title AS fallback_title, meta_title, meta_desc, meta_key, schema_markup FROM blogs WHERE slug = '$slug' AND status=1 LIMIT 1";
    } elseif ($current_page == 'category.php') {
        $seo_query = "SELECT categories AS fallback_title, meta_title, meta_desc, meta_key, schema_markup FROM categories WHERE slug_url = '$slug' AND status=1 LIMIT 1";
    } elseif ($current_page == 'subcategory.php') {
        $seo_query = "SELECT categories AS fallback_title, meta_title, meta_desc, meta_key, '' AS schema_markup FROM sub_categories WHERE slug_url = '$slug' AND status=1 LIMIT 1";
    } elseif ($current_page == 'page.php' || $current_page == 'page-details.php') {
        $seo_query = "SELECT page_title AS fallback_title, meta_title, meta_desc, meta_key, schema_markup FROM custom_pages WHERE slug_url = '$slug' AND status=1 LIMIT 1";
    }
} else {
    // Standard Pages (index.php, about.php, contact.php, etc.)
    $seo_query = "SELECT m.page_name AS fallback_title, m.meta_title, m.meta_desc, m.meta_key, s.schema_markup 
                  FROM meta m 
                  LEFT JOIN page_schemas s ON m.page_url = s.page_url 
                  WHERE m.page_url = '$current_page' LIMIT 1";
}

if (isset($seo_query)) {
    $seo_res = mysqli_query($conn, $seo_query);
    if ($seo_res && mysqli_num_rows($seo_res) > 0) {
        $seo_data = mysqli_fetch_assoc($seo_res);
        $meta_title    = !empty($seo_data['meta_title']) ? $seo_data['meta_title'] : (!empty($seo_data['fallback_title']) ? $seo_data['fallback_title'] : $meta_title);
        $meta_desc     = !empty($seo_data['meta_desc']) ? $seo_data['meta_desc'] : $meta_desc;
        $meta_key      = !empty($seo_data['meta_key']) ? $seo_data['meta_key'] : $meta_key;
        $schema_markup = !empty($seo_data['schema_markup']) ? $seo_data['schema_markup'] : $schema_markup;
    }
}

// =========================================================
// 4. DYNAMIC CANONICAL URL (FORCE WWW ON LIVE SERVER)
// =========================================================
$canonical_host = $host;
if (strpos($canonical_host, 'www.') !== 0 && $canonical_host !== 'localhost' && $canonical_host !== '127.0.0.1') {
    $canonical_host = 'www.' . $canonical_host;
}
$request_uri   = strtok($_SERVER['REQUEST_URI'], '?');
$canonical_url = $protocol . "://" . $canonical_host . $request_uri;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Base URL Tag: Fixes CSS & Image paths on trailing-slash URLs like /career/ -->
    <base href="<?= htmlspecialchars($site) ?>">

    <!-- Dynamic SEO Tags -->
    <title><?= htmlspecialchars($meta_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta_key) ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>" />

    <!-- Dynamic Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $favicon_src ?>">

    <!-- Stylesheets -->
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
    <?php if (stripos($schema_markup, '<script') !== false): ?>
    <?= $schema_markup ?>
    <?php else: ?>
    <script type="application/ld+json">
    <?= $schema_markup ?>
    </script>
    <?php endif; ?>
    <?php endif; ?>

    <style>
    /* ================= ENHANCED HEADER & DROPDOWN UI ================= */
    .header-top {
        background: linear-gradient(90deg, #071324 0%, #0f2544 65%, #b8000b 100%);
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .header-top-list ul {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 18px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .header-top-list ul li a {
        color: #f8fafc;
        font-size: 13.5px;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .header-top-list ul li a:hover {
        color: #ff6b72;
    }

    .header-top-list ul li i {
        color: #e30613;
        margin-right: 5px;
    }

    .top-follow {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .top-follow a {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .top-follow a:hover {
        background: #ffffff;
        color: #e30613;
        transform: translateY(-2px);
    }

    /* Navbar & Logo Polish */
    .main-navigation {
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
    }

    .navbar-brand img {
        max-height: 62px;
        width: auto;
        object-fit: contain;
    }

    .navbar .nav-item .nav-link {
        font-weight: 600;
        color: #0f172a;
        padding: 24px 14px;
        transition: color 0.2s ease;
    }

    .navbar .nav-item .nav-link:hover,
    .navbar .nav-item .nav-link.active {
        color: #e30613;
    }

    /* Fixed Category & Subcategory Dropdown (No Cut-off Text) */
    .navbar .dropdown-menu {
        min-width: 290px !important;
        padding: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12);
        background: #ffffff;
    }

    .navbar .dropdown-item {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        white-space: normal !important;
        /* Prevents long names from cutting off */
        line-height: 1.4;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        border-radius: 8px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .navbar .dropdown-menu li:last-child>.dropdown-item {
        border-bottom: none;
    }

    .navbar .dropdown-item:hover,
    .navbar .dropdown-submenu:hover>.dropdown-item {
        background-color: #13345d !important;
        color: #ffffff !important;
        padding-left: 18px;
    }

    .navbar .dropdown-item .sub-arrow {
        font-size: 11px;
        opacity: 0.7;
        flex-shrink: 0;
    }

    /* Sub-menu Positioning on Desktop */
    .dropdown-submenu {
        position: relative;
    }

    @media (min-width: 992px) {
        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -8px;
            margin-left: 4px;
            min-width: 310px !important;
            display: none;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }
    }

    @media (max-width: 991px) {
        .header-top-list ul {
            gap: 8px;
            font-size: 12px;
        }

        .navbar .nav-item .nav-link {
            padding: 12px 0;
        }

        .dropdown-submenu>.dropdown-menu {
            padding-left: 15px;
            box-shadow: none;
            border: none;
            background: #f8fafc;
        }
    }
    </style>
</head>

<body class="home-2">
    <header class="header">
        <!-- TOP CONTACT & SOCIAL BAR -->
        <div class="header-top">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="header-top-list">
                        <ul>
                            <li>
                                <a href="mailto:<?= $top_email ?>">
                                    <i class="fa fa-envelope" aria-hidden="true"></i> <?= $top_email ?>
                                </a>
                                <?php if (!empty($top_email2)): ?>
                                <span class="text-white-50 mx-1 d-none d-md-inline">|</span>
                                <a href="mailto:<?= $top_email2 ?>" class="d-none d-md-inline"
                                    style="font-size: 12.5px; opacity:0.9;"><?= $top_email2 ?></a>
                                <?php endif; ?>
                                <?php if (!empty($top_email3)): ?>
                                <span class="text-white-50 mx-1 d-none d-lg-inline">|</span>
                                <a href="mailto:<?= $top_email3 ?>" class="d-none d-lg-inline"
                                    style="font-size: 12.5px; opacity:0.9;"><?= $top_email3 ?></a>
                                <?php endif; ?>
                            </li>

                            <li>
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $top_phone) ?>">
                                    <i class="fa fa-phone" aria-hidden="true"></i> <?= $top_phone ?>
                                </a>
                                <?php if (!empty($top_phone2)): ?>
                                <span class="text-white-50 mx-1">/</span>
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $top_phone2) ?>">
                                    <?= $top_phone2 ?>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($top_tele)): ?>
                                <span class="text-white-50 mx-1 d-none d-xl-inline">/</span>
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $top_tele) ?>"
                                    class="d-none d-xl-inline">
                                    <?= $top_tele ?>
                                </a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>

                    <div class="top-follow">
                        <?php if (!empty($fb_link)): ?>
                        <a href="<?= $fb_link ?>" target="_blank" aria-label="Facebook"><i
                                class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($insta_link)): ?>
                        <a href="<?= $insta_link ?>" target="_blank" aria-label="Instagram"><i
                                class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($tw_link)): ?>
                        <a href="<?= $tw_link ?>" target="_blank" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($yt_link)): ?>
                        <a href="<?= $yt_link ?>" target="_blank" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($link_link)): ?>
                        <a href="<?= $link_link ?>" target="_blank" aria-label="LinkedIn"><i
                                class="bi bi-linkedin"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN NAVIGATION BAR -->
        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container position-relative">
                    <!-- Dynamic Header Logo -->
                    <a class="navbar-brand" href="<?= $site ?>">
                        <img src="<?= $header_logo ?>" alt="Bestok Industries"
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
                        <div class="offcanvas-header border-bottom">
                            <!-- Dynamic Mobile Logo -->
                            <a href="<?= $site ?>" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                <img src="<?= $mobile_logo ?>" alt="Bestok Industries" style="max-height: 50px;"
                                    onerror="this.src='<?= $site ?>assets/img/logo.png'">
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body gap-xl-1 align-items-center">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-lg-3">
                                <li class="nav-item">
                                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>"
                                        href="<?= $site ?>">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($current_page == 'about.php') ? 'active' : '' ?>"
                                        href="<?= $site ?>about/">About Us</a>
                                </li>

                                <!-- Dynamic Products & Subcategories Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?= in_array($current_page, ['category.php', 'subcategory.php', 'product-details.php']) ? 'active' : '' ?>"
                                        href="javascript:void(0);" data-bs-toggle="dropdown">
                                        Our Products <i class="fa fa-chevron-down ms-1" style="font-size: 12px;"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        // 1. Fetch Active Parent Categories
                                        $parent_cat_query = "SELECT cate_id, categories, slug_url 
                                                             FROM categories 
                                                             WHERE status = 1 
                                                             ORDER BY display_order ASC, categories ASC";
                                        $parent_cat_res = mysqli_query($conn, $parent_cat_query);

                                        $categories_list = [];
                                        if ($parent_cat_res && mysqli_num_rows($parent_cat_res) > 0) {
                                            while ($row = mysqli_fetch_assoc($parent_cat_res)) {
                                                $categories_list[] = $row;
                                            }
                                        }

                                        // 2. Fetch Active Sub-Categories grouped by parent_id
                                        $sub_cat_query = "SELECT id, parent_id, categories, slug_url 
                                                          FROM sub_categories 
                                                          WHERE status = 1 
                                                          ORDER BY display_order ASC, categories ASC";
                                        $sub_cat_res = mysqli_query($conn, $sub_cat_query);

                                        $sub_categories_list = [];
                                        if ($sub_cat_res && mysqli_num_rows($sub_cat_res) > 0) {
                                            while ($row = mysqli_fetch_assoc($sub_cat_res)) {
                                                $sub_categories_list[$row['parent_id']][] = $row;
                                            }
                                        }

                                        // 3. Render Dropdown Menu
                                        if (!empty($categories_list)) {
                                            foreach ($categories_list as $p_cat) {
                                                $master_cate_id = $p_cat['cate_id'];
                                                $p_cat_name     = htmlspecialchars(trim($p_cat['categories']));
                                                $p_cat_slug     = htmlspecialchars(trim($p_cat['slug_url']));

                                                $has_sub_cats = !empty($sub_categories_list[$master_cate_id]);
                                                $li_class = $has_sub_cats ? "dropdown-submenu" : "";
                                                ?>
                                        <li class="<?= $li_class ?>">
                                            <a class="dropdown-item"
                                                href="<?= $site ?>category/<?= rawurlencode($p_cat_slug) ?>">
                                                <span><?= ucwords($p_cat_name) ?></span>
                                                <?php if ($has_sub_cats): ?>
                                                <i class="fa fa-chevron-right sub-arrow"></i>
                                                <?php endif; ?>
                                            </a>

                                            <?php if ($has_sub_cats): ?>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($sub_categories_list[$master_cate_id] as $s_cat):
                                                                $s_cat_name = htmlspecialchars(trim($s_cat['categories']));
                                                                $s_cat_slug = htmlspecialchars(trim($s_cat['slug_url']));
                                                            ?>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?= $site ?>subcategory/<?= rawurlencode($s_cat_slug) ?>">
                                                        <span><?= ucwords($s_cat_name) ?></span>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
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

                                <li class="nav-item">
                                    <a class="nav-link <?= ($current_page == 'certificates.php') ? 'active' : '' ?>"
                                        href="<?= $site ?>certificates/">Certificates</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link <?= in_array($current_page, ['blogs.php', 'blog.php', 'blog-details.php']) ? 'active' : '' ?>"
                                        href="<?= $site ?>blogs/">Blogs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($current_page == 'gallery.php') ? 'active' : '' ?>"
                                        href="<?= $site ?>gallery/">Gallery</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($current_page == 'contact-us.php') ? 'active' : '' ?>"
                                        href="<?= $site ?>contact-us/">Contact Us</a>
                                </li>
                            </ul>

                            <div class="nav-right">
                                <div class="nav-btn">
                                    <a href="<?= $site ?>contact-us/" class="theme-btn"
                                        style="background: #13345d; border-radius: 8px; padding: 10px 22px;">
                                        Get A Quote <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>