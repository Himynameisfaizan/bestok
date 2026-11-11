<?php
if (file_exists('inc/header.php')) {
    include 'inc/header.php';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Bestok Industries Pvt Ltd</title>
    <meta name="description"
        content="The page you are looking for could not be found. Explore Bestok Industries compressed air solutions, equipment, and services.">

    <style>
    :root {
        --bestok-primary: #1D5B79;
        /* Theme Blue */
        --bestok-secondary: #E30613;
        /* Accent Red */
        --bestok-dark: #0b0f19;
        --bestok-light: #f8f9fa;
    }

    .bestok-404-wrap {
        background-color: var(--bestok-light);
        background-image:
            radial-gradient(circle at 80% 20%, rgba(29, 91, 121, 0.08), transparent 45%),
            radial-gradient(circle at 20% 80%, rgba(227, 6, 19, 0.05), transparent 45%);
        color: var(--bestok-dark);
        padding: 100px 20px;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid var(--bestok-primary);
    }

    .bestok-404-card {
        position: relative;
        z-index: 2;
        max-width: 680px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid rgba(29, 91, 121, 0.1);
        padding: 55px 40px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 576px) {
        .bestok-404-card {
            padding: 40px 22px;
        }
    }

    .bestok-404-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(227, 6, 19, 0.1);
        color: var(--bestok-secondary);
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 50px;
        margin-bottom: 20px;
    }

    .bestok-404-code {
        font-size: clamp(5rem, 12vw, 8rem);
        font-weight: 900;
        line-height: 1;
        margin-bottom: 10px;
        color: var(--bestok-primary);
        text-shadow: 2px 2px 0 rgba(227, 6, 19, 0.1);
        letter-spacing: -2px;
    }

    .bestok-404-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        margin-bottom: 15px;
        color: var(--bestok-dark);
    }

    .bestok-404-desc {
        color: #64748b;
        font-size: 1.05rem;
        margin-bottom: 35px;
        line-height: 1.6;
    }

    .bestok-404-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        margin-bottom: 35px;
    }

    .btn-bestok-primary {
        background-color: var(--bestok-primary);
        color: #ffffff !important;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 2px solid var(--bestok-primary);
    }

    .btn-bestok-primary:hover {
        background-color: var(--bestok-dark);
        border-color: var(--bestok-dark);
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(29, 91, 121, 0.2);
    }

    .btn-bestok-outline {
        background-color: transparent;
        color: var(--bestok-primary) !important;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 2px solid rgba(29, 91, 121, 0.3);
    }

    .btn-bestok-outline:hover {
        background-color: rgba(29, 91, 121, 0.05);
        border-color: var(--bestok-primary);
    }

    .bestok-quick-links {
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .bestok-quick-links span {
        color: #94a3b8;
    }

    .bestok-quick-links a {
        color: var(--bestok-primary);
        text-decoration: none;
        transition: color 0.2s;
    }

    .bestok-quick-links a:hover {
        color: var(--bestok-secondary);
        text-decoration: underline;
    }
    </style>
</head>

<body>


    <div class="bestok-404-wrap">
        <div class="bestok-404-card">
            <div class="bestok-404-badge">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error 404 • Page Not Found
            </div>
            <h1 class="bestok-404-code">404</h1>
            <h2 class="bestok-404-title">Oops! We Couldn't Find That Page</h2>
            <p class="bestok-404-desc">
                Jis equipment ya page ko aap dhoondh rahe hain, ho sakta hai uska URL galat ho ya wo hataya ja chuka ho.
                Kripya niche diye gaye links ka upyog karein.
            </p>

            <div class="bestok-404-actions">
                <a href="<?php echo $site ?? '/'; ?>" class="btn-bestok-primary">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Homepage
                </a>
                <a href="<?php echo $site ?? '/'; ?>#products" class="btn-bestok-outline">
                    <i class="fa fa-cogs" aria-hidden="true"></i> View Equipment Catalog
                </a>
            </div>

            <div class="bestok-quick-links">
                <span>Quick Links:</span>
                <a href="<?php echo $site ?? '/'; ?>about">About Bestok</a>
                <a href="<?php echo $site ?? '/'; ?>certificates">Certificates</a>
                <a href="<?php echo $site ?? '/'; ?>gallery">Gallery</a>
                <a href="<?php echo $site ?? '/'; ?>contact-us">Contact Us</a>
            </div>
        </div>
    </div>

    <?php 
if (file_exists('inc/footer.php')) {
    include 'inc/footer.php';
}
?>

</body>

</html>