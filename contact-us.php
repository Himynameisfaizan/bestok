<?php include 'inc/header.php'; ?>

<body class="home-2">

    <main class="main">

        <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/bradcrambanner.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Contact Us</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.php">Home</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
                    <li class="active">Contact Us</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->


        <div class="contact-area py-4 mt-5">
            <div class="container">
                <div class="contact-content">
                    <div class="site-heading mb-3">

                        <h2 class="site-title">Feel Free to <span>Contact us</span></h2>
                        <p>
                        <p>If you have any questions about what we offer for consumers or for business, you can always
                            email us or call us via the below details. We&rsquo;ll reply within 24 hours.</p>
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-8">

                            <div class="row mt-2 contact-textpage">
                                <?php
    // 1. FETCH THE BASE CONFIGURATION INDEX MATRIX FROM CONTACTS TABLE
    $page_contact_query = "SELECT * FROM `contacts` LIMIT 1";
    $page_contact_res   = mysqli_query($conn, $page_contact_query);
    $p_data = ($page_contact_res && mysqli_num_rows($page_contact_res) > 0) ? mysqli_fetch_assoc($page_contact_res) : [];

    // Formulating variable streams with strict database fallbacks
    $p_address  = !empty($p_data['address']) ? htmlspecialchars($p_data['address']) : '103-104, Durga Enclave G.T Road Rajendra Nagar Sahibabad Ghaziabad 201005';
    
    $p_phone    = !empty($p_data['phone']) ? htmlspecialchars($p_data['phone']) : '+91-9871255334';
    $p_phone2   = !empty($p_data['phone2']) ? htmlspecialchars($p_data['phone2']) : '';
    $p_tele     = !empty($p_data['telephone']) ? htmlspecialchars($p_data['telephone']) : '';

    $p_email    = !empty($p_data['email']) ? htmlspecialchars($p_data['email']) : 'bestocompressors@gmail.com';
    $p_email2   = !empty($p_data['email2']) ? htmlspecialchars($p_data['email2']) : '';
    $p_email3   = !empty($p_data['email3']) ? htmlspecialchars($p_data['email3']) : '';
    ?>

                                <div class="col-md-12">
                                    <div class="contact-info">
                                        <div class="icon">
                                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                        </div>
                                        <div class="content">
                                            <h5>Visit our office</h5>
                                            <p><?= $p_address ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="contact-info">
                                        <div class="icon">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </div>
                                        <div class="content">
                                            <h5>Call Us</h5>
                                            <p>
                                                <a
                                                    href="tel:<?= preg_replace('/[^0-9+]/', '', $p_phone) ?>"><?= $p_phone ?></a>
                                            </p>
                                            <?php if(!empty($p_phone2)): ?>
                                            <p>
                                                <a
                                                    href="tel:<?= preg_replace('/[^0-9+]/', '', $p_phone2) ?>"><?= $p_phone2 ?></a>
                                            </p>
                                            <?php endif; ?>
                                            <?php if(!empty($p_tele)): ?>
                                            <p>
                                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $p_tele) ?>"><?= $p_tele ?>
                                                    (Tel)</a>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="contact-info">
                                        <div class="icon">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </div>
                                        <div class="content">
                                            <h5>Email Us</h5>
                                            <p>
                                                <a href="mailto:<?= $p_email ?>"><?= $p_email ?></a>
                                            </p>
                                            <?php if(!empty($p_email2)): ?>
                                            <p>
                                                <a href="mailto:<?= $p_email2 ?>"><?= $p_email2 ?></a>
                                            </p>
                                            <?php endif; ?>
                                            <?php if(!empty($p_email3)): ?>
                                            <p>
                                                <a href="mailto:<?= $p_email3 ?>"><?= $p_email3 ?></a>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="contactfill">
                                <h4>Get a Free Quote Today</h4>
                                <p>Looking for reliable </p>
                                <?php
// 1. INITIATE TRANSACTION PROCESSING ON POST REQUEST SUBMISSION
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    
    // Clean and cleanly sanitize incoming form input fields to prevent SQL injections
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email   = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone   = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $city    = mysqli_real_escape_string($conn, trim($_POST['city'])); // Form element
    
    // Mapping city parameter to table standard NOT NULL subject column index
    $subject = "Inquiry Location: " . (!empty($city) ? $city : "Not Specified");
    $message = "User has submitted a contact request from the sidebar quote widget form setup.";
    $status  = 0; // 0 = Unread entry status index matrix

    // 2. DATABASE PERSISTENCE: Save the record inside table structure sequence
    $inquiry_insert_query = "INSERT INTO `inquiries` (`name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`, `updated_at`) 
                             VALUES ('$name', '$email', '$phone', '$subject', '$message', '$status', NOW(), NOW())";
    
    $db_status = mysqli_query($conn, $inquiry_insert_query);

    // 3. SMTP EMAIL NOTIFICATION: Fetch active dynamic receiver config from system records
    $notify_email_query = "SELECT `contact_email` FROM `contacts` LIMIT 1";
    $notify_email_res   = mysqli_query($conn, $notify_email_query);
    $notify_data        = ($notify_email_res && mysqli_num_rows($notify_email_res) > 0) ? mysqli_fetch_assoc($notify_email_res) : [];
    
    // Fallback email address layer if no backend configurations row exists
    $to_receiver_email  = !empty($notify_data['contact_email']) ? $notify_data['contact_email'] : 'bestocompressors@gmail.com';

    // Compiling visual clean HTML email interface block template
    $mail_subject = "New Sidebar Web Inquiry from: " . $name;
    
    $mail_message = "
    <html>
    <head>
        <title>New Sidebar Inquiry Notification</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333333; }
            .container { width: 100%; max-width: 550px; margin: 0 auto; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; font-size: 14px; }
            .header-bar { background-color: #2b6cb0; color: white; padding: 15px; text-align: center; border-radius: 6px 6px 0 0; font-size: 18px; font-weight: bold; }
            .data-table { width: 100%; margin-top: 15px; border-collapse: collapse; }
            .data-table td { padding: 12px 10px; border-bottom: 1px solid #edf2f7; }
            .data-table td.label-bold { font-weight: bold; width: 35%; color: #4a5568; }
            .footer-tag { margin-top: 25px; font-size: 11px; color: #a0aec0; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header-bar'>New Sidebar Inquiry Received</div>
            <table class='data-table'>
                <tr><td class='label-bold'>Full Name:</td><td>{$name}</td></tr>
                <tr><td class='label-bold'>Email Address:</td><td><a href='mailto:{$email}'>{$email}</a></td></tr>
                <tr><td class='label-bold'>Contact Number:</td><td><a href='tel:{$phone}'>{$phone}</a></td></tr>
                <tr><td class='label-bold'>State / City:</td><td>{$city}</td></tr>
                <tr><td class='label-bold'>Tracking Status:</td><td>Unread Lead (Logged in Admin)</td></tr>
            </table>
            <div class='footer-tag'>This inquiry token transaction was triggered live from your website widget block panel template.</div>
        </div>
    </body>
    </html>
    ";

    // Defining standard structural network protocol headers tracking nodes
    $mail_headers  = "MIME-Version: 1.0" . "\r\n";
    $mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $mail_headers .= "From: Bestok System Alert <no-reply@bestok.in>" . "\r\n";
    $mail_headers .= "Reply-To: {$email}" . "\r\n";

    // Dispatch execution tracking request
    $mail_status = @mail($to_receiver_email, $mail_subject, $mail_message, $mail_headers);

    // 4. CLIENT SIDE RESPONSE FLASH
    if ($db_status) {
        echo "<script>
                alert('Thank you! Your information has been successfully logged and sent to our representative.');
                window.location.href = window.location.href;
              </script>";
    } else {
        echo "<script>alert('Error: Failed to process inquiry parameters into the database framework.');</script>";
    }
}
?>

                                <form autocomplete="off" action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input name="name" type="text" placeholder="Your Full Name" required
                                                class="getquotinput">
                                        </div>
                                        <div class="col-md-12">
                                            <input name="email" type="email" placeholder="Email Id" required
                                                class="getquotinput">
                                        </div>
                                        <div class="col-md-12">
                                            <input name="phone" type="text" pattern="[6-9]{1}[0-9]{9}" maxlength="10"
                                                placeholder="Contact No" required class="getquotinput">
                                        </div>
                                        <div class="col-md-12">
                                            <input name="city" type="text" placeholder="State/City" required
                                                class="getquotinput">
                                        </div>
                                        <div class="col-md-12">
                                            <button name="submit" type="submit" value="submit"
                                                class="getquotbutton d-none d-sm-block"
                                                style="border:none; width: 100%;">Submit Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
            <!-- end contact area -->

        </div>


        <div class="mapouter">
            <div class="gmap_canvas">
                <?php
        // 1. FETCH THE MAIN MAP EMBED CODE MATRIX FROM DB
        $canvas_map_query = "SELECT `map` FROM `contacts` LIMIT 1";
        $canvas_map_res   = mysqli_query($conn, $canvas_map_query);
        $canvas_map_data  = ($canvas_map_res && mysqli_num_rows($canvas_map_res) > 0) ? mysqli_fetch_assoc($canvas_map_res) : [];

        if (!empty($canvas_map_data['map'])) {
            // 🎯 FIXED ENGINE: Strip out backslashes added during escaping mechanisms
            $cleaned_canvas_map = stripslashes($canvas_map_data['map']);
            
            if (strpos($cleaned_canvas_map, '<iframe') !== false) {
                
                // 🛠️ CANVAS LAYOUT ADAPTER: Force layout container heights to match 600px schema
                if (strpos($cleaned_canvas_map, 'height=') !== false) {
                    $cleaned_canvas_map = preg_replace('/height="[0-9]+"/i', 'height="600"', $cleaned_canvas_map);
                    $cleaned_canvas_map = preg_replace('/height:[ ]*[0-9]+px/i', 'height:600px', $cleaned_canvas_map);
                }
                
                // Force responsiveness width to perfectly align within .gmap_canvas
                if (strpos($cleaned_canvas_map, 'width=') !== false) {
                    $cleaned_canvas_map = preg_replace('/width="[0-9\%\b]+"/i', 'width="100%"', $cleaned_canvas_map);
                }

                // Render out pure validated safe HTML node
                echo htmlspecialchars_decode($cleaned_canvas_map);
            } else {
                echo '<div class="alert alert-warning small m-2">Invalid Iframe map matrix configuration data.</div>';
            }
        } else {
            // FALLBACK BLOCK: Standard default blueprint inside canvas wrapper
            ?>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3500.1227931205685!2d77.0904194!3d28.6859732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d02331869ebd7%3A0xcb7112f7f4aed565!2sMR%20Lab!5e0!3m2!1sen!2sin!4v1778217393510!5m2!1sen!2sin"
                    width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <?php
        }
        ?>
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