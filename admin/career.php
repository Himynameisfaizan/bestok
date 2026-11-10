<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database central connection mapping core
include "db-conn.php"; 

$msg = "";
$msg_class = "";

// 🎯 1. HANDLE ACTION TERMINATION (DELETE OPERATION ROUTE)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = (int)$_GET['id'];
    
    $delete_query = "DELETE FROM `career` WHERE `id` = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success_message'] = "Application record successfully cleared from repository.";
        header("Location: career.php");
        exit();
    } else {
        $msg = "Deletion Error: " . mysqli_error($conn);
        $msg_class = "alert-danger";
    }
}

// Fetch existing data rows safely sorted by latest arrivals
$result = $conn->query("SELECT * FROM `career` ORDER BY `id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Career Inquiries | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include "links.php"; ?>
    
    <style>
        .table-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }
        .details-bubble {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }
        .badge-budget {
            background-color: #e0fcff;
            color: #2b6cb0;
            font-weight: 500;
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
                                        <h2 class="m-0"><i class="fas fa-briefcase me-2 text-primary"></i> Career & Quote Submissions</h2>
                                        <p class="text-muted small mb-0 mt-1">Manage and track dynamic submissions sent via the web career section form portal.</p>
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

                                <?php if(!empty($msg)): ?>
                                    <div class="alert <?= $msg_class ?> alert-dismissible fade show" role="alert">
                                        <?= $msg ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="table-card">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="5%">S.No</th>
                                                    <th scope="col" width="15%">Applicant Name</th>
                                                    <th scope="col" width="20%">Communication Details</th>
                                                    <th scope="col" width="10%">Pin Code</th>
                                                    <th scope="col" width="12%">Budget</th>
                                                    <th scope="col" width="23%">Requirements Details</th>
                                                    <th scope="col" width="10%">Date Received</th>
                                                    <th scope="col" width="5%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if ($result && $result->num_rows > 0) {
                                                    $count = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td><strong><?= $count++ ?></strong></td>
                                                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['name']) ?></td>
                                                            <td>
                                                                <div class="small"><i class="fas fa-envelope text-muted me-1"></i> <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></div>
                                                                <div class="small mt-1"><i class="fas fa-phone text-muted me-1"></i> <a href="tel:<?= htmlspecialchars($row['mobile']) ?>"><?= htmlspecialchars($row['mobile']) ?></a></div>
                                                            </td>
                                                            <td><span class="text-muted"><?= htmlspecialchars($row['pincode'] ?? 'N/A') ?></span></td>
                                                            <td><span class="badge badge-budget p-2 rounded"><?= htmlspecialchars($row['budget'] ?? 'N/A') ?></span></td>
                                                            <td>
                                                                <div class="details-bubble text-muted" title="<?= htmlspecialchars($row['details']) ?>">
                                                                    <?= htmlspecialchars($row['details']) ?>
                                                                </div>
                                                            </td>
                                                            <td class="small text-secondary"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                                                            <td class="text-center">
                                                                <a href="career.php?action=delete&id=<?= $row['id'] ?>" 
                                                                   class="btn btn-outline-danger btn-sm rounded-circle" 
                                                                   onclick="return confirm('Bhai, kya aap sach me is submission entry ko delete karna chahte hain?');"
                                                                   title="Delete Entry">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4 text-muted">
                                                            <i class="fas fa-folder-open fa-2x d-block mb-2 text-light"></i>
                                                            No record application strings logged into the repository yet.
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
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
            // Auto close notifications engine
            setTimeout(function() {
                var alertElements = document.querySelectorAll('.alert');
                alertElements.forEach(function(alert) {
                    var bsAlert = bootstrap.Alert.getInstance(alert);
                    if (bsAlert) { bsAlert.close(); }
                });
            }, 6000);
        });
    </script>
</body>
</html>