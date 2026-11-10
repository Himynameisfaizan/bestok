<footer class="footer-area">
    <div class="footer-widget">
        <div class="container">
            <div class="footer-widget-wrap pt-60 pb-40">
                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <div class="footer-widget-box">
                            <h4 class="footer-widget-title">Get In Touch</h4>
                            <ul class="footer-contact">
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
                                $f_tele    = !empty($foot_data['telephone']) ? htmlspecialchars($foot_data['telephone']) : '';
                                $f_wp      = !empty($foot_data['wp_number']) ? htmlspecialchars($foot_data['wp_number']) : '';
                                
                                $f_email   = !empty($foot_data['email']) ? htmlspecialchars($foot_data['email']) : 'bestocompressors@gmail.com';
                                $f_email2  = !empty($foot_data['email2']) ? htmlspecialchars($foot_data['email2']) : '';
                                $f_email3  = !empty($foot_data['email3']) ? htmlspecialchars($foot_data['email3']) : '';

                                $f_address  = !empty($foot_data['address']) ? htmlspecialchars($foot_data['address']) : '103-104, Durga Enclave G.T Road Rajendra Nagar Sahibabad Ghaziabad 201005';
                                $f_address2 = !empty($foot_data['address2']) ? htmlspecialchars($foot_data['address2']) : 'Kh No. 55, Baghpat Road, Baleni, Baghpat-250626, Uttar Pradesh, India';
                                ?>

                                <li>
                                    <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                    <div class="content">
                                        <h6>Call Us</h6>
                                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone) ?>"><?= $f_phone ?></a>
                                        <?php if(!empty($f_phone2)): ?>
                                            <span class="text-white">, </span>
                                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone2) ?>"><?= $f_phone2 ?></a>
                                        <?php endif; ?>
                                        <?php if(!empty($f_tele)): ?>
                                            <span class="text-white">, </span>
                                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_tele) ?>"><?= $f_tele ?></a>
                                        <?php endif; ?>
                                    </div>
                                </li>

                                <li>
                                    <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                    <div class="content">
                                        <h6>Mail Us</h6>
                                        <a href="mailto:<?= $f_email ?>"><?= $f_email ?></a>
                                        <?php if(!empty($f_email2)): ?>
                                            <br><a href="mailto:<?= $f_email2 ?>" style="font-size: 13px; opacity: 0.85;"><?= $f_email2 ?></a>
                                        <?php endif; ?>
                                        <?php if(!empty($f_email3)): ?>
                                            <br><a href="mailto:<?= $f_email3 ?>" style="font-size: 13px; opacity: 0.85;"><?= $f_email3 ?></a>
                                        <?php endif; ?>
                                    </div>
                                </li>

                                <li>
                                    <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                    <div class="content">
                                        <h6>Office Address</h6>
                                        <p><?= $f_address ?></p>
                                    </div>
                                </li>

                                <li>
                                    <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                    <div class="content">
                                        <h6>Factory Address</h6>
                                        <p><?= $f_address2 ?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Quick Links</h4>
                            <ul class="footer-list">
                                <li><a href="<?= $site ?>index.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Home</a></li>
                                <li><a href="<?= $site ?>about.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> About Us</a></li>
                                <li><a href="<?= $site ?>gallery.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Gallery</a></li>
                                <li><a href="<?= $site ?>certificates.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Certificates</a></li>
                                <li><a href="<?= $site ?>career.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Career</a></li>
                                <li><a href="<?= $site ?>contact-us.php"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Our Products</h4>
                            <ul class="footer-list">
                                <?php
                                // Fetch active registered products dynamically from standard db mapping
                              $footer_prod_query = "SELECT `pro_name`, `slug_url`
FROM `products`
WHERE `status` = 1
AND `is_disabled` = 0
ORDER BY `display_order` ASC, `pro_id` DESC
LIMIT 7";
                                $footer_prod_res   = mysqli_query($conn, $footer_prod_query);

                                if ($footer_prod_res && mysqli_num_rows($footer_prod_res) > 0) {
                                    while ($f_prod = mysqli_fetch_assoc($footer_prod_res)) {
                                        $f_prod_name = htmlspecialchars($f_prod['pro_name']);
                                        $f_prod_slug = htmlspecialchars($f_prod['slug_url']);
                                        ?>
                                        <li>
                                            <a href="<?= $site ?>product/<?= $f_prod_slug ?>">
                                                <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?= $f_prod_name ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo '<li><a href="javascript:void(0);"><i class="fa fa-angle-double-right" aria-hidden="true"></i> No Products Logged</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="footer-widget-box">
                            <h4 class="footer-widget-title">Our Location</h4>
                            <?php
                            if (!empty($foot_data['map'])) {
                                // Dynamic execution strip removing escaped redundancy slashes
                                $cleaned_footer_map = stripslashes($foot_data['map']);
                                
                                if (strpos($cleaned_footer_map, '<iframe') !== false) {
                                    if (strpos($cleaned_footer_map, 'height=') !== false) {
                                        $cleaned_footer_map = preg_replace('/height="[0-9]+"/i', 'height="310"', $cleaned_footer_map);
                                        $cleaned_footer_map = preg_replace('/height:[ ]*[0-9]+px/i', 'height:310px', $cleaned_footer_map);
                                    }
                                    if (strpos($cleaned_footer_map, 'width=') !== false) {
                                        $cleaned_footer_map = preg_replace('/width="[0-9\%\b]+"/i', 'width="100%"', $cleaned_footer_map);
                                    }
                                    echo htmlspecialchars_decode($cleaned_footer_map);
                                } else {
                                    echo '<div class="text-danger small">Invalid Map Configuration Code.</div>';
                                }
                            } else {
                                ?>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224087.03972049375!2d77.07640006082165!3d28.648937736279436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfbb4034059a9%3A0x1b3b5286449314e0!2sSahibabad!5e0!3m2!1sen!2sin!4v1737536882656!5m2!1sen!2sin" width="100%" height="310" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-12 align-self-center">
                    <p class="copyright-text">
                        <?php
                        // Fetch the dynamic copyright setting details
                        $copyright_query = "SELECT `copyright_text` FROM `contacts` LIMIT 1";
                        $copyright_res   = mysqli_query($conn, $copyright_query);
                        $copyright_data  = ($copyright_res && mysqli_num_rows($copyright_res) > 0) ? mysqli_fetch_assoc($copyright_res) : [];

                        if (!empty($copyright_data['copyright_text'])) {
                            echo htmlspecialchars_decode(stripslashes($copyright_data['copyright_text']));
                        } else {
                            ?>
                            © Copyright 2026 Bestok Enterprises Pvt. Ltd. All Rights Reserved Design & Developed by <a href="https://digitalwebtrackers.com/" target="_blank">Digital Web Trackers.</a>
                            <?php
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="fix-icon">
    <div id="fix-icon" class="fix-icon-item">
        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $f_phone) ?>">
            <img src="<?= $site ?>assets/img/phone-icon.png" alt="Call Bestok Support">
        </a>
    </div>
</div>

<div class="fix-icon-whataap">
    <div id="fix-icon-whataap" class="fix-icon-whataap-item">
        <?php $wp_clean_target = !empty($f_wp) ? preg_replace('/[^0-9]/', '', $f_wp) : preg_replace('/[^0-9]/', '', $f_phone); ?>
        <a href="https://api.whatsapp.com/send?phone=<?= $wp_clean_target ?>&text=Hi%20Bestok%20Industries,%20I%20am%20interested%20in%20your%20products." target="_blank">
            <img src="<?= $site ?>assets/img/whatsapp.svg" alt="Chat With Us on WhatsApp">
        </a>
    </div>
</div>

<a href="#" id="scroll-top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>