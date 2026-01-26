<?php
session_start();
include('config.php');

if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) header("Location: manage_fees.php");
$id = intval($_GET['id']);

// Fetch fee record
$stmt = $conn->prepare("SELECT * FROM fee_structure WHERE fee_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$fee = $result->fetch_assoc();
$stmt->close();

if (!$fee) header("Location: manage_fees.php");

$successMsg = $errorMsg = "";

// Fetch subjects
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = intval($_POST['subject_id']) ?: NULL;
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("UPDATE fee_structure SET subject_id=?, amount=?, description=? WHERE fee_id=?");
    $stmt->bind_param("idsi", $subject_id, $amount, $description, $id);

    if ($stmt->execute()) {
        $successMsg = "Fee updated successfully.";
        $fee['subject_id'] = $subject_id;
        $fee['amount'] = $amount;
        $fee['description'] = $description;
    } else {
        $errorMsg = "Error updating fee: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Fee</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
</head>
<body style="margin-left:280px; background-color:#e6effc;">
<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
<h2 class="mb-4 text-center"><i class="fas fa-edit me-2"></i>Edit Fee</h2>

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
<select name="subject_id" id="subject_id" class="form-select">
<option value="">-- Select Subject (Optional) --</option>
<?php while($subject = $subjects->fetch_assoc()): ?>
<option value="<?= $subject['subject_id'] ?>" <?= ($subject['subject_id']==$fee['subject_id'])?'selected':'' ?>>
<?= htmlspecialchars($subject['subject_name']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="mb-3">
<label for="amount" class="form-label"><i class="fas fa-dollar-sign me-1"></i>Amount</label>
<input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars($fee['amount']) ?>" required>
</div>

<div class="mb-3">
<label for="description" class="form-label"><i class="fas fa-align-left me-1"></i>Description</label>
<input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($fee['description']) ?>">
</div>

<div class="d-flex justify-content-between">
<button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update Fee</button>
<a href="manage_fees.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
