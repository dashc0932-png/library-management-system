<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Add New Book - LMS';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $quantity = (int)$_POST['quantity'];

    if (!empty($title) && !empty($author) && !empty($isbn) && $quantity >= 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO books (title, author, category, isbn, quantity, available_quantity) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $author, $category, $isbn, $quantity, $quantity])) {
                $success = "Book added successfully!";
                header("Location: manage_books.php?msg=" . urlencode($success));
                exit();
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "ISBN already exists!";
            } else {
                $error = "Something went wrong: " . $e->getMessage();
            }
        }
    } else {
        $error = "Please fill in all required fields.";
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
        <span class="text-muted">Add New Book</span>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Book Details</h5>
                        <a href="manage_books.php" class="btn btn-sm btn-outline-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="add_book.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Book Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="Enter book title">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author" class="form-control" required placeholder="Enter author name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ISBN <span class="text-danger">*</span></label>
                                    <input type="text" name="isbn" class="form-control" required placeholder="e.g. 978-0-123456-78-9">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">Select Category</option>
                                        <option value="Fiction">Fiction</option>
                                        <option value="Non-Fiction">Non-Fiction</option>
                                        <option value="Science">Science</option>
                                        <option value="Technology">Technology</option>
                                        <option value="History">History</option>
                                        <option value="Biography">Biography</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save Book</button>
                                <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
