<?php
session_start();
include('config.php');

/* =========================
   Admin Authentication
========================= */
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

/* =========================
   Fetch Departments
========================= */
$departments = [];
$deptResult = $conn->query("
    SELECT department_id, department_name
    FROM departments
    ORDER BY department_name ASC
");
while ($row = $deptResult->fetch_assoc()) {
    $departments[] = $row;
}

/* =========================
   Handle Form Submit
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $class_name    = trim($_POST['class_name']);
    $section       = trim($_POST['section']);
    $department_id = intval($_POST['department_id']);

    if (empty($class_name) || empty($section) || !$department_id) {
        $errorMsg = "All fields are required.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO classes (class_name, section, department_id)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $class_name, $section, $department_id);

        if ($stmt->execute()) {
            $successMsg = "Class added successfully.";
        } else {
            $errorMsg = "Error adding class. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Class</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Add New Class</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($successMsg) ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <div class="card mx-auto shadow" style="max-width: 520px;">
        <div class="card-body">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Class Name</label>
                    <input type="text"
                           name="class_name"
                           class="form-control"
                           placeholder="e.g. Grade 10"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <input type="text"
                           name="section"
                           class="form-control"
                           placeholder="e.g. A"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select" required>
                        <option value="">-- Select Department --</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>">
                                <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Save Class</button>
                    <a href="manage_classes.php" class="btn btn-secondary">View Classes</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
