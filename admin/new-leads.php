<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database central connection mapping core
include "db-conn.php"; 

$msg = "";
$msg_class = "";

// ==========================================
// 🛠️ BACKEND TRANSACTION ROUTER NODE
// ==========================================

// 1. SINGLE OPERATION ACTION HANDLERS
if (isset($_GET['action'])) {
    $action = trim($_GET['action']);
    
    // Action Node: Mark Single Inquiry as Read
    if ($action == 'mark_read' && isset($_GET['id'])) {
        $target_id = (int)$_GET['id'];
        $update_stmt = $conn->prepare("UPDATE `inquiries` SET `status` = 1, `read_at` = NOW() WHERE `id` = ?");
        $update_stmt->bind_param('i', $target_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['flash_msg'] = "Inquiry marked as read successfully.";
            $_SESSION['flash_class'] = "alert-success";
        } else {
            $_SESSION['flash_msg'] = "Database Error: Unable to modify entry status.";
            $_SESSION['flash_class'] = "alert-danger";
        }
        header("Location: inquiries.php");
        exit();
    }

    // Action Node: Delete Single Inquiry Record
    if ($action == 'delete' && isset($_GET['id'])) {
        $target_id = (int)$_GET['id'];
        $delete_stmt = $conn->prepare("DELETE FROM `inquiries` WHERE `id` = ?");
        $delete_stmt->bind_param('i', $target_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['flash_msg'] = "Inquiry deleted permanently from records.";
            $_SESSION['flash_class'] = "alert-success";
        } else {
            $_SESSION['flash_msg'] = "Database Error: Deletion process failed.";
            $_SESSION['flash_class'] = "alert-danger";
        }
        header("Location: inquiries.php");
        exit();
    }

    // 2. BULK OPERATIONS ACTION HANDLERS
    if (isset($_GET['ids']) && !empty($_GET['ids'])) {
        // Sanitize incoming comma-separated IDs via cleaning integer map
        $raw_ids = explode(',', $_GET['ids']);
        $clean_ids_array = array_map('intval', $raw_ids);
        $ids_string = implode(',', $clean_ids_array);

        if (!empty($ids_string)) {
            // Action Node: Bulk Mark Selection as Read
            if ($action == 'bulk_read') {
                $bulk_query = "UPDATE `inquiries` SET `status` = 1, `read_at` = NOW() WHERE `id` IN ($ids_string)";
                if (mysqli_query($conn, $bulk_query)) {
                    $_SESSION['flash_msg'] = "Selected inquiries updated to read status.";
                    $_SESSION['flash_class'] = "alert-success";
                } else {
                    $_SESSION['flash_msg'] = "Bulk Update Error: Execution failed.";
                    $_SESSION['flash_class'] = "alert-danger";
                }
            }
            
            // Action Node: Bulk Delete Selection
            if ($action == 'bulk_delete') {
                $bulk_query = "DELETE FROM `inquiries` WHERE `id` IN ($ids_string)";
                if (mysqli_query($conn, $bulk_query)) {
                    $_SESSION['flash_msg'] = "Selected inquiries purged permanently.";
                    $_SESSION['flash_class'] = "alert-success";
                } else {
                    $_SESSION['flash_msg'] = "Bulk Deletion Error: Execution failed.";
                    $_SESSION['flash_class'] = "alert-danger";
                }
            }
            header("Location: inquiries.php");
            exit();
        }
    }
}

// Read alert messages from session lifecycle hooks
if (isset($_SESSION['flash_msg'])) {
    $msg = $_SESSION['flash_msg'];
    $msg_class = $_SESSION['flash_class'];
    unset($_SESSION['flash_msg']);
    unset($_SESSION['flash_class']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Customer Inquiries | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include "links.php"; ?>
    
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }
        .status-unread {
            background-color: rgba(255, 193, 7, 0.1);
            color: #d39e00;
            border: 1px solid #ffc107;
        }
        .status-read {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid #6c757d;
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .indicator-unread {
            background-color: #ffc107;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
        }
        .indicator-read {
            background-color: #6c757d;
            box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.2);
        }
        .customer-name {
            font-weight: 600;
            color: #0d6efd;
        }
        .customer-email {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .inquiry-subject {
            font-weight: 500;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
        .message-preview {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #495057;
            display: block;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .action-buttons .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        .filter-badge {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .filter-badge:hover {
            transform: translateY(-2px);
        }
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .message-cell {
            max-width: 250px;
            cursor: pointer;
        }
        .message-modal .modal-body {
            white-space: pre-wrap;
            line-height: 1.6;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
        }
        .bulk-actions {
            background-color: #f1f5f9;
            border-left: 4px solid #4e73df;
            border-radius: 6px;
            padding: 12px 20px;
            margin-bottom: 15px;
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table tbody tr.unread-row {
            background-color: rgba(13, 110, 253, 0.02) !important;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-state i {
            font-size: 48px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        .date-cell {
            font-size: 0.85rem;
            color: #6c757d;
            white-space: nowrap;
        }
        .stats-card {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .checkbox-cell {
            width: 40px;
            text-align: center;
        }
        .table-container {
            position: relative;
        }
        .loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            display: none;
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
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <h3 class="m-0"><i class="fas fa-envelope me-2 text-primary"></i>Customer Inquiries</h3>
                                            <p class="text-muted mb-0 mt-1">Manage all customer inquiries in one place</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-success text-white" onclick="printTable()">
                                                <i class="fas fa-print me-1"></i>Print Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="white_card_body">
                                
                                <?php if(!empty($msg)): ?>
                                    <div class="alert <?= $msg_class ?> alert-dismissible fade show" role="alert">
                                        <?= $msg ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="row mb-4">
                                    <?php
                                    // Fetch current counts from database schema indexes
                                    $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM `inquiries`"))['total'];
                                    $unread = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as unread FROM `inquiries` WHERE `status` = 0"))['unread'];
                                    $today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as today FROM `inquiries` WHERE DATE(`created_at`) = CURDATE()"))['today'];
                                    $week = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as week FROM `inquiries` WHERE YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1)"))['week'];
                                    ?>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="bg-primary bg-gradient text-white rounded p-3 me-3"><i class="fas fa-inbox"></i></div>
                                                <div>
                                                    <h6 class="text-muted mb-1">Total Inquiries</h6>
                                                    <h3 class="mb-0"><?= $total ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="bg-warning bg-gradient text-white rounded p-3 me-3"><i class="fas fa-envelope"></i></div>
                                                <div>
                                                    <h6 class="text-muted mb-1">Unread Inquiries</h6>
                                                    <h3 class="mb-0 text-warning"><?= $unread ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="bg-success bg-gradient text-white rounded p-3 me-3"><i class="fas fa-calendar-day"></i></div>
                                                <div>
                                                    <h6 class="text-muted mb-1">Received Today</h6>
                                                    <h3 class="mb-0 text-success"><?= $today ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="card stats-card border-0 shadow-sm h-100">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="bg-info bg-gradient text-white rounded p-3 me-3"><i class="fas fa-calendar-week"></i></div>
                                                <div>
                                                    <h6 class="text-muted mb-1">This Week</h6>
                                                    <h3 class="mb-0 text-info"><?= $week ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary filter-badge px-3 py-2" onclick="filterInquiries('all')"><i class="fas fa-list me-1"></i>All (<?= $total ?>)</span>
                                            <span class="badge bg-warning filter-badge px-3 py-2 text-dark" onclick="filterInquiries('unread')"><i class="fas fa-envelope-open me-1"></i>Unread (<?= $unread ?>)</span>
                                            <span class="badge bg-success filter-badge px-3 py-2" onclick="filterInquiries('today')"><i class="fas fa-calendar-day me-1"></i>Today (<?= $today ?>)</span>
                                            <span class="badge bg-info filter-badge px-3 py-2" onclick="filterInquiries('week')"><i class="fas fa-calendar-week me-1"></i>This Week (<?= $week ?>)</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex gap-2 justify-content-md-end justify-content-start mt-3 mt-md-0">
                                            <div class="input-group" style="max-width: 300px;">
                                                <input type="text" id="searchInput" class="form-control" placeholder="Search parameters...">
                                                <button class="btn btn-primary" type="button" onclick="searchTable()"><i class="fas fa-search"></i></button>
                                            </div>
                                            <select class="form-select" style="max-width: 160px;" id="sortSelect" onchange="sortTable()">
                                                <option value="newest">Newest First</option>
                                                <option value="oldest">Oldest First</option>
                                                <option value="name">Name A-Z</option>
                                                <option value="unread">Unread First</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="bulk-actions mb-3" style="display: none;" id="bulkActions">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div><i class="fas fa-info-circle me-1 text-primary"></i> <span id="selectedCount" class="fw-bold text-dark">0</span> inquiries checked selection.</div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-success text-white" onclick="markSelectedAsRead()"><i class="fas fa-check me-1"></i>Mark Read</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteSelected()"><i class="fas fa-trash me-1"></i>Delete Selection</button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-container">
                                    <div class="loading-overlay" id="loadingOverlay"><div class="spinner-border text-primary" role="status"></div></div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="inquiriesTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th scope="col" class="checkbox-cell">
                                                        <div class="form-check d-flex justify-content-center">
                                                            <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                                        </div>
                                                    </th>
                                                    <th scope="col" width="5%">#</th>
                                                    <th scope="col" width="20%">Customer</th>
                                                    <th scope="col" width="18%">Contact Channel</th>
                                                    <th scope="col" width="15%">Subject / City</th>
                                                    <th scope="col" width="22%">Message Content</th>
                                                    <th scope="col" width="10%">Status</th>
                                                    <th scope="col" width="10%">Date</th>
                                                    <th scope="col" width="10%" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sno = 1;
                                                // Default sorting execution pipeline array load
                                                $sql = "SELECT * FROM `inquiries` ORDER BY `created_at` DESC";
                                                $result = mysqli_query($conn, $sql);
                                                
                                                if($result && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $statusClass = $row['status'] == 0 ? 'status-unread' : 'status-read';
                                                        $indicatorClass = $row['status'] == 0 ? 'indicator-unread' : 'indicator-read';
                                                        $statusText = $row['status'] == 0 ? 'Unread' : 'Read';
                                                        $rowClass = $row['status'] == 0 ? 'unread-row' : '';
                                                        
                                                        $rawMessage = !empty($row['message']) ? $row['message'] : 'No message string passed.';
                                                        $messagePreview = htmlspecialchars($rawMessage);
                                                        $isLongMessage = strlen($messagePreview) > 45;
                                                        $shortMessage = $isLongMessage ? substr($messagePreview, 0, 42) . '...' : $messagePreview;
                                                        ?>
                                                        <tr class="inquiry-row <?= $rowClass ?>" 
                                                            data-id="<?= $row['id'] ?>"
                                                            data-status="<?= $row['status'] ?>"
                                                            data-date="<?= $row['created_at'] ?>"
                                                            data-name="<?= strtolower(htmlspecialchars($row['name'])) ?>"
                                                            data-email="<?= strtolower(htmlspecialchars($row['email'])) ?>"
                                                            data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                                            data-subject="<?= strtolower(htmlspecialchars($row['subject'])) ?>"
                                                            data-message="<?= htmlspecialchars($rawMessage) ?>">
                                                            
                                                            <td class="checkbox-cell">
                                                                <div class="form-check d-flex justify-content-center">
                                                                    <input class="form-check-input row-checkbox" type="checkbox" value="<?= $row['id'] ?>" onclick="updateBulkActions()">
                                                                </div>
                                                            </td>
                                                            <td><strong><?= $sno++ ?></strong></td>
                                                            <td><span class="customer-name"><?= htmlspecialchars($row['name']) ?></span></td>
                                                            <td>
                                                                <div class="customer-email mb-1"><i class="far fa-envelope me-1"></i><a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></div>
                                                                <div class="small text-dark fw-medium"><i class="fas fa-phone me-1 text-muted"></i><a href="tel:<?= htmlspecialchars($row['phone']) ?>"><?= htmlspecialchars($row['phone']) ?></a></div>
                                                            </td>
                                                            <td><span class="inquiry-subject" title="<?= htmlspecialchars($row['subject']) ?>"><?= htmlspecialchars($row['subject']) ?></span></td>
                                                            <td class="message-cell" onclick="showMessageModal('<?= addslashes(htmlspecialchars($rawMessage)) ?>', '<?= addslashes(htmlspecialchars($row['name'])) ?>', <?= $row['id'] ?>, <?= $row['status'] ?>)">
                                                                <span class="message-preview"><?= $shortMessage ?></span>
                                                                <?php if($isLongMessage): ?>
                                                                    <small class="text-primary d-inline-flex align-items-center mt-1"><i class="fas fa-expand-alt me-1"></i> Read More</small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <span class="status-badge <?= $statusClass ?>">
                                                                    <span class="status-indicator <?= $indicatorClass ?>"></span><?= $statusText ?>
                                                                </span>
                                                            </td>
                                                            <td class="date-cell">
                                                                <i class="far fa-clock me-1 text-muted"></i><?= date('d M Y', strtotime($row['created_at'])) ?><br>
                                                                <small class="text-muted"><?= date('h:i A', strtotime($row['created_at'])) ?></small>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="action-buttons">
                                                                    <?php if($row['status'] == 0): ?>
                                                                        <button class="btn btn-sm btn-success text-white" onclick="markAsRead(<?= $row['id'] ?>)" title="Mark as Read"><i class="fas fa-check"></i></button>
                                                                    <?php endif; ?>
                                                                    <button class="btn btn-sm btn-info text-white" onclick="showMessageModal('<?= addslashes(htmlspecialchars($rawMessage)) ?>', '<?= addslashes(htmlspecialchars($row['name'])) ?>', <?= $row['id'] ?>, <?= $row['status'] ?>)" title="View Details"><i class="fas fa-eye"></i></button>
                                                                    <button class="btn btn-sm btn-primary" onclick="respondToInquiry(<?= $row['id'] ?>)" title="Reply via Email"><i class="fas fa-reply"></i></button>
                                                                    <button class="btn btn-sm btn-danger" onclick="deleteInquiry(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>')" title="Delete permanent"><i class="fas fa-trash"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="9" class="empty-state">
                                                            <i class="fas fa-inbox fa-4x mb-4 text-light"></i>
                                                            <h4 class="text-muted">No Inquiries Registered Yet</h4>
                                                            <p class="text-muted mb-0">Customer sidebar entry data stream returns completely empty.</p>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
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

    <div class="modal fade message-modal" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message from <span id="customerName" class="text-primary fw-bold"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="checkReloadFlag()"></button>
                </div>
                <div class="modal-body text-dark" id="messageContent" style="font-size: 15px;"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="checkReloadFlag()">Close</button>
                    <button type="button" class="btn class-btn btn-primary" onclick="copyMessage()"><i class="fas fa-copy me-1"></i>Copy Message</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let selectedInquiries = new Set();
    let shouldReloadOnClose = false;

    // 🎯 FIX NODE: JavaScript Router Redirection Engine mapped to SELF context execution endpoints
    function markAsRead(id) {
        if(confirm('Bhai, kya aap is inquiry ko read mark karna chahte hain?')) {
            showLoading(true);
            window.location.href = 'inquiries.php?action=mark_read&id=' + id;
        }
    }

    function deleteInquiry(id, name) {
        if(confirm(`Are you sure you want to delete inquiry from "${name}" permanent?`)) {
            showLoading(true);
            window.location.href = 'inquiries.php?action=delete&id=' + id;
        }
    }

    function markSelectedAsRead() {
        if(selectedInquiries.size === 0) return;
        if(confirm(`Mark ${selectedInquiries.size} checked entry logs as read?`)) {
            showLoading(true);
            const ids = Array.from(selectedInquiries).join(',');
            window.location.href = 'inquiries.php?action=bulk_read&ids=' + ids;
        }
    }

    // Dynamic Bulk Delete Routing Engine
    function deleteSelected() {
        if(selectedInquiries.size === 0) return;
        if(confirm(`Delete ${selectedInquiries.size} selected items permanently? This action is irreversible.`)) {
            showLoading(true);
            const ids = Array.from(selectedInquiries).join(',');
            window.location.href = 'inquiries.php?action=bulk_delete&ids=' + ids;
        }
    }

    // 🎯 MODAL MANAGER INTEGRATION: Automatically updates read state via asynchronous background trigger if unread
    function showMessageModal(message, customerName, id, currentStatus) {
        document.getElementById('customerName').textContent = customerName;
        document.getElementById('messageContent').textContent = message;
        
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        modal.show();

        // If the opened message is completely unread, push update token quietly
        if (currentStatus === 0) {
            shouldReloadOnClose = true;
            fetch('inquiries.php?action=mark_read&id=' + id)
                .then(response => { console.log('Auto-read synchronized background trace.'); })
                .catch(err => { console.error(err); });
        }
    }

    function checkReloadFlag() {
        if(shouldReloadOnClose) {
            window.location.reload();
        }
    }

    // Real-Time Table Client Side Filtering Algorithms
    function filterInquiries(filter) {
        const rows = document.querySelectorAll('.inquiry-row');
        let visibleCount = 0;
        const todayStr = new Date().toISOString().split('T')[0];
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
        
        rows.forEach(row => {
            let show = false;
            const itemDate = new Date(row.dataset.date);
            
            if(filter === 'all') show = true;
            else if(filter === 'unread') show = row.dataset.status === '0';
            else if(filter === 'today') show = row.dataset.date.includes(todayStr);
            else if(filter === 'week') show = itemDate >= oneWeekAgo;
            
            row.style.display = show ? '' : 'none';
            if(show) visibleCount++;
        });
        document.getElementById('showingCount').textContent = visibleCount;
    }

    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.inquiry-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const matches = row.dataset.name.includes(searchTerm) || 
                            row.dataset.email.includes(searchTerm) || 
                            row.dataset.phone.includes(searchTerm) || 
                            row.dataset.subject.includes(searchTerm) || 
                            row.dataset.message.toLowerCase().includes(searchTerm);
            
            row.style.display = matches ? '' : 'none';
            if(matches) visibleCount++;
        });
        document.getElementById('showingCount').textContent = visibleCount;
    }

    function sortTable() {
        const sortBy = document.getElementById('sortSelect').value;
        const tbody = document.querySelector('#inquiriesTable tbody');
        const rows = Array.from(tbody.querySelectorAll('.inquiry-row'));
        if(rows.length === 0) return;
        
        rows.sort((a, b) => {
            if(sortBy === 'newest') return new Date(b.dataset.date) - new Date(a.dataset.date);
            if(sortBy === 'oldest') return new Date(a.dataset.date) - new Date(b.dataset.date);
            if(sortBy === 'name') return a.dataset.name.localeCompare(b.dataset.name);
            if(sortBy === 'unread') return parseInt(a.dataset.status) - parseInt(b.dataset.status);
        });
        rows.forEach(row => tbody.appendChild(row));
    }

    function respondToInquiry(id) {
        const row = document.querySelector(`.inquiry-row[data-id="${id}"]`);
        const name = row.querySelector('.customer-name').textContent;
        const email = row.dataset.email;
        const subject = row.querySelector('.inquiry-subject').textContent;
        
        window.location.href = `mailto:${email}?subject=Re: ${encodeURIComponent(subject)}&body=Dear ${encodeURIComponent(name)},\n\nThank you for reaching out to Bestok Industries.\n\n`;
    }

    function toggleSelectAll() {
        const masterBox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        
        checkboxes.forEach(cb => {
            cb.checked = masterBox.checked;
            if(masterBox.checked) selectedInquiries.add(cb.value);
            else selectedInquiries.delete(cb.value);
        });
        updateBulkActionsUI();
    }

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        selectedInquiries.clear();
        checkboxes.forEach(cb => selectedInquiries.add(cb.value));
        updateBulkActionsUI();
    }

    function updateBulkActionsUI() {
        document.getElementById('selectedCount').textContent = selectedInquiries.size;
        document.getElementById('bulkActions').style.display = selectedInquiries.size > 0 ? 'block' : 'none';
        
        const allBoxes = document.querySelectorAll('.row-checkbox').length;
        document.getElementById('selectAll').checked = selectedInquiries.size === allBoxes && allBoxes > 0;
    }

    function clearSelection() {
        selectedInquiries.clear();
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActionsUI();
    }

    function copyMessage() {
        const txt = document.getElementById('messageContent').textContent;
        navigator.clipboard.writeText(txt).then(() => { alert('Message text copied to clipboard panel!'); });
    }

    function printTable() {
        const printWin = window.open('', '_blank');
        printWin.document.write(`
            <html><head><title>Customer Inquiries Report</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top:20px; }
                th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-size:13px; }
                th { background-color: #f1f5f9; font-weight: bold; }
                .checkbox-cell, th:first-child, td:first-child { display: none !important; }
                .action-buttons, th:last-child, td:last-child { display: none !important; }
                h2 { margin-bottom: 5px; color:#1e293b; }
            </style></head>
            <body><h2>Customer Inquiries Matrix Report</h2><p>Generated Date: ${new Date().toLocaleString()}</p>
            ${document.getElementById('inquiriesTable').outerHTML}
            </body></html>
        `);
        printWin.document.close();
        printWin.print();
    }

    function showLoading(show) {
        document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
    }

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') searchTable();
    });
    </script>
</body>
</html>