<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// Pagination Setup
$limit = 9; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) *$limit;

// Count total blogs
$count_query = "SELECT COUNT(blog_id) AS total FROM `blogs` WHERE `status` = 1";
$count_res = mysqli_query($conn,$count_query);
$total_rows = ($count_res && mysqli_num_rows($count_res) > 0) ? mysqli_fetch_assoc($count_res)['total'] : 0;
$total_pages = ceil($total_rows / $limit);

// Fetch Blogs
$blog_query = "SELECT * FROM `blogs` WHERE `status` = 1 ORDER BY `blog_id` DESC LIMIT $limit OFFSET $offset";
$blog_res = mysqli_query($conn,$blog_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <meta name="description" content="Home">
    <link rel="canonical" href="">
    <link rel="icon" type="image/x-icon" href="assets/img/fav.png">
    <!-- css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" type="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <style>
        .blog-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #edf2f7;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(19, 52, 93, 0.15);
        }
        .blog-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .blog-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .blog-meta {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .blog-meta i { color: #13345d; margin-right: 5px; }
        .blog-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .blog-title a { color: inherit; text-decoration: none; transition: color 0.3s; }
        .blog-title a:hover { color: #13345d; }
        .blog-desc {
            font-size: 14px;
            color: #475569;
            margin-bottom: 20px;
            display: -webkit-box;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .read-more-btn {
            margin-top: auto;
            align-self: flex-start;
            font-size: 14px;
            font-weight: 600;
            color: #13345d;
            text-decoration: none;
            transition: all 0.3s;
        }
        .read-more-btn:hover { letter-spacing: 1px; }
    </style>
</head>
<body class="home-2">
    <?php include 'inc/header.php'; ?>

    <main class="main">
        <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Our Latest Blogs</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                    <li class="active">Blogs</li>
                </ul>
            </div>
        </div>

        <div class="blog-area pt-80 pb-80">
            <div class="container">
                <div class="row g-4">
                    <?php
                    if ($blog_res && mysqli_num_rows($blog_res) > 0) {
                        while ($blog = mysqli_fetch_assoc($blog_res)) {
                            $b_title = htmlspecialchars($blog['title']);
                            $b_slug  = htmlspecialchars($blog['slug']);
                            $b_desc  = strip_tags(html_entity_decode($blog['description']));
                            $b_date  = date('F j, Y', strtotime($blog['created_at']));
                            $b_author = htmlspecialchars($blog['author']);
                            $b_img   =$blog['image'];

                            if (!empty($b_img)) {
                                $safe_img = rawurlencode($b_img);
                                $path1 = "admin/uploads/blogs/" . $b_img;
                                $path2 = "admin/uploads/" . $b_img;
                                
                                if (file_exists($path1)) {$img_src = $site . "admin/assets/img/uploads/blogs/" . $safe_img;
                                } elseif (file_exists($path2)) {$img_src = $site . "admin/assets/img/uploads/blogs/" . $safe_img;
                                } else {
                                    $img_src = $site . "admin/assets/img/uploads/blogs/" . $safe_img; 
                                }
                            } else {
                                $img_src =$site . "assets/img/1.webp";
                            }
                    ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="blog-card">
                                    <img src="<?= $img_src ?>" alt="<?= $b_title ?>" class="blog-img" onerror="this.src='<?= $site ?>assets/img/1.webp'">
                                    <div class="blog-content">
                                        <div class="blog-meta">
                                            <span><i class="fa-solid fa-calendar-days"></i> <?= $b_date ?></span>
                                            <span class="ms-3"><i class="fa-solid fa-user"></i> <?= $b_author ?></span>
                                        </div>
                                        <!-- Yahan blogs/ ki jagah blog/ kar diya gaya hai -->
                                        <h3 class="blog-title"><a href="<?= $site ?>blog/<?= $b_slug ?>"><?= $b_title ?></a></h3>
                                        <div class="blog-desc">
                                            <?= strlen($b_desc) > 120 ? mb_strimwidth($b_desc, 0, 117, '...') :$b_desc; ?>
                                        </div>
                                        <!-- Yahan blogs/ ki jagah blog/ kar diya gaya hai -->
                                        <a href="<?= $site ?>blog/<?= $b_slug ?>" class="read-more-btn">Read More <i class="fa-solid fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center py-5"><div class="alert alert-info">No blogs published yet. Stay tuned!</div></div>';
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <nav>
                            <ul class="pagination justify-content-center gap-2">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Prev</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($page ==$i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >=$total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
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
</body>
</html>