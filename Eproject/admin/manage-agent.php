<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get all agents
$agents = mysqli_query($conn, "
    SELECT a.*, u.username, u.email, u.city
    FROM agents a
    JOIN users u ON a.user_id = u.id
    WHERE u.role = 3
    ORDER BY a.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agents - Admin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Your Original CSS - Exactly as you had it -->
    <style>
        :root {
            --primary-blue: #0b1c2d;
            --secondary-blue: #1a365d;
            --accent-orange: #ff7a00;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            overflow-x: hidden;
        }
        
        .text-orange {
            color: var(--accent-orange) !important;
        }
        
        .btn-orange {
            background-color: var(--accent-orange);
            border-color: var(--accent-orange);
            color: white;
        }
        
        .btn-orange:hover {
            background-color: #e56a00;
            border-color: #e56a00;
            color: white;
        }
        
        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary-blue);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            font-size: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .mobile-menu-btn:hover {
            background: var(--secondary-blue);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--primary-blue);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--accent-orange);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-orange);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .form-control:focus {
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 0.25rem rgba(255, 122, 0, 0.25);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--accent-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        /* Table Styles */
        .table-custom thead {
            background: var(--primary-blue);
            color: white;
        }
        
        .table-custom thead th {
            padding: 15px;
            border: none;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(255, 122, 0, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        /* Agent Avatar */
        .agent-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        
        .agent-name {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .agent-email {
            font-size: 14px;
            color: #6c757d;
        }
        
        .agent-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Logo styling */
        .sidebar img {
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding-top: 70px;
            }
            
            .page-header {
                padding: 20px 15px;
                margin-top: 20px;
            }
            
            .table-card {
                padding: 20px 15px;
            }
            
            .table-card .row {
                flex-direction: column;
            }
            
            .table-card .col-md-8,
            .table-card .col-md-4 {
                width: 100%;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .table-card {
                padding: 15px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 5px;
            }
            
            .agent-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .agent-avatar {
                align-self: flex-start;
            }
            
            .table-responsive {
                font-size: 14px;
            }
            
            .table-custom th,
            .table-custom td {
                padding: 10px 8px;
            }
            
            .table-custom th:nth-child(4),
            .table-custom td:nth-child(4) {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }
            
            .table-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .page-header {
                margin-bottom: 20px;
            }
            
            .agent-name {
                font-size: 14px;
            }
            
            .agent-email {
                font-size: 12px;
            }
            
            .table-custom th:nth-child(3),
            .table-custom td:nth-child(3) {
                display: none;
            }
        }
        
        @media (max-width: 400px) {
            .table-custom th:nth-child(5),
            .table-custom td:nth-child(5) {
                display: none;
            }
            
            .action-buttons {
                min-width: 100px;
            }
            
            .agent-info {
                gap: 5px;
            }
        }
        
        @media print {
            .sidebar, .mobile-menu-btn, .sidebar-overlay {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .table-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" 
                 alt="Courier MS Logo"
                 style="max-width: 140px; height: auto;"
                 class="mb-2">
            <div class="text-white-50 small">Admin Panel</div>
        </div>
        
        <!-- Navigation -->
        <div class="flex-grow-1 p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-courier.php">
                        <i class="fas fa-plus-circle"></i> Add Courier
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-courier.php">
                        <i class="fas fa-boxes"></i> Manage Couriers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-agent.php">
                        <i class="fas fa-user-plus"></i> Add Agent
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="manage-agent.php">
                        <i class="fas fa-users"></i> Manage Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customers.php">
                        <i class="fas fa-user-friends"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-booking.php">
                        <i class="fas fa-sms"></i> Booking SMS
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-delivery.php">
                        <i class="fas fa-truck"></i> Delivery SMS
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- User Info -->
        <div class="p-3 border-top mt-auto">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-3">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $username; ?></h6>
                    <small class="text-white-50">Administrator</small>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-light w-100 mt-3">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-users text-orange me-2"></i>Manage Agents</h1>
                    <p class="text-muted mb-0">View and manage all delivery agents</p>
                </div>
                <div>
                    <a href="add-agent.php" class="btn btn-orange">
                        <i class="fas fa-user-plus me-1"></i> Add New Agent
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="table-card mb-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search agents by name, email, or branch...">
                        <button class="btn btn-orange" type="button" id="searchButton">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-grid">
                        <a href="add-agent.php" class="btn btn-success">
                            <i class="fas fa-user-plus me-1"></i> Quick Add Agent
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Agents Table -->
        <div class="table-card">
            <div class="mb-4">
                <h4 class="text-dark mb-3"><i class="fas fa-list me-2"></i>Agents List</h4>
                <p class="text-muted">Total <?php echo mysqli_num_rows($agents); ?> agents found</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-custom" id="agentsTable">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Branch</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($agents) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($agents)): ?>
                                <tr>
                                    <td>
                                        <div class="agent-info">
                                            <div class="agent-avatar">
                                                <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="agent-name"><?= htmlspecialchars($row['username']) ?></div>
                                                <div class="agent-email">Agent ID: #<?= $row['id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['branch_name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= htmlspecialchars($row['city']) ?></span>
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted me-1"></i>
                                        <?= htmlspecialchars($row['phone']) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        <?= htmlspecialchars($row['email']) ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a class="btn btn-sm btn-warning" href="edit-agent.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-sm btn-danger" 
                                               href="delete-agent.php?id=<?= $row['id'] ?>"
                                               onclick="return confirm('Are you sure you want to delete this agent?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-user-tie fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No agents found. Add your first agent above.</p>
                                    <a href="add-agent.php" class="btn btn-orange mt-3">
                                        <i class="fas fa-user-plus me-1"></i> Add First Agent
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted">© <?php echo date('Y'); ?> Courier Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">Admin Panel v1.0</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Responsive Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const agentsTable = document.getElementById('agentsTable');
            
            if (searchInput && searchButton && agentsTable) {
                function performSearch() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const rows = agentsTable.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
                
                searchButton.addEventListener('click', performSearch);
                
                searchInput.addEventListener('keyup', function(event) {
                    if (event.key === 'Enter') {
                        performSearch();
                    }
                });
                
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 300);
                });
            }
        });
    </script>
</body>
</html>