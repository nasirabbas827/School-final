<?php
session_start();
include('config.php');

/*
|--------------------------------------------------------------------------
| Admin Authentication Check
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Department ID
|--------------------------------------------------------------------------
*/
$department_id = intval($_GET['department_id'] ?? 0);

/*
|--------------------------------------------------------------------------
| Fetch Department Data
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare(
    "SELECT * FROM departments WHERE department_id = ?"
);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$department = $result->fetch_assoc();
$stmt->close();

if (!$department) {
    echo "<script>
            alert('Department not found.');
            window.location='manage_departments.php';
          </script>";
    exit;
}

$successMsg = $errorMsg = "";

/*
|--------------------------------------------------------------------------
| Handle Update
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $department_name = trim($_POST['department_name']);

    if (empty($department_name)) {
        $errorMsg = "Department name is required.";
    } else {

        // Check duplicate department (excluding current)
        $checkStmt = $conn->prepare(
            "SELECT department_id FROM departments 
             WHERE department_name = ? AND department_id != ?"
        );
        $checkStmt->bind_param("si", $department_name, $department_id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errorMsg = "Department name already exists.";
        } else {

            $stmt = $conn->prepare(
                "UPDATE departments SET department_name = ? 
                 WHERE department_id = ?"
            );
            $stmt->bind_param("si", $department_name, $department_id);

            if ($stmt->execute()) {
                $successMsg = "Department updated successfully.";
            } else {
                $errorMsg = "Error updating department.";
            }

            $stmt->close();
        }

        $checkStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body style="margin-left:280px; background-color:#e6effc;">

<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">
        <i class="fas fa-edit me-2"></i>Edit Department
    </h2>

    <?php if ($successMsg): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($successMsg) ?>
        </div>
    <?php elseif ($errorMsg): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>

    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-body">
            <form method="POST" action="">

                <div class="mb-3">
                    <label for="department_name" class="form-label">
                        Department Name
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="department_name"
                        name="department_name"
                        value="<?= htmlspecialchars($department['department_name']) ?>"
                        required
                    >
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Update Department
                    </button>
                    <a href="manage_departments.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>
