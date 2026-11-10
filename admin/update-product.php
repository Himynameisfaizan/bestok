<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db-conn.php";

if (isset($_POST['update-product'])) {
    
    // Get all form data
    $pro_id         = intval($_POST['pro_id']);
    $pro_name       = mysqli_real_escape_string($conn, $_POST['pro_name']);
    $brand_name     = mysqli_real_escape_string($conn, $_POST['brand_name'] ?? '');
    $pro_cate       = intval($_POST['pro_cate']);
    $pro_sub_cate   = intval($_POST['pro_sub_cate'] ?? 0);
    $short_desc     = mysqli_real_escape_string($conn, $_POST['short_desc']);
    $description    = mysqli_real_escape_string($conn, $_POST['pro_desc']);
    $new_arrival    = intval($_POST['new_arrival']);
    $trending       = intval($_POST['trending'] ?? 0);
    $qty            = intval($_POST['qty'] ?? 0);
    $whole_sale_price = mysqli_real_escape_string($conn, $_POST['whole_sale_selling_price'] ?? '');
    $mrp            = floatval($_POST['mrp']);
    $selling_price  = floatval($_POST['selling_price']);
    $stock          = intval($_POST['stock']);
    $status         = intval($_POST['status']);
    $display_order = intval($_POST['display_order']);
    $meta_title     = mysqli_real_escape_string($conn, $_POST['meta_title']);
    $meta_desc      = mysqli_real_escape_string($conn, $_POST['meta_desc']);
    $meta_key       = mysqli_real_escape_string($conn, $_POST['meta_key']);
    $added_on       = date('Y-m-d H:i:s');
    
    // Generate slug URL
    $slug_url = strtolower(str_replace(" ", "-", $pro_name));

    // ==========================================
    // SINGLE IMAGE UPLOAD (FIXED)
    // ==========================================
    
    $target_dir = "assets/img/uploads/";

    // Create directory if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Check if new image is uploaded (SINGLE FILE, not array)
    if (isset($_FILES['pro_img']) && $_FILES['pro_img']['error'] === UPLOAD_ERR_OK && !empty($_FILES['pro_img']['name'])) {
        
        $filename = $_FILES['pro_img']['name'];
        $tempname = $_FILES['pro_img']['tmp_name'];
        $filesize = $_FILES['pro_img']['size'];
        
        // Get file extension
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if (in_array($file_extension, $allowed_extensions)) {
            
            // Create unique filename
            $uniqueFilename = time() . "_" . rand(1000, 9999) . "." . $file_extension;
            $target_file = $target_dir . $uniqueFilename;

            // Upload file
            if (move_uploaded_file($tempname, $target_file)) {
                $pro_img = $uniqueFilename; // New image name
                
                // Optional: Delete old image if exists
                $old_img_query = "SELECT pro_img FROM products WHERE pro_id = '$pro_id'";
                $old_result = mysqli_query($conn, $old_img_query);
                if ($old_result && mysqli_num_rows($old_result) > 0) {
                    $old_data = mysqli_fetch_assoc($old_result);
                    $old_image = $old_data['pro_img'];
                    if (!empty($old_image)) {
                        $old_image_path = $target_dir . $old_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path); // Delete old image
                        }
                    }
                }
                
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, GIF, WEBP allowed.');</script>";
        }
    }

    // ==========================================
    // BUILD UPDATE QUERY
    // ==========================================
    
    if (isset($pro_img) && !empty($pro_img)) {
        // New image uploaded - update image field
        $query = "UPDATE `products` SET 
    `pro_name` = '$pro_name',
    `brand_name` = '$brand_name',
    `pro_cate` = '$pro_cate',
    `pro_sub_cate` = '$pro_sub_cate',
    `display_order` = '$display_order',
    `short_desc` = '$short_desc',
    `description` = '$description',
    `new_arrival` = '$new_arrival',
    `trending` = '$trending',
    `qty` = '$qty',
    `mrp` = '$mrp',
    `selling_price` = '$selling_price',
    `whole_sale_selling_price` = '$whole_sale_price',
    `stock` = '$stock',
    `pro_img` = '$pro_img',
    `status` = '$status',
    `slug_url` = '$slug_url',
    `meta_title` = '$meta_title',
    `meta_desc` = '$meta_desc',
    `meta_key` = '$meta_key',
    `added_on` = '$added_on'
WHERE `pro_id` = '$pro_id'";
    } else {
        // No new image - don't update image field
        $query = "UPDATE `products` SET 
    `pro_name` = '$pro_name',
    `brand_name` = '$brand_name',
    `pro_cate` = '$pro_cate',
    `pro_sub_cate` = '$pro_sub_cate',
    `display_order` = '$display_order',
    `short_desc` = '$short_desc',
    `description` = '$description',
    `new_arrival` = '$new_arrival',
    `trending` = '$trending',
    `qty` = '$qty',
    `mrp` = '$mrp',
    `selling_price` = '$selling_price',
    `whole_sale_selling_price` = '$whole_sale_price',
    `stock` = '$stock',
    `status` = '$status',
    `slug_url` = '$slug_url',
    `meta_title` = '$meta_title',
    `meta_desc` = '$meta_desc',
    `meta_key` = '$meta_key',
    `added_on` = '$added_on'
WHERE `pro_id` = '$pro_id'";
    }

    // ==========================================
    // EXECUTE QUERY
    // ==========================================
    
    if (mysqli_query($conn, $query)) {
        echo "<script type='text/javascript'>
                alert('Product updated successfully!');
                window.location.href = 'show-products.php';
              </script>";
        exit;
    } else {
        echo "Error updating product: " . mysqli_error($conn);
        echo "<br>Query: " . $query;
    }

    mysqli_close($conn);
}
?>