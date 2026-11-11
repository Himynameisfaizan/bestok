<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config/connect.php');

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

$pageTitle = "Shipping and Delivery Policy | Bestok Industries Pvt Ltd";
$meta_title = $pageTitle;
$meta_desc = "Learn about domestic and international shipping options for premium air compressors, air dryers, and spare parts by Bestok Industries.";
$meta_key = "shipping policy, delivery policy, Bestok Industries, air compressor shipping, heavy machinery logistics";

$contactQuery = mysqli_query($conn, "SELECT phone, email FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$d_phone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-9871126707';

include 'inc/header.php';
?>

<main class="main bg-light">
    <!-- Breadcrumb -->
    <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Shipping & Delivery</h2>
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
                        <h1 class="mb-4" style="color: #13345d; font-weight: 800;">Shipping and Delivery Policy</h1>
                        <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                        <h3 class="mt-5 mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Shipping
                            Policy</h3>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">1. Order
                            Processing & Dispatch</h4>
                        <p style="color: #475569; line-height: 1.8;">Standard retail orders of spare parts, filters, and
                            valves are processed and dispatched within <strong>3 to 5 business days</strong>. Heavy
                            industrial machinery, such as large Rotary Screw Air Compressors and Air Receivers, follow
                            custom schedules based on manufacturing batch readiness and rigorous testing.</p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">2. Domestic
                            Delivery (Within India)</h4>
                        <p style="color: #475569; line-height: 1.8;">Standard delivery across India is handled via
                            trusted surface transport and logistics partners. Transit time takes approximately <strong>5
                                to 7 business days</strong> depending on your factory's location and the volumetric
                            weight of the machinery.</p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">3. Handling of
                            Heavy Machinery</h4>
                        <p style="color: #475569; line-height: 1.8;">Customers are advised to ensure proper unloading
                            equipment (like forklifts or cranes) is available at the delivery site for large air
                            compressors, as standard transport companies only provide delivery up to the factory gate.
                        </p>

                        <hr class="my-5" style="border-color: #e2e8f0;">

                        <h3 class="mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Returns Policy
                        </h3>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">1. Non-Returnable
                            Goods</h4>
                        <p style="color: #475569; line-height: 1.8;">Due to the complex logistics and safe handling
                            requirements involved in moving heavy industrial air compressors, <strong>we do not accept
                                standard returns</strong> on equipment once shipped from our warehouse.</p>

                        <h4 class="mt-4" style="font-size: 1.1rem; color: #13345d; font-weight: 700;">2. Damaged or
                            Incorrect Shipments</h4>
                        <p style="color: #475569; line-height: 1.8;">Exceptions apply strictly if you receive the wrong
                            machine or severely transit-damaged items affecting their pneumatic utility. Claims must be
                            filed within <strong>48 hours of receipt</strong> along with an unboxing video and clear
                            photographs.</p>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sidebar -->
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
                            <div class="icon-wrap mb-3"><i class="fa-solid fa-truck-fast fs-1 text-danger"></i></div>
                            <h4 class="fw-bold mb-2 text-white">Logistics Inquiry?</h4>
                            <p class="small mb-4 text-light opacity-75">Get reliable transport schedules and shipping
                                costs for wholesale machinery.</p>
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