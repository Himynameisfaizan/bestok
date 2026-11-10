<?php
http_response_code(404);
$pageTitle  = "404 - Page Not Found | SHB Technologies & Medical Systems";
$meta_title = "404 - Page Not Found | SHB Technologies & Medical Systems";
$meta_desc  = "The page you are looking for could not be found. Explore SHB Technologies Medical Gas Pipeline Systems, Modular OTs, and Healthcare IT solutions.";

if (file_exists('includes/header.php')) {
    include 'includes/header.php';
} elseif (file_exists('inc/header.php')) {
    include 'inc/header.php';
}
?>

<style>
:root {
    --shb-red: #e30613;
    --shb-red-dark: #b8000b;
    --shb-dark: #0b0f19;
    --shb-slate: #161e2e;
}

.shb-404-wrap {
    background: radial-gradient(circle at 80% 20%, rgba(227, 6, 19, 0.22), transparent 45%),
                radial-gradient(circle at 20% 80%, rgba(29, 91, 121, 0.2), transparent 45%),
                linear-gradient(135deg, var(--shb-dark) 0%, var(--shb-slate) 100%);
    color: #ffffff;
    padding: 100px 20px;
    min-height: 78vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
    border-bottom: 4px solid var(--shb-red);
}

.shb-404-card {
    position: relative;
    z-index: 2;
    max-width: 680px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    padding: 55px 40px;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
}

@media (max-width: 576px) {
    .shb-404-card {
        padding: 40px 22px;
    }
}

.shb-404-badge {
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
    margin-bottom: 15px;
}

.shb-404-code {
    font-size: clamp(5.5rem, 14vw, 9.5rem);
    font-weight: 900;
    line-height: 1;
    margin-bottom: 10px;
    color: transparent;
    -webkit-text-stroke: 2.5px var(--shb-red);
    text-shadow: 0 12px 35px rgba(227, 6, 19, 0.35);
    letter-spacing: -3px;
}

.shb-404-title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    margin-bottom: 14px;
    color: #ffffff;
}

.shb-404-desc {
    color: #cbd5e1;
    font-size: 1.02rem;
    margin-bottom: 34px;
    line-height: 1.7;
}

.shb-404-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: center;
    margin-bottom: 32px;
}

.btn-shb-red {
    background-color: var(--shb-red);
    color: #ffffff !important;
    padding: 13px 30px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: 2px solid var(--shb-red);
}

.btn-shb-red:hover {
    background-color: transparent;
    color: #ff6b72 !important;
    box-shadow: 0 8px 25px rgba(227, 6, 19, 0.35);
}

.btn-shb-outline {
    background-color: transparent;
    color: #ffffff !important;
    padding: 13px 26px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.92rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.btn-shb-outline:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #ffffff;
}

.shb-quick-links {
    padding-top: 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
    font-size: 0.88rem;
}

.shb-quick-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s;
}

.shb-quick-links a:hover {
    color: #ffffff;
}
</style>

<div class="shb-404-wrap">
    <div class="shb-404-card">
        <div class="shb-404-badge">
            <i class="fa-solid fa-triangle-exclamation"></i> Error 404 • Page Not Found
        </div>
        <h1 class="shb-404-code">404</h1>
        <h2 class="shb-404-title">Oops! We Couldn't Find That Page</h2>
        <p class="shb-404-desc">
            Jis page ya equipment URL ko aap dhoondh rahe hain, ho sakta hai uska naam badal gaya ho, wo hataya ja chuka ho, ya URL galat type hua ho.
        </p>

        <div class="shb-404-actions">
            <a href="<?php echo $site ?? ''; ?>" class="btn-shb-red">
                <i class="fa-solid fa-house"></i> Back to Homepage
            </a>
            <a href="<?php echo $site ?? ''; ?>products" class="btn-shb-outline">
                <i class="fa-solid fa-boxes-stacked"></i> View Equipment Catalog
            </a>
        </div>

        <div class="shb-quick-links">
            <span>Quick Links:</span>
            <a href="<?php echo $site ?? ''; ?>about">About SHB</a>
            <a href="<?php echo $site ?? ''; ?>solution">Medical Solutions</a>
            <a href="<?php echo $site ?? ''; ?>It-services">Healthcare IT</a>
            <a href="<?php echo $site ?? ''; ?>location">Service Locations</a>
            <a href="<?php echo $site ?? ''; ?>contact">Contact Support</a>
        </div>
    </div>
</div>

<?php 
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
} elseif (file_exists('inc/footer.php')) {
    include 'inc/footer.php';
}
?>