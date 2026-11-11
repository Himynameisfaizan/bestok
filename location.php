<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'config/connect.php';

if (!isset($site)) {
    $site = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/bestok/";
}

// Global SEO Setup for Header
$meta_title = 'Information Hub & Services | Bestok Industries Pvt Ltd';
$meta_desc = 'Explore regional service locations, turnkey industrial projects, air compressor solutions, and compressed air networks by Bestok Industries.';
$meta_key = 'Bestok locations, Air Compressor supplier, Industrial Air Solutions, Turnkey compressed air projects';

// Fetch active custom pages
$customPages = [];
$sql = "SELECT id, page_title, breadcrumb_title, slug_url, content, meta_title, meta_desc, created_at
        FROM custom_pages
        WHERE status = 1 AND slug_url <> ''
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customPages[] = $row;
    }
}
$totalPages = count($customPages);

// Fetch Contact Info for CTA
$contactQuery = mysqli_query($conn, "SELECT phone, email, address FROM contacts LIMIT 1");
$contactInfo = $contactQuery ? mysqli_fetch_assoc($contactQuery) : [];
$sitePhone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-9871126707';
$siteEmail = !empty($contactInfo['email']) ? $contactInfo['email'] : 'bestocompressors@gmail.com';

function bestokCleanText($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Ensure header is included correctly
include 'inc/header.php';
?>

<style>
/* Bestok Custom Pages Grid Styling */
.bestok-page-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 25px;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}

.bestok-page-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(19, 52, 93, 0.1);
    border-color: #13345d;
}

.bestok-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.bestok-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: rgba(19, 52, 93, 0.1);
    color: #13345d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.bestok-page-card:hover .bestok-card-icon {
    background: #13345d;
    color: #ffffff;
}

.bestok-card-tag {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 4px;
    background: #f1f5f9;
    color: #475569;
}

.bestok-page-card h3 {
    color: #1e293b;
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 10px;
}

.bestok-page-card:hover h3 {
    color: #e30613;
    /* Bestok Red Hover */
}

.bestok-page-card p {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
    display: -webkit-box;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bestok-card-footer {
    padding-top: 15px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.85rem;
    font-weight: 700;
    color: #13345d;
    text-transform: uppercase;
}

.bestok-page-card:hover .bestok-card-footer {
    color: #e30613;
}

/* Filter Search Bar */
.search-filter-box {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-top: -30px;
    position: relative;
    z-index: 9;
    border: 1px solid #e2e8f0;
}
</style>

<main class="main">
    <!-- Breadcrumb using Bestok Theme -->
    <div class="site-breadcrumb" style="background: url(<?= $site ?>assets/img/bradcrambanner.jpg)">
        <div class="container">
            <h2 class="breadcrumb-title">Information Hub & Services</h2>
            <ul class="breadcrumb-menu">
                <li><a href="<?= $site ?>index.php">Home</a> <i class="fa fa-angle-double-right"></i></li>
                <li class="active">Pages</li>
            </ul>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="container">
        <div class="search-filter-box">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="m-0 fw-bold text-dark">
                        <span class="badge bg-danger me-2"><?= $totalPages; ?></span> Active Solutions & Pages
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-search"></i></span>
                        <input type="text" id="pageSearchInput" class="form-control"
                            placeholder="Search by location, keyword or service...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages Grid Section -->
    <section class="py-5 bg-light mt-4">
        <div class="container pb-5">
            <?php if (!empty($customPages)): ?>
            <div class="row g-4" id="customPagesGrid">
                <?php 
                // Industrial Icons Array
                $icons = ['fa-industry', 'fa-cogs', 'fa-wrench', 'fa-fan', 'fa-bolt', 'fa-tools'];
                $index = 0;
                foreach ($customPages as $customPage):
                    $cardTitle = trim($customPage['page_title'] ?: $customPage['breadcrumb_title']);
                    $badgeText = trim($customPage['breadcrumb_title'] ?: 'Industrial Solution');
                    
                    $rawDesc = trim(strip_tags($customPage['meta_desc']));
                    if (empty($rawDesc)) {
                        $rawDesc = mb_strimwidth(trim(strip_tags($customPage['content'])), 0, 120, '...');
                    }

                    $slug = trim($customPage['slug_url']);
                    // Link mapping to your htaccess rule
                    $pageUrl = $site . 'page/' . rawurlencode($slug);
                    $iconClass = $icons[$index % count($icons)];
                    $index++;
                ?>
                <div class="col-lg-4 col-md-6 page-grid-item"
                    data-search="<?= bestokCleanText(strtolower($cardTitle . ' ' . $badgeText . ' ' . $rawDesc)); ?>">
                    <a href="<?= $pageUrl; ?>" class="bestok-page-card">
                        <div class="bestok-card-top">
                            <div class="bestok-card-icon">
                                <i class="fa-solid <?= $iconClass; ?>"></i>
                            </div>
                            <span class="bestok-card-tag"><?= bestokCleanText($badgeText); ?></span>
                        </div>
                        <h3><?= bestokCleanText($cardTitle); ?></h3>
                        <p><?= bestokCleanText($rawDesc); ?></p>
                        <div class="bestok-card-footer">
                            <span>Explore Details</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <div id="noMatchMsg" class="text-center py-5 d-none">
                <h4 class="fw-bold text-muted">No matching pages found</h4>
                <p>Try searching with a different industrial keyword.</p>
            </div>

            <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                <h3>No Custom Pages Found</h3>
                <p>Pages created from the admin panel will appear here.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('pageSearchInput');
    const items = document.querySelectorAll('.page-grid-item');
    const noMatch = document.getElementById('noMatchMsg');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            items.forEach(function(item) {
                const text = item.getAttribute('data-search') || '';
                if (text.includes(query)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (noMatch) {
                noMatch.classList.toggle('d-none', visibleCount > 0);
            }
        });
    }
});
</script>

<?php include 'inc/footer.php'; ?>