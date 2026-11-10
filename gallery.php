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

    <meta name="google-site-verification" content="GGGzfztUktF_-gsCYU0Mi8gIcXEhBB6Rc9ECNgl1Crg" />

</head>

<body class="home-2">


    <?php include 'inc/header.php'; ?>


    <main class="main">

        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Gallery</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.php">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
                    <li class="active">Gallery</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- gallery-area -->
      <div class="gallery-area py-100 mt-5 mb-5">
    <div class="container">
        <div class="row g-4 popup-gallery">
            <?php
            // Global directory environment path mapping routing pipeline
            if (!isset($site)) {
                $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
            }

            // 🎯 FETCH ACTIVE GALLERY ITEMS FROM THE DYNAMIC DATABASE ROWS
            $gallery_query = "SELECT * FROM `gallery` ORDER BY `ID` DESC";
            $gallery_res   = mysqli_query($conn, $gallery_query);

            if ($gallery_res && mysqli_num_rows($gallery_res) > 0) {
                while ($gate_row = mysqli_fetch_assoc($gallery_res)) {
                    $img_title = htmlspecialchars($gate_row['image_name']);
                    $img_path  = htmlspecialchars($gate_row['image_path']); // Target record array: uploads/pro8.webp

                    // Absolute folder asset checking criteria validation mapping
                    $gallery_image_src = (!empty($img_path) && file_exists("admin/".$img_path)) ? $site."admin/".$img_path : $site."assets/img/p1.jpg";
                    ?>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="gallery-img">
                                <img src="<?= $gallery_image_src ?>" alt="<?= $img_title ?>" onerror="this.src='<?= $site ?>assets/img/p1.jpg'">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" href="<?= $gallery_image_src ?>">
                                    <i class="fal fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Empty fallback record notification block
                echo '<div class="col-12 text-center py-5"><div class="alert alert-info">No solution portfolio or gallery items loaded in database currently.</div></div>';
            }
            ?>
        </div>
    </div>
</div>
        <!-- gallery-area end -->




    </main>


    <?php include 'inc/footer.php'; ?>

    </main>

    <!-- js -->
    <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/jquery.appear.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/counter-up.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/parsley.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        new WOW().init();
    </script>
</body>

</body>

</html>