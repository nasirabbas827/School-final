<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

// Fetch teachers and subjects
$teachers = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Teacher' ORDER BY first_name");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = intval($_POST['teacher_id']);
    $subject_id = intval($_POST['subject_id']);

    if (empty($teacher_id) || empty($subject_id)) {
        $errorMsg = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $teacher_id, $subject_id);

        if ($stmt->execute()) {
            $successMsg = "Teacher assigned successfully.";
        } else {
            $errorMsg = "Error assigning teacher: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Teacher</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-chalkboard-teacher me-2"></i>Assign Teacher to Subject</h2>

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
<select class="form-select" name="teacher_id" id="teacher_id" required>
<option value="">-- Select Teacher --</option>
<?php while($teacher = $teachers->fetch_assoc()): ?>
<option value="<?= $teacher['user_id'] ?>"><?= htmlspecialchars($teacher['first_name'].' '.$teacher['last_name']) ?></option>
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
<button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Assign</button>
<a href="manage_assignments.php" class="btn btn-secondary"><i class="fas fa-eye me-1"></i>View Assignments</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
