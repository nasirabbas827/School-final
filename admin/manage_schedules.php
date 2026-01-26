<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete
if(isset($_GET['delete_id'])){
    $id=intval($_GET['delete_id']);
    $stmt=$conn->prepare("DELETE FROM timetables WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_schedules.php");
    exit;
}

// Fetch all schedules
$result = $conn->query("
    SELECT t.id, c.class_name, c.section, s.subject_name, u.first_name, u.last_name, t.day_of_week, t.start_time, t.end_time
    FROM timetables t
    JOIN classes c ON t.class_id=c.class_id
    JOIN subjects s ON t.subject_id=s.subject_id
    JOIN users u ON t.teacher_id=u.user_id
    ORDER BY t.day_of_week, t.start_time
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Schedules</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-calendar-alt me-2"></i>Class Timetables</h2>

<div class="card shadow">
<div class="card-body">
<div class="mb-3 text-end">
<a href="add_schedule.php" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>Add Schedule</a>
</div>

<?php if($result->num_rows>0): ?>
<table class="table table-bordered table-striped align-middle text-center">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Class</th>
<th>Subject</th>
<th>Teacher</th>
<th>Day</th>
<th>Start</th>
<th>End</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row=$result->fetch_assoc()): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['class_name'].' - '.$row['section']) ?></td>
<td><?= htmlspecialchars($row['subject_name']) ?></td>
<td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
<td><?= $row['day_of_week'] ?></td>
<td><?= $row['start_time'] ?></td>
<td><?= $row['end_time'] ?></td>
<td>
<a href="edit_schedule.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit me-1"></i>Edit</a>
<a href="manage_schedules.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Delete this schedule?');"><i class="fas fa-trash me-1"></i>Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center text-muted">No schedules found. <a href="add_schedule.php">Add a schedule now</a>.</p>
<?php endif; ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
