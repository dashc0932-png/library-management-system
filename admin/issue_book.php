<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Issue Book - LMS';
$error = '';

// Fetch Students
$students = $pdo->query("SELECT id, name, email FROM students ORDER BY name ASC")->fetchAll();

// Fetch Available Books
$books = $pdo->query("SELECT id, title, isbn, available_quantity FROM books WHERE available_quantity > 0 ORDER BY title ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = (int)$_POST['book_id'];
    $student_id = (int)$_POST['student_id'];
    $return_date = $_POST['return_date'];
    $issue_date = date('Y-m-d');

    if ($book_id && $student_id && $return_date) {
        // Double check availability
        $stmt = $pdo->prepare("SELECT available_quantity FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $avail = $stmt->fetchColumn();

        if ($avail > 0) {
            try {
                $pdo->beginTransaction();

                // 1. Insert into issued_books
                $stmt = $pdo->prepare("INSERT INTO issued_books (book_id, student_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, 'issued')");
                $stmt->execute([$book_id, $student_id, $issue_date, $return_date]);

                // 2. Decrease available quantity
                $stmt = $pdo->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE id = ?");
                $stmt->execute([$book_id]);

                $pdo->commit();
                header("Location: manage_issued_books.php?msg=" . urlencode("Book issued successfully!"));
                exit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Failed to issue book: " . $e->getMessage();
            }
        } else {
            $error = "Selected book is currently out of stock.";
        }
    } else {
        $error = "Please select a book, student, and set a return date.";
    }
}

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div id="content">
    <div class="topbar">
        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
            <i class="bi bi-list"></i>
        </button>
        <span class="text-muted">Issue Book</span>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Issue Book to Student</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="issue_book.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Select Student <span class="text-danger">*</span></label>
                                <select name="student_id" class="form-select shadow-none" required>
                                    <option value="">-- Choose Student --</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student['email']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Select Book <span class="text-danger">*</span></label>
                                <select name="book_id" class="form-select shadow-none" required>
                                    <option value="">-- Choose Book --</option>
                                    <?php foreach ($books as $book): ?>
                                        <option value="<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?> [ISBN: <?php echo htmlspecialchars($book['isbn']); ?>] (Qty: <?php echo $book['available_quantity']; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Return Date <span class="text-danger">*</span></label>
                                <input type="date" name="return_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                <small class="text-muted">The student is expected to return the book by this date.</small>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Issue This Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
