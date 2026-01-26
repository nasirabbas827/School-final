<!DOCTYPE html>
<html lang="en">
<head>
    <title>EduControl | Smart School Management System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --dark-navy: #1e293b;
            --light-bg: #f8fafc;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            padding: 6rem 1rem;
            border-bottom-right-radius: 100px;
        }

        .hero h1 {
            font-weight: 800;
            color: var(--dark-navy);
            font-size: clamp(2rem, 6vw, 3.5rem);
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: 1.25rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto 2.5rem;
        }

        .btn-main {
            padding: 1rem 2.5rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-admin {
            background-color: var(--dark-navy);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3);
        }

        .btn-admin:hover {
            background-color: #0f172a;
            color: white;
            transform: translateY(-2px);
        }

        /* Feature Section */
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-weight: 700;
            color: var(--dark-navy);
        }

        .feature-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            padding: 2.5rem 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: #eff6ff;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .feature-card h5 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            font-size: 0.95rem;
            color: #64748b;
        }

        .feature-list li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .feature-list li i {
            color: #10b981;
            margin-right: 10px;
            font-size: 0.8rem;
        }

        /* Footer */
        footer {
            background: var(--dark-navy);
            color: #94a3b8;
            padding: 4rem 0 2rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #334155;
            color: white;
            border-radius: 50%;
            margin: 0 10px;
            transition: 0.3s;
        }

        .social-link:hover {
            background: var(--primary-blue);
            color: white;
        }
    </style>
</head>
<body>

    <header class="hero text-center">
        <div class="container">
            <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">v2.0 Web-Based Automation</span>
            <h1>Manage Your School <br><span class="text-primary">Smarter, Not Harder.</span></h1>
            <p>A centralized platform to automate admissions, teacher allocations, fee tracking, and schedules with absolute precision.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="admin/admin_login.php" class="btn btn-main btn-admin">
                    <i class="fas fa-lock me-2"></i> Administrator Portal
                </a>
            </div>
        </div>
    </header>

    <section class="container my-5 py-5">
        <div class="section-title">
            <h2>Comprehensive Management Modules</h2>
            <p>Designed to cover every administrative touchpoint in the academic lifecycle.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-user-graduate"></i></div>
                    <h5>Student Admission</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Digital Profile Management</li>
                        <li><i class="fas fa-check"></i> Enrollment History Tracking</li>
                        <li><i class="fas fa-check"></i> Academic Status Monitoring</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h5>Teacher Allocation</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Subject & Class Assignment</li>
                        <li><i class="fas fa-check"></i> Load Management</li>
                        <li><i class="fas fa-check"></i> Performance Overview</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-book-open"></i></div>
                    <h5>Curriculum Control</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Department-Course Linking</li>
                        <li><i class="fas fa-check"></i> Dynamic Subject Adding</li>
                        <li><i class="fas fa-check"></i> Credit Hour Management</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h5>Fee Management</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Payment History Logs</li>
                        <li><i class="fas fa-check"></i> Automated Receipt Generation</li>
                        <li><i class="fas fa-check"></i> Financial Reporting</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-calendar-alt"></i></div>
                    <h5>Smart Timetables</h5>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Conflict Prevention Engine</li>
                        <li><i class="fas fa-check"></i> Real-time Schedule Updates</li>
                        <li><i class="fas fa-check"></i> Teacher/Student Specific Views</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box  "><i class="fas fa-chart-line"></i></div>
                    <h5 class="">Central Analytics</h5>
                    <ul class="feature-list   text-opacity-75">
                        <li><i class="fas fa-check  "></i> Daily Attendance Reports</li>
                        <li><i class="fas fa-check "></i> Revenue Summaries</li>
                        <li><i class="fas fa-check "></i> System Audit Logs</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <div class="bg-white p-5 rounded-4 shadow-sm border-start border-primary border-5">
            <h4 class="fw-bold mb-3">System Abstract</h4>
            <p class="mb-0 text-muted">
                Our School Management System is built using a robust stack (PHP, MySQL, Bootstrap) to reduce manual paperwork and increase institutional productivity. By centralizing data, we ensure that administrators have a 360-degree view of their institution's health, from financial solvency to academic excellence.
            </p>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <div class="mb-4">
                <a href="#" class="social-link text-decoration-none"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link text-decoration-none"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link text-decoration-none"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p class="mb-1">Built with PHP & MySQL</p>
            <p class="small">© 2026 EduControl Smart School System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>