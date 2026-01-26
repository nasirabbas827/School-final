<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM student_subjects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_enrollments.php");
    exit;
}

// Fetch enrollments
$result = $conn->query("
    SELECT ss.id, u.first_name, u.last_name, sub.subject_name, ss.enrollment_date
    FROM student_subjects ss
    JOIN users u ON ss.student_id = u.user_id
    JOIN subjects sub ON ss.subject_id = sub.subject_id
    ORDER BY ss.enrollment_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Enrollments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-users me-2"></i>Student Enrollments</h2>

<div class="card shadow">
<div class="card-body">
<div class="mb-3">
<a href="add_enrollment.php" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>Add Enrollment</a>
</div>

<?php if($result->num_rows > 0): ?>
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Student Name</th>
<th>Subject</th>
<th>Enrollment Date</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
<td><?= htmlspecialchars($row['subject_name']) ?></td>
<td><?= htmlspecialchars($row['enrollment_date']) ?></td>
<td>
<a href="edit_enrollment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
<a href="manage_enrollments.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this enrollment?');"><i class="fas fa-trash me-1"></i>Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center">No enrollments found.</p>
<?php endif; ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
