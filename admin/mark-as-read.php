<?php
session_start();
include "db-conn.php";

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "No inquiry ID provided!";
    header("Location: /new-leads.php");
    exit();
}

$inquiry_id = intval($_GET['id']);

// Validate inquiry exists
$check_sql = "SELECT id, name FROM inquiries WHERE id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "i", $inquiry_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) == 0) {
    $_SESSION['error'] = "Inquiry not found!";
    header("Location: /new-leads.php");
    exit();
}

$inquiry = mysqli_fetch_assoc($check_result);

// Update inquiry status to read (status = 1)
$update_sql = "UPDATE inquiries SET status = 1 WHERE id = ?";
$update_stmt = mysqli_prepare($conn, $update_sql);
mysqli_stmt_bind_param($update_stmt, "i", $inquiry_id);

if (mysqli_stmt_execute($update_stmt)) {
    // Success message with inquiry name
    $_SESSION['success'] = "Inquiry from <strong>" . htmlspecialchars($inquiry['name']) . "</strong> has been marked as read!";
    
    // Optional: Log the action
    $admin_id = $_SESSION['admin_id'] ?? 0;
    $admin_name = $_SESSION['admin_name'] ?? 'Unknown';
    $log_sql = "INSERT INTO activity_logs (admin_id, admin_name, action, details, created_at) 
                VALUES (?, ?, 'marked_as_read', ?, NOW())";
    $log_stmt = mysqli_prepare($conn, $log_sql);
    $log_details = "Marked inquiry #" . $inquiry_id . " from " . $inquiry['name'] . " as read";
    mysqli_stmt_bind_param($log_stmt, "iss", $admin_id, $admin_name, $log_details);
    mysqli_stmt_execute($log_stmt);
} else {
    $_SESSION['error'] = "Failed to mark inquiry as read! Please try again.";
}

// Close statements
mysqli_stmt_close($update_stmt);
if (isset($check_stmt)) mysqli_stmt_close($check_stmt);
if (isset($log_stmt)) mysqli_stmt_close($log_stmt);

// Redirect back to inquiries page
header("Location: /new-leads.php");
exit();
?>