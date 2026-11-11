<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// SEO Meta Variables
$pageTitle = "Privacy Policy | Bestok Industries Pvt Ltd";
$meta_title = $pageTitle;
$meta_desc = "Read the Privacy Policy of Bestok Industries regarding personal data protection for our Rotary Screw Air Compressors, Refrigerated Dryers, and industrial products.";
$meta_key = "privacy policy, Bestok Industries, air compressor, industrial equipment, data protection";

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
            <h2 class="breadcrumb-title">Privacy Policy</h2>
            <ul class="breadcrumb-menu">
                <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                <li class="active">Privacy Policy</li>
            </ul>
        </div>
    </div>

    <!-- Main Policy Section -->
    <section class="py-5 pb-5">
        <div class="container">
            <div class="row g-5">

                <!-- LEFT COLUMN: Policy Content -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm border"
                        style="border-color: #e2e8f0 !important;">
                        <h1 class="mb-3" style="color: #13345d; font-weight: 800;">Privacy Policy</h1>
                        <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                        <p>Welcome to <strong>Bestok Industries Pvt Ltd</strong>. We respect your privacy and are
                            committed to protecting your personal data. This Privacy Policy outlines how we collect,
                            use, process, and safeguard your information when you visit our website, purchase our
                            premium industrial equipment—including <strong>Rotary Screw Air Compressors, Reciprocating
                                Compressors, Air Dryers, and Spare Parts</strong>—or use our installation and
                            maintenance services.</p>

                        <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">1. Information We
                            Collect</h4>
                        <p>To provide you with a seamless and secure experience for your industrial infrastructure
                            requirements, we collect the following types of information:</p>
                        <ul style="color: #475569; line-height: 1.8;">
                            <li><strong>Business & Personal Identification:</strong> Name, company name, email address,
                                phone number, and factory/plant details.</li>
                            <li><strong>Shipping & Billing Information:</strong> Delivery address, billing address,
                                postal code, and GST numbers for wholesale and domestic billing.</li>
                            <li><strong>Technical Data:</strong> IP address, browser type, time zone setting, operating
                                system, and platform details when browsing our catalog.</li>
                            <li><strong>Turnkey & Bulk Order Data:</strong> For large-scale compressor setups and
                                wholesale B2B orders, we may collect business credentials required by trade regulations.
                            </li>
                        </ul>

                        <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">2. How We Use
                            Your Information</h4>
                        <p>We use the information we collect for the following purposes:</p>
                        <ul style="color: #475569; line-height: 1.8;">
                            <li>To process, pack, and fulfill your orders for air compressors and pneumatic components.
                            </li>
                            <li>To communicate with you regarding site inspections, installation schedules, shipping
                                updates, and service engineer support.</li>
                            <li>To process payments securely and prevent fraudulent activities.</li>
                            <li>To improve our website functionality, product display, and overall B2B purchasing
                                experience.</li>
                        </ul>

                        <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">3. Secure Payment
                            Processing</h4>
                        <p>We do not store your credit card, debit card, UPI, or net banking details on our servers. All
                            financial transactions are handled through secure, PCI-DSS compliant third-party payment
                            gateways.</p>

                        <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">4. Data Security
                            & Third-Party Sharing</h4>
                        <p>We implement robust administrative and technical security measures to protect your data. We
                            do not sell or trade your personal information. Trusted third parties, such as industrial
                            logistics & freight partners, only receive the data necessary to deliver your heavy
                            machinery safely.</p>

                        <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">5. Contact Us
                        </h4>
                        <p>If you have any questions or concerns regarding this Privacy Policy, please feel free to
                            contact us:</p>
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

                        <!-- Categories Widget -->
                        <div class="sidebar-widget bg-white p-4 rounded-3 shadow-sm border mb-4">
                            <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #13345d;">Industrial Categories
                            </h5>
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

                        <!-- Request Quote Banner -->
                        <div class="sidebar-widget rounded-3 shadow-sm text-center p-4 text-white"
                            style="background: #13345d; border-top: 4px solid #e30613;">
                            <div class="icon-wrap mb-3"><i class="fa-solid fa-industry fs-1 text-danger"></i></div>
                            <h4 class="fw-bold mb-2 text-white">Need Compressors?</h4>
                            <p class="small mb-4 text-light opacity-75">Get custom quotes for Air Compressors, Dryers,
                                and Industrial Air Systems.</p>
                            <a href="<?= $site ?>contact-us.php" class="btn fw-bold shadow-sm mb-3 w-100"
                                style="background: #e30613; color: #fff;">Request Consultation</a>
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