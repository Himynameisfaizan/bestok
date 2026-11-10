<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database central connection mapping core
include "db-conn.php"; 

$msg = "";
$msg_class = "";

// 🛠️ AUTOMATION AUTO-PATCH: Automatically ensure new column spaces exist inside contacts table schema
$check_phone2 = $conn->query("SHOW COLUMNS FROM `contacts` LIKE 'phone2'");
if ($check_phone2 && $check_phone2->num_rows == 0) {
    $conn->query("ALTER TABLE `contacts` ADD `phone2` VARCHAR(20) DEFAULT NULL AFTER `phone`");
}
$check_youtube = $conn->query("SHOW COLUMNS FROM `contacts` LIKE 'youtube'");
if ($check_youtube && $check_youtube->num_rows == 0) {
    $conn->query("ALTER TABLE `contacts` ADD `youtube` VARCHAR(255) DEFAULT NULL AFTER `linkdin`");
}
$check_email2 = $conn->query("SHOW COLUMNS FROM `contacts` LIKE 'email2'");
if ($check_email2 && $check_email2->num_rows == 0) {
    $conn->query("ALTER TABLE `contacts` ADD `email2` VARCHAR(100) DEFAULT NULL AFTER `email`");
}
$check_email3 = $conn->query("SHOW COLUMNS FROM `contacts` LIKE 'email3'");
if ($check_email3 && $check_email3->num_rows == 0) {
    $conn->query("ALTER TABLE `contacts` ADD `email3` VARCHAR(100) DEFAULT NULL AFTER `email2`");
}
// 🎯 NEW COPYRIGHT EXTENSION PATCH
$check_copyright = $conn->query("SHOW COLUMNS FROM `contacts` LIKE 'copyright_text'");
if ($check_copyright && $check_copyright->num_rows == 0) {
    $conn->query("ALTER TABLE `contacts` ADD `copyright_text` TEXT DEFAULT NULL AFTER `contact_email`");
}

// Handle form submission transactions pipeline
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and cleanly sanitize incoming user form node values
    $address        = mysqli_real_escape_string($conn, trim($_POST['address']));
    $address2       = mysqli_real_escape_string($conn, trim($_POST['address2']));
    $phone          = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $phone2         = mysqli_real_escape_string($conn, trim($_POST['phone2']));
    $wp_number      = mysqli_real_escape_string($conn, trim($_POST['wp_number']));
    $telephone      = mysqli_real_escape_string($conn, trim($_POST['telephone']));
    $email          = mysqli_real_escape_string($conn, trim($_POST['email']));
    $email2         = mysqli_real_escape_string($conn, trim($_POST['email2'])); 
    $email3         = mysqli_real_escape_string($conn, trim($_POST['email3'])); 
    $working_hours  = mysqli_real_escape_string($conn, trim($_POST['working_hours'] ?? ''));
    $facebook       = mysqli_real_escape_string($conn, trim($_POST['facebook'] ?? ''));
    $instagram      = mysqli_real_escape_string($conn, trim($_POST['instagram'] ?? ''));
    $twitter        = mysqli_real_escape_string($conn, trim($_POST['twitter'] ?? ''));
    $linkdin        = mysqli_real_escape_string($conn, trim($_POST['linkdin'] ?? ''));
    $youtube        = mysqli_real_escape_string($conn, trim($_POST['youtube'] ?? ''));
    $contact_email  = mysqli_real_escape_string($conn, trim($_POST['contact_email'] ?? ''));
    $copyright_text = mysqli_real_escape_string($conn, trim($_POST['copyright_text'] ?? ''));

    // 🎯 CRITICAL MAP FIX: Stripping quotes injection escape slashes before committing to DB storage
    $raw_map_input  = $_POST['map'] ?? '';
    $pure_map_code  = stripslashes(trim($raw_map_input));
    $map            = mysqli_real_escape_string($conn, $pure_map_code);

    // Fetch checking flag to verify if a contact row configuration index matrix is already present
    $check = $conn->query("SELECT `id` FROM `contacts` LIMIT 1");

    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $contact_id = $row['id'];

        // Upgraded Update Query Binding System Including 3 Emails, YouTube & Copyright Text
        $stmt = $conn->prepare("UPDATE `contacts` 
            SET `address` = ?, `address2` = ?, `phone` = ?, `phone2` = ?, `wp_number` = ?, `telephone` = ?, 
                `email` = ?, `email2` = ?, `email3` = ?, `working_hours` = ?, `facebook` = ?, `instagram` = ?, `twitter` = ?, `linkdin` = ?, `youtube` = ?,
                `map` = ?, `contact_email` = ?, `copyright_text` = ?, `updated_at` = NOW()
            WHERE `id` = ?");

        $stmt->bind_param(
            'ssssssssssssssssssi', 
            $address, $address2, $phone, $phone2, $wp_number, $telephone, 
            $email, $email2, $email3, $working_hours, $facebook, $instagram, $twitter, $linkdin, $youtube,
            $map, $contact_email, $copyright_text, $contact_id
        );
    } else {
        // Upgraded Insert New Fallback Record Matrix
        $stmt = $conn->prepare("INSERT INTO `contacts` 
            (`address`, `address2`, `phone`, `phone2`, `wp_number`, `telephone`, `email`, `email2`, `email3`, `working_hours`, `facebook`, `instagram`, `twitter`, `linkdin`, `youtube`, `map`, `contact_email`, `copyright_text`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            'ssssssssssssssssss', 
            $address, $address2, $phone, $phone2, $wp_number, $telephone, 
            $email, $email2, $email3, $working_hours, $facebook, $instagram, $twitter, $linkdin, $youtube,
            $map, $contact_email, $copyright_text
        );
    }

    // Execute statement wrapper with dynamic validation notices
    if ($stmt && $stmt->execute()) {
        $_SESSION['success_message'] = "Contact configuration matrices and system nodes updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating database entry: " . ($stmt ? $stmt->error : $conn->error);
    }
}

// Fetch existing data rows safely
$result = $conn->query("SELECT * FROM `contacts` LIMIT 1");
$data = $result ? $result->fetch_assoc() : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Contact & Media Information | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include "links.php"; ?>
    
    <style>
        .contact-form-container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .section-title {
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        textarea.form-control {
            min-height: 100px;
        }
        .btn-submit {
            background-color: #4e73df;
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background-color: #3a5bc7;
            transform: translateY(-2px);
        }
        .social-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 16px;
        }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon input {
            padding-left: 42px;
        }
        .preview-map {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            background: #f9f9f9;
        }
        .preview-map iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 5px;
        }
        .info-text {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>

<body class="crm_body_bg">

    <?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>

        <div class="main_content_iner">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h2 class="m-0">Contact Information Management</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <?php if(isset($_SESSION['success_message'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($_SESSION['error_message'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="contact-form-container">
                                    <form action="" method="post">
                                        <h4 class="section-title"><i class="fas fa-map-marker-alt me-2"></i> Address Information</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Primary Address</label>
                                                <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($data['address'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Secondary Address (Optional)</label>
                                                <textarea name="address2" class="form-control" rows="3"><?php echo htmlspecialchars($data['address2'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <h4 class="section-title"><i class="fas fa-phone me-2"></i> Contact Numbers</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Primary Phone <span class="text-danger">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-mobile-alt social-icon text-primary"></i>
                                                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Secondary Phone (Optional)</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-phone-alt social-icon text-info"></i>
                                                    <input type="text" class="form-control" name="phone2" placeholder="Alternate phone" value="<?php echo htmlspecialchars($data['phone2'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">WhatsApp Number</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-whatsapp social-icon text-success"></i>
                                                    <input type="text" class="form-control" name="wp_number" value="<?php echo htmlspecialchars($data['wp_number'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Telephone Number</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-tty social-icon text-secondary"></i>
                                                    <input type="text" class="form-control" name="telephone" value="<?php echo htmlspecialchars($data['telephone'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4 class="section-title"><i class="fas fa-envelope me-2"></i> Email Information</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Primary Email Address <span class="text-danger">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-envelope social-icon text-primary"></i>
                                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Secondary Email Address (Optional)</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-envelope-open social-icon text-info"></i>
                                                    <input type="email" class="form-control" name="email2" placeholder="Second Email ID" value="<?php echo htmlspecialchars($data['email2'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Third Email Address (Optional)</label>
                                                <div class="input-with-icon">
                                                    <i class="far fa-envelope social-icon text-secondary"></i>
                                                    <input type="email" class="form-control" name="email3" placeholder="Third Email ID" value="<?php echo htmlspecialchars($data['email3'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-2">
                                                <label class="form-label">Contact Form Receiver Email (Backend Admin Notification Receiver)</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-mail-bulk social-icon text-warning"></i>
                                                    <input type="email" class="form-control" name="contact_email" placeholder="Email where you want to receive inquiries" value="<?php echo htmlspecialchars($data['contact_email'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4 class="section-title"><i class="fas fa-clock me-2"></i> Business Hours & Footer Data</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Working Hours</label>
                                                <input type="text" class="form-control" name="working_hours" placeholder="e.g., Monday-Friday: 9:00 AM - 6:00 PM" value="<?php echo htmlspecialchars($data['working_hours'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Website Copyright Statement</label>
                                                <input type="text" class="form-control" name="copyright_text" placeholder="© Copyright 2026 Bestok Enterprises..." value="<?php echo htmlspecialchars($data['copyright_text'] ?? '© Copyright 2026 Bestok Enterprises Pvt. Ltd. All Rights Reserved Design & Developed by Digital Web Trackers.'); ?>">
                                            </div>
                                        </div>
                                        
                                        <h4 class="section-title"><i class="fas fa-share-alt me-2"></i> Social Media Links</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Facebook</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-facebook-f social-icon text-primary"></i>
                                                    <input type="url" class="form-control" name="facebook" placeholder="https://facebook.com/yourpage" value="<?php echo htmlspecialchars($data['facebook'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Instagram</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-instagram social-icon text-danger"></i>
                                                    <input type="url" class="form-control" name="instagram" placeholder="https://instagram.com/yourpage" value="<?php echo htmlspecialchars($data['instagram'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Twitter</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-twitter social-icon text-info"></i>
                                                    <input type="url" class="form-control" name="twitter" placeholder="https://twitter.com/yourpage" value="<?php echo htmlspecialchars($data['twitter'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">LinkedIn</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-linkedin-in social-icon text-primary"></i>
                                                    <input type="url" class="form-control" name="linkdin" placeholder="https://linkedin.com/yourpage" value="<?php echo htmlspecialchars($data['linkdin'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">YouTube</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-youtube social-icon text-danger"></i>
                                                    <input type="url" class="form-control" name="youtube" placeholder="https://youtube.com/@yourchannel" value="<?php echo htmlspecialchars($data['youtube'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <h4 class="section-title"><i class="fas fa-map-marked-alt me-2"></i> Location Map</h4>
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <label class="form-label">Google Map Embed Code</label>
                                                <textarea name="map" class="form-control" rows="4" placeholder="Paste the full iframe code from Google Maps"><?php echo htmlspecialchars(stripslashes($data['map'] ?? '')); ?></textarea>
                                                <p class="info-text">Get the embed code from Google Maps > Share > Embed a map</p>
                                                
                                                <?php if(!empty($data['map'])): ?>
                                                <div class="preview-map">
                                                    <h6><i class="fas fa-eye me-2"></i>Map Preview</h6>
                                                    <?php echo htmlspecialchars_decode(stripslashes($data['map'])); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary btn-submit px-4">
                                                <i class="fas fa-save me-2"></i> Update Settings Framework
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = bootstrap.Alert.getInstance(alert);
                    if (bsAlert) { bsAlert.close(); }
                });
            }, 5000);
        });
    </script>
</body>
</html>