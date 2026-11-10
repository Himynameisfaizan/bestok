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
                <h2 class="breadcrumb-title">Certificates</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.php">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
                    <li class="active">Certificates</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- gallery-area -->
        <div class="gallery-area py-100 mt-5 mb-5">
    <div class="container">
        <div class="row g-4 popup-gallery">
            <?php
            // Global directory environment root initialization
            if (!isset($site)) {
                $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
            }

            // 🎯 FETCH ACTIVE CERTIFICATES FROM THE DATABASE
            $cert_query = "SELECT * FROM `certificates` WHERE `status` = 1 ORDER BY `cert_id` DESC";
            $cert_res   = mysqli_query($conn, $cert_query);

            if ($cert_res && mysqli_num_rows($cert_res) > 0) {
                while ($cert_row = mysqli_fetch_assoc($cert_res)) {
                    $cert_title = htmlspecialchars($cert_row['title']);
                    $cert_img   = htmlspecialchars($cert_row['image']); // Pattern: cert_1779357402_4945.webp

                    // Dynamic absolute folder routing framework validation check
                    // Aapke structural pattern ke mutabik admin panel uploads folder path match kiya hai
                    $cert_image_src = (!empty($cert_img) && file_exists("admin/uploads/certificates/".$cert_img)) ? $site."admin/uploads/certificates/".$cert_img : $site."assets/img/cat1.webp";
                    
                    // Fallback check alternative in case path is directly under admin/uploads/
                    if (!file_exists("admin/uploads/certificates/".$cert_img) && file_exists("admin/uploads/".$cert_img)) {
                        $cert_image_src = $site."admin/uploads/".$cert_img;
                    }
                    ?>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="gallery-img dfss">
                                <img src="<?= $cert_image_src ?>" alt="<?= $cert_title ?>" onerror="this.src='<?= $site ?>assets/img/cat1.webp'">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" href="<?= $cert_image_src ?>">
                                    <i class="fal fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center py-4"><div class="alert alert-info">No structural certification records loaded in repository tree currently.</div></div>';
            }
            ?>
        </div>
    </div>
</div>



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