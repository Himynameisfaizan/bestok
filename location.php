<?php
include 'config/connect.php';

$pageTitle = 'Service Locations & Custom Pages | SHB Technologies & Medical Systems';
$meta_title = 'Service Locations & Information Hub | SHB Technologies & Medical Systems';
$meta_desc = 'Explore regional service locations, turnkey hospital project capabilities, Medical Gas Pipeline (MGPS) networks, and Healthcare IT solutions by SHB Technologies.';
$meta_description = $meta_desc;
$meta_key = 'SHB Technologies locations, Medical Gas Pipeline India, Modular OT supplier Delhi NCR, Hospital IT services, Turnkey healthcare projects';
$meta_keywords = $meta_key;
$breadcrumb_title = 'Locations & Information Hub';
$breadcrumb_name = $breadcrumb_title;

// Fetch active custom pages
$customPages = [];
$sql = "SELECT id, page_title, breadcrumb_title, slug_url, content, meta_title, meta_desc, created_at
        FROM custom_pages
        WHERE status = 1 AND slug_url <> ''
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customPages[] = $row;
    }
}

$totalPages = count($customPages);

// Fetch contact phone for CTA
$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$sitePhone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-8178037626';
$siteEmail = !empty($contactInfo['email']) ? $contactInfo['email'] : 'technoshb@gmail.com';

function shbCleanText($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Header file path compatibility (checks includes/header.php first, then inc/header.php)
if (file_exists('includes/header.php')) {
    include 'includes/header.php';
} elseif (file_exists('inc/header.php')) {
    include 'inc/header.php';
}
?>

<style>
/* ================= SHB TECHNOLOGIES MEDICAL & IT THEME ================= */
:root {
    --shb-red: #e30613;
    --shb-red-dark: #b8000b;
    --shb-red-soft: rgba(227, 6, 19, 0.08);
    --shb-dark: #0f172a;
    --shb-black: #111111;
    --shb-blue: #1d5b79;
    --shb-text: #475569;
    --shb-light: #f8fafc;
    --shb-border: #e2e8f0;
}

.shb-loc-wrapper {
    background-color: var(--shb-light);
    font-family: 'Inter', 'Poppins', sans-serif;
    color: var(--shb-dark);
}

/* Hero Banner matching SHB Dark Stats/Footer style */
.shb-loc-hero {
    background: radial-gradient(circle at 80% 20%, rgba(227, 6, 19, 0.18), transparent 45%),
                linear-gradient(135deg, #0b0f19 0%, #161e2e 100%);
    padding: 85px 0 75px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    border-bottom: 4px solid var(--shb-red);
}

.shb-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(227, 6, 19, 0.15);
    border: 1px solid rgba(227, 6, 19, 0.4);
    color: #ff6b72;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 50px;
    margin-bottom: 18px;
}

.shb-hero-badge span.dot {
    width: 7px;
    height: 7px;
    background: var(--shb-red);
    border-radius: 50%;
    display: inline-block;
}

.shb-loc-hero h1 {
    font-size: clamp(2.1rem, 4vw, 3.2rem);
    font-weight: 800;
    letter-spacing: -0.5px;
    margin-bottom: 16px;
    color: #ffffff;
}

.shb-loc-hero h1 span {
    color: var(--shb-red);
}

.shb-loc-hero p {
    color: #cbd5e1;
    font-size: 1.05rem;
    max-width: 680px;
    margin: 0 auto 30px;
    line-height: 1.7;
}

/* Stats Strip & Filter Bar */
.shb-filter-bar {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px 26px;
    margin-top: -38px;
    position: relative;
    z-index: 10;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    border: 1px solid var(--shb-border);
}

.shb-count-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--shb-dark);
}

.shb-count-pill .count-num {
    background: var(--shb-red);
    color: #fff;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.85rem;
}

.shb-search-input {
    position: relative;
}

.shb-search-input input {
    width: 100%;
    padding: 11px 16px 11px 42px;
    border-radius: 8px;
    border: 1px solid var(--shb-border);
    font-size: 0.92rem;
    background: var(--shb-light);
    transition: all 0.25s ease;
}

.shb-search-input input:focus {
    outline: none;
    border-color: var(--shb-red);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(227, 6, 19, 0.1);
}

.shb-search-input i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

/* Custom Page Cards */
.shb-grid-section {
    padding: 60px 0 80px;
}

.shb-page-card {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--shb-border);
    padding: 28px;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
}

.shb-page-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--shb-red), var(--shb-blue));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.shb-page-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(15, 23, 42, 0.09);
    border-color: rgba(227, 6, 19, 0.25);
}

.shb-page-card:hover::before {
    opacity: 1;
}

.shb-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.shb-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--shb-red-soft);
    color: var(--shb-red);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

.shb-page-card:hover .shb-card-icon {
    background: var(--shb-red);
    color: #ffffff;
}

.shb-card-tag {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 4px 10px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
}

.shb-page-card h3 {
    color: var(--shb-dark);
    font-size: 1.22rem;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 12px;
    transition: color 0.25s ease;
}

.shb-page-card:hover h3 {
    color: var(--shb-red);
}

.shb-page-card p {
    color: var(--shb-text);
    font-size: 0.93rem;
    line-height: 1.65;
    margin-bottom: 22px;
    flex-grow: 1;
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.shb-card-footer {
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--shb-dark);
}

.shb-page-card:hover .shb-card-footer {
    color: var(--shb-red);
}

.shb-card-footer i {
    transition: transform 0.25s ease;
}

.shb-page-card:hover .shb-card-footer i {
    transform: translateX(5px);
}

/* Bottom CTA Banner */
.shb-cta-box {
    background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
    border-radius: 16px;
    padding: 45px 40px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    margin-top: 50px;
}

.shb-btn-red {
    background: var(--shb-red);
    color: #ffffff;
    font-weight: 600;
    padding: 12px 26px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.25s ease;
}

.shb-btn-red:hover {
    background: var(--shb-red-dark);
    color: #ffffff;
}
</style>

<div class="shb-loc-wrapper">
    <!-- Hero Section -->
    <header class="shb-loc-hero text-center">
        <div class="container">
            <div class="shb-hero-badge">
                <span class="dot"></span> SHB TECHNOLOGIES & MEDICAL SYSTEMS
            </div>
            <h1>Our Service Network & <span>Information Hub</span></h1>
            <p>Explore our regional turnkey capabilities in Medical Gas Pipeline Systems (MGPS), Modular Operation Theatres, Biomedical Engineering, and Healthcare IT infrastructure across India.</p>
        </div>
    </header>

    <!-- Total Pages Counter & Instant Filter -->
    <div class="container">
        <div class="shb-filter-bar">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="shb-count-pill">
                        <span class="count-num"><?= $totalPages; ?></span>
                        <span>Active Location & Solution Pages Available</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="shb-search-input">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="shbPageSearch" placeholder="Search by location, service, or keyword (e.g., Delhi, MGPS, Export)...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Grid Section -->
    <section class="shb-grid-section">
        <div class="container">
            <?php if (!empty($customPages)): ?>
            <div class="row g-4" id="shbPagesGrid">
                <?php 
                $icons = ['fa-hospital', 'fa-Location-dot', 'fa-stethoscope', 'fa-network-wired', 'fa-shield-heart', 'fa-globe'];
                $index = 0;
                foreach ($customPages as $customPage):
                    $cardTitle = trim((string)($customPage['page_title'] ?? ''));
                    if ($cardTitle === '') {
                        $cardTitle = trim((string)($customPage['breadcrumb_title'] ?? 'SHB Solution Page'));
                    }

                    $badgeText = trim((string)($customPage['breadcrumb_title'] ?? ''));
                    if ($badgeText === '') {
                        $badgeText = 'Medical Systems';
                    }

                    // Extract clean summary from meta_desc or content
                    $rawDesc = trim(strip_tags((string)($customPage['meta_desc'] ?? '')));
                    if ($rawDesc === '' || strtolower($rawDesc) === strtolower($cardTitle)) {
                        $rawDesc = trim(strip_tags((string)($customPage['content'] ?? '')));
                    }
                    if ($rawDesc === '') {
                        $rawDesc = 'Explore turnkey medical gas pipeline systems, modular OT equipment, and healthcare IT infrastructure tailored for hospitals.';
                    }

                    $slug = trim((string)($customPage['slug_url'] ?? ''));
                    // Matches page.php $_GET['slug_url'] and $_GET['slug']
                    $pageUrl = 'page/' . rawurlencode($slug);
                    $iconClass = $icons[$index % count($icons)];
                    $index++;
                ?>
                <div class="col-12 col-md-6 col-lg-4 shb-page-item" data-search="<?= shbCleanText(strtolower($cardTitle . ' ' . $badgeText . ' ' . $rawDesc)); ?>">
                    <a href="<?= shbCleanText($pageUrl); ?>" class="shb-page-card">
                        <div class="shb-card-top">
                            <div class="shb-card-icon">
                                <i class="fa-solid <?= $iconClass; ?>"></i>
                            </div>
                            <span class="shb-card-tag"><?= shbCleanText($badgeText); ?></span>
                        </div>
                        <h3><?= shbCleanText($cardTitle); ?></h3>
                        <p><?= shbCleanText($rawDesc); ?></p>
                        <div class="shb-card-footer">
                            <span>Explore Details</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty Search Result Message -->
            <div id="shbNoMatch" class="text-center py-5 d-none">
                <h4 class="fw-bold text-secondary mb-2">No matching page found</h4>
                <p class="text-muted mb-0">Try searching with a different location or medical equipment keyword.</p>
            </div>

            <?php else: ?>
            <div class="bg-white rounded-4 p-5 text-center border">
                <i class="fa-solid fa-folder-open fs-1 text-danger mb-3"></i>
                <h3 class="fw-bold">No Custom Pages Published Yet</h3>
                <p class="text-muted mb-4">You can create new location and service pages directly from the Admin Panel.</p>
                <a href="contact" class="shb-btn-red">Contact Our Team</a>
            </div>
            <?php endif; ?>

            <!-- Bottom Turnkey Project Inquiry Banner -->
            <div class="shb-cta-box">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="text-uppercase fw-bold small" style="color: #ff6b72; letter-spacing: 1px;">24/7 Field Support & Turnkey Execution</span>
                        <h2 class="fw-bold mt-1 mb-2 text-white">Planning a Modular OT or Medical Gas Pipeline Project?</h2>
                        <p class="mb-0 text-light opacity-75">With 120+ hospitals served and 350+ installations across India, our biomedical engineers provide custom BOQs and on-site consultation.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="contact" class="shb-btn-red me-2 mb-2">Get In Touch <i class="fa-solid fa-arrow-right"></i></a>
                        <a href="tel:<?= shbCleanText($sitePhone); ?>" class="btn btn-outline-light px-3 py-2 mb-2 fw-semibold">
                            <i class="fa-solid fa-phone me-1"></i> <?= shbCleanText($sitePhone); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('shbPageSearch');
    const items = document.querySelectorAll('.shb-page-item');
    const noMatch = document.getElementById('shbNoMatch');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            items.forEach(function (item) {
                const text = item.getAttribute('data-search') || '';
                if (text.includes(query)) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            if (noMatch) {
                noMatch.classList.toggle('d-none', visibleCount > 0);
            }
        });
    }
});
</script>

<?php
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
} elseif (file_exists('inc/footer.php')) {
    include 'inc/footer.php';
}
?>