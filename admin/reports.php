<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Library Reports - LMS';

// Filter
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

$query = "SELECT ib.*, b.title, b.isbn, s.name as student_name 
          FROM issued_books ib 
          JOIN books b ON ib.book_id = b.id 
          JOIN students s ON ib.student_id = s.id";

if ($status == 'issued') {
    $query .= " WHERE ib.status = 'issued'";
} elseif ($status == 'returned') {
    $query .= " WHERE ib.status = 'returned'";
} elseif ($status == 'overdue') {
    $query .= " WHERE ib.status = 'issued' AND ib.return_date < CURDATE()";
}

$query .= " ORDER BY ib.issue_date DESC";
$reports = $pdo->query($query)->fetchAll();

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div id="content">
    <div class="topbar">
        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
            <i class="bi bi-list"></i>
        </button>
        <span class="text-muted">Reports</span>
    </div>

    <div class="container-fluid">
        <h2 class="fw-bold mb-4">Library Reports</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status == 'all' ? 'active' : ''; ?>" href="reports.php?status=all">All Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status == 'issued' ? 'active' : ''; ?>" href="reports.php?status=issued">Currently Issued</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status == 'returned' ? 'active' : ''; ?>" href="reports.php?status=returned">Returned History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $status == 'overdue' ? 'active' : ''; ?>" href="reports.php?status=overdue">Overdue Books</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Issue Date</th>
                                <th>Student</th>
                                <th>Book Title</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reports)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">No records found for this filter.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reports as $row): 
                                    $is_overdue = ($row['status'] == 'issued' && $row['return_date'] < date('Y-m-d'));
                                ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($row['issue_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td>
                                            <span class="<?php echo $is_overdue ? 'text-danger fw-bold' : ''; ?>">
                                                <?php echo date('M d, Y', strtotime($row['return_date'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 'returned'): ?>
                                                <span class="badge bg-success">Returned</span>
                                            <?php elseif ($is_overdue): ?>
                                                <span class="badge bg-danger">Overdue</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Issued</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-3 text-end">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i> Print Report</button>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
