<?php
session_start();
include('config.php'); 

// 1. Authorization Check
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// 2. Data Counting Function
function getCount($conn, $table, $where = "") {
    $sql = "SELECT COUNT(*) as total FROM `$table` $where";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
}

// Core Stats
$classCount      = getCount($conn, "classes");
$deptCount       = getCount($conn, "departments");
$subjectCount    = getCount($conn, "subjects");
$studentCount    = getCount($conn, "users", "WHERE usertype = 'Student'");
$teacherCount    = getCount($conn, "users", "WHERE usertype = 'Teacher'");

// Financial Summary
$feeResult = mysqli_query($conn, "SELECT SUM(amount_paid) as total_collected FROM fee_payments");
$totalFees = mysqli_fetch_assoc($feeResult)['total_collected'] ?? 0;

// 3. Fetch Latest Records
// Classes with Department names
$classList = mysqli_query($conn, "SELECT c.*, d.department_name 
    FROM classes c 
    LEFT JOIN departments d ON c.department_id = d.department_id 
    ORDER BY c.created_at DESC LIMIT 5");

// Subjects with Credit Hours
$subjectList = mysqli_query($conn, "SELECT s.*, c.class_name 
    FROM subjects s 
    JOIN classes c ON s.class_id = c.class_id 
    ORDER BY s.subject_id DESC LIMIT 5");

// Timetables with Teacher names
$timetableList = mysqli_query($conn, "SELECT t.*, c.class_name, s.subject_name, u.first_name, u.last_name 
    FROM timetables t 
    JOIN classes c ON t.class_id = c.class_id 
    JOIN subjects s ON t.subject_id = s.subject_id 
    JOIN users u ON t.teacher_id = u.user_id 
    ORDER BY t.id DESC LIMIT 5");

// Recent Fee Payments
$recentPayments = mysqli_query($conn, "SELECT p.*, u.first_name, u.last_name 
    FROM fee_payments p 
    JOIN users u ON p.student_id = u.user_id 
    ORDER BY p.payment_id DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard | Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; margin-left: 280px; transition: 0.3s; }
        .card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-3px); }
        .stat-icon { font-size: 2.5rem; opacity: 0.3; }
        @media (max-width: 992px) { body { margin-left: 0; } }
    </style>
</head>
<body>

<?php include('admin_sidebar.php'); ?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Overview</h2>
        <span class="badge bg-dark p-2">Academic Year 2025-2026</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Students</h6>
                        <h3><?= $studentCount ?></h3>
                    </div>
                    <i class="fas fa-user-graduate stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Total Teachers</h6>
                        <h3><?= $teacherCount ?></h3>
                    </div>
                    <i class="fas fa-chalkboard-teacher stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Departments</h6>
                        <h3><?= $deptCount ?></h3>
                    </div>
                    <i class="fas fa-building stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6>Fees Collected</h6>
                        <h3><?= number_format($totalFees, 2) ?></h3>
                    </div>
                    <i class="fas fa-hand-holding-usd stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">
                    <i class="fas fa-calendar-alt me-2"></i>Recent Timetable Assignments
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($t = mysqli_fetch_assoc($timetableList)): ?>
                            <tr>
                                <td><?= $t['class_name'] ?></td>
                                <td><?= $t['subject_name'] ?></td>
                                <td><?= $t['first_name'] ?></td>
                                <td><small><?= $t['day_of_week'] ?> (<?= date('H:i', strtotime($t['start_time'])) ?>)</small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">
                    <i class="fas fa-receipt me-2"></i>Recent Payments
                </div>
                <ul class="list-group list-group-flush">
                    <?php while($pay = mysqli_fetch_assoc($recentPayments)): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <small class="d-block text-muted"><?= $pay['payment_date'] ?></small>
                            <?= $pay['first_name'] ?>
                        </div>
                        <span class="text-success font-weight-bold">+RS <?= $pay['amount_paid'] ?></span>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">Active Classes</div>
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <tbody>
                            <?php while($c = mysqli_fetch_assoc($classList)): ?>
                            <tr>
                                <td><strong><?= $c['class_name'] ?> - <?= $c['section'] ?></strong></td>
                                <td><span class="badge bg-secondary"><?= $c['department_name'] ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white font-weight-bold">Subject Catalog</div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <?php while($s = mysqli_fetch_assoc($subjectList)): ?>
                            <tr>
                                <td><?= $s['subject_name'] ?></td>
                                <td><?= $s['class_name'] ?></td>
                                <td><small><?= $s['credit_hours'] ?> Credits</small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>

 