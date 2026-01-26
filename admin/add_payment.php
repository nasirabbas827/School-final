<?php
session_start();
include('config.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

$successMsg = $errorMsg = "";

// Fetch students and fees
$students = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Student' ORDER BY first_name");
$fees = $conn->query("SELECT fee_id, description, amount FROM fee_structure ORDER BY fee_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $fee_id = intval($_POST['fee_id']);
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $receipt_no = trim($_POST['receipt_no']);

    if (empty($student_id) || empty($fee_id) || empty($amount_paid) || empty($payment_date) || empty($payment_method)) {
        $errorMsg = "All fields except receipt are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO fee_payments (student_id, fee_id, amount_paid, payment_date, payment_method, receipt_no) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $student_id, $fee_id, $amount_paid, $payment_date, $payment_method, $receipt_no);

        if ($stmt->execute()) {
            $successMsg = "Payment added successfully.";
        } else {
            $errorMsg = "Error adding payment: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center"><i class="fas fa-credit-card me-2"></i>Add Payment</h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($successMsg) ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-1"></i><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <div class="card mx-auto shadow" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-user-graduate me-1"></i>Student</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">-- Select Student --</option>
                            <?php while($student = $students->fetch_assoc()): ?>
                                <option value="<?= $student['user_id'] ?>"><?= htmlspecialchars($student['first_name'].' '.$student['last_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-book me-1"></i>Fee</label>
                        <select name="fee_id" class="form-select" required>
                            <option value="">-- Select Fee --</option>
                            <?php while($fee = $fees->fetch_assoc()): ?>
                                <option value="<?= $fee['fee_id'] ?>"><?= htmlspecialchars($fee['description'].' (RS '.$fee['amount'].')') ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Amount </label>
                        <input type="number" step="0.01" name="amount_paid" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-money-bill-wave me-1"></i>Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">-- Select Method --</option>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-receipt me-1"></i>Receipt No (Optional)</label>
                        <input type="text" name="receipt_no" class="form-control">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Payment</button>
                    <a href="manage_payments.php" class="btn btn-secondary"><i class="fas fa-eye me-1"></i>View Payments</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
