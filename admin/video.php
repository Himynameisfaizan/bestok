<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";

$msg = "";
$msg_class = "";

// --- 1. YouTube URL se Video ID nikalne ka Custom Function (UPDATED FOR SHORTS & ALL FORMATS) ---
function getYouTubeId($url) {
    $video_id = '';
    // Sabhi tarah ke YouTube URLs (Standard, Shorts, Mobile, Embed) ko handle karne ke liye advance regex
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
        $video_id = $match[1];
    }
    return $video_id;
}

// --- 2. Video Add karne ka Logic ---
if (isset($_POST['add_video'])) {
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $youtube_url = mysqli_real_escape_string($conn, trim($_POST['youtube_url']));
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    if (!empty($title) && !empty($youtube_url)) {
        $video_code = getYouTubeId($youtube_url);

        if (!empty($video_code)) {
            $insert_query = "INSERT INTO `videos` (`title`, `youtube_url`, `video_code`, `status`) 
                             VALUES ('$title', '$youtube_url', '$video_code', '$status')";
            
            if (mysqli_query($conn, $insert_query)) {
                $msg = "Video added successfully!";
                $msg_class = "alert-success";
            } else {
                $msg = "Database Error: " . mysqli_error($conn);
                $msg_class = "alert-danger";
            }
        } else {
            $msg = "Invalid YouTube URL! Please enter a valid YouTube video link.";
            $msg_class = "alert-danger";
        }
    } else {
        $msg = "All fields are required!";
        $msg_class = "alert-danger";
    }
}

// --- 3. Video Delete karne ka Logic ---
if (isset($_GET['delete_id'])) {
    $del_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM `videos` WHERE `video_id` = '$del_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        $msg = "Video deleted successfully!";
        $msg_class = "alert-success";
    } else {
        $msg = "Error deleting video: " . mysqli_error($conn);
        $msg_class = "alert-danger";
    }
}

// --- 4. Saved Videos Fetch karna ---
$fetch_query = "SELECT * FROM `videos` ORDER BY `video_id` DESC";
$all_videos = mysqli_query($conn, $fetch_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Videos | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .custom-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
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
                                <h3 class="mb-0 fw-bold">Add YouTube Video</h3>
                                <p class="text-muted small mb-0">Paste link to display video on website</p>
                            </div>
                            <div class="white_card_body py-3">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Video Title / Name</label>
                                        <input type="text" class="form-control" name="title" placeholder="e.g. Factory Inspection Video" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">YouTube URL / Link</label>
                                        <input type="url" class="form-control" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required>
                                        <small class="text-muted d-block mt-1">Supports standard, mobile, shorts, or share links.</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1">Active (Show)</option>
                                            <option value="0">Inactive (Hide)</option>
                                        </select>
                                    </div>

                                    <button type="submit" name="add_video" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i>Save & Publish
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-12">
                        <div class="white_card custom-card">
                            <div class="card-header bg-white border-0 pt-3">
                                <h3 class="mb-0 fw-bold">Gallery / Video List</h3>
                            </div>
                            <div class="white_card_body py-3">
                                <?php if (mysqli_num_rows($all_videos) > 0): ?>
                                    <div class="row">
                                        <?php while($video = mysqli_fetch_assoc($all_videos)): ?>
                                            <div class="col-md-6 col-12 mb-4">
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="video-container mb-2">
                                                        <iframe src="https://www.youtube.com/embed/<?= $video['video_code']; ?>" allowfullscreen></iframe>
                                                    </div>
                                                    
                                                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($video['title']); ?>">
                                                        <?= htmlspecialchars($video['title']); ?>
                                                    </h5>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                                        <div>
                                                            <?php if($video['status'] == 1): ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Hidden</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <a href="video.php?delete_id=<?= $video['video_id']; ?>" 
                                                           onclick="return confirm('Are you sure you want to delete this video?');" 
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
                                        <i class="fas fa-video fa-3x mb-3"></i>
                                        <p>No videos uploaded yet.</p>
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