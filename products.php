<?php
// Session aur Database Connection (Agar header.php mein included hai, toh ye line hata sakte hain)
// include 'config/connect.php'; 

// ==========================================
// 1. PAGINATION SETUP
// ==========================================
include_once 'config/connect.php';
$limit = 8; // Ek page par kitne products dikhane hain (Aap isko 12 ya 16 bhi kar sakte hain)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ==========================================
// 2. DYNAMIC BREADCRUMB & FILTERING SETUP
// ==========================================
// Agar URL mein category ID ya slug pass ho raha hai, toh condition banayenge (Optional)
$where_clause = "WHERE status = 1 AND is_disabled = 0";
$page_title = "Our Products";

// ==========================================
// 3. FETCH DATA & TOTAL PAGES
// ==========================================
// Total products count karna pagination ke liye
$count_query = "SELECT COUNT(id) AS total FROM products $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_rows = ($count_result && mysqli_num_rows($count_result) > 0) ? mysqli_fetch_assoc($count_result)['total'] : 0;
$total_pages = ceil($total_rows / $limit);

// Main products fetch query
$product_query = "SELECT pro_name, short_desc, pro_img, slug_url 
                  FROM products 
                  $where_clause 
                  ORDER BY display_order ASC, id DESC 
                  LIMIT $limit OFFSET $offset";
$product_result = mysqli_query($conn, $product_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?> | Bestok</title>
    <meta name="description" content="Discover powerful and reliable products by Bestok.">
    <link rel="icon" type="image/x-icon" href="assets/img/fav.png">

    <!-- css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Custom CSS for Uniform Product Cards -->
    <style>
        .product-box-card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-box-img {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
            padding: 10px;
        }

        .product-box-img img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-box-card p {
            flex-grow: 1;
            margin-bottom: 15px;
        }

        .pagination .page-link {
            color: #0f1c3f;
        }

        .pagination .page-item.active .page-link {
            background-color: #0f1c3f;
            border-color: #0f1c3f;
            color: #fff;
        }

        /* ADD THIS INSIDE YOUR <style> TAG IN <head> */

        /* Premium Responsive Pagination Design */
        .product-pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            /* Adds space between buttons to prevent overlap */
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .product-pagination .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            color: #13345d;
            /* Theme color */
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .product-pagination .page-item.active .page-link,
        .product-pagination .page-item .page-link:hover {
            background-color: #13345d;
            color: #ffffff;
            border-color: #13345d;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(19, 52, 93, 0.15);
        }

        .product-pagination .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Mobile Responsiveness for Grid */
        @media (max-width: 768px) {
            .product-box-card {
                margin-bottom: 20px;
            }

            .product-pagination .page-item .page-link {
                min-width: 35px;
                height: 35px;
                padding: 0 10px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body class="home-2">

    <?php include 'inc/header.php'; ?>

    <main class="main">
        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title"><?= $page_title ?></h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
                    <li class="active"><?= $page_title ?></li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->

        <!-- our products -->
        <div id="rs-blog" class="rs-blog gray-color pt-60 pb-80 md-pt-70 md-pb-80">
            <div class="container">
                <div class="sec-title2 text-center mb-45">
                    <h2 class="title title2">Products and Solutions</h2>
                    <p>Discover powerful and reliable products crafted to deliver smart, efficient, and effective solutions for your business needs.</p>
                    <div class="heading-divider"></div>
                </div>

                <div class="row">
                    <?php
                    // 4. LOOP DYNAMIC PRODUCTS FROM DATABASE
                    if ($product_result && mysqli_num_rows($product_result) > 0) {
                        while ($row = mysqli_fetch_assoc($product_result)) {
                            $pro_name = htmlspecialchars($row['pro_name']);

                            // Remove HTML tags from description and limit length
                            $short_desc = strip_tags(html_entity_decode($row['short_desc']));
                            $short_desc = mb_strimwidth($short_desc, 0, 80, '...');

                            $slug = htmlspecialchars($row['slug_url']);
                            $prod_img = $row['pro_img']; // Get the image name from DB

                            // ==========================================
                            // DIRECT URL APPROACH (Best for Localhost & Live)
                            // ==========================================
                            if (!empty($prod_img)) {
                                // Spaces ko %20 mein convert kar rahe hain taaki URL break na ho
                                $safe_img_name = str_replace(' ', '%20', $prod_img);
                                $img_src = $site . "admin/uploads/" . $safe_img_name;
                            } else {
                                $img_src = $site . "assets/img/logo.png";
                            }
                    ?>

                            <!-- PRODUCT CARD -->
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="product-box-card shadow-sm">
                                    <div class="product-box-img">
                                        <!-- Agar abhi bhi URL match nahi hua, toh onerror aapka logo dikha dega -->
                                        <img src="<?= $img_src ?>" alt="<?= $pro_name ?>" onerror="this.src='<?= $site ?>assets/img/logo.png'">
                                    </div>
                                    <h3 class="mt-3 fs-5"><?= $pro_name ?></h3>
                                    <p class="text-muted" style="font-size: 14px;"><?= $short_desc ?></p>
                                    <a href="<?= $site ?>product/<?= $slug ?>" class="product-box-btn mt-auto">Enquire Now</a>
                                </div>
                            </div>

                    <?php
                        }
                    } else {
                        // Agar koi product database mein na ho
                        echo '<div class="col-12 text-center"><div class="alert alert-warning">No products available at the moment.</div></div>';
                    }
                    ?>
                </div>

                <!-- 5. PAGINATION HTML -->
                <?php if ($total_pages > 1): ?>
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Product Pagination">
                                <ul class="product-pagination">
                                    <!-- Previous Button -->
                                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1">Previous</a>
                                    </li>

                                    <!-- Page Numbers -->
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Next Button -->
                                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <!-- /our products -->

    </main>

    <?php include 'inc/footer.php'; ?>

    <!-- js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <!-- Add other original JS scripts below as needed -->
</body>

</html>