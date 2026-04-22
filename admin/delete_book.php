<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Check if the book has active issues
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE book_id = ? AND status = 'issued'");
    $stmt->execute([$id]);
    $active_issues = $stmt->fetchColumn();
    
    if ($active_issues > 0) {
        header("Location: manage_books.php?error=" . urlencode("Cannot delete book: It has active issues. Mark all as returned first."));
    } else {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: manage_books.php?msg=" . urlencode("Book deleted successfully!"));
        } else {
            header("Location: manage_books.php?error=Delete failed.");
        }
    }
} else {
    header("Location: manage_books.php");
}
exit();
?>
