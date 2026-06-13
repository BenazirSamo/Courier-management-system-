<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Get admin details
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $admin_id"));

if(!$admin) {
    header("Location: logout.php");
    exit();
}

// Get all statistics in one query
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        (SELECT COUNT(*) FROM couriers) as total_couriers,
        (SELECT COUNT(*) FROM users WHERE role = 3) as total_agents,
        (SELECT COUNT(*) FROM customers) as total_customers,
        (SELECT SUM(price) FROM couriers) as total_revenue,
        (SELECT SUM(price) FROM couriers WHERE DATE(booking_date) = CURDATE()) as today_revenue,
        (SELECT COUNT(*) FROM couriers WHERE DATE(booking_date) = CURDATE()) as today_sales,
        (SELECT COUNT(*) FROM couriers WHERE status = 'Booked') as pending_couriers,
        (SELECT COUNT(*) FROM couriers WHERE status = 'In Transit') as in_transit_couriers,
        (SELECT COUNT(*) FROM couriers WHERE status = 'Delivered') as delivered_couriers
"));

// Set defaults
$total_couriers = $stats['total_couriers'] ?? 0;
$total_agents = $stats['total_agents'] ?? 0;
$total_customers = $stats['total_customers'] ?? 0;
$total_revenue = $stats['total_revenue'] ?? 0;
$today_revenue = $stats['today_revenue'] ?? 0;
$today_sales = $stats['today_sales'] ?? 0;
$pending_couriers = $stats['pending_couriers'] ?? 0;
$in_transit_couriers = $stats['in_transit_couriers'] ?? 0;
$delivered_couriers = $stats['delivered_couriers'] ?? 0;

// Weekly revenue data
$weeklyRevenue = [];
for($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $result = mysqli_query($conn, "SELECT SUM(price) AS total FROM couriers WHERE DATE(booking_date) = '$date'");
    $weeklyRevenue[] = $result->fetch_assoc()['total'] ?? 0;
}

// Status data for chart
$statusData = [$pending_couriers, $in_transit_couriers, $delivered_couriers];

// Recent couriers
$recentCouriers = mysqli_query($conn, "
    SELECT c.*, s.name AS sender_name, u.username AS agent_name
    FROM couriers c
    JOIN customers s ON c.sender_id = s.id
    JOIN agents a ON c.agent_id = a.id
    JOIN users u ON a.user_id = u.id
    ORDER BY c.id DESC LIMIT 5
");

// Recent customers
$recentCustomers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Courier Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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
        
        /* Stats Cards */
        .stat-box {
            text-align: center;
            padding: 25px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Status Badges */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .bg-booked {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .bg-transit {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .bg-delivered {
            background-color: #d1fae5;
            color: #065f46;
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
            }
            
            .page-header {
                padding: 20px 15px;
            }
            
            .stats-card {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .stat-value {
                font-size: 24px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .chart-container {
                height: 250px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .page-header {
                padding: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
            }
            
            .stat-box {
                padding: 15px 10px;
            }
            
            .stat-value {
                font-size: 20px;
            }
            
            .stat-label {
                font-size: 12px;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar, .mobile-menu-btn, .sidebar-overlay {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .stats-card {
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
                    <a class="nav-link active" href="index.php">
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
                    <a class="nav-link" href="manage-agent.php">
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
                    <?php echo strtoupper(substr($admin['username'] ?? 'A', 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $admin['username'] ?? 'Admin'; ?></h6>
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
                    <h1 class="h3 mb-2"><i class="fas fa-tachometer-alt text-orange me-2"></i>Dashboard</h1>
                    <p class="text-muted mb-0">Welcome to Courier Management System Admin Panel</p>
                </div>
                <div>
                    <span class="badge bg-success">
                        <i class="fas fa-calendar me-1"></i> <?php echo date('d M Y'); ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-card">
            <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i> Quick Statistics</h4>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #2196f3; color: white;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value"><?php echo $today_sales; ?></div>
                        <div class="stat-label">Today's Sales</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #4caf50; color: white;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-value"><?php echo $total_couriers; ?></div>
                        <div class="stat-label">Total Sales</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #ff9800; color: white;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-value">PKR <?php echo number_format($today_revenue, 2); ?></div>
                        <div class="stat-label">Today's Revenue</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #9c27b0; color: white;">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stat-value">PKR <?php echo number_format($total_revenue, 2); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card">
                    <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i> Courier Status Distribution</h4>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i> Weekly Revenue Trend</h4>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="stats-card">
                    <h4 class="mb-4"><i class="fas fa-shipping-fast me-2"></i> Courier Status Overview</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3" style="background: #fef3c7; border-radius: 8px;">
                                <i class="fas fa-clock fa-2x me-3" style="color: #92400e;"></i>
                                <div>
                                    <h4 class="mb-0"><?php echo $pending_couriers; ?></h4>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3" style="background: #dbeafe; border-radius: 8px;">
                                <i class="fas fa-truck fa-2x me-3" style="color: #1e40af;"></i>
                                <div>
                                    <h4 class="mb-0"><?php echo $in_transit_couriers; ?></h4>
                                    <small class="text-muted">In Transit</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center p-3" style="background: #d1fae5; border-radius: 8px;">
                                <i class="fas fa-check-circle fa-2x me-3" style="color: #065f46;"></i>
                                <div>
                                    <h4 class="mb-0"><?php echo $delivered_couriers; ?></h4>
                                    <small class="text-muted">Delivered</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card h-100">
                    <h4 class="mb-4"><i class="fas fa-users me-2"></i> System Users</h4>
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-user-tie fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="mb-0"><?php echo $total_agents; ?></h5>
                            <small class="text-muted">Total Agents</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-friends fa-2x text-success me-3"></i>
                        <div>
                            <h5 class="mb-0"><?php echo $total_customers; ?></h5>
                            <small class="text-muted">Total Customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Couriers -->
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fas fa-history me-2"></i> Recent Couriers</h4>
                <a href="manage-courier.php" class="btn btn-sm btn-orange">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>Tracking No</th>
                            <th>Sender</th>
                            <th>Agent</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($recentCouriers) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($recentCouriers)): ?>
                                <tr>
                                    <td><strong><?= $row['tracking_no'] ?></strong></td>
                                    <td><?= htmlspecialchars($row['sender_name']) ?></td>
                                    <td><?= htmlspecialchars($row['agent_name']) ?></td>
                                    <td><strong>PKR <?= number_format($row['price'], 2) ?></strong></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        if($row['status'] == 'Booked') $status_class = 'bg-booked';
                                        if($row['status'] == 'In Transit') $status_class = 'bg-transit';
                                        if($row['status'] == 'Delivered') $status_class = 'bg-delivered';
                                        ?>
                                        <span class="badge <?= $status_class ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a class="btn btn-sm btn-info" href="view-courier.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a class="btn btn-sm btn-warning" href="edit-courier.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No couriers found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Customers & Quick Actions -->
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0"><i class="fas fa-user-friends me-2"></i> Recent Customers</h4>
                        <a href="customers.php" class="btn btn-sm btn-orange">View All</a>
                    </div>
                    <?php if(mysqli_num_rows($recentCustomers) > 0): ?>
                        <?php while($customer = mysqli_fetch_assoc($recentCustomers)): ?>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div class="user-avatar me-3">
                                <?php echo strtoupper(substr($customer['name'] ?? 'C', 0, 1)); ?>
                            </div>
                            <div class="w-100">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-0"><?php echo htmlspecialchars($customer['name'] ?? 'Unknown'); ?></h6>
                                    <small><?php echo htmlspecialchars($customer['phone'] ?? ''); ?></small>
                                </div>
                                <span class="text-muted small"><?php echo htmlspecialchars($customer['email'] ?? ''); ?></span>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No customers found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="stats-card h-100">
                    <h4 class="mb-4"><i class="fas fa-bolt me-2"></i> Quick Actions</h4>
                    <div class="d-flex flex-column">
                        <a href="add-courier.php" class="btn btn-orange mb-2">
                            <i class="fas fa-plus-circle me-2"></i> Add New Courier
                        </a>
                        <a href="add-agent.php" class="btn btn-success mb-2">
                            <i class="fas fa-user-plus me-2"></i> Add New Agent
                        </a>
                        <a href="customers.php" class="btn btn-info mb-2">
                            <i class="fas fa-user me-2"></i> Add Customer
                        </a>
                        <a href="reports.php" class="btn btn-warning mb-2">
                            <i class="fas fa-chart-bar me-2"></i> Generate Report
                        </a>
                        <a href="export.php" class="btn btn-secondary">
                            <i class="fas fa-file-export me-2"></i> Export Data
                        </a>
                    </div>
                </div>
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
            
            // Charts
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Transit', 'Delivered'],
                    datasets: [{
                        data: <?php echo json_encode($statusData); ?>,
                        backgroundColor: [
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(13, 110, 253, 0.8)',
                            'rgba(25, 135, 84, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 193, 7, 1)',
                            'rgba(13, 110, 253, 1)',
                            'rgba(25, 135, 84, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const days = [];
            for(let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                days.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
            }
            
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Revenue (PKR)',
                        data: <?php echo json_encode($weeklyRevenue); ?>,
                        backgroundColor: 'rgba(255, 122, 0, 0.1)',
                        borderColor: 'rgba(255, 122, 0, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(255, 122, 0, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
</body>
</html>