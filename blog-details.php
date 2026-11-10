<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: " . $site . "blogs.php");
    exit();
}

$blog_slug = mysqli_real_escape_string($conn,$_GET['slug']);

$blog_query = "SELECT * FROM `blogs` WHERE `slug` = '$blog_slug' AND `status` = 1 LIMIT 1";
$blog_res = mysqli_query($conn,$blog_query);

if (!$blog_res || mysqli_num_rows($blog_res) == 0) {
    header("Location: " . $site . "blogs.php");
    exit();
}

$blog = mysqli_fetch_assoc($blog_res);
$b_id    =$blog['blog_id'];
$b_title = htmlspecialchars($blog['title']);
$b_desc  = nl2br($blog['description']); 
$b_date  = date('F j, Y', strtotime($blog['created_at']));
$b_author = htmlspecialchars($blog['author']);
$b_img   =$blog['image'];

if (!empty($b_img)) {
    $safe_img = rawurlencode($b_img);
    $path1 = "admin/uploads/blogs/" . $b_img;
    $path2 = "admin/uploads/" . $b_img;
    $img_src = file_exists($path1) ? $site . "admin/assets/img/uploads/blogs/" . $safe_img : $site . "admin/assets/img/uploads/blogs/" . $safe_img;
} else {
    $img_src =$site . "assets/img/placeholder.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $b_title ?> | Bestok Blog</title>
    
    <link rel="icon" type="image/x-icon" href="<?= $site ?>assets/img/fav.png">
    <link rel="stylesheet" href="<?= $site ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="<?= $site ?>assets/css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        .blog-detail-img { width: 100%; border-radius: 12px; margin-bottom: 25px; max-height: 500px; object-fit: cover; }
        .blog-detail-meta { font-size: 14px; color: #64748b; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0; }
        .blog-detail-meta i { color: #13345d; margin-right: 5px; }
        .blog-detail-content { font-size: 16px; color: #475569; line-height: 1.8; }
        
        .sidebar-widget { background: #f8fafc; padding: 25px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #edf2f7; }
        .sidebar-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #13345d; display: inline-block; }
        .recent-blog-list { list-style: none; padding: 0; margin: 0; }
        .recent-blog-list li { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #cbd5e1; }
        .recent-blog-list li:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .recent-blog-list a { color: #1e293b; font-weight: 600; text-decoration: none; transition: color 0.2s; font-size: 15px; }
        .recent-blog-list a:hover { color: #13345d; }
        .recent-date { display: block; font-size: 12px; color: #94a3b8; margin-top: 5px; }
    </style>
</head>
<body class="home-2">
    <?php include 'inc/header.php'; ?>

    <main class="main">
        <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Blog Details</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                    <li><a href="<?= $site ?>blogs.php">Blogs</a> <i class="fa fa-angle-double-right"></i></li>
                    <li class="active">Details</li>
                </ul>
            </div>
        </div>

        <div class="blog-detail-area pt-80 pb-80">
            <div class="container">
                <div class="row">
                    <!-- Main Blog Content -->
                    <div class="col-lg-8 mb-5 mb-lg-0">
                        <article>
                            <img src="<?= $img_src ?>" alt="<?= $b_title ?>" class="blog-detail-img" onerror="this.src='<?= $site ?>assets/img/placeholder.jpg'">
                            <h1 class="mb-3" style="font-size: 28px; font-weight: 800; color: #0f1c3f;"><?= $b_title ?></h1>
                            <div class="blog-detail-meta">
                                <span class="me-4"><i class="fa-solid fa-calendar-days"></i> Published on: <?= $b_date ?></span>
                                <span><i class="fa-solid fa-user"></i> Author: <?= $b_author ?></span>
                            </div>
                            <div class="blog-detail-content">
                                <?= $b_desc ?>
                            </div>
                        </article>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <div class="sidebar-widget">
                                <h3 class="sidebar-title">Recent Posts</h3>
                                <ul class="recent-blog-list">
                                    <?php
                                    $recent_query = "SELECT title, slug, created_at FROM `blogs` WHERE `status` = 1 AND `blog_id` != '$b_id' ORDER BY `blog_id` DESC LIMIT 5";
                                    $recent_res = mysqli_query($conn,$recent_query);
                                    
                                    if ($recent_res && mysqli_num_rows($recent_res) > 0) {
                                        while ($r_blog = mysqli_fetch_assoc($recent_res)) {
                                            $r_date = date('M j, Y', strtotime($r_blog['created_at']));
                                            // Yahan blog-details.php/ ki jagah sahi url lagaya gaya hai
                                            echo '<li>
                                                    <a href="'.$site.'blog/'.htmlspecialchars($r_blog['slug']).'">'.htmlspecialchars($r_blog['title']).'</a>
                                                    <span class="recent-date"><i class="fa-regular fa-clock me-1"></i>'.$r_date.'</span>
                                                  </li>';
                                        }
                                    } else {
                                        echo '<li class="text-muted">No other posts found.</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'inc/footer.php'; ?>
</body>
</html>