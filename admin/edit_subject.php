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
   Validate Subject ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_subjects.php");
    exit;
}

$subject_id = intval($_GET['id']);

/* =========================
   Fetch Subject
========================= */
$stmt = $conn->prepare("
    SELECT subject_id, subject_name, class_id, department_id, credit_hours
    FROM subjects
    WHERE subject_id = ?
");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_subjects.php");
    exit;
}

$subject = $result->fetch_assoc();
$stmt->close();

/* =========================
   Handle Update
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $subject_name  = trim($_POST['subject_name']);
    $class_id      = intval($_POST['class_id']);
    $department_id = intval($_POST['department_id']);
    $credit_hours  = intval($_POST['credit_hours']);

    if (
        empty($subject_name) ||
        !$class_id ||
        !$department_id ||
        $credit_hours <= 0
    ) {
        $errorMsg = "All fields are required and credit hours must be valid.";
    } else {

        $updateStmt = $conn->prepare("
            UPDATE subjects
            SET subject_name = ?, class_id = ?, department_id = ?, credit_hours = ?
            WHERE subject_id = ?
        ");

        $updateStmt->bind_param(
            "siiii",
            $subject_name,
            $class_id,
            $department_id,
            $credit_hours,
            $subject_id
        );

        if ($updateStmt->execute()) {
            $successMsg = "Subject updated successfully.";

            // Refresh data
            $subject['subject_name']  = $subject_name;
            $subject['class_id']      = $class_id;
            $subject['department_id'] = $department_id;
            $subject['credit_hours']  = $credit_hours;
        } else {
            $errorMsg = "Update failed. Please try again.";
        }

        $updateStmt->close();
    }
}

/* =========================
   Fetch Classes
========================= */
$classes = [];
$classResult = $conn->query("
    SELECT class_id, class_name, section
    FROM classes
    ORDER BY class_name ASC
");
while ($row = $classResult->fetch_assoc()) {
    $classes[] = $row;
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Subject</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Subject</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($successMsg) ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <div class="card mx-auto shadow" style="max-width: 520px;">
        <div class="card-body">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subject_name" class="form-control"
                           value="<?= htmlspecialchars($subject['subject_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select" required>
                        <option value="">-- Select Department --</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>"
                                <?= ($dept['department_id'] == $subject['department_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['class_id'] ?>"
                                <?= ($class['class_id'] == $subject['class_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['class_name'] . ' - ' . $class['section']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Credit Hours</label>
                    <input type="number"
                           name="credit_hours"
                           class="form-control"
                           min="1"
                           max="6"
                           value="<?= (int)$subject['credit_hours'] ?>"
                           required>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                    <a href="manage_subjects.php" class="btn btn-secondary">Back</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
