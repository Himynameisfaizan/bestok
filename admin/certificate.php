<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";

$msg = "";
$msg_class = "";

// --- 1. Certificate Add / Upload karne ka Logic ---
if (isset($_POST['add_certificate'])) {
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    // Image Uploading Logic
    if (!empty($title) && isset($_FILES['cert_image']) && $_FILES['cert_image']['error'] == 0) {
        $allowed_ext = array("jpg", "jpeg", "png", "webp");
        $file_name = $_FILES['cert_image']['name'];
        $file_size = $_FILES['cert_image']['size'];
        $file_tmp = $_FILES['cert_image']['tmp_path'] ?? $_FILES['cert_image']['tmp_name'];
        
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check format
        if (in_array($ext, $allowed_ext)) {
            // Uniq name generate karna taaki purani file replace na ho
            $unique_image_name = "cert_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            $upload_path = "uploads/certificates/" . $unique_image_name;

            // Agar folder nahi bana to create karne ke liye
            if (!is_dir('uploads/certificates/')) {
                mkdir('uploads/certificates/', 0777, true);
            }

            // File ko folder me move karna
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $insert_query = "INSERT INTO `certificates` (`title`, `image`, `status`) 
                                 VALUES ('$title', '$unique_image_name', '$status')";
                
                if (mysqli_query($conn, $insert_query)) {
                    $msg = "Certificate added and uploaded successfully!";
                    $msg_class = "alert-success";
                } else {
                    $msg = "Database Error: " . mysqli_error($conn);
                    $msg_class = "alert-danger";
                }
            } else {
                $msg = "Failed to upload image. Please check folder permissions.";
                $msg_class = "alert-danger";
            }
        } else {
            $msg = "Invalid file format! Only JPG, JPEG, PNG, and WEBP are allowed.";
            $msg_class = "alert-danger";
        }
    } else {
        $msg = "All fields and a valid image are required!";
        $msg_class = "alert-danger";
    }
}

// --- 2. Certificate Delete karne ka Logic ---
if (isset($_GET['delete_id'])) {
    $del_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    // Pehle database se image ka naam nikaalein taaki folder se bhi delete ho sake
    $select_query = "SELECT `image` FROM `certificates` WHERE `cert_id` = '$del_id'";
    $res = mysqli_query($conn, $select_query);
    
    if (mysqli_num_rows($res) > 0) {
        $cert_data = mysqli_fetch_assoc($res);
        $file_to_delete = "uploads/certificates/" . $cert_data['image'];
        
        // Folder se image unlink/delete karna
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }

        // Database se record delete karna
        $delete_query = "DELETE FROM `certificates` WHERE `cert_id` = '$del_id'";
        if (mysqli_query($conn, $delete_query)) {
            $msg = "Certificate deleted successfully!";
            $msg_class = "alert-success";
        } else {
            $msg = "Error deleting from database: " . mysqli_error($conn);
            $msg_class = "alert-danger";
        }
    }
}

// --- 3. Saved Certificates Fetch karna ---
$fetch_query = "SELECT * FROM `certificates` ORDER BY `cert_id` DESC";
$all_certs = mysqli_query($conn, $fetch_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Certificates | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .custom-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .cert-img-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cert-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body class="crm_body_bg">
    <?php include "header.php"; ?>

    <section class="main_content dashboard_part">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>

        <div class="main_content_iner">
            <div class="container-fluid p-3">
                
                <?php if (!empty($msg)): ?>
                    <div class="alert <?= $msg_class ?> alert-dismissible fade show" role="alert">
                        <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-4 col-12 mb-4">
                        <div class="white_card custom-card">
                            <div class="card-header bg-white border-0 pt-3">
                                <h3 class="mb-0 fw-bold">Add Certificate</h3>
                                <p class="text-muted small mb-0">Upload document images or achievements</p>
                            </div>
                            <div class="white_card_body py-3">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Certificate Title / Name</label>
                                        <input type="text" class="form-control" name="title" placeholder="e.g. ISO 9001 Certificate" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select Image File</label>
                                        <input type="file" class="form-control" name="cert_image" accept="image/*" required>
                                        <small class="text-muted d-block mt-1">Supported formats: JPG, JPEG, PNG, WEBP.</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1">Active (Show)</option>
                                            <option value="0">Inactive (Hide)</option>
                                        </select>
                                    </div>

                                    <button type="submit" name="add_certificate" class="btn btn-primary w-100">
                                        <i class="fas fa-upload me-2"></i>Upload & Publish
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-12">
                        <div class="white_card custom-card">
                            <div class="card-header bg-white border-0 pt-3">
                                <h3 class="mb-0 fw-bold">Certificates Gallery</h3>
                            </div>
                            <div class="white_card_body py-3">
                                <?php if (mysqli_num_rows($all_certs) > 0): ?>
                                    <div class="row">
                                        <?php while($cert = mysqli_fetch_assoc($all_certs)): ?>
                                            <div class="col-md-6 col-12 mb-4">
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="cert-img-container mb-2">
                                                        <a href="uploads/certificates/<?= $cert['image']; ?>" target="_blank">
                                                            <img src="uploads/certificates/<?= $cert['image']; ?>" alt="<?= htmlspecialchars($cert['title']); ?>">
                                                        </a>
                                                    </div>
                                                    
                                                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($cert['title']); ?>">
                                                        <?= htmlspecialchars($cert['title']); ?>
                                                    </h5>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                                        <div>
                                                            <?php if($cert['status'] == 1): ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Hidden</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <a href="certificate.php?delete_id=<?= $cert['cert_id']; ?>" 
                                                           onclick="return confirm('Are you sure you want to delete this certificate?');" 
                                                           class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-award fa-3x mb-3"></i>
                                        <p>No certificates uploaded yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include "footer.php"; ?>
</body>
</html>