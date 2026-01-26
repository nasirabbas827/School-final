<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete fee
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM fee_structure WHERE fee_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_fees.php");
    exit;
}

// Fetch fees
$result = $conn->query("
    SELECT f.fee_id, s.subject_name, f.amount, f.description
    FROM fee_structure f
    LEFT JOIN subjects s ON f.subject_id = s.subject_id
    ORDER BY f.fee_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Fees</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-money-bill-wave me-2"></i>Manage Fees</h2>

<div class="card shadow">
<div class="card-body">
<div class="mb-3">
<a href="add_fee.php" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>Add Fee</a>
</div>

<?php if($result->num_rows > 0): ?>
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Subject</th>
<th>Amount</th>
<th>Description</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['subject_name'] ?? 'N/A') ?></td>
<td><?= htmlspecialchars($row['amount']) ?></td>
<td><?= htmlspecialchars($row['description']) ?></td>
<td>
<a href="edit_fee.php?id=<?= $row['fee_id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>Edit</a>
<a href="manage_fees.php?delete_id=<?= $row['fee_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this fee?');"><i class="fas fa-trash me-1"></i>Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center">No fee records found.</p>
<?php endif; ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
