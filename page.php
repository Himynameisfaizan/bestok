<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// 1. URL LOGIC & REDIRECTION (Sabse pehle, error prevention ke liye)
$rawSlug = $_GET['slug_url'] ?? $_GET['slug'] ?? '';
$slug = mysqli_real_escape_string($conn, trim((string)$rawSlug));

if (empty($slug)) {
    header("Location: " . $site . "404.php");
    exit();
}

$pageQuery = mysqli_query($conn, "SELECT * FROM custom_pages WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
if (!$pageQuery || mysqli_num_rows($pageQuery) == 0) {
    header("Location: " . $site . "404.php");
    exit();
}
$pageData = mysqli_fetch_assoc($pageQuery);

// 2. SEO & META VARIABLES SETUP
$cleanContentText = trim(preg_replace('/\s+/', ' ', strip_tags((string)$pageData['content'])));
$defaultMetaDesc = mb_substr($cleanContentText, 0, 160);

$meta_title = !empty($pageData['meta_title']) ? $pageData['meta_title'] : $pageData['page_title'];
$meta_desc  = !empty($pageData['meta_desc']) ? $pageData['meta_desc'] : $defaultMetaDesc;
$meta_key   = !empty($pageData['meta_key']) ? $pageData['meta_key'] : 'Bestok Industries, Air Compressors, Industrial Equipment';

$breadcrumb_name  = !empty($pageData['breadcrumb_title']) ? $pageData['breadcrumb_title'] : $pageData['page_title'];

// 3. INDUSTRIAL / MANUFACTURER SCHEMA MARKUP
$page_schema = trim($pageData['schema_markup'] ?? '');
if (empty($page_schema)) {
    $autoSchema = [
        "@context" => "https://schema.org",
        "@type" => "Manufacturer",
        "name" => "Bestok Industries Pvt Ltd",
        "description" => $meta_desc,
        "telephone" => "+91-9871126707",
        "email" => "bestocompressors@gmail.com",
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => "103-104, Durga Enclave G.T Road Rajendra Nagar Sahibabad",
            "addressLocality" => "Ghaziabad",
            "postalCode" => "201005",
            "addressCountry" => "IN"
        ]
    ];
    $page_schema = '<script type="application/ld+json">' . json_encode($autoSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

// Add schema global variable so header.php prints it properly
$schema_markup = $page_schema; 

// 4. NOW WE CAN INCLUDE HEADER
include 'inc/header.php';
?>

<style>
/* Bestok Content Page Styling */
.page-content-wrapper {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.02);
}

.bestok-trust-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
    background: #13345d;
    color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
}

.bestok-trust-item strong {
    display: block;
    font-size: 1.8rem;
    font-weight: 800;
}

.bestok-trust-item strong span {
    color: #e30613;
}

.bestok-trust-item small {
    color: #cbd5e1;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
}

.page-content-body {
    line-height: 1.8;
    color: #475569;
    font-size: 16px;
}

.page-content-body h2 {
    color: #1e293b;
    font-size: 24px;
    font-weight: 700;
    margin-top: 30px;
    margin-bottom: 15px;
    border-left: 4px solid #e30613;
    padding-left: 15px;
}

.page-content-body h3 {
    color: #13345d;
    font-size: 20px;
    font-weight: 600;
    margin-top: 25px;
}

.page-content-body ul {
    padding-left: 20px;
}

.page-content-body ul li {
    margin-bottom: 10px;
}

/* Sidebar Widget Adjustments */
.sidebar-widget {
    background: #f8fafc;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    border: 1px solid #edf2f7;
}

.sidebar-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #13345d;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 12px;
    border-bottom: 1px dashed #cbd5e1 !important;
    padding-bottom: 12px;
}

.category-list li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.category-list a {
    color: #475569 !important;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
    display: flex;
    justify-content: space-between;
}

.category-list a:hover {
    color: #e30613 !important;
}
</style>

<main class="main bg-light">
    <!-- Breadcrumb using Bestok Theme -->
    <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title"><?= htmlspecialchars($breadcrumb_name) ?></h2>
            <ul class="breadcrumb-menu">
                <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                <li><a href="<?= $site ?>locations.php">Pages</a> <i class="fa fa-angle-double-right"></i></li>
                <li class="active"><?= htmlspecialchars($breadcrumb_name) ?></li>
            </ul>
        </div>
    </div>

    <section class="py-5 mt-4 pb-5">
        <div class="container">
            <div class="row g-5">
                <!-- Left Content -->
                <div class="col-lg-8">
                    <article class="page-content-wrapper">
                        <h1 class="mb-4 fw-bold" style="color: #13345d;">
                            <?= htmlspecialchars($pageData['page_title']); ?></h1>

                        <!-- Bestok Client Stats -->
                        <div class="bestok-trust-strip">
                            <div class="bestok-trust-item">
                                <strong>45k<span>+</span></strong>
                                <small>Projects Complete</small>
                            </div>
                            <div class="bestok-trust-item">
                                <strong>25k<span>+</span></strong>
                                <small>Active Clients</small>
                            </div>
                            <div class="bestok-trust-item">
                                <strong>50<span>+</span></strong>
                                <small>Experience Team</small>
                            </div>
                            <div class="bestok-trust-item">
                                <strong>15k<span>+</span></strong>
                                <small>Winning Awards</small>
                            </div>
                        </div>

                        <!-- Dynamic Content Rendered Here -->
                        <div class="page-content-body">
                            <?= $pageData['content']; ?>
                        </div>
                    </article>
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-4">
                    <aside class="sidebar">

                        <!-- Other Custom Pages -->
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Explore Other Pages</h3>
                            <ul class="category-list">
                                <?php
                                $otherPagesQuery = mysqli_query($conn, "SELECT page_title, slug_url FROM custom_pages WHERE status = 1 AND slug_url != '$slug' ORDER BY id DESC LIMIT 5");
                                if ($otherPagesQuery && mysqli_num_rows($otherPagesQuery) > 0) {
                                    while ($op = mysqli_fetch_assoc($otherPagesQuery)) {
                                        echo '<li><a href="'.$site.'page/'.rawurlencode($op['slug_url']).'">'.htmlspecialchars($op['page_title']).' <i class="fa fa-angle-right"></i></a></li>';
                                    }
                                } else {
                                    echo '<li class="text-muted">No other pages found.</li>';
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Products Category Widget -->
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">Our Categories</h3>
                            <ul class="category-list">
                                <?php
                                $catQuery = mysqli_query($conn, "SELECT categories, slug_url FROM categories WHERE status = 1 ORDER BY display_order ASC LIMIT 6");
                                if ($catQuery && mysqli_num_rows($catQuery) > 0) {
                                    while ($c = mysqli_fetch_assoc($catQuery)) {
                                        echo '<li><a href="'.$site.'category/'.htmlspecialchars($c['slug_url']).'">'.htmlspecialchars($c['categories']).' <i class="fa fa-angle-right"></i></a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Quick Contact Banner -->
                        <div class="sidebar-widget bg-dark text-white text-center rounded-3 p-4 border-0 mt-4">
                            <i class="fa fa-headset fa-3x text-danger mb-3"></i>
                            <h4 class="text-white fw-bold mb-3">Need Compressors?</h4>
                            <p class="small text-light mb-4">Contact Bestok Industries for premium air solutions and
                                turnkey installations.</p>
                            <a href="<?= $site ?>contact-us.php" class="btn btn-danger w-100 fw-bold">Contact Us
                                Today</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'inc/footer.php'; ?>