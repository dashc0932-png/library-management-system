<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Manage Books - LMS';

// Handle Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ? ORDER BY added_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%", "%$search%"]);
$books = $stmt->fetchAll();

include('../includes/header.php');
include('../includes/sidebar.php');
?>

<div id="content">
    <div class="topbar">
        <button type="button" id="sidebarCollapse" class="btn btn-outline-primary">
            <i class="bi bi-list"></i>
        </button>
        <span class="text-muted">Manage Books</span>
    </div>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Library Inventory</h2>
            <a href="add_book.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add New Book</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <form action="manage_books.php" method="GET" class="row g-2">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Search title, author, ISBN..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                    <?php if ($search): ?>
                    <div class="col-auto">
                        <a href="manage_books.php" class="btn btn-outline-secondary">Clear</a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Total</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($books)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No books found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($book['category']); ?></span></td>
                                        <td><?php echo $book['quantity']; ?></td>
                                        <td>
                                            <?php if ($book['available_quantity'] > 0): ?>
                                                <span class="text-success fw-bold"><?php echo $book['available_quantity']; ?></span>
                                            <?php else: ?>
                                                <span class="text-danger fw-bold">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                            <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this book?')"><i class="bi bi-trash"></i></a>
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

<?php include('../includes/footer.php'); ?>
