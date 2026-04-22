<?php
require_once('../includes/config.php');
require_once('../includes/auth.php');
checkAdminSession();

$page_title = 'Add New Student - LMS';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (!empty($name) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, email, phone) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $phone])) {
                $success = "Student registered successfully!";
                header("Location: manage_students.php?msg=" . urlencode($success));
                exit();
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already registered!";
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
        <span class="text-muted">Add New Student</span>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Student Information</h5>
                        <a href="manage_students.php" class="btn btn-sm btn-outline-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="add_student.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter student's full name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="student@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="+1234567890">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">Register Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
