<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Check if the student has active issued books
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM issued_books WHERE student_id = ? AND status = 'issued'");
    $stmt->execute([$id]);
    $active_issues = $stmt->fetchColumn();
    
    if ($active_issues > 0) {
        header("Location: manage_students.php?error=" . urlencode("Cannot delete student: They have active issued books. Mark as returned first."));
    } else {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: manage_students.php?msg=" . urlencode("Student deleted successfully!"));
        } else {
            header("Location: manage_students.php?error=Delete failed.");
        }
    }
} else {
    header("Location: manage_students.php");
}
exit();
?>
