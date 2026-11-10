<?php include 'inc/header.php'; ?>

<body class="home-2">

    <main class="main">

        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">About Us</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.php">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
                    <li class="active">About Us</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->

        <!-- about area -->
        <div class="about-area pb-70">
            <div class="container">
                <div class="row align-items-center">
                    <?php
            // Global environmental site pipeline setup
            if (!isset($site)) {
                $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
            }

            // 🎯 FETCH ABOUT US DATA DIRECTLY FROM about_us TABLE
            $about_us_query = "SELECT * FROM `about_sections` WHERE `id` = 1 LIMIT 1";
            $about_us_res   = mysqli_query($conn, $about_us_query);

            if ($about_us_res && mysqli_num_rows($about_us_res) > 0) {
                $about_data = mysqli_fetch_assoc($about_us_res);
                $db_title   = htmlspecialchars($about_data['title']);
                $db_img     = htmlspecialchars($about_data['image_url']); // Pattern: uploads/about_us/about-us-...
                $db_content = $about_data['content']; // Dynamic text format content row

                // Image absolute administrative check logic path mapping
                $about_image_src = (!empty($db_img) && file_exists("admin/".$db_img)) ? $site."admin/".$db_img : $site."assets/img/about-img.jpg";
                ?>

                    <div class="col-lg-6">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <img class="img-3" src="<?= $about_image_src ?>" alt="<?= $db_title ?>"
                                    onerror="this.src='<?= $site ?>assets/img/about-img.jpg'">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                            <div class="site-heading mb-2">
                                <span class="site-title-tagline">About Us</span>
                            </div>
                            <h2 class="dffg"><?= $db_title ?></h2>

                            <div class="mb-3 product-dynamic-brief">
                                <?= $db_content ?>
                            </div>

                            <!--<a href="<?= $site ?>about.php" class="theme-btn">Know More<i class="fas fa-arrow-right"></i></a>-->
                        </div>
                    </div>

                    <?php 
            } else {
                // Secure layout fallback loop if dataset returns empty on production
            ?>
                    <div class="col-lg-6">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <img class="img-3" src="<?= $site ?>assets/img/about-img.jpg" alt="About Bestok">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                            <div class="site-heading mb-2">
                                <span class="site-title-tagline">About Us</span>
                            </div>
                            <h2 class="dffg">Welcome to BESTOK INDUSTRIES PVT LTD</h2>
                            <p class="mb-3">We have consistently upheld the highest standards of quality in both the
                                products we manufacture and the manner in which we interact with our clients.</p>
                            <!--<a href="<?= $site ?>about.php" class="theme-btn">Know More<i class="fas fa-arrow-right"></i></a>-->
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- about area end -->


        <!-- counter area -->
        <div class="counter-area pt-40 pb-40">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-12">
                        <div class="content-box">
                            <h2>Provide You The Highest Quality Work <br>That Meets Your Expectation.</h2>
                            <div class="link-box"><a href="tel:9871126707" class="theme-btn btn-style-one">Call Now:
                                    +91-9871126707</a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- counter area end -->


        <!-- Feautes Section -->
        <section class="features-section">
            <div class="container">
                <div class="outer-box">
                    <div class="row">

                        <!-- Feature Block -->
                        <div class="feature-block-two col-lg-4 col-md-4 col-sm-12 wow fadeInUp">
                            <div class="inner-box">
                                <div class="upper-box">
                                    <div class="icon-box"> <img src="assets/img/crane.png" alt="Crane Icon"></div>
                                </div>
                                <div class="lower-content">
                                    <h4><a href="#">Our Workforce</a></h4>
                                    <div class="text">Our highly skilled and versatile workforce has been crucial to our
                                        continuous success. Our complete workforce is made up of a diverse range of
                                        professionals who all work persistently and collaboratively to help achieve our
                                        organizational goals.
                                    </div>
                                    <div class="link-box"><a href="#">Read More</a></div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Block -->
                        <div class="feature-block-two col-lg-4 col-md-4 col-sm-12 wow fadeInUp" data-wow-delay="400ms">
                            <div class="inner-box">
                                <div class="upper-box">
                                    <div class="icon-box"> <img src="assets/img/worker.png" alt="Crane Icon"></div>
                                </div>
                                <div class="lower-content">
                                    <h4><a href="#">Customer Satisfaction</a></h4>
                                    <div class="text">In our company, we always place an immense emphasis on customer
                                        satisfaction and go the extra mile to achieve it. Before presenting the best
                                        solutions, we first thoroughly recognize the specific requirements of the
                                        customers.</div>
                                    <div class="link-box"><a href="#">Read More</a></div>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Block -->
                        <div class="feature-block-two col-lg-4 col-md-4 col-sm-12 wow fadeInUp" data-wow-delay="800ms">
                            <div class="inner-box">
                                <div class="upper-box">
                                    <div class="icon-box"><img src="assets/img/robot-arm.png" alt="Crane Icon"></div>
                                </div>
                                <div class="lower-content">
                                    <h4><a href="#">Smart Technology</a></h4>
                                    <div class="text">Innovative solutions leveraging cutting-edge smart technology to
                                        deliver highly efficient, intelligent, and sustainable operations across
                                        industries, ensuring optimal performance, enhanced productivity, and long-term
                                        reliability for modern businesses.</div>
                                    <div class="link-box"><a href="#">Read More</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Feautes Section -->


        <!-- partner area -->

        <div class="partner-area mt-40 mb-40">
            <div class="container">
                <?php
        // Ensure global base directory path is initiated
        if (!isset($site)) {
            $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/bestok/";
        }

        // 🎯 FETCH brands from database
        $carousel_brand_query = "SELECT * FROM `brands` ORDER BY `id` DESC";
        $carousel_brand_res   = mysqli_query($conn, $carousel_brand_query);
        $total_brands         = ($carousel_brand_res) ? mysqli_num_rows($carousel_brand_res) : 0;

        // SMART CAROUSEL SWITCH:
        // Agar 4 ya usse kam logos hain, toh Owl Carousel class mat lagao (taaki 1 logo duplicate na ho).
        // Flexbox class lagao jisse logo automatically center mein dikhe.
        // Jaise hi 5 ya zyada logos honge, automatic slider chalu ho jayega!
        $wrapper_class = ($total_brands > 4) 
            ? "partner-wrapper partner-slider owl-carousel owl-theme" 
            : "d-flex flex-wrap justify-content-center align-items-center gap-5 py-3";
        ?>

                <div class="<?= $wrapper_class ?>">
                    <?php
            if ($total_brands > 0) {
                while ($b_row = mysqli_fetch_assoc($carousel_brand_res)) {
                    $b_title     = htmlspecialchars($b_row['brand_name']);
                    $b_logo_file = $b_row['logo_path'];

                    // Foolproof Image Path Logic (Spaces ko %20 banayega)
                    if (!empty($b_logo_file)) {
                        $safe_logo_name = str_replace(' ', '%20', $b_logo_file);
                        $live_logo_src  = $site . "admin/" . $safe_logo_name;
                    } else {
                        $live_logo_src  = $site . "assets/img/logo.png";
                    }
                    ?>
                    <div class="partner-item text-center px-3">
                        <img src="<?= $live_logo_src ?>" alt="<?= $b_title ?>" class="logoclint img-fluid"
                            style="max-height: 150px; width: auto; object-fit: contain; display: inline-block;"
                            onerror="this.src='<?= $site ?>assets/img/logo.png'">
                    </div>
                    <?php
                }
            } else {
                echo '<div class="text-center text-muted py-3 w-100">No client logos added yet.</div>';
            }
            ?>
                </div>
            </div>
        </div>

        <!-- partner area end -->


    </main>


    <?php include 'inc/footer.php'; ?>

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