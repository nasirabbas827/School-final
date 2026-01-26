<?php
session_start();
include('config.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

// Get payment ID from query string
$payment_id = intval($_GET['id'] ?? 0);
if ($payment_id <= 0) {
    echo "<script>alert('Invalid Payment ID.'); window.location='manage_payments.php';</script>";
    exit;
}

// Fetch payment details
$payment_result = $conn->query("
    SELECT p.payment_id, p.student_id, p.fee_id, p.amount_paid, p.payment_date, p.payment_method, p.receipt_no
    FROM fee_payments p
    WHERE p.payment_id = $payment_id
");
if ($payment_result->num_rows === 0) {
    echo "<script>alert('Payment not found.'); window.location='manage_payments.php';</script>";
    exit;
}
$payment = $payment_result->fetch_assoc();

// Fetch students and fees for dropdowns
$students = $conn->query("SELECT user_id, first_name, last_name FROM users WHERE usertype='Student' ORDER BY first_name");
$fees = $conn->query("SELECT fee_id, description, amount FROM fee_structure ORDER BY fee_id");

$successMsg = $errorMsg = "";

// Handle form submission
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
        $stmt = $conn->prepare("
            UPDATE fee_payments
            SET student_id=?, fee_id=?, amount_paid=?, payment_date=?, payment_method=?, receipt_no=?
            WHERE payment_id=?
        ");
        $stmt->bind_param("iidsssi", $student_id, $fee_id, $amount_paid, $payment_date, $payment_method, $receipt_no, $payment_id);

        if ($stmt->execute()) {
            $successMsg = "Payment updated successfully.";
            // Refresh payment data
            $payment['student_id'] = $student_id;
            $payment['fee_id'] = $fee_id;
            $payment['amount_paid'] = $amount_paid;
            $payment['payment_date'] = $payment_date;
            $payment['payment_method'] = $payment_method;
            $payment['receipt_no'] = $receipt_no;
        } else {
            $errorMsg = "Error updating payment: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center"><i class="fas fa-credit-card me-2"></i>Edit Payment</h2>

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
                                <option value="<?= $student['user_id'] ?>" <?= ($student['user_id'] == $payment['student_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($student['first_name'].' '.$student['last_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-book me-1"></i>Fee</label>
                        <select name="fee_id" class="form-select" required>
                            <option value="">-- Select Fee --</option>
                            <?php while($fee = $fees->fetch_assoc()): ?>
                                <option value="<?= $fee['fee_id'] ?>" <?= ($fee['fee_id'] == $payment['fee_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fee['description'].' (RS '.$fee['amount'].')') ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-dollar-sign me-1"></i>Amount Paid</label>
                        <input type="number" step="0.01" name="amount_paid" class="form-control" value="<?= htmlspecialchars($payment['amount_paid']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="<?= htmlspecialchars($payment['payment_date']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-money-bill-wave me-1"></i>Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">-- Select Method --</option>
                            <option value="Cash" <?= ($payment['payment_method']=='Cash') ? 'selected' : '' ?>>Cash</option>
                            <option value="Card" <?= ($payment['payment_method']=='Card') ? 'selected' : '' ?>>Card</option>
                            <option value="Online" <?= ($payment['payment_method']=='Online') ? 'selected' : '' ?>>Online</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-receipt me-1"></i>Receipt No (Optional)</label>
                        <input type="text" name="receipt_no" class="form-control" value="<?= htmlspecialchars($payment['receipt_no']) ?>">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Payment</button>
                    <a href="manage_payments.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
