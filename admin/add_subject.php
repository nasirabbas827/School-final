<?php
session_start();
include('config.php');

// Check admin login
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $subject_name  = trim($_POST['subject_name']);
    $class_id      = intval($_POST['class_id']);
    $department_id = intval($_POST['department_id']);
    $credit_hours  = intval($_POST['credit_hours']);

    if (empty($subject_name) || empty($class_id) || empty($department_id) || empty($credit_hours)) {
        $errorMsg = "All fields are required.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO subjects (subject_name, class_id, department_id, credit_hours)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "siii",
            $subject_name,
            $class_id,
            $department_id,
            $credit_hours
        );

        if ($stmt->execute()) {
            $successMsg = "Subject added successfully.";
        } else {
            $errorMsg = "Error adding subject.";
        }

        $stmt->close();
    }
}

// Fetch classes
$classes = [];
$classResult = $conn->query(
    "SELECT class_id, class_name, section FROM classes ORDER BY class_name ASC"
);
while ($row = $classResult->fetch_assoc()) {
    $classes[] = $row;
}

// Fetch departments
$departments = [];
$deptResult = $conn->query(
    "SELECT department_id, department_name FROM departments ORDER BY department_name ASC"
);
while ($row = $deptResult->fetch_assoc()) {
    $departments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subject</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body style="margin-left:280px; background-color:#e6effc;">

<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">
        <i class="fas fa-plus-circle me-2"></i>Add New Subject
    </h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($successMsg) ?>
        </div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>

    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST" action="">

                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" class="form-control" name="subject_name"
                           placeholder="e.g., Mathematics" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Class</label>
                    <select class="form-select" name="class_id" required>
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['class_id'] ?>">
                                <?= htmlspecialchars($class['class_name'] . ' - ' . $class['section']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Department</label>
                    <select class="form-select" name="department_id" required>
                        <option value="">-- Select Department --</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>">
                                <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Credit Hours</label>
                    <input type="number" class="form-control"
                           name="credit_hours" min="1" max="6" required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Subject
                    </button>
                    <a href="manage_subjects.php" class="btn btn-secondary">
                        <i class="fas fa-eye me-1"></i>View Subjects
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>
