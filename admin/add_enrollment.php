<?php
session_start();
include('config.php');

// Check admin login
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

// Fetch students and subjects
$students = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Student' ORDER BY first_name");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);

    if (empty($student_id) || empty($subject_id)) {
        $errorMsg = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $subject_id);

        if ($stmt->execute()) {
            $successMsg = "Student enrolled successfully.";
        } else {
            $errorMsg = "Error enrolling student: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Enrollment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-user-plus me-2"></i>Enroll Student in Subject</h2>

<?php if($successMsg): ?>
<div class="alert alert-success"><i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($successMsg) ?></div>
<?php elseif($errorMsg): ?>
<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-1"></i><?= htmlspecialchars($errorMsg) ?></div>
<?php endif; ?>

<div class="card mx-auto shadow" style="max-width:500px;">
<div class="card-body">
<form method="POST" action="">
    <div class="mb-3">
        <label for="student_id" class="form-label"><i class="fas fa-user-graduate me-1"></i>Student</label>
        <select class="form-select" name="student_id" id="student_id" required>
            <option value="">-- Select Student --</option>
            <?php while($student = $students->fetch_assoc()): ?>
            <option value="<?= $student['user_id'] ?>">
                <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="subject_id" class="form-label"><i class="fas fa-book me-1"></i>Subject</label>
        <select class="form-select" name="subject_id" id="subject_id" required>
            <option value="">-- Select Subject --</option>
            <?php while($subject = $subjects->fetch_assoc()): ?>
            <option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Enroll</button>
        <a href="manage_enrollments.php" class="btn btn-secondary"><i class="fas fa-eye me-1"></i>View Enrollments</a>
    </div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
