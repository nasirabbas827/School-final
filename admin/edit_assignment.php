<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) header("Location: manage_assignments.php");
$id = intval($_GET['id']);

// Fetch assignment
$stmt = $conn->prepare("SELECT * FROM teacher_subjects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$assign = $result->fetch_assoc();
$stmt->close();

if (!$assign) header("Location: manage_assignments.php");

$successMsg = $errorMsg = "";

// Fetch teachers & subjects
$teachers = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Teacher'");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = intval($_POST['teacher_id']);
    $subject_id = intval($_POST['subject_id']);

    $stmt = $conn->prepare("UPDATE teacher_subjects SET teacher_id=?, subject_id=? WHERE id=?");
    $stmt->bind_param("iii", $teacher_id, $subject_id, $id);

    if ($stmt->execute()) {
        $successMsg = "Assignment updated successfully.";
        $assign['teacher_id'] = $teacher_id;
        $assign['subject_id'] = $subject_id;
    } else {
        $errorMsg = "Error updating assignment: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Assignment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-edit me-2"></i>Edit Teacher Assignment</h2>

<?php if($successMsg): ?>
<div class="alert alert-success"><i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($successMsg) ?></div>
<?php elseif($errorMsg): ?>
<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-1"></i><?= htmlspecialchars($errorMsg) ?></div>
<?php endif; ?>

<div class="card mx-auto shadow" style="max-width:500px;">
<div class="card-body">
<form method="POST" action="">
<div class="mb-3">
<label for="teacher_id" class="form-label"><i class="fas fa-user-tie me-1"></i>Teacher</label>
<select name="teacher_id" id="teacher_id" class="form-select" required>
<?php while($teacher = $teachers->fetch_assoc()): ?>
<option value="<?= $teacher['user_id'] ?>" <?= ($teacher['user_id']==$assign['teacher_id'])?'selected':'' ?>>
<?= htmlspecialchars($teacher['first_name'].' '.$teacher['last_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label for="subject_id" class="form-label"><i class="fas fa-book me-1"></i>Subject</label>
<select name="subject_id" id="subject_id" class="form-select" required>
<?php while($subject = $subjects->fetch_assoc()): ?>
<option value="<?= $subject['subject_id'] ?>" <?= ($subject['subject_id']==$assign['subject_id'])?'selected':'' ?>>
<?= htmlspecialchars($subject['subject_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="d-flex justify-content-between">
<button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update</button>
<a href="manage_assignments.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
