<nav id="sidebar">
    <div class="sidebar-header">
        <i class="bi bi-book-half"></i> LMS Admin
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
        </li>
        <li class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_books.php', 'add_book.php', 'edit_book.php']) ? 'active' : ''; ?>">
            <a href="#bookSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-journal-text"></i> Books
            </a>
            <ul class="collapse list-unstyled <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_books.php', 'add_book.php', 'edit_book.php']) ? 'show' : ''; ?>" id="bookSubmenu">
                <li><a href="add_book.php">Add Book</a></li>
                <li><a href="manage_books.php">Manage Books</a></li>
            </ul>
        </li>
        <li class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_students.php', 'add_student.php', 'edit_student.php']) ? 'active' : ''; ?>">
            <a href="#studentSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-people"></i> Students
            </a>
            <ul class="collapse list-unstyled <?php echo in_array(basename($_SERVER['PHP_SELF']), ['manage_students.php', 'add_student.php', 'edit_student.php']) ? 'show' : ''; ?>" id="studentSubmenu">
                <li><a href="add_student.php">Add Student</a></li>
                <li><a href="manage_students.php">Manage Students</a></li>
            </ul>
        </li>
        <li class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['issue_book.php', 'manage_issued_books.php']) ? 'active' : ''; ?>">
            <a href="#issueSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-journal-check"></i> Book Issue
            </a>
            <ul class="collapse list-unstyled <?php echo in_array(basename($_SERVER['PHP_SELF']), ['issue_book.php', 'manage_issued_books.php']) ? 'show' : ''; ?>" id="issueSubmenu">
                <li><a href="issue_book.php">Issue New Book</a></li>
                <li><a href="manage_issued_books.php">Manage Issued Books</a></li>
            </ul>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php"><i class="bi bi-graph-up"></i> Reports</a>
        </li>
        <li>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
    </ul>
</nav>
