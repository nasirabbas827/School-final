<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) header("Location: manage_enrollments.php");
$id = intval($_GET['id']);

// Fetch enrollment
$stmt = $conn->prepare("SELECT * FROM student_subjects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$enroll = $result->fetch_assoc();
$stmt->close();

if (!$enroll) header("Location: manage_enrollments.php");

$successMsg = $errorMsg = "";

// Fetch students & subjects
$students = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Student'");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);

    $stmt = $conn->prepare("UPDATE student_subjects SET student_id=?, subject_id=? WHERE id=?");
    $stmt->bind_param("iii", $student_id, $subject_id, $id);

    if ($stmt->execute()) {
        $successMsg = "Enrollment updated successfully.";
        $enroll['student_id'] = $student_id;
        $enroll['subject_id'] = $subject_id;
    } else {
        $errorMsg = "Error updating: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Enrollment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-edit me-2"></i>Edit Enrollment</h2>

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
<select name="student_id" id="student_id" class="form-select" required>
<?php while($student = $students->fetch_assoc()): ?>
<option value="<?= $student['user_id'] ?>" <?= ($student['user_id']==$enroll['student_id'])?'selected':'' ?>>
<?= htmlspecialchars($student['first_name'].' '.$student['last_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>
<div class="mb-3">
<label for="subject_id" class="form-label"><i class="fas fa-book me-1"></i>Subject</label>
<select name="subject_id" id="subject_id" class="form-select" required>
<?php while($subject = $subjects->fetch_assoc()): ?>
<option value="<?= $subject['subject_id'] ?>" <?= ($subject['subject_id']==$enroll['subject_id'])?'selected':'' ?>>
<?= htmlspecialchars($subject['subject_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>
<div class="d-flex justify-content-between">
<button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update</button>
<a href="manage_enrollments.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
