<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Edit Book - LMS';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: manage_books.php");
    exit();
}

// Fetch current book details
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header("Location: manage_books.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $isbn = $_POST['isbn'];
    $quantity = (int)$_POST['quantity'];
    
    // Calculate new available quantity based on change in total quantity
    $quantity_diff = $quantity - $book['quantity'];
    $new_available = $book['available_quantity'] + $quantity_diff;

    if (!empty($title) && !empty($author) && !empty($isbn) && $quantity >= 0) {
        if ($new_available < 0) {
            $error = "Total quantity cannot be less than books currently issued.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, category = ?, isbn = ?, quantity = ?, available_quantity = ? WHERE id = ?");
                if ($stmt->execute([$title, $author, $category, $isbn, $quantity, $new_available, $id])) {
                    header("Location: manage_books.php?msg=" . urlencode("Book updated successfully!"));
                    exit();
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "ISBN already exists!";
                } else {
                    $error = "Something went wrong: " . $e->getMessage();
                }
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
        <span class="text-muted">Edit Book</span>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Edit Book Details</h5>
                        <a href="manage_books.php" class="btn btn-sm btn-outline-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="edit_book.php?id=<?php echo $id; ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Book Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($book['title']); ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author" class="form-control" required value="<?php echo htmlspecialchars($book['author']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ISBN <span class="text-danger">*</span></label>
                                    <input type="text" name="isbn" class="form-control" required value="<?php echo htmlspecialchars($book['isbn']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="Fiction" <?php if($book['category'] == 'Fiction') echo 'selected'; ?>>Fiction</option>
                                        <option value="Non-Fiction" <?php if($book['category'] == 'Non-Fiction') echo 'selected'; ?>>Non-Fiction</option>
                                        <option value="Science" <?php if($book['category'] == 'Science') echo 'selected'; ?>>Science</option>
                                        <option value="Technology" <?php if($book['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
                                        <option value="History" <?php if($book['category'] == 'History') echo 'selected'; ?>>History</option>
                                        <option value="Biography" <?php if($book['category'] == 'Biography') echo 'selected'; ?>>Biography</option>
                                        <option value="Other" <?php if($book['category'] == 'Other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" min="0" required value="<?php echo $book['quantity']; ?>">
                                    <small class="text-muted">Available: <?php echo $book['available_quantity']; ?></small>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Update Book</button>
                                <a href="manage_books.php" class="btn btn-outline-secondary px-4">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
