<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

$pageTitle = "Terms & Conditions | Bestok Industries Pvt Ltd";
$meta_title = $pageTitle;
$meta_desc = "Read the terms and conditions of Bestok Industries for purchasing Air Compressors, Dryers, and Industrial Air Systems.";
$meta_key = "terms and conditions, Bestok Industries, air compressors, industrial equipment, manufacturing terms";

$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$d_phone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-9871126707';
$d_email = !empty($contactInfo['email']) ? $contactInfo['email'] : 'bestocompressors@gmail.com';
$d_address = !empty($contactInfo['address']) ? $contactInfo['address'] : '103-104, Durga Enclave G.T Road Rajendra Nagar Sahibabad Ghaziabad 201005';

include 'inc/header.php';
?>

<main class="main bg-light">
    <!-- Breadcrumb -->
    <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Terms & Conditions</h2>
            <ul class="breadcrumb-menu">
                <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                <li class="active">Terms</li>
            </ul>
        </div>
    </div>

    <section class="py-5 pb-5">
        <div class="container">
            <div class="row g-5">

                <!-- LEFT COLUMN: Content -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm border"
                        style="border-color: #e2e8f0 !important;">
                        <h1 class="mb-4" style="color: #13345d; font-weight: 800;">Terms & Conditions</h1>
                        <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">1. Introduction
                        </h4>
                        <p style="color: #475569; line-height: 1.8;">Welcome to <strong>Bestok Industries Pvt
                                Ltd</strong>. By browsing our website and purchasing our industrial machinery, including
                            Rotary Screw Air Compressors, Reciprocating Compressors, Air Dryers, and Spare Parts, you
                            agree to abide by these Terms & Conditions.</p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">2. Equipment
                            Specifications & Installations</h4>
                        <p style="color: #475569; line-height: 1.8;">We manufacture and deal in high-performance
                            compressed air infrastructure. For heavy installations, site readiness (including electrical
                            connections, flat structural bases, and initial pneumatic piping) is the responsibility of
                            the client. Minor aesthetic variations may occur between manufacturing batches, which do not
                            affect operational performance.</p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">3. Pricing and
                            Duties</h4>
                        <p style="color: #475569; line-height: 1.8;">Prices displayed or quoted are typically ex-factory
                            unless explicitly mentioned. Domestic prices exclude GST and freight charges, which are
                            calculated at checkout or upon final invoicing. For wholesale or export purchases, the buyer
                            is solely responsible for destination customs duties and local taxes.</p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">4. Product Usage
                            Disclaimer</h4>
                        <p style="color: #475569; line-height: 1.8;">Information on our website regarding equipment
                            benefits (like CFM, Pressure bounds, and Energy efficiency) is provided based on standard
                            testing conditions. Compressors must be operated within specified industrial parameters and
                            maintained by certified service engineers.</p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">5. Governing Law
                        </h4>
                        <p style="color: #475569; line-height: 1.8;">Any disputes arising from purchases or website
                            usage shall be governed by the laws of India and subject to local jurisdiction in
                            Ghaziabad/Delhi NCR.</p>

                        <h4 class="mt-4" style="font-size: 1.15rem; color: #13345d; font-weight: 700;">6. Contact
                            Information</h4>
                        <div class="p-4 mt-3 rounded-3"
                            style="background-color: #f8fafc; border-left: 4px solid #13345d;">
                            <p class="mb-2"><strong>Bestok Industries Pvt Ltd</strong></p>
                            <p class="mb-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i>
                                <strong>Address:</strong> <?= htmlspecialchars($d_address); ?></p>
                            <p class="mb-2"><i class="fa fa-envelope me-2 text-danger"></i> <strong>Email:</strong>
                                <?= htmlspecialchars($d_email); ?></p>
                            <p class="mb-0"><i class="fa fa-phone me-2 text-danger"></i> <strong>Phone:</strong>
                                <?= htmlspecialchars($d_phone); ?></p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sidebar Widgets -->
                <div class="col-lg-4">
                    <div class="sidebar position-sticky" style="top: 25px;">

                        <div class="sidebar-widget bg-white p-4 rounded-3 shadow-sm border mb-4">
                            <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #13345d;">Categories</h5>
                            <ul class="list-unstyled mb-0" style="padding:0;">
                                <?php
                                $catQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 LIMIT 6");
                                if ($catQuery && mysqli_num_rows($catQuery) > 0) {
                                    while ($catRow = mysqli_fetch_assoc($catQuery)) {
                                ?>
                                <li class="mb-2 pb-2 border-bottom" style="border-style: dashed !important;">
                                    <a href="<?= $site ?>category/<?= urlencode(trim($catRow['slug_url'])) ?>"
                                        class="text-decoration-none d-flex justify-content-between align-items-center text-secondary fw-semibold"
                                        style="font-size: 0.95rem; transition: 0.3s;">
                                        <span><i class="fa-solid fa-angle-right me-2 text-danger"
                                                style="font-size: 0.75rem;"></i>
                                            <?= htmlspecialchars($catRow['categories']) ?></span>
                                    </a>
                                </li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="sidebar-widget rounded-3 shadow-sm text-center p-4 text-white"
                            style="background: #13345d; border-top: 4px solid #e30613;">
                            <div class="icon-wrap mb-3"><i class="fa-solid fa-file-contract fs-1 text-danger"></i></div>
                            <h4 class="fw-bold mb-2 text-white">Project Inquiries</h4>
                            <p class="small mb-4 text-light opacity-75">Get in touch for large-scale industrial
                                compressor projects and B2B pricing.</p>
                            <a href="<?= $site ?>contact-us.php" class="btn fw-bold shadow-sm mb-3 w-100"
                                style="background: #e30613; color: #fff;">Request Quote</a>
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $d_phone); ?>"
                                class="text-white text-decoration-none small fw-bold">
                                <i class="fa-solid fa-phone me-1 text-danger"></i> <?= htmlspecialchars($d_phone); ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<?php include 'inc/footer.php'; ?>