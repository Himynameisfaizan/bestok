<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "db-conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Testimonials Management | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .testimonial-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box input {
            padding-left: 40px;
            border-radius: 20px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .user-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #eee;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 3px;
        }

        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 500;
        }

        .pagination .page-item.active .page-link {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        
        .msg-truncated {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                    <div class="col-12">
                        <div class="white_card mb_30">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mb-3 mb-md-0">
                                        <h2 class="mb-0 fw-bold">Testimonials Management</h2>
                                        <p class="text-muted mb-0 small">Manage your client reviews and feedback</p>
                                    </div>
                                    <div class="d-flex">
                                        <form method="GET" class="position-relative me-3">
                                            <input type="text" class="form-control" name="search"
                                                placeholder="Search testimonials..."
                                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                        </form>
                                        <a href="add-testimonials.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Add Testimonial
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <div class="QA_section">
                                    <div class="QA_table mb_30">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered align-middle text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ID</th>
                                                        <th>User Image</th>
                                                        <th>Name</th>
                                                        <th>Designation</th>
                                                        <th>Message</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sno = 1;
                                                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                                                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                                                    $perPage = 10;
                                                    $offset = ($page - 1) * $perPage;

                                                    // Change table name or columns according to your DB structure
                                                    $sql = "SELECT * FROM testimonials";
                                                    $countSql = "SELECT COUNT(*) as total FROM testimonials";

                                                    if (!empty($search)) {
                                                        $searchTerm = mysqli_real_escape_string($conn, $search);
                                                        $sql .= " WHERE name LIKE '%$searchTerm%' OR designation LIKE '%$searchTerm%' OR test_id LIKE '%$searchTerm%'";
                                                        $countSql .= " WHERE name LIKE '%$searchTerm%' OR designation LIKE '%$searchTerm%' OR test_id LIKE '%$searchTerm%'";
                                                    }

                                                    $sql .= " ORDER BY test_id DESC LIMIT $offset, $perPage";

                                                    $result = mysqli_query($conn, $sql);
                                                    $countResult = mysqli_query($conn, $countSql);
                                                    $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                                    $totalPages = ceil($totalRows / $perPage);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $status_text = $row['status'] == "1" ? "Active" : "Inactive";
                                                            $status_color = $row['status'] == "1" ? "text-success" : "text-danger";
                                                            $user_img = !empty($row['image']) ? $row['image'] : 'default-user.png';
                                                            ?>
                                                            <tr>
                                                                <td><?= $sno++ ?></td>
                                                                <td class="fw-bold">
                                                                    <?= htmlspecialchars($row['test_id']) ?>
                                                                </td>
                                                                <td>
                                                                    <img src="assets/img/uploads/<?= htmlspecialchars($user_img) ?>"
                                                                        alt="<?= htmlspecialchars($row['name']) ?>"
                                                                        class="user-img img-thumbnail">
                                                                </td>
                                                                <td class="fw-bold"><?= htmlspecialchars($row['name']) ?></td>
                                                                <td><?= htmlspecialchars($row['designation']) ?></td>
                                                                <td>
                                                                    <div class="msg-truncated" data-bs-toggle="tooltip" title="<?= htmlspecialchars($row['message']) ?>">
                                                                        <?= htmlspecialchars($row['message']) ?>
                                                                    </div>
                                                                </td>
                                                                <td class="<?= $status_color ?> fw-bold"><?= $status_text ?></td>
                                                                <td>
                                                                    <div class="d-flex justify-content-center">
                                                                        <a href="edit_testimonials.php?edit_testimonial_details=<?= $row['test_id'] ?>"
                                                                            class="btn btn-outline-info btn-sm me-2" data-bs-toggle="tooltip" title="Edit">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                       <a href="testimonial_delete.php?delete_testimonial_details=<?= $row['test_id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this testimonial?');" 
   class="btn btn-danger">
   Delete
</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="8" class="text-center text-muted py-4">No testimonials found</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <?php if ($totalPages > 1): ?>
                                            <div class="d-flex justify-content-between align-items-center mt-4">
                                                <div class="text-muted small">
                                                    Showing <?= $offset + 1 ?> to <?= min($offset + $perPage, $totalRows) ?>
                                                    of <?= $totalRows ?> entries
                                                </div>
                                                <nav>
                                                    <ul class="pagination mb-0">
                                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                                            <a class="page-link"
                                                                href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
                                                        </li>
                                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                                <a class="page-link"
                                                                    href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                                            </li>
                                                        <?php endfor; ?>
                                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                                            <a class="page-link"
                                                                href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "footer.php"; ?>

    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>