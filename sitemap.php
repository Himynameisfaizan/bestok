<?php
header("Content-Type: application/xml; charset=utf-8");
include 'config/connect.php';

// Automatically detect base URL for XAMPP Localhost & Live Hostinger Server
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = rtrim($script_dir, '/') . '/';
$baseUrl = $protocol . "://" . $host . $base_path;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // 1. STATIC PAGES (Clean URLs without .php)
    $staticPages = [
    ["url" => "", "priority" => "1.0", "changefreq" => "daily"],
    ["url" => "about/", "priority" => "0.9", "changefreq" => "monthly"],
    ["url" => "certificates/", "priority" => "0.8", "changefreq" => "monthly"],
    ["url" => "career/", "priority" => "0.7", "changefreq" => "monthly"],
    ["url" => "gallery/", "priority" => "0.8", "changefreq" => "monthly"],
    ["url" => "contact-us/", "priority" => "0.9", "changefreq" => "monthly"],
    ["url" => "blogs/", "priority" => "0.8", "changefreq" => "weekly"],
    ["url" => "privacy-policy/", "priority" => "0.3", "changefreq" => "yearly"],
    ["url" => "shipping-return/", "priority" => "0.3", "changefreq" => "yearly"],
    ["url" => "refund-policy/", "priority" => "0.3", "changefreq" => "yearly"],
    ["url" => "terms-condition/", "priority" => "0.3", "changefreq" => "yearly"]
    ];

    foreach ($staticPages as $page) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . $page['url'] . "</loc>\n";
        echo " <changefreq>" . $page['changefreq'] . "</changefreq>\n";
        echo " <priority>" . $page['priority'] . "</priority>\n";
        echo " </url>\n";
    }

    if (isset($conn)) {
    // 2. MAIN CATEGORIES
    $catQuery = mysqli_query($conn, "SELECT slug_url FROM categories WHERE status = 1");
    if ($catQuery && mysqli_num_rows($catQuery) > 0) {
    while ($row = mysqli_fetch_assoc($catQuery)) {
    if (!empty($row['slug_url'])) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . 'category/' . rawurlencode(trim($row['slug_url'])) . "</loc>\n";
        echo " <changefreq>weekly</changefreq>\n";
        echo " <priority>0.8</priority>\n";
        echo " </url>\n";
    }
    }
    }

    // 3. SUB CATEGORIES
    $subCatQuery = mysqli_query($conn, "SELECT slug_url FROM sub_categories WHERE status = 1");
    if ($subCatQuery && mysqli_num_rows($subCatQuery) > 0) {
    while ($row = mysqli_fetch_assoc($subCatQuery)) {
    if (!empty($row['slug_url'])) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . 'subcategory/' . rawurlencode(trim($row['slug_url'])) . "</loc>\n";
        echo " <changefreq>weekly</changefreq>\n";
        echo " <priority>0.8</priority>\n";
        echo " </url>\n";
    }
    }
    }

    // 4. PRODUCTS
    $prodQuery = mysqli_query($conn, "SELECT slug_url FROM products WHERE status = 1 AND is_disabled = 0");
    if ($prodQuery && mysqli_num_rows($prodQuery) > 0) {
    while ($row = mysqli_fetch_assoc($prodQuery)) {
    if (!empty($row['slug_url'])) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . 'product/' . rawurlencode(trim($row['slug_url'])) . "</loc>\n";
        echo " <changefreq>daily</changefreq>\n";
        echo " <priority>0.9</priority>\n";
        echo " </url>\n";
    }
    }
    }

    // 5. BLOGS
    $blogQuery = mysqli_query($conn, "SELECT slug FROM blogs WHERE status = 1");
    if ($blogQuery && mysqli_num_rows($blogQuery) > 0) {
    while ($row = mysqli_fetch_assoc($blogQuery)) {
    if (!empty($row['slug'])) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . 'blog/' . rawurlencode(trim($row['slug'])) . "</loc>\n";
        echo " <changefreq>monthly</changefreq>\n";
        echo " <priority>0.7</priority>\n";
        echo " </url>\n";
    }
    }
    }

    // 6. DYNAMIC CUSTOM PAGES (Admin Panel Created)
    $pageQuery = mysqli_query($conn, "SELECT slug_url FROM custom_pages WHERE status = 1");
    if ($pageQuery && mysqli_num_rows($pageQuery) > 0) {
    while ($row = mysqli_fetch_assoc($pageQuery)) {
    if (!empty($row['slug_url'])) {
    echo " <url>\n";
        echo " <loc>" . $baseUrl . 'page/' . rawurlencode(trim($row['slug_url'])) . "</loc>\n";
        echo " <changefreq>monthly</changefreq>\n";
        echo " <priority>0.8</priority>\n";
        echo " </url>\n";
    }
    }
    }
    }

    echo '</urlset>';
?>