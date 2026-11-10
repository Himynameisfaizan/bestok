<?php
include('config/connect.php');

$pageTitle = "Terms & Conditions | SHB Technologies";
$meta_description = "Read the terms and conditions of SHB Technologies for purchasing Medical Gas Pipeline Systems (MGPS), Modular OTs, and Healthcare IT products.";
$meta_key = "terms and conditions, SHB Technologies, export terms, medical equipment, turnkey hospital projects";

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
                    <h1 class="mb-4" style="color: #0f172a; font-weight: 800;">Terms & Conditions</h1>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">1. Introduction</h4>
                    <p style="color: #334155; line-height: 1.8;">Welcome to <strong>SHB Technologies & Medical Systems</strong>. By browsing our website and purchasing our medical equipment, Medical Gas Pipeline Systems (MGPS), Modular Operation Theatres, and Healthcare IT products, you agree to abide by these Terms & Conditions.</p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">2. Equipment Specifications & Turnkey Installations</h4>
                    <p style="color: #334155; line-height: 1.8;">We deal in high-precision medical infrastructure. For turnkey projects, site readiness is the responsibility of the hospital/client unless agreed otherwise. Minor aesthetic variations may occur between manufacturing batches, which do not affect clinical performance.</p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">3. Pricing and Import Duties</h4>
                    <p style="color: #334155; line-height: 1.8;">Domestic prices include applicable taxes unless specified. For international shipments and exports, the buyer is solely responsible for all destination customs duties, medical import taxes, and local levies.</p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">4. Product Usage Disclaimer</h4>
                    <p style="color: #334155; line-height: 1.8;">Information on our website regarding equipment benefits and technical specifications is for general informational purposes. All installations should be verified and certified by authorized biomedical engineers and local healthcare authorities.</p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">5. Governing Law</h4>
                    <p style="color: #334155; line-height: 1.8;">Any disputes arising from purchases or website usage shall be governed by the laws of India and subject to local jurisdiction in Delhi.</p>

                    <h4 class="mt-4" style="font-size: 1.15rem; color: #0f172a; font-weight: 700;">6. Contact Information</h4>
                    <div class="p-4 mt-3 rounded-3" style="background-color: #f1f5f9; border-left: 4px solid #e30613;">
                        <p class="mb-2"><strong>SHB Technologies & Medical Systems</strong></p>
                        <p class="mb-2"><strong>Address:</strong> <?= htmlspecialchars($d_address); ?></p>
                        <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($d_email); ?></p>
                        <p class="mb-0"><strong>Phone:</strong> <?= htmlspecialchars($d_phone); ?></p>
                    </div>
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
                        <div class="icon-wrap mb-3"><i class="fa-solid fa-file-contract fs-1 text-danger opacity-75"></i></div>
                        <h4 class="fw-bold mb-2 text-white">Project Inquiries</h4>
                        <p class="small mb-4 opacity-75 text-light">Get in touch for hospital turnkey projects and export pricing.</p>
                        <a href="contact" class="btn w-100 fw-bold shadow-sm mb-3" style="background: #e30613; color: #fff;">Request Quote</a>
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