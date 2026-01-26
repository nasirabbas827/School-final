<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Sidebar</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin-left: 280px;
    background-color: #e6effc;
}

/* Fixed Sidebar */
nav.sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: linear-gradient(180deg, #0f3460 0%, #16213e 70%, #1a1a2e 100%);
    color: white;
    overflow-y: auto;
    padding-top: 1rem;
    z-index: 1000;
}

/* Scrollbar */
nav.sidebar::-webkit-scrollbar {
    width: 6px;
}
nav.sidebar::-webkit-scrollbar-thumb {
    background: #34495e;
    border-radius: 10px;
}
nav.sidebar::-webkit-scrollbar-thumb:hover {
    background: #4a69bd;
}

/* Brand */
.sidebar-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(45deg,#f39c12,#e67e22);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-align: center;
    border-bottom: 2px solid rgba(243,156,18,0.3);
    padding: 1rem 0;
}

/* Section headers */
.section-header {
    font-size: 0.85rem;
    font-weight: bold;
    color: #bdc3c7;
    text-transform: uppercase;
    padding: 0.5rem 1rem;
    letter-spacing: 1px;
    margin-top: 1rem;
}

/* Navigation links */
.nav-link {
    color: #ecf0f1 !important;
    padding: 0.75rem 1rem;
    margin: 0.125rem 0.75rem;
    border-left: 3px solid transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}
.nav-link i {
    width: 20px;
    text-align: center;
    transition: transform 0.3s ease;
}
.nav-link:hover {
    background: linear-gradient(90deg,#e74c3c,#c0392b);
    transform: translateX(3px);
    border-left-color: #e74c3c;
    box-shadow: 0 4px 8px rgba(231,76,60,0.3);
    color: white !important;
}
.nav-link.active {
    background: linear-gradient(90deg,#f39c12,#e67e22);
    border-left-color: #f39c12;
    box-shadow: 0 4px 12px rgba(243,156,18,0.4);
}

/* Mobile toggle button */
.toggle-btn {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 1100;
    border: none;
    border-radius: 8px;
    padding: 0.75rem;
    background: linear-gradient(45deg,#e74c3c,#c0392b);
    box-shadow: 0 4px 12px rgba(231,76,60,0.3);
}
@media(max-width:992px){
    body{margin-left:0;}
    nav.sidebar{display:none;}
    .toggle-btn{display:block;}
}
</style>
</head>
<body>

<!-- Mobile Hamburger -->
<button class="btn toggle-btn shadow-lg" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
    <i class="fas fa-bars fs-5"></i>
</button>

<!-- Desktop Sidebar -->
<nav class="sidebar d-flex flex-column">
    <div class="sidebar-brand">
        <i class="fas fa-user-shield me-2"></i> Admin Portal
    </div>

    <div class="p-3">
        <!-- Dashboard -->
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="admin_home.php">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- School Management -->
        <div class="section-header"><i class="fas fa-school me-1 text-warning"></i> SCHOOL MANAGEMENT</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item"><a class="nav-link" href="manage_departments.php"><i class="fas fa-sitemap me-2"></i> Departments</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_classes.php"><i class="fas fa-chalkboard me-2"></i> Classes</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php"><i class="fas fa-users me-2"></i> Users</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_subjects.php"><i class="fas fa-book me-2"></i> Subjects</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_enrollments.php"><i class="fas fa-user-graduate me-2"></i> Student Enrollments</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_assignments.php"><i class="fas fa-chalkboard-teacher me-2"></i> Teacher Enrollments</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_schedules.php"><i class="fas fa-calendar-alt me-2"></i> Manage Schedules</a></li>
        </ul>

        <!-- Finance & Services -->
        <div class="section-header"><i class="fas fa-dollar-sign me-1 text-primary"></i> FINANCE & SERVICES</div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item"><a class="nav-link" href="manage_fees.php"><i class="fas fa-money-bill-wave me-2"></i> Fee Management</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_payments.php"><i class="fas fa-money-check-dollar me-2"></i> Payments</a></li>
        </ul>

        

        <hr class="sidebar-divider">
        <!-- Logout -->
        <div class="mt-auto">
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>
</nav>

<!-- Mobile Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel"><i class="fas fa-user-shield me-2"></i> Admin Portal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <?php // Repeat all links here for mobile (same structure as desktop) ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Active link toggle
document.querySelectorAll('.nav-link').forEach(link=>{
    link.addEventListener('click', function(){
        document.querySelectorAll('.nav-link').forEach(l=>l.classList.remove('active'));
        this.classList.add('active');
    });
});
// Auto-close mobile sidebar
document.querySelectorAll('.offcanvas .nav-link').forEach(link=>{
    link.addEventListener('click', function(){
        bootstrap.Offcanvas.getInstance(document.getElementById('sidebarOffcanvas'))?.hide();
    });
});
</script>
</body>
</html>
