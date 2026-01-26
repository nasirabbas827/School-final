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

$successMsg = $errorMsg = "";

/*
|--------------------------------------------------------------------------
| Handle Form Submission
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $department_name = trim($_POST['department_name']);

    if (empty($department_name)) {
        $errorMsg = "Department name is required.";
    } else {

        // Check for duplicate department
        $checkStmt = $conn->prepare(
            "SELECT department_id FROM departments WHERE department_name = ?"
        );
        $checkStmt->bind_param("s", $department_name);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errorMsg = "Department already exists.";
        } else {

            // Insert department
            $stmt = $conn->prepare(
                "INSERT INTO departments (department_name) VALUES (?)"
            );
            $stmt->bind_param("s", $department_name);

            if ($stmt->execute()) {
                $successMsg = "Department added successfully.";
            } else {
                $errorMsg = "Error adding department.";
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
    <title>Add Department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body style="margin-left:280px; background-color:#e6effc;">

<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">
        <i class="fas fa-building me-2"></i>Add New Department
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
                        placeholder="e.g., Computer Science"
                        required
                    >
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Department
                    </button>
                    <a href="manage_departments.php" class="btn btn-secondary">
                        <i class="fas fa-eye me-1"></i>View Departments
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
