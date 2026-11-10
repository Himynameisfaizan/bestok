<?php
include('config/connect.php');

$pageTitle = "Shipping and Delivery Policy | SHB Technologies";
$meta_description = "Learn about domestic and international shipping options for premium medical equipment, MGPS, and infrastructure products by SHB Technologies.";
$meta_key = "shipping policy, delivery policy, SHB Technologies, medical equipment export, hospital infrastructure";

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
                    <h1 class="mb-4" style="color: #0f172a; font-weight: 800;">Shipping and Delivery Policy</h1>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <h3 class="mt-5 mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Shipping Policy</h3>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">1. Order Processing & Dispatch</h4>
                    <p style="color: #334155; line-height: 1.8;">Standard retail orders of medical equipment (e.g., Gas Outlets, Flow Meters) are processed and dispatched within <strong>3 to 5 business days</strong>. Turnkey projects, modular OT setups, and international container exports follow custom schedules based on manufacturing, documentation, and site readiness.</p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">2. Domestic Delivery (Within India)</h4>
                    <p style="color: #334155; line-height: 1.8;">Standard delivery across India takes approximately <strong>5 to 7 business days</strong> depending on the hospital's delivery location and the volume/weight of the shipment.</p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">3. International Shipping & Exports</h4>
                    <p style="color: #334155; line-height: 1.8;">International shipments are handled via sea or air freight through specialized medical logistics channels. <strong>Note:</strong> The buyer is fully responsible for destination country customs duties, medical import clearance, and local taxes. SHB Technologies provides all mandatory Indian export compliance and technical documentation.</p>

                    <hr class="my-5" style="border-color: #e2e8f0;">

                    <h3 class="mb-3" style="color: #e30613; font-size: 1.35rem; font-weight: 700;">Returns Policy</h3>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">1. Non-Returnable Goods</h4>
                    <p style="color: #334155; line-height: 1.8;">Due to stringent healthcare standards, hygiene protocols, and the logistics involved in moving heavy medical infrastructure, <strong>we do not accept standard returns</strong> on equipment once shipped.</p>

                    <h4 class="mt-4" style="font-size: 1.1rem; color: #0f172a; font-weight: 700;">2. Damaged or Incorrect Shipments</h4>
                    <p style="color: #334155; line-height: 1.8;">Exceptions apply strictly if you receive wrong or severely transit-damaged items affecting their clinical utility. Claims must be filed within <strong>48 hours of receipt</strong> along with an unboxing video/photos.</p>
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
                        <div class="icon-wrap mb-3"><i class="fa-solid fa-globe fs-1 text-danger opacity-75"></i></div>
                        <h4 class="fw-bold mb-2 text-white">Bulk Export Inquiry?</h4>
                        <p class="small mb-4 opacity-75 text-light">Get reliable global shipping for wholesale medical equipment.</p>
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