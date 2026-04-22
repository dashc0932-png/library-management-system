<?php
/**
 * Authentication Helper
 */

/**
 * Check if the admin is logged in
 * If not, redirect to login page
 */
function checkAdminSession() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Check if admin is NOT logged in (for login page)
 * If logged in, redirect to dashboard
 */
function checkLoginSession() {
    if (isset($_SESSION['admin_id'])) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
