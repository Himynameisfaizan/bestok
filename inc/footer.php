<footer class="footer-area" style="background-color: #0b1f3c; color: #fff; padding-top: 60px;">
    <div class="footer-widget">
        <div class="container">
            <div class="row g-4 pb-5 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">

                <?php
                // Ensure global base website link folder exists cleanly
                if (!isset($site)) {
                    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
                }

                // 1. FETCH THE BASE CONTACT DATA FROM CONTACTS TABLE
                $footer_contact_query = "SELECT * FROM `contacts` LIMIT 1";
                $footer_contact_res   = mysqli_query($conn, $footer_contact_query);
                $foot_data = ($footer_contact_res && mysqli_num_rows($footer_contact_res) > 0) ? mysqli_fetch_assoc($footer_contact_res) : [];

                // Data formatting with safe dynamic fallbacks
                $f_phone   = !empty($foot_data['phone']) ? htmlspecialchars($foot_data['phone']) : '+91-9871126707';
                $f_phone2  = !empty($foot_data['phone2']) ? htmlspecialchars($foot_data['phone2']) : '';
                
                $f_email   = !empty($foot_data['email']) ? htmlspecialchars($foot_data['email']) : 'bestocompressors@gmail.com';
                $f_email2  = !empty($foot_data['email2']) ? htmlspecialchars($foot_data['email2']) : '';

                $f_address  = !empty($foot_data['address']) ? htmlspecialchars($foot_data['address']) : '103-104, Durga Enclave G.T Road Rajendra Nagar Sahibabad Ghaziabad 201005';
                $f_address2 = !empty($foot_data['address2']) ? htmlspecialchars($foot_data['address2']) : 'Kh No. 55, Baghpat Road, Baleni, Baghpat-250626, Uttar Pradesh, India';
                
                $f_map      = !empty($foot_data['map']) ? $foot_data['map'] : '';
                ?>

                <!-- COLUMN 1: Company Info & Contact -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                    <div class="footer-widget-box pe-lg-3">
                        <?php
                        // FETCH ACTIVE FOOTER LOGO
                        $f_logo_query = "SELECT `logo_path` FROM `logos` WHERE `location` = 'footer' AND `is_active` = 1 LIMIT 1";
                        $f_logo_res   = mysqli_query($conn, $f_logo_query);
                        $f_logo_src   = ($f_logo_res && mysqli_num_rows($f_logo_res) > 0) ? $site . "admin/uploads/" . mysqli_fetch_assoc($f_logo_res)['logo_path'] : $site . "assets/img/logo-white.png";
                        ?>
                        <a href="<?= $site ?>index.php" class="d-inline-block mb-4 bg-white p-2 rounded">
                            <img src="<?= htmlspecialchars($f_logo_src) ?>" alt="Bestok Industries"
                                style="max-width: 180px;" onerror="this.src='<?= $site ?>assets/img/logo.png'">
                        </a>
                        <p style="font-size: 0.9rem; color: #cbd5e1; line-height: 1.6; margin-bottom: 25px;">
                            We manufacture and supply a comprehensive range of premium compressed air equipment for
                            diverse industrial applications across India.
                        </p>

                        <ul class="list-unstyled" style="font-size: 0.95rem;">
                            <li class="d-flex mb-3">
                                <i class="fa fa-phone text-danger me-3 mt-1"></i>
                                <div>
                                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone) ?>"
                                        class="text-white text-decoration-none hover-red"><?= $f_phone ?></a>
                                    <?php if(!empty($f_phone2)): ?>
                                    <br><a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone2) ?>"
                                        class="text-white text-decoration-none hover-red"><?= $f_phone2 ?></a>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="fa fa-envelope text-danger me-3 mt-1"></i>
                                <div>
                                    <a href="mailto:<?= $f_email ?>"
                                        class="text-white text-decoration-none hover-red"><?= $f_email ?></a>
                                </div>
                            </li>
                            <li class="d-flex mb-2">
                                <i class="fa fa-map-marker-alt text-danger me-3 mt-1"></i>
                                <span class="text-white opacity-75" style="line-height: 1.5;"><?= $f_address ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- COLUMN 2: Quick Links & Policies -->
                <div class="col-lg-2 col-md-6 col-sm-6 mb-4 mb-lg-0">
                    <div class="footer-widget-box">
                        <h4 class="text-white fw-bold mb-4 position-relative pb-2" style="font-size: 1.2rem;">Quick
                            Links
                            <span
                                style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #e30613;"></span>
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="<?= $site ?>index.php"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>Home</a></li>
                            <li class="mb-2"><a href="<?= $site ?>about/"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>About Us</a></li>
                            <li class="mb-2"><a href="<?= $site ?>gallery/"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>Gallery</a></li>
                            <li class="mb-2"><a href="<?= $site ?>certificates/"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>Certificates</a></li>
                            <li class="mb-2"><a href="<?= $site ?>career/"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>Career</a></li>
                            <li class="mb-4"><a href="<?= $site ?>contact-us/"
                                    class="text-white text-decoration-none hover-red" style="transition:0.3s;"><i
                                        class="fa fa-angle-right text-danger me-2"></i>Contact Us</a></li>
                        </ul>

                        <h4 class="text-white fw-bold mb-3 position-relative pb-2 mt-4" style="font-size: 1.1rem;">
                            Policies
                            <span
                                style="position: absolute; bottom: 0; left: 0; width: 30px; height: 2px; background: #e30613;"></span>
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="<?= $site ?>privacy-policy/"
                                    class="text-white text-decoration-none hover-red opacity-75"
                                    style="transition:0.3s; font-size: 0.9rem;">Privacy Policy</a></li>
                            <li class="mb-2"><a href="<?= $site ?>refund-policy/"
                                    class="text-white text-decoration-none hover-red opacity-75"
                                    style="transition:0.3s; font-size: 0.9rem;">Refund & Cancellation</a></li>
                            <li class="mb-2"><a href="<?= $site ?>shipping-return/"
                                    class="text-white text-decoration-none hover-red opacity-75"
                                    style="transition:0.3s; font-size: 0.9rem;">Shipping Policy</a></li>
                            <li class="mb-2"><a href="<?= $site ?>terms-condition/"
                                    class="text-white text-decoration-none hover-red opacity-75"
                                    style="transition:0.3s; font-size: 0.9rem;">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>

                <!-- COLUMN 3: Products -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0">
                    <div class="footer-widget-box">
                        <h4 class="text-white fw-bold mb-4 position-relative pb-2" style="font-size: 1.2rem;">Our
                            Products
                            <span
                                style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #e30613;"></span>
                        </h4>
                        <ul class="list-unstyled">
                            <?php
                            $footer_prod_query = "SELECT `pro_name`, `slug_url` FROM `products` WHERE `status` = 1 AND `is_disabled` = 0 ORDER BY `display_order` ASC, `pro_id` DESC LIMIT 7";
                            $footer_prod_res   = mysqli_query($conn, $footer_prod_query);

                            if ($footer_prod_res && mysqli_num_rows($footer_prod_res) > 0) {
                                while ($f_prod = mysqli_fetch_assoc($footer_prod_res)) {
                                    $f_prod_name = htmlspecialchars($f_prod['pro_name']);
                                    $f_prod_slug = htmlspecialchars($f_prod['slug_url']);
                                    ?>
                            <li class="mb-3 border-bottom pb-2"
                                style="border-color: rgba(255,255,255,0.05) !important;">
                                <a href="<?= $site ?>product/<?= $f_prod_slug ?>"
                                    class="text-white text-decoration-none hover-red"
                                    style="transition:0.3s; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="fa fa-angle-double-right text-danger me-2"></i> <?= $f_prod_name ?>
                                </a>
                            </li>
                            <?php
                                }
                            } else {
                                echo '<li class="text-muted"><i class="fa fa-angle-double-right me-2"></i> No Products Found</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- COLUMN 4: Location Map -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="footer-widget-box">
                        <h4 class="text-white fw-bold mb-4 position-relative pb-2" style="font-size: 1.2rem;">Our
                            Location
                            <span
                                style="position: absolute; bottom: 0; left: 0; width: 40px; height: 2px; background: #e30613;"></span>
                        </h4>
                        <div class="map-container rounded overflow-hidden shadow-sm"
                            style="height: 250px; border: 2px solid rgba(255,255,255,0.1);">
                            <?php
                            if (!empty($f_map) && (strpos($f_map, '<iframe') !== false || strpos($f_map, 'https://www.google.com/maps/embed') !== false)) {
                                
                                // Clean up the input string (if it's just a URL, wrap it in iframe)
                                if (strpos($f_map, '<iframe') === false) {
                                    $f_map = '<iframe src="' . $f_map . '" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                                }
                                
                                // Standardize height and width if iframe exists
                                $f_map = preg_replace('/width="[^"]*"/i', 'width="100%"', $f_map);
                                $f_map = preg_replace('/height="[^"]*"/i', 'height="250"', $f_map);
                                
                                echo htmlspecialchars_decode(stripslashes($f_map));
                            } else {
                                // Default Fallback Map
                                ?>
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224087.03972049375!2d77.07640006082165!3d28.648937736279436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfbb4034059a9%3A0x1b3b5286449314e0!2sSahibabad!5e0!3m2!1sen!2sin!4v1737536882656!5m2!1sen!2sin"
                                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- COPYRIGHT SECTION -->
    <div class="container pb-3 pt-4">
        <div class="copyright text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <p class="copyright-text mb-0" style="font-size: 0.9rem; color: #94a3b8;">
                        <?php
                        // Fetch the dynamic copyright setting details
                        $copyright_query = "SELECT `copyright_text` FROM `contacts` LIMIT 1";
                        $copyright_res   = mysqli_query($conn, $copyright_query);
                        $copyright_data  = ($copyright_res && mysqli_num_rows($copyright_res) > 0) ? mysqli_fetch_assoc($copyright_res) : [];

                        if (!empty($copyright_data['copyright_text'])) {
                            echo htmlspecialchars_decode(stripslashes($copyright_data['copyright_text']));
                        } else {
                            ?>
                        © Copyright <?= date('Y') ?> Bestok Enterprises Pvt. Ltd. All Rights Reserved. Design &
                        Developed by <a href="https://digitalwebtrackers.com/" target="_blank"
                            class="text-danger text-decoration-none fw-bold hover-white">Digital Web Trackers.</a>
                        <?php
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Hover Effects */
.footer-area .hover-red:hover {
    color: #e30613 !important;
}

.footer-area .hover-white:hover {
    color: #ffffff !important;
}

/* Sticky Side Icons */
.fix-icon,
.fix-icon-whataap {
    position: fixed;
    z-index: 999;
    bottom: 80px;
    left: 20px;
    transition: transform 0.3s;
}

.fix-icon-whataap {
    bottom: 20px;
}

.fix-icon:hover,
.fix-icon-whataap:hover {
    transform: scale(1.1);
}

.fix-icon img,
.fix-icon-whataap img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

/* Scroll Top Button */
#scroll-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #000;
    color: #fff;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    z-index: 999;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: background 0.3s;
}

#scroll-top:hover {
    background: #e30613;
}
</style>

<!-- Floating Action Buttons -->
<div class="fix-icon">
    <div id="fix-icon" class="fix-icon-item">
        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone) ?>">
            <img src="<?= $site ?>assets/img/phone-icon.png" alt="Call Bestok Support">
        </a>
    </div>
</div>

<div class="fix-icon-whataap">
    <div id="fix-icon-whataap" class="fix-icon-whataap-item">
        <?php 
        $f_wp = !empty($foot_data['wp_number']) ? $foot_data['wp_number'] : $f_phone;
        $wp_clean_target = preg_replace('/[^0-9]/', '', $f_wp); 
        ?>
        <a href="https://api.whatsapp.com/send?phone=<?= $wp_clean_target ?>&text=Hi%20Bestok%20Industries,%20I%20am%20interested%20in%20your%20products."
            target="_blank">
            <img src="<?= $site ?>assets/img/whatsapp.svg" alt="Chat With Us on WhatsApp">
        </a>
    </div>
</div>

<a href="#" id="scroll-top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>