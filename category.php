<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// 1. REDIRECT LOGIC SABSE PEHLE
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: " . $site . "404.php");
    exit();
}

$category_slug = mysqli_real_escape_string($conn, $_GET['slug']);

// 2. CHECK CATEGORY EXISTENCE
$cat_master_query = "SELECT * FROM `categories` WHERE `slug_url` = '$category_slug' AND `status` = 1 LIMIT 1";
$cat_master_res = mysqli_query($conn, $cat_master_query);

if (!$cat_master_res || mysqli_num_rows($cat_master_res) == 0) {
    header("Location: " . $site . "404.php");
    exit();
}

// 3. AB SUB KUCH CONFIRM HONE KE BAAD HEADER.PHP INCLUDE KAREIN
include 'inc/header.php';

$category_row = mysqli_fetch_assoc($cat_master_res);
$master_cate_id = $category_row['cate_id'];
// ... (baaki ka sub_categories fetch aur HTML design same rahega)
$category_title = htmlspecialchars($category_row['categories']);
$meta_title = htmlspecialchars($category_row['meta_title'] ?? $category_row['categories']);
$meta_desc = htmlspecialchars($category_row['meta_desc'] ?? '');
$meta_key = htmlspecialchars($category_row['meta_key'] ?? '');

// 3. FETCH RELATED SUBCATEGORIES FOR FILTERING BAR
// 3. FETCH RELATED SUBCATEGORIES FOR FILTERING BAR (Image added)
$sub_cats_arr = [];
// Added `sub_cat_img` to the query
$sub_query = "SELECT `id`, `categories`, `slug_url`, `sub_cat_img` FROM `sub_categories` WHERE `parent_id` =
'$master_cate_id' AND `status` = 1 ORDER BY `categories` ASC";
$sub_res = mysqli_query($conn, $sub_query);
if ($sub_res && mysqli_num_rows($sub_res) > 0) {
while ($sub_row = mysqli_fetch_assoc($sub_res)) {
$sub_cats_arr[] = $sub_row;
}
}
?>

<style>
/* 💎 PREMIUM SUBCATEGORY FILTER CHIPS STYLING */
.subcat-filter-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 40px;
}

.subcat-chip {
    display: inline-block;
    padding: 10px 22px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-weight: 600;
    font-size: 14px;
    border-radius: 50px;
    text-decoration: none !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.subcat-chip:hover {
    background: #13345d;
    color: #ffffff !important;
    border-color: #13345d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(19, 52, 93, 0.15);
}

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
    line-clamp: 2;
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
    line-clamp: 3;
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

/* 🌟 MODERN SUB-CATEGORY CARD DESIGN */
.subcat-card {
    background: #ffffff;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
    display: block;
    text-decoration: none;
    height: 100%;
    cursor: pointer;
}

.subcat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(19, 52, 93, 0.12);
    border-color: #13345d;
}

.subcat-img-wrapper {
    height: 160px;
    width: 100%;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 15px;
    position: relative;
}

.subcat-img-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.subcat-card:hover .subcat-img-wrapper img {
    transform: scale(1.1);
    /* Smooth Zoom Animation */
}

.subcat-info {
    padding: 15px;
    text-align: center;
    background: #fff;
    border-top: 1px solid #edf2f7;
}

.subcat-info h4 {
    color: #1e293b;
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
}

.subcat-card:hover .subcat-info h4 {
    color: #13345d;
}

.subcat-info i {
    font-size: 12px;
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.subcat-card:hover .subcat-info i {
    opacity: 1;
    transform: translateX(0);
    /* Arrow slide-in animation */
}
</style>
</head>

<body class="home-2">



    <main class="main">

        <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title"><?= $category_title ?></h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"
                            aria-hidden="true"></i></li>
                    <li class="active"><?= $category_title ?></li>
                </ul>
            </div>
        </div>

        <div id="rs-blog" class="rs-blog gray-color pt-60 pb-80">
            <div class="container">
                <div class="sec-title2 text-center mb-45">
                    <h2 class="title title2">Products and Solutions</h2>
                    <p>Discover powerful and reliable products crafted to deliver smart, efficient, and effective
                        solutions for your business needs in <?= $category_title ?>.</p>
                    <div class="heading-divider"></div>
                </div>

                <!-- MODERN SUBCATEGORY GRID SECTION -->
                <?php if (!empty($sub_cats_arr)): ?>
                <div class="row mb-5 justify-content-center">
                    <div class="col-12 mb-4 text-center">
                        <h3 class="fs-4 fw-bold text-dark border-bottom pb-2 d-inline-block">Browse by Categories</h3>
                    </div>
                    <?php foreach ($sub_cats_arr as $sub_chip):
                            $sub_name = htmlspecialchars($sub_chip['categories']);
                            $sub_slug = htmlspecialchars($sub_chip['slug_url']);
                            $sub_img  = $sub_chip['sub_cat_img']; // Fetched from Database
                            if (!empty($sub_img)) {
                                $safe_sub_img = str_replace(' ', '%20', $sub_img);

                                $sub_img_src = $site . "admin/uploads/sub_category/" . $safe_sub_img;
                            } else {
                                $sub_img_src = $site . "assets/img/logo.png";
                            }
                        ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-4">
                        <a href="<?= $site ?>subcategory/<?= $sub_slug ?>" class="subcat-card">
                            <div class="subcat-img-wrapper">
                                <img src="<?= $sub_img_src ?>" alt="<?= $sub_name ?>"
                                    onerror="this.src='<?= $site ?>assets/img/logo.png'">
                            </div>
                            <div class="subcat-info">
                                <h4><?= $sub_name ?> <i class="fa-solid fa-arrow-right"></i></h4>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Divider for Products Section -->
                <div class="row">
                    <div class="col-12 mb-4 text-center mt-3">
                        <h3 class="fs-4 fw-bold text-dark border-bottom pb-2 d-inline-block">All Products in
                            <?= $category_title ?></h3>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4">
                    <?php
                    // ==========================================
                    // 1. PAGINATION SETUP & STRICT FILTERING
                    // ==========================================
                    $limit = 12; // Ek page par kitne products dikhane hain (12 is best for 4-column grid)
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // STRICT RULE: Only fetch products belonging to this specific master category[cite: 1]
                    $where_condition = "WHERE `pro_cate` = '$master_cate_id' AND `status` = 1 AND `is_disabled` = 0";

                    // Count total products for this category to make pagination pages
                    $count_query = "SELECT COUNT(id) AS total FROM `products` $where_condition";
                    $count_res = mysqli_query($conn, $count_query);
                    $total_rows = ($count_res && mysqli_num_rows($count_res) > 0) ? mysqli_fetch_assoc($count_res)['total'] : 0;
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch actual products with LIMIT for current page
                    $product_query = "SELECT * FROM `products` 
                                      $where_condition 
                                      ORDER BY 
                                          CASE WHEN display_order IS NULL OR display_order = 0 THEN 999999 ELSE display_order END ASC, 
                                          id DESC 
                                      LIMIT $limit OFFSET $offset";
                    
                    $product_res = mysqli_query($conn, $product_query);

                    if ($product_res && mysqli_num_rows($product_res) > 0) {
                        while ($prod = mysqli_fetch_assoc($product_res)) {
                            $prod_name = htmlspecialchars($prod['pro_name']);
                            $prod_desc = $prod['short_desc'];
                            $prod_slug = htmlspecialchars($prod['slug_url']);
                            $prod_img  = $prod['pro_img']; 

                            // EXACT Image Path Logic (No more broken images)
                            if (!empty($prod_img)) {
                                $safe_img_name = str_replace(' ', '%20', $prod_img);
                                $image_src = $site . "admin/assets/img/uploads/" . $safe_img_name;
                            } else {
                                $image_src = $site . "assets/img/logo.png";
                            }
                    ?>
                    <!-- PRODUCT CARD -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="product-box-card shadow-sm">
                            <div class="product-box-img">
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
                        echo '<div class="col-12 text-center py-5"><div class="alert alert-info w-100">No products found for ' . $category_title . ' currently.</div></div>';
                    }
                    ?>
                </div>

                <!-- ========================================== -->
                <!-- 2. MODERN PAGINATION UI -->
                <!-- ========================================== -->
                <?php if ($total_pages > 1): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Category Product Pagination">
                            <ul
                                class="product-pagination d-flex flex-wrap justify-content-center gap-2 list-unstyled m-0 p-0">

                                <!-- Previous Button -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link px-3 py-2 border rounded shadow-sm text-decoration-none fw-bold"
                                        href="?slug=<?= $category_slug ?>&page=<?= $page - 1 ?>"
                                        style="color: <?= ($page <= 1) ? '#94a3b8' : '#13345d' ?>; background: <?= ($page <= 1) ? '#f8fafc' : '#fff' ?>;">Prev</a>
                                </li>

                                <!-- Page Numbers -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item">
                                    <a class="page-link px-3 py-2 border rounded shadow-sm text-decoration-none fw-bold"
                                        href="?slug=<?= $category_slug ?>&page=<?= $i ?>"
                                        style="<?= ($page == $i) ? 'background-color: #13345d; color: #fff; border-color: #13345d;' : 'background-color: #fff; color: #13345d;' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                                <?php endfor; ?>

                                <!-- Next Button -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link px-3 py-2 border rounded shadow-sm text-decoration-none fw-bold"
                                        href="?slug=<?= $category_slug ?>&page=<?= $page + 1 ?>"
                                        style="color: <?= ($page >= $total_pages) ? '#94a3b8' : '#13345d' ?>; background: <?= ($page >= $total_pages) ? '#f8fafc' : '#fff' ?>;">Next</a>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
                <?php endif; ?>

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