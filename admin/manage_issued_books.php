<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Manage Issued Books - LMS';

// Fetch Issued Books (not returned)
$query = "SELECT ib.*, b.title, b.isbn, s.name as student_name, s.email as student_email 
          FROM issued_books ib 
          JOIN books b ON ib.book_id = b.id 
          JOIN students s ON ib.student_id = s.id 
          WHERE ib.status = 'issued' 
          ORDER BY ib.issue_date DESC";
$issued_books = $pdo->query($query)->fetchAll();

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div id="content">
    <div class="topbar">
        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
            <i class="bi bi-list"></i>
        </button>
        <span class="text-muted">Issued Books</span>
    </div>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Active Issues</h2>
            <a href="issue_book.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Issue New Book</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Book Title</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($issued_books)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No active issues found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($issued_books as $issue): 
                                    $today = date('Y-m-d');
                                    $is_overdue = ($issue['return_date'] < $today);
                                ?>
                                    <tr class="<?php echo $is_overdue ? 'table-danger-light' : ''; ?>">
                                        <td>
                                            <strong><?php echo htmlspecialchars($issue['student_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($issue['student_email']); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($issue['title']); ?></strong><br>
                                            <small class="text-muted">ISBN: <?php echo htmlspecialchars($issue['isbn']); ?></small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($issue['issue_date'])); ?></td>
                                        <td>
                                            <span class="<?php echo $is_overdue ? 'text-danger fw-bold' : ''; ?>">
                                                <?php echo date('M d, Y', strtotime($issue['return_date'])); ?>
                                            </span>
                                            <?php if ($is_overdue): ?>
                                                <span class="badge bg-danger ms-1">Overdue</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-warning text-dark">Issued</span></td>
                                        <td>
                                            <a href="return_book.php?id=<?php echo $issue['id']; ?>" class="btn btn-sm btn-primary" onclick="return confirm('Confirm book return?')">
                                                <i class="bi bi-arrow-return-left"></i> Mark Returned
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-danger-light {
        background-color: #fff5f5;
    }
</style>

<?php include('../includes/footer.php'); ?>
