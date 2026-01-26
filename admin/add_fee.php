<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$successMsg = $errorMsg = "";

// Fetch subjects
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = intval($_POST['subject_id']) ?: NULL;
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);

    if (empty($amount)) {
        $errorMsg = "Amount is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO fee_structure (subject_id, amount, description) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $subject_id, $amount, $description);

        if ($stmt->execute()) {
            $successMsg = "Fee added successfully.";
        } else {
            $errorMsg = "Error adding fee: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Fee</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-money-bill-wave me-2"></i>Add Fee</h2>

<?php if($successMsg): ?>
<div class="alert alert-success"><i class="fas fa-check-circle me-1"></i><?= htmlspecialchars($successMsg) ?></div>
<?php elseif($errorMsg): ?>
<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-1"></i><?= htmlspecialchars($errorMsg) ?></div>
<?php endif; ?>

<div class="card mx-auto shadow" style="max-width:500px;">
<div class="card-body">
<form method="POST" action="">
<div class="mb-3">
<label for="subject_id" class="form-label"><i class="fas fa-book me-1"></i>Subject</label>
<select class="form-select" name="subject_id" id="subject_id">
<option value="">-- Select Subject (Optional) --</option>
<?php while($subject = $subjects->fetch_assoc()): ?>
<option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label for="amount" class="form-label"><i class="fas fa-dollar-sign me-1"></i>Amount</label>
<input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
</div>

<div class="mb-3">
<label for="description" class="form-label"><i class="fas fa-align-left me-1"></i>Description</label>
<input type="text" class="form-control" id="description" name="description">
</div>

<div class="d-flex justify-content-between">
<button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Fee</button>
<a href="manage_fees.php" class="btn btn-secondary"><i class="fas fa-eye me-1"></i>View Fees</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
