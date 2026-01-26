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
| Handle Delete Request
|--------------------------------------------------------------------------
*/
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $conn->prepare(
        "DELETE FROM departments WHERE department_id = ?"
    );
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_departments.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Fetch Departments
|--------------------------------------------------------------------------
*/
$result = $conn->query(
    "SELECT * FROM departments ORDER BY department_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Departments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body style="margin-left:280px; background-color:#e6effc;">

<?php include('admin_sidebar.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">
        <i class="fas fa-building me-2"></i>Manage Departments
    </h2>

    <div class="card mx-auto shadow" style="max-width: 900px;">
        <div class="card-body">

            <div class="mb-3">
                <a href="add_department.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Add Department
                </a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['department_id']) ?></td>
                            <td><?= htmlspecialchars($row['department_name']) ?></td>
                            <td>
                                <a href="edit_department.php?department_id=<?= $row['department_id'] ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="javascript:void(0);"
                                   onclick="confirmDelete(<?= $row['department_id'] ?>)"
                                   class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">
                            No departments found
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this department?")) {
        window.location = "manage_departments.php?delete_id=" + id;
    }
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php mysqli_close($conn); ?>
