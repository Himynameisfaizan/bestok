<?php
include('config/connect.php');

// SEO Meta Variables
$pageTitle = "Privacy Policy | SHB Technologies & Medical Systems";
$meta_description = "Read the Privacy Policy of SHB Technologies regarding personal data protection for our Medical Gas Pipeline Systems (MGPS), Modular OTs, and Healthcare IT products.";
$meta_key = "privacy policy, SHB Technologies, medical equipment, MGPS, hospital infrastructure, modular OT";

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

<!-- Main Policy Section -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row g-5">

            <!-- LEFT COLUMN: Policy Content -->
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border" style="border-color: #e2e8f0 !important;">
                    <h1 class="mb-3" style="color: #0f172a; font-weight: 800;">Privacy Policy</h1>
                    <p class="text-muted mb-5"><strong>Last Updated:</strong> <?= date('F d, Y'); ?></p>

                    <p>Welcome to <strong>SHB Technologies & Medical Systems</strong>. We respect your privacy and are committed to protecting your personal data. This Privacy Policy outlines how we collect, use, process, and safeguard your information when you visit our website, purchase our premium hospital equipment—including <strong>Medical Gas Pipeline Systems (MGPS), Modular Operation Theatres, and Healthcare IT products</strong>—or use our turnkey installation services.</p>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">1. Information We Collect</h4>
                    <p>To provide you with a seamless and secure experience for your hospital infrastructure requirements, we collect the following types of information:</p>
                    <ul style="color: #334155; line-height: 1.8;">
                        <li><strong>Personal & Hospital Identification:</strong> Name, email address, phone number, and hospital/clinic details (if applicable).</li>
                        <li><strong>Shipping & Billing Information:</strong> Delivery address, billing address, postal code, and destination country for domestic or international freight of medical equipment.</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, time zone setting, operating system, and platform details when browsing our medical catalog.</li>
                        <li><strong>Turnkey & Bulk Order Data:</strong> For turnkey OT projects and wholesale orders, we may collect business credentials, GST/VAT numbers, or import-export licenses as required by trade regulations.</li>
                    </ul>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">2. How We Use Your Information</h4>
                    <p>We use the information we collect for the following purposes:</p>
                    <ul style="color: #334155; line-height: 1.8;">
                        <li>To process, pack, and fulfill your orders for medical equipment and MGPS components.</li>
                        <li>To communicate with you regarding site inspections, installation schedules, shipping updates, and 24/7 biomedical engineering support.</li>
                        <li>To process payments securely and prevent fraudulent activities.</li>
                        <li>To comply with healthcare infrastructure standards, export regulations, and legal obligations.</li>
                        <li>To improve our website functionality, product display, and overall user experience.</li>
                    </ul>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">3. Secure Payment Processing</h4>
                    <p>We do not store your credit card, debit card, UPI, or net banking details on our servers. All financial transactions are handled through secure, PCI-DSS compliant third-party payment gateways. Your payment data is fully encrypted and protected under strict industry standards.</p>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">4. Third-Party Data Sharing</h4>
                    <p>We do not sell or trade your personal information. However, trusted third parties assist us in conducting our business operations, including:</p>
                    <ul style="color: #334155; line-height: 1.8;">
                        <li><strong>Logistics & Freight Partners:</strong> Courier companies and freight forwarders required to deliver heavy medical equipment safely.</li>
                        <li><strong>Payment Gateways:</strong> Authorized financial processors for transaction management.</li>
                        <li><strong>Regulatory Authorities:</strong> Government or customs bodies when legally required for medical export compliance.</li>
                    </ul>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">5. Data Security</h4>
                    <p>We implement robust administrative, technical, and physical security measures, including SSL encryption and secure firewalls, to protect your personal data against unauthorized access, disclosure, or misuse.</p>

                    <h4 class="mt-5" style="color: #e30613; font-size: 1.25rem; font-weight: 700;">6. Contact Us</h4>
                    <p>If you have any questions or concerns regarding this Privacy Policy, please feel free to contact us:</p>
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

                    <!-- Search Widget -->
                    <div class="widget-box bg-white p-4 rounded-4 shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #0f172a;">Search Equipment</h5>
                        <form action="products" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2 bg-light border-0" placeholder="Search MGPS, OT Lights..." required style="font-size: 0.95rem;">
                            <button type="submit" class="btn text-white px-3" style="background: #e30613;"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <!-- Categories Widget -->
                    <div class="widget-box bg-white p-4 rounded-4 shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-3" style="color: #0f172a;">Medical Categories</h5>
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

                    <!-- Request Quote Banner -->
                    <div class="widget-box rounded-4 shadow-sm text-center p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-top: 4px solid #e30613;">
                        <div class="icon-wrap mb-3"><i class="fa-solid fa-user-doctor fs-1 text-danger opacity-75"></i></div>
                        <h4 class="fw-bold mb-2 text-white">Planning a Hospital Project?</h4>
                        <p class="small mb-4 opacity-75 text-light">Get custom BOQs and pricing for Modular OTs and Medical Gas Pipeline Systems.</p>
                        <a href="contact" class="btn w-100 fw-bold shadow-sm mb-3" style="background: #e30613; color: #fff;">Request Consultation</a>
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