<?php
include('config/connect.php');

$pageTitle = "Refund and Cancellation Policy | SHB Technologies";
$meta_description = "Read the refund and cancellation policy of SHB Technologies for medical equipment, MGPS infrastructure, and turnkey hospital projects.";
$meta_key = "refund policy, cancellation policy, SHB Technologies, medical equipment, hospital infrastructure";

$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$d_phone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-8178037626';
$d_email = !empty($contactInfo['email']) ? $contactInfo['email'] : 'technoshb@gmail.com';
$d_address = !empty($contactInfo['address']) ? $contactInfo['address'] : 'B-96, 1st Floor G.D. Colony, Mayur Vihar Phase -3, Delhi - 110096';


if (file_exists('includes/header.php')) {
    include 'includes/header.php';
} else {
    include 'inc/header.php';
}
if (file_exists('includes/breadcrumb.php')) {
    include 'includes/breadcrumb.php';
}
?>

<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row g-5">

            <!-- LEFT COLUMN: Content -->
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border" style="border-color: #e2e8f0 !important;">
                    <h1 class="mb-4" style="color: #0f172a; font-weight: 800;">Refund and Cancellation Policy</h1>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <p>At <strong>SHB Technologies & Medical Systems</strong>, we ensure the highest quality of our medical infrastructure commodities, including premium Medical Gas Pipeline Systems (MGPS) and Modular OT equipment. Because we deal in critical healthcare infrastructure and turnkey projects, our refund and cancellation policies are structured to comply with strict medical and trade standards.</p>

                    <h3 class="mt-5 mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Cancellation Policy</h3>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">1. Order Cancellation Before Dispatch</h4>
                    <p style="color: #334155; line-height: 1.8;">You may cancel your standalone equipment order within <strong>24 hours</strong> of placing it, provided it has not yet been processed, packed, or dispatched from our facility. A full refund will be processed immediately upon a valid request sent to our official support email.</p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">2. Cancellation After Dispatch</h4>
                    <p style="color: #334155; line-height: 1.8;">Due to the heavy and customized nature of medical infrastructure (e.g., OT Tables, Laminar Air Flow systems), orders <strong>cannot be cancelled</strong> once they have been handed over to our logistics partners or shipped.</p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">3. Turnkey Projects & Wholesale Orders</h4>
                    <p style="color: #334155; line-height: 1.8;">For custom Modular OT setups, MGPS installations, or bulk export orders, cancellations are not permitted once site mobilization, sourcing, and processing have commenced. Advance payments for turnkey hospital projects remain strictly non-refundable.</p>

                    <hr class="my-5" style="border-color: #e2e8f0;">

                    <h3 class="mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Return and Refund Policy</h3>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">1. Eligibility for Returns & Refunds</h4>
                    <p style="color: #334155; line-height: 1.8;">We <strong>do not accept general returns</strong> (such as a change of mind) due to the hygiene, logistics, and critical nature of medical supplies. However, replacements or refunds are applicable if:</p>
                    <ul style="color: #334155; line-height: 1.8;">
                        <li>The product packaging was severely damaged or compromised during transit, affecting the material's medical utility.</li>
                        <li>An incorrect medical product or quantity was delivered.</li>
                    </ul>
                    <p style="color: #334155;"><em>Note: You must notify us within <strong>48 hours of delivery</strong> with unboxing video or photographic proof.</em></p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">2. Refund Timeline</h4>
                    <p style="color: #334155; line-height: 1.8;">Approved refunds are credited back to the original payment method within <strong>5 to 7 business days</strong>.</p>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar Widgets -->
            <div class="col-lg-4">
                <div class="sidebar-widgets position-sticky" style="top: 25px;">
                    <div class="widget-box bg-white p-4 rounded-4 shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #0f172a;">Search Equipment</h5>
                        <form action="products" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2 bg-light border-0" placeholder="Search..." required style="font-size: 0.95rem;">
                            <button type="submit" class="btn text-white px-3" style="background: #e30613;"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <div class="widget-box bg-white p-4 rounded-4 shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #0f172a;">Categories</h5>
                        <ul class="list-unstyled mb-0 category-list">
                            <?php
                            $catQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1");
                            if ($catQuery && mysqli_num_rows($catQuery) > 0) {
                                while ($catRow = mysqli_fetch_assoc($catQuery)) {
                            ?>
                                    <li class="mb-2 pb-2 border-bottom">
                                        <a href="category/<?= urlencode(trim($catRow['slug_url'])) ?>" class="text-decoration-none d-flex justify-content-between align-items-center text-secondary" style="font-size: 0.95rem; transition: 0.3s;">
                                            <span><i class="fa-solid fa-angle-right me-2 text-danger" style="font-size: 0.75rem;"></i> <?= htmlspecialchars($catRow['categories']) ?></span>
                                        </a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="widget-box rounded-4 shadow-sm text-center p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-top: 4px solid #e30613;">
                        <div class="icon-wrap mb-3"><i class="fa-solid fa-headset fs-1 text-danger opacity-75"></i></div>
                        <h4 class="fw-bold mb-2 text-white">Need Support?</h4>
                        <p class="small mb-4 opacity-75 text-light">Contact our engineering team for any order or installation assistance.</p>
                        <a href="contact" class="btn w-100 fw-bold shadow-sm mb-3" style="background: #e30613; color: #fff;">Contact Us</a>
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $d_phone); ?>" class="text-white text-decoration-none small fw-bold">
                            <i class="fa-solid fa-phone me-1 text-danger"></i> <?= htmlspecialchars($d_phone); ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
if (file_exists('includes/inquiry-form.php')) {
    include 'includes/inquiry-form.php';
}
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
} else {
    include 'inc/footer.php';
}
?>