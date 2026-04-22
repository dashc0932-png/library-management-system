<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

if (isset($_GET['id'])) {
    $issue_id = (int)$_GET['id'];
    
    // Fetch issue details to get book_id
    $stmt = $pdo->prepare("SELECT book_id FROM issued_books WHERE id = ? AND status = 'issued'");
    $stmt->execute([$issue_id]);
    $issue = $stmt->fetch();
    
    if ($issue) {
        $book_id = $issue['book_id'];
        
        try {
            $pdo->beginTransaction();

            // 1. Update issued_books status
            $stmt = $pdo->prepare("UPDATE issued_books SET status = 'returned' WHERE id = ?");
            $stmt->execute([$issue_id]);

            // 2. Increase available quantity in books table
            $stmt = $pdo->prepare("UPDATE books SET available_quantity = available_quantity + 1 WHERE id = ?");
            $stmt->execute([$book_id]);

            $pdo->commit();
            header("Location: manage_issued_books.php?msg=" . urlencode("Book marked as returned. Stock updated."));
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: manage_issued_books.php?error=" . urlencode("Failed to return book: " . $e->getMessage()));
            exit();
        }
    } else {
        header("Location: manage_issued_books.php?error=Issue record not found.");
        exit();
    }
} else {
    header("Location: manage_issued_books.php");
    exit();
}
?>
