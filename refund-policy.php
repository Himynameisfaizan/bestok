<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

$pageTitle = "Refund and Cancellation Policy | Bestok Industries Pvt Ltd";
$meta_title = $pageTitle;
$meta_desc = "Read the refund and cancellation policy of Bestok Industries for industrial air compressors, dryers, and spare parts.";
$meta_key = "refund policy, cancellation policy, Bestok Industries, air compressor, industrial machinery";

$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$d_phone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-9871126707';
$d_email = !empty($contactInfo['email']) ? $contactInfo['email'] : 'bestocompressors@gmail.com';

include 'inc/header.php';
?>

<main class="main bg-light">
    <!-- Breadcrumb -->
    <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Refund & Cancellation</h2>
            <ul class="breadcrumb-menu">
                <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                <li class="active">Policy</li>
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
                        <h1 class="mb-4" style="color: #13345d; font-weight: 800;">Refund and Cancellation Policy</h1>
                        <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                        <p>At <strong>Bestok Industries Pvt Ltd</strong>, we ensure the highest quality of our
                            compressed air solutions, including premium Rotary Screw Air Compressors, Reciprocating
                            Compressors, and Air Dryers. Because we deal in heavy industrial machinery and customized
                            setups, our refund and cancellation policies are structured to comply with B2B trade and
                            manufacturing standards.</p>

                        <h3 class="mt-5 mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Cancellation
                            Policy</h3>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">1. Order
                            Cancellation Before Dispatch</h4>
                        <p style="color: #475569; line-height: 1.8;">You may cancel your standalone spare parts or small
                            equipment order within <strong>24 hours</strong> of placing it, provided it has not yet been
                            processed, packed, or dispatched from our manufacturing facility. A full refund will be
                            processed upon a valid request sent to our official support email.</p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">2. Cancellation
                            After Dispatch</h4>
                        <p style="color: #475569; line-height: 1.8;">Due to the heavy and customized nature of
                            industrial infrastructure (e.g., Variable Speed Air Compressors, Large Air Receivers),
                            orders <strong>cannot be cancelled</strong> once they have been handed over to our logistics
                            partners or shipped.</p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">3. Turnkey
                            Projects & Wholesale Orders</h4>
                        <p style="color: #475569; line-height: 1.8;">For custom compressed air setups or bulk B2B
                            orders, cancellations are not permitted once assembly, sourcing, and processing have
                            commenced. Advance payments for large-scale industrial setups remain strictly
                            non-refundable.</p>

                        <hr class="my-5" style="border-color: #e2e8f0;">

                        <h3 class="mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Return and Refund
                            Policy</h3>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">1. Eligibility for
                            Returns & Refunds</h4>
                        <p style="color: #475569; line-height: 1.8;">We <strong>do not accept general returns</strong>
                            (such as a change of mind) due to the heavy logistics and critical operational nature of
                            industrial supplies. However, replacements or refunds are applicable if:</p>
                        <ul style="color: #475569; line-height: 1.8;">
                            <li>The product packaging was severely damaged during transit, affecting the machinery's
                                operational utility.</li>
                            <li>An incorrect machine, spare part, or quantity was delivered.</li>
                        </ul>
                        <p style="color: #475569;"><em>Note: You must notify us within <strong>48 hours of
                                    delivery</strong> with an unboxing video or photographic proof.</em></p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">2. Refund Timeline
                        </h4>
                        <p style="color: #475569; line-height: 1.8;">Approved refunds are credited back to the original
                            payment method within <strong>5 to 7 business days</strong>.</p>
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
                            <div class="icon-wrap mb-3"><i class="fa-solid fa-headset fs-1 text-danger"></i></div>
                            <h4 class="fw-bold mb-2 text-white">Need Support?</h4>
                            <p class="small mb-4 text-light opacity-75">Contact our engineering team for any order or
                                installation assistance.</p>
                            <a href="<?= $site ?>contact-us.php" class="btn fw-bold shadow-sm mb-3 w-100"
                                style="background: #e30613; color: #fff;">Contact Us</a>
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