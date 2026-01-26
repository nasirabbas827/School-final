<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

// Fetch classes, subjects, teachers
$classes = $conn->query("SELECT class_id, class_name, section FROM classes ORDER BY class_name");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
$teachers = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Teacher' ORDER BY first_name");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $class_id = intval($_POST['class_id']);
    $subject_id = intval($_POST['subject_id']);
    $teacher_id = intval($_POST['teacher_id']);
    $day = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if (!$class_id || !$subject_id || !$teacher_id || !$day || !$start_time || !$end_time) {
        $errorMsg = "All fields are required.";
    } else {
        // Check for conflicts: class or teacher
        $conflict = $conn->prepare("
            SELECT * FROM timetables 
            WHERE day_of_week=? AND (
                (class_id=? AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))) OR
                (teacher_id=? AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?)))
            )
        ");
        $conflict->bind_param(
            "siisssiiiss",
            $day, $class_id, $end_time, $start_time, $start_time, $end_time,
            $teacher_id, $end_time, $start_time, $start_time, $end_time
        );
        $conflict->execute();
        $conflict_result = $conflict->get_result();

        if ($conflict_result->num_rows > 0) {
            $errorMsg = "Schedule conflict detected for this class or teacher at the selected time.";
        } else {
            $stmt = $conn->prepare("INSERT INTO timetables (class_id, subject_id, teacher_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisss", $class_id, $subject_id, $teacher_id, $day, $start_time, $end_time);
            if ($stmt->execute()) {
                $successMsg = "Schedule added successfully.";
            } else {
                $errorMsg = "Error adding schedule: " . $conn->error;
            }
            $stmt->close();
        }
        $conflict->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Schedule</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-calendar-plus me-2"></i>Add Class Schedule</h2>

<?php if ($successMsg): ?>
<div class="alert alert-success text-center"><i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($successMsg) ?></div>
<?php elseif ($errorMsg): ?>
<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle me-1"></i><?= htmlspecialchars($errorMsg) ?></div>
<?php endif; ?>

<div class="card mx-auto shadow" style="max-width:900px;">
<div class="card-body">
<form method="POST" action="">
<div class="row mb-3">
<div class="col-md-4">
<label for="class_id" class="form-label"><i class="fas fa-school me-1"></i>Class</label>
<select class="form-select" id="class_id" name="class_id" required>
<option value="">-- Select Class --</option>
<?php while($row=$classes->fetch_assoc()): ?>
<option value="<?= $row['class_id'] ?>"><?= htmlspecialchars($row['class_name'].' - '.$row['section']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="col-md-4">
<label for="subject_id" class="form-label"><i class="fas fa-book me-1"></i>Subject</label>
<select class="form-select" id="subject_id" name="subject_id" required>
<option value="">-- Select Subject --</option>
<?php while($row=$subjects->fetch_assoc()): ?>
<option value="<?= $row['subject_id'] ?>"><?= htmlspecialchars($row['subject_name']) ?></option>
<?php endwhile; ?>
</select>
</div>
<div class="col-md-4">
<label for="teacher_id" class="form-label"><i class="fas fa-chalkboard-teacher me-1"></i>Teacher</label>
<select class="form-select" id="teacher_id" name="teacher_id" required>
<option value="">-- Select Teacher --</option>
<?php while($row=$teachers->fetch_assoc()): ?>
<option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></option>
<?php endwhile; ?>
</select>
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="day_of_week" class="form-label"><i class="fas fa-calendar-day me-1"></i>Day</label>
<select class="form-select" id="day_of_week" name="day_of_week" required>
<option value="">-- Select Day --</option>
<option value="Mon">Monday</option>
<option value="Tue">Tuesday</option>
<option value="Wed">Wednesday</option>
<option value="Thu">Thursday</option>
<option value="Fri">Friday</option>
<option value="Sat">Saturday</option>
</select>
</div>
<div class="col-md-4">
<label for="start_time" class="form-label"><i class="fas fa-hourglass-start me-1"></i>Start Time</label>
<input type="time" class="form-control" name="start_time" id="start_time" required>
</div>
<div class="col-md-4">
<label for="end_time" class="form-label"><i class="fas fa-hourglass-end me-1"></i>End Time</label>
<input type="time" class="form-control" name="end_time" id="end_time" required>
</div>
</div>

<div class="d-flex justify-content-between">
<button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Schedule</button>
<a href="manage_schedules.php" class="btn btn-secondary"><i class="fas fa-eye me-1"></i>View Schedules</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
