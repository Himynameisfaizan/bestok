<?php include 'inc/header.php'; ?>

<body class="home-2">

    <main class="main">

        <!-- hero area -->
        <div class="hero-section">
            <div class="hero-slider owl-carousel">
                <?php
        // Global directory environment path checking pipeline
        if (!isset($site)) {
            $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
        }

        // 🎯 FETCH ACTIVE BANNERS ORDERED BY DISPLAY_ORDER FROM DATABASE
        // Note: Agar admin panel status toggle 1 karta hai to standard application check run hoga
        $banner_query = "SELECT * FROM `banners` ORDER BY `display_order` ASC, `id` DESC";
        $banner_res   = mysqli_query($conn, $banner_query);

        if ($banner_res && mysqli_num_rows($banner_res) > 0) {
            while ($b_row = mysqli_fetch_assoc($banner_res)) {
                $b_title = htmlspecialchars($b_row['title'] ?? 'Banner');
                $b_path  = htmlspecialchars($b_row['banner_path']); // Pattern: uploads/banners/banner_6a0...webp

                // Absolute administrative path evaluation mapping check
                $banner_image_src = (!empty($b_path) && file_exists("admin/".$b_path)) ? $site."admin/".$b_path : $site."assets/img/banner3.jpg";
                ?>
                <div class="hero-single">
                    <img class="img-3" src="<?= $banner_image_src ?>" alt="<?= $b_title ?>"
                        onerror="this.src='<?= $site ?>assets/img/banner3.jpg'">
                </div>
                <?php
            }
        } else {
            // Secure static fallback frames if table rows are empty in system repository
            ?>
                <div class="hero-single"><img class="img-3" src="<?= $site ?>assets/img/banner3.jpg"
                        alt="Default Banner 1"></div>
                <div class="hero-single"><img class="img-3" src="<?= $site ?>assets/img/banner4.jpg"
                        alt="Default Banner 2"></div>
                <div class="hero-single"><img class="img-3" src="<?= $site ?>assets/img/banner5.jpg"
                        alt="Default Banner 3"></div>
                <?php
        }
        ?>
            </div>
        </div>

        <!-- hero area end -->

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

        <!-- about area -->
        <div class="about-area pb-70">
            <div class="container">
                <div class="row align-items-center">
                    <?php
            // Ensure global environmental site pipeline is initiated
            if (!isset($site)) {
                $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
            }

            // 🎯 FETCH ABOUT SECTION DATA DIRECTLY FROM about_sections TABLE WHERE ID = 1
            $about_section_query = "SELECT * FROM `about_sections` WHERE `id` = 1 LIMIT 1";
            $about_section_res   = mysqli_query($conn, $about_section_query);

            if ($about_section_res && mysqli_num_rows($about_section_res) > 0) {
                $about_sec = mysqli_fetch_assoc($about_section_res);
                $db_title  = htmlspecialchars($about_sec['title']);
                $db_img    = htmlspecialchars($about_sec['image_url']); // Pattern: uploads/about_us/about-section-1...
                $raw_content = $about_sec['content'];

                // 🛠️ RUNTIME CLEANER: Automatically filter out unwanted text injections from database content strings
                $unwanted_strings = array("Repliky Hodinek", "Replik Rolex Uhren");
                $clean_content = str_replace($unwanted_strings, "", $raw_content);

                // Image absolute folder structural verification pathway check
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

                            <p class="mb-3">
                                <?= nl2br(htmlspecialchars($clean_content)) ?>
                            </p>

                            <a href="<?= $site ?>about" class="theme-btn">Know More<i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <?php 
            } else {
                // Hardcoded secure layout fallback if dataset is unexpectedly cleared on live production
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
                            <p class="mb-3">At Bestok Industries Private Limited, we have consistently upheld the
                                highest standards of quality in both the products we manufacture and the manner in which
                                we interact with our clients.</p>
                            <a href="<?= $site ?>about" class="theme-btn">Know More<i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- about area end -->

        <!-- our products -->
        <div id="rs-blog" class="rs-blog gray-color pt-60 pb-80 md-pt-70 md-pb-80">
            <div class="container">
                <div class="sec-title2 text-center mb-45">
                    <h2 class="title title2">
                        Products and Solutions
                    </h2>
                    <p>Discover powerful and reliable products crafted to deliver smart, efficient, and effective
                        solutions for your business needs.</p>
                    <div class="heading-divider"></div>
                </div>

                <div class="row">
                    <?php
            // 🎯 FETCH ACTIVE GLOBAL PRODUCTS LIMITING TO 8 ITEMS FOR HOMEPAGE GRID DISPLAY
           $homepage_prod_query = "SELECT
                            p.*,
                            c.categories AS category_name,
                            s.categories AS sub_category_name
                        FROM products p
                        LEFT JOIN categories c
                            ON c.cate_id = p.pro_cate
                        LEFT JOIN sub_categories s
                            ON s.id = p.pro_sub_cate
                        WHERE p.status = 1
                        AND p.is_disabled = 0
                        ORDER BY p.display_order ASC, p.id DESC
                        LIMIT 8";
            $homepage_prod_res   = mysqli_query($conn, $homepage_prod_query);

            if ($homepage_prod_res && mysqli_num_rows($homepage_prod_res) > 0) {
                while ($prod = mysqli_fetch_assoc($homepage_prod_res)) {
                    $prod_name = htmlspecialchars($prod['pro_name']);
                    $prod_desc = $prod['short_desc']; 
                    $prod_slug = htmlspecialchars($prod['slug_url']);
                    $prod_img  = htmlspecialchars($prod['pro_img']);
                    
                    // Direct dynamic image check via administrative architecture pipeline rules
                    $image_src = (!empty($prod_img) && file_exists("admin/assets/img/uploads/".$prod_img)) ? $site."admin/assets/img/uploads/".$prod_img : $site."assets/img/p1.jpg";
                    ?>
                    <div class="col-sm-3 col-md-3 col-12 mb-4">
                        <div class="product-box-card">
                            <div class="product-box-img">
                                <img src="<?= $image_src ?>" alt="<?= $prod_name ?>"
                                    onerror="this.src='<?= $site ?>assets/img/p1.jpg'">
                            </div>
                            <h3><?= $prod_name ?></h3>
                            <div class="product-short-text-wrap mb-2">
                                <?php 
                                // Strip tags to avoid unclosed HTML elements inside structural grid blocks
                                $clean_text = strip_tags($prod_desc);
                                echo strlen($clean_text) > 95 ? substr($clean_text, 0, 92) . '...' : $clean_text;
                                ?>
                            </div>
                            <a href="<?= $site ?>product/<?= $prod_slug ?>" class="product-box-btn">Read more</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center py-4"><div class="alert alert-warning">No solutions or products logged in repository grid matrix currently.</div></div>';
            }
            ?>
                </div>
            </div>
        </div>
        <!-- /our products -->


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


        <!-- Achivements Section Start -->
        <section class="achivements-section fix section-bg-2 section-padding bg-cover"
            style="background-image: url('assets/img/achivements-bg-shape.png');">
            <div class="container">
                <div class="achivements-wrapper">
                    <div class="row g-4 justify-content-between">
                        <div class="col-xxl-6 col-xl-7 col-lg-8">
                            <div class="achivements-content">
                                <div class="section-title">
                                    <h6 class="wow fadeInUp">Why Choose
                                        Us</h6>
                                    <h2 class="splt-txt wow" data-splitting>Your Trusted Partner in Compressed Air
                                        Solutions

                                    </h2>
                                </div>
                                <p class="mt-3 mt-md-0 wow fadeInUp">
                                    At Bestok Industries Private Limited, we are committed to delivering high-quality
                                    compressed air equipment that meets the exacting standards of modern industries.
                                    From rotary screw compressors to air dryers, every product is manufactured with
                                    precision to ensure reliability, efficiency, and long-lasting performance for our
                                    valued clients.
                                </p>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                        <div class="counter-items">
                                            <div class="content">
                                                <div class="icon">
                                                    <img src="assets/img/11.svg" alt="img">
                                                </div>
                                                <h2><span class="counter" data-count="+" data-to="45"
                                                        data-speed="3000">45</span>k+</h2>
                                                <p>project Complete</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                        <div class="counter-items">
                                            <div class="content">
                                                <div class="icon">
                                                    <img src="assets/img/12.svg" alt="img">
                                                </div>
                                                <h2><span class="counter" data-count="+" data-to="25"
                                                        data-speed="3000">25</span>k+</h2>
                                                <p>Active On Clients</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                        <div class="counter-items">
                                            <div class="content">
                                                <div class="icon">
                                                    <img src="assets/img/13.svg" alt="img">
                                                </div>
                                                <h2><span class="counter" data-count="+" data-to="50"
                                                        data-speed="3000">50</span>+</h2>
                                                <p>Experience Team</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                        <div class="counter-items">
                                            <div class="content">
                                                <div class="icon">
                                                    <img src="assets/img/14.svg" alt="img">
                                                </div>
                                                <h2><span class="counter" data-count="+" data-to="15"
                                                        data-speed="3000">15</span>k+</h2>
                                                <p>Winning Awards</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-5 col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                            <div class="achivements-image">
                                <img src="assets/img/whyimg1.jpg" alt="img">
                                <div class="achivements-image-2 float-bob-y">
                                    <img src="assets/img/whyimg2.jpg" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- gallery-area -->
        <div class="gallery-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                            <span class="site-title-tagline">Our Gallery</span>
                            <h2 class="site-title">Let's Check Our <span>Gallery</span></h2>
                            <div class="heading-divider"></div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 popup-gallery">
                    <?php
            // Ensure global database context path variable exists
            if (!isset($site)) {
                $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
            }

            // 🎯 FETCH portfolio collection values sequentially from gallery table rows
            $main_gallery_query = "SELECT * FROM `gallery` ORDER BY `ID` DESC";
            $main_gallery_res   = mysqli_query($conn, $main_gallery_query);

            if ($main_gallery_res && mysqli_num_rows($main_gallery_res) > 0) {
                while ($g_row = mysqli_fetch_assoc($main_gallery_res)) {
                    $image_identifier = htmlspecialchars($g_row['image_name']);
                    $image_file_path  = htmlspecialchars($g_row['image_path']); // Linked field path string

                    // Absolute folder schema tracking validation rules
                    $live_image_target = (!empty($image_file_path) && file_exists("admin/".$image_file_path)) ? $site."admin/".$image_file_path : $site."assets/img/p1.jpg";
                    ?>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="gallery-img">
                                <img src="<?= $live_image_target ?>" alt="<?= $image_identifier ?>"
                                    onerror="this.src='<?= $site ?>assets/img/p1.jpg'">
                            </div>
                            <div class="gallery-content">
                                <a class="popup-img gallery-link" href="<?= $live_image_target ?>">
                                    <i class="fal fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center py-5"><div class="alert alert-info">No structural asset items configured in portfolio collection block currently.</div></div>';
            }
            ?>
                </div>
            </div>
        </div>
        <!-- gallery-area end -->



        <!-- testimonial-area -->
        <div class="testimonial-area ts-bg pt-80 pb-60">
            <div class="container">

                <div class="sec-title2 ftyr text-center mb-45">
                    <h2 class="title title2">
                        What Our Client Say
                    </h2>
                    <p>"Every testing solution we received exceeded our expectations in terms of accuracy, consistency,
                        and dependability."</p>
                    <div class="heading-divider"></div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                            <?php
                    // Environmental site variables fallback loop protection
                    if (!isset($site)) {
                        $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
                    }

                    // 🎯 FETCH ACTIVE TESTIMONIALS FROM DATABASE
                    $test_query = "SELECT * FROM `testimonials` WHERE `status` = 1 ORDER BY `test_id` DESC";
                    $test_res   = mysqli_query($conn, $test_query);

                    if ($test_res && mysqli_num_rows($test_res) > 0) {
                        while ($t_row = mysqli_fetch_assoc($test_res)) {
                            $client_name = htmlspecialchars($t_row['name']);
                            $client_desg = htmlspecialchars($t_row['designation'] ?? 'Client');
                            $client_msg  = htmlspecialchars($t_row['message']);
                            $client_img  = htmlspecialchars($t_row['image'] ?? ''); // Pattern string value rows

                            // Dynamic image path assessment handling logic
                            // Table data rows me image field NULL ho sakti hai, isliye fallback image target map kiya hai
                            $avatar_src = (!empty($client_img) && file_exists("admin/assets/img/uploads/".$client_img)) ? $site."admin/assets/img/uploads/".$client_img : $site."assets/img/2.webp";
                            ?>
                            <div class="testimonial-item">
                                <div class="testimonial-card-items">
                                    <div class="shape-img">
                                        <img src="<?= $site ?>assets/img/shape-left.png" alt="quote-shape">
                                    </div>
                                    <p>
                                        "<?= $client_msg ?>"
                                    </p>
                                    <!--<div class="client-info-items">-->
                                    <!--    <div class="client-info">-->
                                    <!--        <img src="<?= $avatar_src ?>" alt="<?= $client_name ?>" onerror="this.src='<?= $site ?>assets/img/2.webp'">-->
                                    <!--        <div class="content">-->
                                    <!--<h4><?= $client_name ?></h4>-->
                                    <!--            <span><?= $client_desg ?></span>-->
                                    <!--            <div class="star">-->
                                    <!--                <i class="fa-solid fa-star"></i>-->
                                    <!--                <i class="fa-solid fa-star"></i>-->
                                    <!--                <i class="fa-solid fa-star"></i>-->
                                    <!--                <i class="fa-solid fa-star"></i>-->
                                    <!--                <i class="fa-solid fa-star"></i>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        // Empty repository fallback structure loop
                        ?>
                            <!--<div class="testimonial-item">-->
                            <!--    <div class="testimonial-card-items">-->
                            <!--        <div class="shape-img"><img src="<?= $site ?>assets/img/shape-left.png" alt="img"></div>-->
                            <!--        <p>"Bestok Industries provides top-quality compressed air equipment that we can always rely on. Their products are efficient, precise, and long-lasting."</p>-->
                            <!--        <div class="client-info-items">-->
                            <!--            <div class="client-info">-->
                            <!--                <img src="<?= $site ?>assets/img/2.webp" alt="Default User">-->
                            <!--                <div class="content">-->
                            <!--                    <h4>Rahul Mishra</h4>-->
                            <!--                    <span>Founder & CEO</span>-->
                            <!--                    <div class="star">-->
                            <!--                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>-->
                            <!--                    </div>-->
                            <!--                </div>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- testimonial-area end -->


        <div class="partner-area mt-40 mb-40">
            <div class="container">
                <div class="partner-wrapper partner-slider owl-carousel owl-theme">

                    <?php
            $video_query = mysqli_query($conn,"SELECT * FROM videos WHERE status='1' ORDER BY video_id DESC");

            if(mysqli_num_rows($video_query)>0){

                while($video=mysqli_fetch_assoc($video_query)){
            ?>

                    <div class="item">
                        <iframe width="100%" height="220"
                            src="https://www.youtube.com/embed/<?php echo $video['video_code']; ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo $video['video_code']; ?>"
                            title="<?php echo htmlspecialchars($video['title']); ?>" frameborder="0"
                            allow="autoplay; encrypted-media" allowfullscreen>
                        </iframe>
                    </div>

                    <?php
                }
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