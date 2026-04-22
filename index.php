<?php
require_once('includes/config.php');

if (isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
} else {
    header("Location: admin/login.php");
}
exit();
?>
