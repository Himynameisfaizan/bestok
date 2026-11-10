<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";

$msg = "";
$msg_class = "";

// Check karna agar edit ID url me di gayi hai
if (!isset($_GET['edit_testimonial_details']) || empty(trim($_GET['edit_testimonial_details']))) {
    header("Location: testimonials.php");
    exit();
}

$test_id = mysqli_real_escape_string($conn, trim($_GET['edit_testimonial_details']));

// Pehle se saved data fetch karna form me dikhane ke liye
$fetch_sql = "SELECT * FROM `testimonials` WHERE `test_id` = '$test_id'";
$fetch_res = mysqli_query($conn, $fetch_sql);

if (mysqli_num_rows($fetch_res) == 0) {
    header("Location: testimonials.php");
    exit();
}

$row = mysqli_fetch_assoc($fetch_res);

// Update submission handle karna
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $designation = mysqli_real_escape_string($conn, trim($_POST['designation']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    
    $image_name = $row['image']; // By default purani image ka naam rakhenge

    // Agar nayi image upload ki gayi hai
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            // Naya unique naam file ke liye
            $new_image_name = time() . '_' . rand(1000, 9999) . '.' . $file_extension;
            $upload_path = "assets/img/uploads/" . $new_image_name;
            
            if (!is_dir("assets/img/uploads/")) {
                mkdir("assets/img/uploads/", 0777, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Purani file delete karna agar exist karti ho aur empty na ho
                if (!empty($row['image']) && file_exists("assets/img/uploads/" . $row['image'])) {
                    unlink("assets/img/uploads/" . $row['image']);
                }
                $image_name = $new_image_name; // Naya naam DB query ke liye
            } else {
                $msg = "Failed to upload new image.";
                $msg_class = "alert-danger";
            }
        } else {
            $msg = "Invalid image format! Only JPG, JPEG, PNG, and WEBP are allowed.";
            $msg_class = "alert-danger";
        }
    }

    // Agar koi file validation error nahi aayi, toh update karein
    if (empty($msg)) {
        if (!empty($name) && !empty($message)) {
            $update_query = "UPDATE `testimonials` 
                             SET `name` = '$name', `designation` = '$designation', `image` = '$image_name', `message` = '$message', `status` = '$status' 
                             WHERE `test_id` = '$test_id'";
            
            if (mysqli_query($conn, $update_query)) {
                $msg = "Testimonial updated successfully!";
                $msg_class = "alert-success";
                
                // Form data ko refresh karne ke liye firse query chalana
                $fetch_res = mysqli_query($conn, $fetch_sql);
                $row = mysqli_fetch_assoc($fetch_res);
            } else {
                $msg = "Database Error: " . mysqli_error($conn);
                $msg_class = "alert-danger";
            }
        } else {
            $msg = "Name and Message fields are required!";
            $msg_class = "alert-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Testimonial | Admin Panel</title>
    <link class="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .form-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .current-img, .preview-img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
            margin-top: 10px;
        }
        .preview-img {
            display: none;
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
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-12">
                        <div class="white_card mb_30 form-card">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="mb-0 fw-bold">Edit Testimonial</h2>
                                        <p class="text-muted mb-0 small">Update user feedback details (ID: #<?= htmlspecialchars($row['test_id']) ?>)</p>
                                    </div>
                                    <a href="testimonials.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to List
                                    </a>
                                </div>
                            </div>

                            <div class="white_card_body py-4">
                                <?php if (!empty($msg)): ?>
                                    <div class="alert <?= $msg_class ?> alert-dismissible fade show" role="alert">
                                        <?= $msg ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Client/User Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Designation / Company</label>
                                            <input type="text" class="form-control" name="designation" value="<?= htmlspecialchars($row['designation'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label fw-bold">Change Image</label>
                                            <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
                                            <small class="text-muted">Leave empty to keep the current image.</small>
                                            
                                            <div class="d-flex align-items-center gap-4 flex-wrap mt-2">
                                                <div>
                                                    <span class="d-block small text-muted">Current Image:</span>
                                                    <?php if (!empty($row['image']) && file_exists("assets/img/uploads/" . $row['image'])): ?>
                                                        <img class="current-img" src="assets/img/uploads/<?= htmlspecialchars($row['image']) ?>" alt="Current">
                                                    <?php else: ?>
                                                        <img class="current-img" src="assets/img/uploads/default-user.png" alt="Default">
                                                    <?php endif; ?>
                                                </div>
                                                <div id="previewContainer">
                                                    <span class="d-block small text-muted">New Preview:</span>
                                                    <img id="imagePreview" class="preview-img" src="#" alt="New Preview">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= $row['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Message / Review <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message" rows="5" required><?= htmlspecialchars($row['message']) ?></textarea>
                                    </div>

                                    <div class="text-end">
                                        <a href="testimonials.php" class="btn btn-light me-2">Cancel</a>
                                        <button type="submit" name="update" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Update Testimonial
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "footer.php"; ?>

    <script>
        // Real-time live preview for new image selection
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        });
    </script>
</body>

</html>