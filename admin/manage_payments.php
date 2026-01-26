<?php
session_start();
include('config.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM fee_payments WHERE payment_id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_payments.php");
    exit;
}

// Fetch payments
$result = $conn->query("
    SELECT p.payment_id, u.first_name, u.last_name, f.description, p.amount_paid, p.payment_date, p.payment_method, p.receipt_no
    FROM fee_payments p
    JOIN users u ON p.student_id = u.user_id
    JOIN fee_structure f ON p.fee_id = f.fee_id
    ORDER BY p.payment_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Payments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-credit-card me-2"></i>Manage Payments</h2>

<div class="card shadow">
<div class="card-body">
<div class="mb-3 text-end">
<a href="add_payment.php" class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>Add Payment</a>
</div>

<?php if($result->num_rows>0): ?>
<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead class="table-dark text-center">
<tr>
<th>#</th>
<th>Student</th>
<th>Fee</th>
<th>Amount Paid</th>
<th>Payment Date</th>
<th>Method</th>
<th>Receipt No</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row=$result->fetch_assoc()): ?>
<tr class="text-center">
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
<td><?= htmlspecialchars($row['description']) ?></td>
<td>RS <?= number_format($row['amount_paid'], 2) ?></td>
<td><?= htmlspecialchars($row['payment_date']) ?></td>
<td><?= htmlspecialchars($row['payment_method']) ?></td>
<td><?= htmlspecialchars($row['receipt_no']) ?></td>
<td>
<a href="edit_payment.php?id=<?= $row['payment_id'] ?>" class="btn btn-sm btn-warning mb-1"><i class="fas fa-edit me-1"></i>Edit</a>
<a href="manage_payments.php?delete_id=<?= $row['payment_id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this payment?');"><i class="fas fa-trash me-1"></i>Delete</a>
<a href="generate_receipt.php?id=<?= $row['payment_id'] ?>" class="btn btn-sm btn-success mb-1" target="_blank"><i class="fas fa-file-invoice-dollar me-1"></i>Receipt</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php else: ?>
<p class="text-center text-muted">No payments found. <a href="add_payment.php">Add a payment now</a>.</p>
<?php endif; ?>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
