<?php
session_start();
include('config.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// Get payment ID
$payment_id = intval($_GET['id'] ?? 0);
if ($payment_id <= 0) {
    die("Invalid payment ID.");
}

// Fetch payment data
$stmt = $conn->prepare("
    SELECT p.payment_id, u.first_name, u.last_name, u.user_id, f.description, p.amount_paid, p.payment_date, p.payment_method, p.receipt_no
    FROM fee_payments p
    JOIN users u ON p.student_id = u.user_id
    JOIN fee_structure f ON p.fee_id = f.fee_id
    WHERE p.payment_id = ?
");
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Payment not found.");
}

$payment = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Receipt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script>
function printReceipt() {
    window.print();
}
</script>
</head>
<body class="p-4">

<div class="container border p-4 shadow" style="max-width:600px;">
<h2 class="text-center mb-4"><i class="fas fa-file-invoice-dollar me-2"></i>Payment Receipt</h2>

<table class="table table-borderless">
<tr>
<th>Receipt No:</th><td><?= htmlspecialchars($payment['receipt_no'] ?: 'N/A') ?></td>
</tr>
<tr>
<th>Student:</th><td><?= htmlspecialchars($payment['first_name'].' '.$payment['last_name']) ?></td>
</tr>
<tr>
<th>Fee:</th><td><?= htmlspecialchars($payment['description']) ?></td>
</tr>
<tr>
<th>Amount Paid:</th><td>RS <?= number_format($payment['amount_paid'],2) ?></td>
</tr>
<tr>
<th>Payment Date:</th><td><?= htmlspecialchars($payment['payment_date']) ?></td>
</tr>
<tr>
<th>Method:</th><td><?= htmlspecialchars($payment['payment_method']) ?></td>
</tr>
</table>

<div class="text-center mt-4">
<button onclick="printReceipt()" class="btn btn-primary"><i class="fas fa-print me-1"></i>Print Receipt</button>
<a href="manage_payments.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
