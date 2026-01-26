<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete assignment
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM teacher_subjects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_assignments.php");
    exit;
}

// Fetch assignments
$result = $conn->query("
    SELECT ts.id, u.first_name, u.last_name, s.subject_name, ts.assigned_date
    FROM teacher_subjects ts
    JOIN users u ON ts.teacher_id = u.user_id
    JOIN subjects s ON ts.subject_id = s.subject_id
    ORDER BY ts.assigned_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Teacher Assignments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Subject Assignments</h2>

<div class="card shadow">
<div class="card-body">
<div class="mb-3">
<a href="add_assignment.php" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>Add Assignment</a>
</div>

<?php if($result->num_rows > 0): ?>
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Teacher Name</th>
<th>Subject</th>
<th>Assigned Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
<td><?= htmlspecialchars($row['subject_name']) ?></td>
<td><?= htmlspecialchars($row['assigned_date']) ?></td>
<td>
<a href="edit_assignment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
<a href="manage_assignments.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this assignment?');"><i class="fas fa-trash me-1"></i>Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center">No assignments found.</p>
<?php endif; ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
