<?php
include('config/connect.php');

// Support both ?slug_url= and ?slug= parameters seamlessly
$rawSlug = $_GET['slug_url'] ?? $_GET['slug'] ?? '';
$slug = mysqli_real_escape_string($conn, trim((string)$rawSlug));

$pageData = null;
if ($slug !== '') {
    $pageQuery = mysqli_query($conn, "SELECT * FROM custom_pages WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
    if ($pageQuery) {
        $pageData = mysqli_fetch_assoc($pageQuery);
    }
}

if (!$pageData) {
    header("HTTP/1.0 404 Not Found");
    echo "<script>window.location.href='404.php';</script>";
    exit;
}

// Clean & Fallback SEO Variables (Supports both $meta_desc and $meta_description conventions)
$cleanContentText = trim(preg_replace('/\s+/', ' ', strip_tags((string)$pageData['content'])));
$defaultMetaDesc = mb_substr($cleanContentText, 0, 160);

$pageTitle        = !empty($pageData['meta_title']) ? $pageData['meta_title'] : ($pageData['page_title'] . ' | SHB Technologies & Medical Systems');
$meta_title       = $pageTitle;
$meta_desc        = !empty($pageData['meta_desc']) ? $pageData['meta_desc'] : $defaultMetaDesc;
$meta_description = $meta_desc;
$meta_key         = !empty($pageData['meta_key']) ? $pageData['meta_key'] : 'SHB Technologies, Medical Gas Pipeline System, Modular Operation Theatre, Healthcare IT';
$meta_keywords    = $meta_key;

// Breadcrumb Variables
$breadcrumb_name  = !empty($pageData['breadcrumb_title']) ? $pageData['breadcrumb_title'] : $pageData['page_title'];
$breadcrumb_title = $breadcrumb_name;

// Smart JSON-LD Schema Logic (Validates JSON if no <script> tag is present)
$raw_schema = trim((string)($pageData['schema_markup'] ?? ''));
$page_schema = '';
if ($raw_schema !== '') {
    if (stripos($raw_schema, '<script') !== false) {
        $page_schema = $raw_schema;
    } else {
        json_decode($raw_schema);
        if (json_last_error() === JSON_ERROR_NONE) {
            $page_schema = "<script type=\"application/ld+json\">\n" . $raw_schema . "\n</script>";
        }
    }
}

// Default MedicalBusiness Schema if custom schema is empty
if ($page_schema === '') {
    $autoSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebPage",
        "name" => $pageData['page_title'],
        "description" => $meta_desc,
        "publisher" => [
            "@type" => "MedicalBusiness",
            "name" => "SHB Technologies and Medical Systems",
            "telephone" => "+91-8178037626",
            "email" => "technoshb@gmail.com",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "B-96, 1st Floor G.D. Colony, Mayur Vihar Phase -3",
                "addressLocality" => "Delhi",
                "postalCode" => "110096",
                "addressCountry" => "IN"
            ]
        ]
    ];
    $page_schema = '<script type="application/ld+json">' . json_encode($autoSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

// Fetch Contact Info for CTA
$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$sitePhone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-8178037626';
$siteEmail = !empty($contactInfo['email']) ? $contactInfo['email'] : 'technoshb@gmail.com';

if (file_exists('includes/header.php')) {
    include 'includes/header.php';
} elseif (file_exists('inc/header.php')) {
    include 'inc/header.php';
}

// Output Schema Markup safely if header didn't echo it
echo $page_schema;
?>

<style>
:root {
    --shb-red: #e30613;
    --shb-red-dark: #b8000b;
    --shb-dark: #0f172a;
    --shb-slate: #334155;
    --shb-light: #f8fafc;
    --shb-border: #e2e8f0;
}

/* Custom Page Hero Banner */
.shb-page-banner {
    background: radial-gradient(circle at 85% 20%, rgba(227, 6, 19, 0.2), transparent 45%),
                linear-gradient(135deg, #0b0f19 0%, #161e2e 100%);
    padding: 65px 0 55px;
    color: #ffffff;
    border-bottom: 4px solid var(--shb-red);
}

.shb-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #cbd5e1;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

.shb-breadcrumb a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s;
}

.shb-breadcrumb a:hover {
    color: var(--shb-red);
}

.shb-breadcrumb .active-crumb {
    color: #ff6b72;
    font-weight: 600;
}

.shb-page-banner h1 {
    font-size: clamp(1.85rem, 3.5vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 0;
}

/* Main Content Styling */
.shb-content-section {
    background-color: var(--shb-light);
    padding: 60px 0 80px;
}

.shb-article-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--shb-border);
    padding: 40px;
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
}

@media (max-width: 767px) {
    .shb-article-card {
        padding: 24px 20px;
    }
}

.shb-trust-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    background: #0f172a;
    color: #ffffff;
    padding: 20px 24px;
    border-radius: 12px;
    margin-bottom: 32px;
}

.shb-trust-item strong {
    display: block;
    font-size: 1.35rem;
    font-weight: 800;
    color: #ffffff;
}

.shb-trust-item strong span {
    color: var(--shb-red);
}

.shb-trust-item small {
    color: #94a3b8;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.page-content-body {
    line-height: 1.85;
    color: var(--shb-slate);
    font-size: 1.03rem;
}

.page-content-body h1,
.page-content-body h2,
.page-content-body h3,
.page-content-body h4 {
    color: var(--shb-dark);
    margin-top: 28px;
    margin-bottom: 14px;
    font-weight: 700;
}

.page-content-body h2 {
    font-size: 1.55rem;
    border-left: 4px solid var(--shb-red);
    padding-left: 14px;
}

.page-content-body h3 {
    font-size: 1.25rem;
}

.page-content-body ul {
    padding-left: 0;
    list-style: none;
    margin: 20px 0;
}

.page-content-body ul li {
    position: relative;
    padding-left: 28px;
    margin-bottom: 12px;
}

.page-content-body ul li::before {
    content: '\f058';
    font-family: 'Font Awesome 6 Free', 'FontAwesome';
    font-weight: 900;
    color: var(--shb-red);
    position: absolute;
    left: 0;
    top: 2px;
}

.page-content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 18px 0;
}

/* Sidebar Widgets */
.shb-widget {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--shb-border);
    padding: 26px;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
}

.shb-widget-title {
    font-size: 1.08rem;
    font-weight: 700;
    color: var(--shb-dark);
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
    position: relative;
}

.shb-widget-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 45px;
    height: 2px;
    background: var(--shb-red);
}

.shb-side-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.shb-side-list li {
    border-bottom: 1px solid #f1f5f9;
}

.shb-side-list li:last-child {
    border-bottom: none;
}

.shb-side-list a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 6px;
    color: var(--shb-slate);
    text-decoration: none;
    font-size: 0.93rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.shb-side-list a:hover,
.shb-side-list a.active-link {
    color: var(--shb-red);
    padding-left: 10px;
    font-weight: 600;
}

/* Recent Post Item */
.shb-recent-item {
    display: flex;
    gap: 14px;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}

.shb-recent-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.shb-recent-item img {
    width: 68px;
    height: 68px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}

.shb-recent-info h6 {
    margin: 0 0 5px;
    font-size: 0.92rem;
    line-height: 1.4;
    font-weight: 700;
}

.shb-recent-info h6 a {
    color: var(--shb-dark);
    text-decoration: none;
    transition: color 0.2s;
}

.shb-recent-info h6 a:hover {
    color: var(--shb-red);
}

.shb-recent-info span {
    font-size: 0.78rem;
    color: #94a3b8;
}

/* Sidebar Contact Consultation Box */
.shb-consult-widget {
    background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
    border-radius: 14px;
    padding: 30px 26px;
    color: #ffffff;
    text-align: center;
    border-top: 4px solid var(--shb-red);
}

.shb-consult-icon {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: rgba(227, 6, 19, 0.2);
    color: #ff6b72;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 16px;
}

.shb-consult-btn {
    background: var(--shb-red);
    color: #ffffff;
    font-weight: 700;
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    display: inline-block;
    text-decoration: none;
    transition: background 0.25s;
}

.shb-consult-btn:hover {
    background: var(--shb-red-dark);
    color: #ffffff;
}
</style>

<!-- Custom Page Hero Banner -->
<section class="shb-page-banner">
    <div class="container">
        <nav class="shb-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fa-solid fa-house me-1"></i> Home</a>
            <span>/</span>
            <a href="location">Locations & Pages</a>
            <span>/</span>
            <span class="active-crumb"><?= htmlspecialchars($breadcrumb_name); ?></span>
        </nav>
        <h1><?= htmlspecialchars($pageData['page_title']); ?></h1>
    </div>
</section>

<!-- Main Page Content -->
<section class="shb-content-section">
    <div class="container">
        <div class="row g-4 g-lg-5">

            <!-- LEFT COLUMN: Main Content Area -->
            <div class="col-lg-8">
                <article class="shb-article-card">

                    <!-- SHB Key Highlights Strip -->
                    <div class="shb-trust-strip">
                        <div class="shb-trust-item">
                            <strong>120<span>+</span></strong>
                            <small>Hospitals Served</small>
                        </div>
                        <div class="shb-trust-item">
                            <strong>350<span>+</span></strong>
                            <small>Installations</small>
                        </div>
                        <div class="shb-trust-item">
                            <strong>24<span>/7</span></strong>
                            <small>Field Support</small>
                        </div>
                        <div class="shb-trust-item">
                            <strong>10<span>+</span></strong>
                            <small>Years Experience</small>
                        </div>
                    </div>

                    <!-- Dynamic HTML Content from Admin Panel -->
                    <div class="page-content-body">
                        <?= $pageData['content']; ?>
                    </div>

                    <!-- Direct Inquiry Callout Inside Article -->
                    <div class="mt-5 p-4 rounded-3 border" style="background: #f8fafc;">
                        <div class="row align-items-center g-3">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-1" style="color: #0f172a;">Looking for Turnkey Installation or Equipment Pricing?</h5>
                                <p class="text-muted small mb-0">Speak directly with our Biomedical & MGPS Engineering team at Mayur Vihar Phase-3, Delhi HQ.</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="contact" class="btn btn-danger fw-semibold px-4 py-2" style="background:#e30613;">Get Free Quote</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Share Links -->
                    <div class="social-share mt-4 pt-4 border-top d-flex align-items-center flex-wrap gap-2">
                        <span class="fw-bold me-2 text-dark">Share this page:</span>
                        <?php $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>"
                            target="_blank" class="btn btn-sm btn-outline-primary rounded-circle"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url) ?>" target="_blank"
                            class="btn btn-sm btn-outline-dark rounded-circle"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://wa.me/?text=<?= urlencode($pageData['page_title'] . ' - ' . $current_url) ?>" target="_blank"
                            class="btn btn-sm btn-outline-success rounded-circle"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($current_url) ?>"
                            target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </article>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 25px;">

                    <!-- Other Custom / Location Pages Widget -->
                    <div class="shb-widget">
                        <h5 class="shb-widget-title">Explore Other Pages</h5>
                        <ul class="shb-side-list">
                            <?php
                            $otherPagesQuery = mysqli_query($conn, "SELECT page_title, breadcrumb_title, slug_url FROM custom_pages WHERE status = 1 AND slug_url <> '' ORDER BY id DESC LIMIT 8");
                            if ($otherPagesQuery && mysqli_num_rows($otherPagesQuery) > 0):
                                while ($otherPage = mysqli_fetch_assoc($otherPagesQuery)):
                                    $isCurrent = ($otherPage['slug_url'] === $slug) ? 'active-link' : '';
                                    $label = !empty($otherPage['breadcrumb_title']) ? $otherPage['breadcrumb_title'] : $otherPage['page_title'];
                            ?>
                            <li>
                                <a href="page/<?= rawurlencode($otherPage['slug_url']); ?>" class="<?= $isCurrent; ?>">
                                    <span><i class="fa-solid fa-location-dot me-2 text-danger" style="font-size: 0.8rem;"></i><?= htmlspecialchars($label); ?></span>
                                    <i class="fa-solid fa-angle-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </li>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </ul>
                        <div class="mt-3 pt-2 border-top text-center">
                            <a href="location" class="text-danger fw-bold small text-decoration-none">View All Pages <i class="fa-solid fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>

                    <!-- Product Categories Widget -->
                    <div class="shb-widget">
                        <h5 class="shb-widget-title">Our Core Solutions</h5>
                        <ul class="shb-side-list">
                            <?php
                            $sideCatQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC");
                            if ($sideCatQuery):
                                while ($sideCat = mysqli_fetch_assoc($sideCatQuery)):
                            ?>
                            <li>
                                <a href="category/<?= htmlspecialchars($sideCat['slug_url']); ?>">
                                    <span><i class="fa-solid fa-angle-right me-2 text-danger" style="font-size: 0.75rem;"></i><?= htmlspecialchars($sideCat['categories']); ?></span>
                                </a>
                            </li>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </ul>
                    </div>

                    <!-- Recent Insights / Blogs Widget -->
                    <div class="shb-widget">
                        <h5 class="shb-widget-title">Latest Insights & News</h5>
                        <?php
                        $recentQuery = mysqli_query($conn, "SELECT * FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 3");
                        if ($recentQuery && mysqli_num_rows($recentQuery) > 0) {
                            while ($recentBlog = mysqli_fetch_assoc($recentQuery)):
                                $r_date = date('M d, Y', strtotime($recentBlog['created_at']));
                                $r_img = !empty($recentBlog['image']) ? 'admin/assets/img/uploads/blogs/' . $recentBlog['image'] : 'assets/img/logo.png';
                        ?>
                        <div class="shb-recent-item">
                            <img src="<?= htmlspecialchars($r_img); ?>" alt="<?= htmlspecialchars($recentBlog['title']); ?>" onerror="this.style.display='none'">
                            <div class="shb-recent-info">
                                <h6><a href="blog/<?= htmlspecialchars($recentBlog['slug']); ?>"><?= htmlspecialchars($recentBlog['title']); ?></a></h6>
                                <span><i class="fa-regular fa-calendar me-1"></i><?= $r_date; ?></span>
                            </div>
                        </div>
                        <?php
                            endwhile;
                        } else {
                            echo "<p class='text-muted small mb-0'>No recent articles available.</p>";
                        }
                        ?>
                    </div>

                    <!-- Request Quote Consultation Widget -->
                    <div class="shb-consult-widget">
                        <div class="shb-consult-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-white">Need Technical Consultation?</h4>
                        <p class="small text-light opacity-75 mb-4">Connect with our biomedical & MGPS specialists for hospital layout planning, BOQ, and wholesale pricing.</p>
                        <a href="contact" class="shb-consult-btn mb-3">Request a Callback</a>
                        <div class="small text-light">
                            <div class="mb-1"><i class="fa-solid fa-phone text-danger me-2"></i><a href="tel:<?= htmlspecialchars($sitePhone); ?>" class="text-white text-decoration-none fw-bold"><?= htmlspecialchars($sitePhone); ?></a></div>
                            <div><i class="fa-solid fa-envelope text-danger me-2"></i><a href="mailto:<?= htmlspecialchars($siteEmail); ?>" class="text-white text-decoration-none"><?= htmlspecialchars($siteEmail); ?></a></div>
                        </div>
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
} elseif (file_exists('inc/footer.php')) {
    include 'inc/footer.php';
}
?>