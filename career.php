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
        <div class="site-breadcrumb dfsdsd" style="background: url(assets/img/career.webp)">

        </div>
        <!-- breadcrumb end -->


        <section class="contact-details-area">
            <div class="sec-title text-center wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1200ms">
                <div class="title clr-white">
                    <h2 style="color:#FFFFFF;"> How Can We <span>Help You?</span> </h2>
                </div>
            </div>
            <div class="parallax-background" style="background-image:url(assets/img/contact-details-bg.jpg);"
                title="Get-quote-to-buy-best-modular-kitchen"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="contact-details-content">
                            <div class="row">
                                <div class="col-xl-5 col-lg-5">
                                    <div class="content-info-box"
                                        style="background-image:url(assets/img/contact-info-box-bg.jpg);"
                                        title="Get-quote-to-buy-best-modular-kitchen"> </div>
                                </div>
                              <?php
// 1. INITIATE SERVER PROCESSING ON POST REQUEST SUBMISSION
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    
    // Clean and sanitize incoming request payloads to protect against structural exploits
    $form_name    = mysqli_real_escape_string($conn, trim($_POST['form_name']));
    $form_email   = mysqli_real_escape_string($conn, trim($_POST['form_email']));
    $form_mobile  = mysqli_real_escape_string($conn, trim($_POST['form_mobile']));
    $form_pincode = mysqli_real_escape_string($conn, trim($_POST['form_pincode']));
    $form_budget  = mysqli_real_escape_string($conn, trim($_POST['form_budget']));
    $form_request = mysqli_real_escape_string($conn, trim($_POST['form_request']));

    // 2. TRANSACTION LOGGING: Commit data entry record inside 'career' data table layout
    // Adjust column naming matrix mapping syntax if your table column variables differ slightly
    $career_insert_query = "INSERT INTO `career` (`name`, `email`, `mobile`, `pincode`, `budget`, `details`, `created_at`) 
                            VALUES ('$form_name', '$form_email', '$form_mobile', '$form_pincode', '$form_budget', '$form_request', NOW())";
    
    $db_status = mysqli_query($conn, $career_insert_query);

    // 3. MAIL DISPATCH SYSTEM: Fetch dynamic target notification receiver email config row
    $notify_email_query = "SELECT `contact_email` FROM `contacts` LIMIT 1";
    $notify_email_res   = mysqli_query($conn, $notify_email_query);
    $notify_data        = ($notify_email_res && mysqli_num_rows($notify_email_res) > 0) ? mysqli_fetch_assoc($notify_email_res) : [];
    
    // Fallback receiver email address boundary if configuration row yields blank results
    $to_receiver_email  = !empty($notify_data['contact_email']) ? $notify_data['contact_email'] : 'bestocompressors@gmail.com';

    // Formulating professional analytical layout mail boundary parameters
    $mail_subject = "New Free Quote Request from: " . $form_name;
    
    $mail_message = "
    <html>
    <head>
        <title>New Quote Query Notification</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333333; }
            .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #eeeeee; padding: 20px; border-radius: 8px; }
            .header { background-color: #4e73df; color: white; padding: 12px; text-align: center; border-radius: 6px 6px 0 0; }
            .table-data { width: 100%; margin-top: 15px; border-collapse: collapse; }
            .table-data td { padding: 10px; border-bottom: 1px solid #f4f4f4; }
            .table-data td.title-bold { font-weight: bold; width: 30%; color: #555555; }
            .footer { margin-top: 20px; font-size: 11px; color: #999999; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'><h2>New Quote Inquiry Received</h2></div>
            <table class='table-data'>
                <tr><td class='title-bold'>Client Name:</td><td>{$form_name}</td></tr>
                <tr><td class='title-bold'>Email Address:</td><td><a href='mailto:{$form_email}'>{$form_email}</a></td></tr>
                <tr><td class='title-bold'>Mobile/Phone:</td><td><a href='tel:{$form_mobile}'>{$form_mobile}</a></td></tr>
                <tr><td class='title-bold'>Area Pin Code:</td><td>{$form_pincode}</td></tr>
                <tr><td class='title-bold'>Estimated Budget:</td><td>{$form_budget}</td></tr>
                <tr><td class='title-bold'>Requirement Details:</td><td>{$form_request}</td></tr>
            </table>
            <div class='footer'>This query was securely logged and processed live from your website frontend workspace template dashboard form.</div>
        </div>
    </body>
    </html>
    ";

    // Standard structural mail server protocol encoding headers configuration context
    $mail_headers  = "MIME-Version: 1.0" . "\r\n";
    $mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $mail_headers .= "From: Bestok Notifications <no-reply@bestok.in>" . "\r\n"; // Replace with your domain active webmail sender handle
    $mail_headers .= "Reply-To: {$form_email}" . "\r\n";

    // Fire processing execution pipeline triggers
    $mail_status = @mail($to_receiver_email, $mail_subject, $mail_message, $mail_headers);

    // 4. FEEDBACK ALERT MODAL PROJECTIONS FOR THE USER INTERFACE
    if ($db_status) {
        echo "<script>
                alert('Thank you! Your quote request has been submitted and sent to our team successfully.');
                window.location.href = window.location.href.split('#')[0] + '#get';
              </script>";
    } else {
        echo "<script>alert('Database Logging Exception Encountered. Please try submitting your specifications again.');</script>";
    }
}
?>

<div class="col-xl-7 col-lg-7"><a href="#" name="get" id="get"></a>
    <div class="contact-details-form-box">
        <div class="title-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1200ms">
            <h2>Get A Free Quote</h2>
        </div>
        <div class="contact-details-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1200ms">
            <form class="contact-details-form" action="" onSubmit="return IsRecapchaValid()" method="post">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="single-box">
                            <input type="text" name="form_name" placeholder="Name" required="">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="single-box">
                            <input type="email" name="form_email" placeholder="Email" required="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Invalid email address">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="single-box">
                            <input type="text" name="form_mobile" placeholder="Mobile" required="" pattern="[6-9]{1}[0-9]{9}" title="Phone number with 6-9 and remaing 9 digit with 0-9" class="phoneCls onlyNumber" maxlength="10">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="single-box">
                            <input type="text" name="form_pincode" placeholder="Pin Code" required="" pattern="[1-9]{1}[0-9]{5}" title="Pin Code with 1-9 and remaing 5 digit with 0-9" class="phoneCls onlyNumber" maxlength="6" minlength="6">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="single-box">
                            <input type="text" name="form_budget" placeholder="Budget" required="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="single-box">
                            <textarea name="form_request" placeholder="Details" required=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="single-box">
                            <button class="btn-one" type="submit" name="submit" value="submit">Send Request
                                <span class="flaticon-next"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




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