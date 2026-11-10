<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Database central core mapping connection pipeline
include "db-conn.php";

// 1. VERIFY GET PARAMETER ID FOR TRANSACTIONS
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    
    // Cast to integer for ultimate sanitization against SQL injection attacks
    $brand_id = (int)$_GET['id'];

    // 2. FETCH THE RECORD FIRST TO TARGET AND ERASE THE GRAPHIC ASSET FROM SERVER FILE SYSTEM
    $select_query = "SELECT `logo_path` FROM `brands` WHERE `id` = '$brand_id' LIMIT 1";
    $select_res   = mysqli_query($conn, $select_query);

    if ($select_res && mysqli_num_rows($select_res) > 0) {
        $row = mysqli_fetch_assoc($select_res);
        $file_to_delete = $row['logo_path'];

        // Clear the graphic file asset from server uploads memory directory if it exists
        if (!empty($file_to_delete) && file_exists($file_to_delete)) {
            @unlink($file_to_delete);
        }
        
        // 3. EXECUTE THE PERMANENT SQL DELETE TRANSACTION QUERY
        $delete_query = "DELETE FROM `brands` WHERE `id` = '$brand_id'";
        
        if (mysqli_query($conn, $delete_query)) {
            // Optional: Store a success flash session message to display on the destination page
            $_SESSION['flash_msg'] = "Brand logo entry successfully cleared from repository.";
            $_SESSION['flash_class'] = "alert-success";
        } else {
            $_SESSION['flash_msg'] = "Database Deletion Error: " . mysqli_error($conn);
            $_SESSION['flash_class'] = "alert-danger";
        }
    } else {
        $_SESSION['flash_msg'] = "Targeted brand partner logo record ID does not exist.";
        $_SESSION['flash_class'] = "alert-warning";
    }
} else {
    $_SESSION['flash_msg'] = "Invalid operational parameters or unlogged transaction request token.";
    $_SESSION['flash_class'] = "alert-danger";
}

// 4. GRACEFUL ROUTING REDIRECTION BACK TO CORE MANAGEMENT GRID
header("Location: brands.php");
exit();
?>