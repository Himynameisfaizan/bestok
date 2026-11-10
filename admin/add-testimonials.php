<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";

$msg = "";
$msg_class = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $designation = mysqli_real_escape_string($conn, trim($_POST['designation']));
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    // Image Upload Logic
   // Image Upload Logic
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            // Unique image name generate karna
            $image_name = time() . '_' . rand(1000, 9999) . '.' . $file_extension;
            $upload_path = "assets/img/uploads/" . $image_name;
            
            // Upload directory check aur create karna agar exist nahi karti
            if (!is_dir("assets/img/uploads/")) {
                mkdir("assets/img/uploads/", 0777, true);
            }

            // Sahi PHP function (move_uploaded_file)
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $msg = "Failed to upload image to server directory.";
                $msg_class = "alert-danger";
            }
        } else {
            $msg = "Invalid image format! Only JPG, JPEG, PNG, and WEBP are allowed.";
            $msg_class = "alert-danger";
        }
    }

    // Agar koi validation error nahi hai toh insert karein
    if (empty($msg)) {
        if (!empty($name) && !empty($message)) {
            $insert_query = "INSERT INTO `testimonials` (`name`, `designation`, `image`, `message`, `status`) 
                             VALUES ('$name', '$designation', '$image_name', '$message', '$status')";
            
            if (mysqli_query($conn, $insert_query)) {
                $msg = "Testimonial added successfully!";
                $msg_class = "alert-success";
                // Form fields reset karne ke liye variables clear karna
                $name = $designation = $message = "";
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
    <title>Add Testimonial | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .form-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .table thead th {
            background-color: #2c3e50;
            color: white;
        }
        .preview-img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 50%;
            display: none;
            margin-top: 10px;
            border: 2px solid #ddd;
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
                                        <h2 class="mb-0 fw-bold">Add New Testimonial</h2>
                                        <p class="text-muted mb-0 small">Create a new client review for your website</p>
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
                                            <input type="text" class="form-control" name="name" placeholder="e.g. Rahul Sharma" value="<?= htmlspecialchars($name ?? '') ?>" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Designation / Company</label>
                                            <input type="text" class="form-control" name="designation" placeholder="e.g. CEO, Sharma Steels" value="<?= htmlspecialchars($designation ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label fw-bold">User Image</label>
                                            <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
                                            <small class="text-muted">Recommended size: Square (e.g., 200x200 px). Formats: JPG, PNG, WebP</small>
                                            <br>
                                            <img id="imagePreview" class="preview-img" src="#" alt="Image Preview">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Message / Review <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message" rows="5" placeholder="Write the testimonial message here..." required><?= htmlspecialchars($message ?? '') ?></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="reset" class="btn btn-light me-2">Reset</button>
                                        <button type="submit" name="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-save me-2"></i>Save Testimonial
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
        // Image preview logic
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