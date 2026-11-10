<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";

// Check karna agar delete ID url me di gayi hai
if (isset($_GET['delete_testimonial_details']) && !empty(trim($_GET['delete_testimonial_details']))) {
    
    $test_id = mysqli_real_escape_string($conn, trim($_GET['delete_testimonial_details']));

    // 1. Pehle image ka naam nikalna database se takki server se file delete ki ja sake
    $select_sql = "SELECT `image` FROM `testimonials` WHERE `test_id` = '$test_id'";
    $select_res = mysqli_query($conn, $select_sql);

    if (mysqli_num_rows($select_res) > 0) {
        $row = mysqli_fetch_assoc($select_res);
        $image_name = $row['image'];

        // 2. Database se testimonial record delete karna
        $delete_sql = "DELETE FROM `testimonials` WHERE `test_id` = '$test_id'";
        
        if (mysqli_query($conn, $delete_sql)) {
            
            // 3. Agar query successful rahi, toh server se physically photo delete karna
            if (!empty($image_name)) {
                $file_path = "assets/img/uploads/" . $image_name;
                
                // Check karna ki file sach me folder me exist karti hai ya nahi
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            // Success alert set karna session me (agar aap testimonials.php par display kar rahe ho)
            $_SESSION['msg'] = "Testimonial deleted successfully!";
            $_SESSION['msg_class'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Database Error: Unable to delete testimonial.";
            $_SESSION['msg_class'] = "alert-danger";
        }
    } else {
        $_SESSION['msg'] = "Testimonial not found or already deleted.";
        $_SESSION['msg_class'] = "alert-danger";
    }

} else {
    $_SESSION['msg'] = "Invalid Request!";
    $_SESSION['msg_class'] = "alert-danger";
}

// Kaam khatam hone ke baad wapas list page par redirect karna
header("Location: testimonials.php");
exit();
?>