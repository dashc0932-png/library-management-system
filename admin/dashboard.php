<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Admin Dashboard - LMS';

// Fetch Statistics
$total_books = $pdo->query("SELECT SUM(quantity) FROM books")->fetchColumn() ?: 0;
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn() ?: 0;
$issued_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'issued'")->fetchColumn() ?: 0;
$returned_books = $pdo->query("SELECT COUNT(*) FROM issued_books WHERE status = 'returned'")->fetchColumn() ?: 0;
$available_books = $pdo->query("SELECT SUM(available_quantity) FROM books")->fetchColumn() ?: 0;

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div id="content">
    <div class="topbar">
        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
            <i class="bi bi-list"></i>
        </button>
        <span class="text-muted">Welcome, <?php echo $_SESSION['admin_name']; ?></span>
    </div>

    <div class="container-fluid">
        <h2 class="mb-4 fw-bold">Dashboard Overview</h2>
        
        <div class="row">
            <!-- Total Books -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-start border-4 border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books (Stock)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_books; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-journal-bookmark fs-1 text-gray-300 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-start border-4 border-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_students; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people fs-1 text-gray-300 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Issued Books -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Currently Issued</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $issued_books; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-journal-check fs-1 text-gray-300 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Books -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100 border-start border-4 border-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Available in Library</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $available_books; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-journal-text fs-1 text-gray-300 opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <a href="add_book.php" class="btn btn-primary w-100 py-3">
                                    <i class="bi bi-plus-circle d-block fs-3 mb-2"></i> Add Book
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="issue_book.php" class="btn btn-success w-100 py-3">
                                    <i class="bi bi-journal-plus d-block fs-3 mb-2"></i> Issue Book
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="add_student.php" class="btn btn-info w-100 py-3 text-white">
                                    <i class="bi bi-person-plus d-block fs-3 mb-2"></i> Add Student
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="reports.php" class="btn btn-secondary w-100 py-3">
                                    <i class="bi bi-file-earmark-text d-block fs-3 mb-2"></i> View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">System Info</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Admin:</strong> <?php echo $_SESSION['admin_name']; ?></p>
                        <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i'); ?></p>
                        <p><strong>Database Status:</strong> <span class="badge bg-success">Online</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
