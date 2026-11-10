<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php"; // Ensure this includes your database connection
include "functions.php";

if (!isset($_GET['edit_product_details'])) {
    die("Product ID is missing from the URL.");
}

$product_id = intval($_GET['edit_product_details']);

// Fetch product details using mysqli_query()
$query = "SELECT * FROM products WHERE pro_id = $product_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    die("Product not found.");
}

// Fetch categories
$category_query = "SELECT * FROM `categories` ORDER BY id DESC";
$categories = $conn->query($category_query);

$sql = "SELECT * FROM `categories` ORDER BY id DESC";
$check = mysqli_query($conn, $sql);

// Fetch subcategories for the selected parent category
$parent_cate_id = $product['pro_cate'];
// CORRECT - Use 'parent_id' not 'parent_cate'
$sub_cate_query = "SELECT * FROM `sub_categories` WHERE parent_id = '$parent_cate_id'";
$sub_categories = mysqli_query($conn, $sub_cate_query);
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin - Edit Product</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">

    <?php include "links.php"; ?>
</head>

<body class="crm_body_bg">

    <?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">

        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none">
                            <i class="ti-menu"></i>
                        </div>
                        <div class="serach_field-area d-flex align-items-center">
                            <div class="search_inner">
                                <form action="#">
                                    <div class="search_field">
                                        <input type="text" placeholder="Search here...">
                                    </div>
                                    <button type="submit"> <img src="assets/img/icon/icon_search.svg" alt> </button>
                                </form>
                            </div>
                            <span class="f_s_14 f_w_400 ml_25 white_text text_white">Apps</span>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                    <a class="bell_notification_clicker nav-link-notify" href="#"> 
                                        <img src="assets/img/icon/bell.svg" alt>
                                    </a>
                                    <!-- Notification dropdown - keep as is -->
                                </li>
                                <li>
                                    <a class="CHATBOX_open nav-link-notify" href="#"> 
                                        <img src="assets/img/icon/msg.svg" alt> 
                                    </a>
                                </li>
                            </div>
                            <div class="profile_info">
                                <img src="assets/img/client_img.png" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>Neurologist </p>
                                        <h5>Dr. Robar Smith</h5>
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="#">My Profile </a>
                                        <a href="#">Settings</a>
                                        <a href="#">Log Out </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h2 class="m-0">Update Product Details</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <br />
                                <div class="card-body">
                                    <form action="update-product.php" method="POST" enctype="multipart/form-data">
                                        <!-- Hidden Input for Product ID -->
                                        <input type="hidden" name="pro_id" value="<?= $product['pro_id'] ?>" />

                                        <div class="row mb-3">
                                            <!-- Product Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_name">Product Name</label>
                                                <input type="text" class="form-control" name="pro_name"
                                                    id="pro_name" value="<?= $product['pro_name'] ?>"
                                                    placeholder="Product Name" required />
                                            </div>

                                            <!-- Brand Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="brand_name">Brand Name</label>
                                                <input type="text" class="form-control" name="brand_name"
                                                    id="brand_name" value="<?= $product['brand_name'] ?? '' ?>"
                                                    placeholder="Brand Name" />
                                            </div>

                                            <!-- Parent Category -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_cate">Parent Category Name</label>
                                                <select class="form-control" name="pro_cate" id="pro_cate" required
                                                    onchange="get_subcategory(this.value)">
                                                    <option value="">--select--</option>
                                                    <?php foreach ($check as $val) { ?>
                                                        <option value="<?= $val['cate_id'] ?>"
                                                            <?= ($product['pro_cate'] == $val['cate_id']) ? 'selected' : '' ?>>
                                                            <?= ucwords($val['categories']) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- Sub Category - FIXED AND ADDED -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_sub_cate">Sub Category</label>
                                                <select class="form-control" name="pro_sub_cate" id="subcate_id" required>
                                                    <option value="">Select</option>
                                                    <?php 
                                                    // Show subcategories for the current parent category
                                                    if ($sub_categories && mysqli_num_rows($sub_categories) > 0) {
                                                        while ($sub_cate = mysqli_fetch_assoc($sub_categories)) {
                                                            $selected = ($product['pro_sub_cate'] == $sub_cate['id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $sub_cate['id'] ?>" <?= $selected ?>>
                                                            <?= ucwords($sub_cate['categories']) ?>
                                                        </option>
                                                    <?php 
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Stock -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="stock">Stock</label>
                                                <input type="text" class="form-control" name="stock"
                                                    id="stock" value="<?= $product['stock'] ?>"
                                                    placeholder="Stock" required />
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
    <label class="form-label" for="display_order">Display Order</label>
    <input type="number"
           class="form-control"
           name="display_order"
           id="display_order"
           value="<?= $product['display_order'] ?>"
           min="1"
           required>
</div>

                                            <!-- Product Image(s) -->
                                         <!-- Product Image -->
<div class="col-md-6 mb-3">
    <label class="form-label" for="pro_img">Product Image</label>
    <input type="file" class="form-control" name="pro_img" id="pro_img" accept="image/*" />
    
    <?php if(!empty($product['pro_img'])): ?>
    <div class="mt-2">
        <small>Current Image:</small>
        <div class="mt-2">
            <img src="assets/img/uploads/<?= trim($product['pro_img']) ?>"
                style="height: 200px; width: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #eee;"
                alt="Product Image"
                onerror="this.src='assets/img/no-image.png'">
        </div>
    </div>
    <?php else: ?>
    <div class="mt-2">
        <small>No image uploaded</small>
    </div>
    <?php endif; ?>
</div>

                                            <!-- New Arrival -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="new_arrival">Exclusive Deal & Offers</label>
                                                <select id="new_arrival" name="new_arrival" class="form-control" required>
                                                    <option value="0" <?= $product['new_arrival'] == 0 ? 'selected' : '' ?>>No</option>
                                                    <option value="1" <?= $product['new_arrival'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                </select>
                                            </div>

                                            <!-- Trending / Special Offers -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="trending">Special Offers</label>
                                                <select id="trending" name="trending" class="form-control" required>
                                                    <option value="0" <?= $product['trending'] == 0 ? 'selected' : '' ?>>No</option>
                                                    <option value="1" <?= $product['trending'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                </select>
                                            </div>

                                            <!-- Short Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="short_desc">Product Short Description</label>
                                                <textarea class="form-control" name="short_desc" id="short_desc"
                                                    required><?= $product['short_desc'] ?></textarea>
                                            </div>

                                            <!-- Long Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="pro_desc">Product Long Description</label>
                                                <textarea class="form-control" name="pro_desc" id="pro_desc"
                                                    required><?= $product['description'] ?></textarea>
                                            </div>

                                            <!-- MRP -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="mrp">MRP</label>
                                                <input type="text" class="form-control" name="mrp" id="mrp"
                                                    value="<?= $product['mrp'] ?>" placeholder="MRP" required />
                                            </div>

                                            <!-- Selling Price -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="selling_price">Selling Price</label>
                                                <input type="text" class="form-control" name="selling_price"
                                                    id="selling_price" value="<?= $product['selling_price'] ?>"
                                                    placeholder="Selling Price" required />
                                            </div>

                                            <!-- Qty -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="qty">Quantity</label>
                                                <input type="text" class="form-control" name="qty" id="qty"
                                                    value="<?= $product['qty'] ?>" placeholder="Quantity" />
                                            </div>
                                        </div>

                                        <!-- SEO Section -->
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title"
                                                    id="meta_title" value="<?= $product['meta_title'] ?>"
                                                    placeholder="Meta Title" />
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_key">Meta Keyword</label>
                                                <input type="text" class="form-control" name="meta_key"
                                                    id="meta_key" value="<?= $product['meta_key'] ?>"
                                                    placeholder="Meta Keyword" />
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                <input type="text" class="form-control" name="meta_desc"
                                                    id="meta_desc" value="<?= $product['meta_desc'] ?>"
                                                    placeholder="Meta Description" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="status">Status</label>
                                                <select id="status" name="status" class="form-control" required>
                                                    <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>
                                                        Active</option>
                                                    <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>
                                                        Deactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="update-product" class="btn btn-primary">
                                            Update Product
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>

        <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

        <script>
            CKEDITOR.replace('pro_desc');
            CKEDITOR.replace('short_desc');
        </script>

        <!-- AJAX function for selecting category then automatically show sub category -->
        <script type="text/javascript">
            function get_subcategory(cate_id) {
                if (cate_id === '') {
                    $("#subcate_id").html('<option value="">Select</option>');
                    return;
                }
                
                $.ajax({
                    url: 'functions.php',
                    method: 'post',
                    data: { cate_id: cate_id },
                    error: function () {
                        alert("Something went wrong while fetching subcategories");
                    },
                    success: function (data) {
                        $("#subcate_id").html(data);
                    }
                });
            }
        </script>

    </section>
</body>
</html>