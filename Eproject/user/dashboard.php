<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';

// Get customer data using prepared statement
$customer_query = "SELECT * FROM customers WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($customer_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$customer_result = $stmt->get_result();
$customer = $customer_result->fetch_assoc();
$customer_id = $customer['id'] ?? 0;

// Initialize counts
$sent_count = 0;
$received_count = 0;
$pending_count = 0;

if ($customer_id > 0) {
    // Get statistics using prepared statements
    $sent_query = "SELECT COUNT(*) as count FROM couriers WHERE sender_id = ?";
    $stmt = $conn->prepare($sent_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $sent_count = $stmt->get_result()->fetch_assoc()['count'] ?? 0;

    $received_query = "SELECT COUNT(*) as count FROM couriers WHERE receiver_id = ?";
    $stmt = $conn->prepare($received_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $received_count = $stmt->get_result()->fetch_assoc()['count'] ?? 0;

    $pending_query = "SELECT COUNT(*) as count FROM couriers WHERE (sender_id = ? OR receiver_id = ?) AND status != 'Delivered'";
    $stmt = $conn->prepare($pending_query);
    $stmt->bind_param("ii", $customer_id, $customer_id);
    $stmt->execute();
    $pending_count = $stmt->get_result()->fetch_assoc()['count'] ?? 0;

    // Recent shipments
    $recent_query = "SELECT c.*, s.name as sender_name, r.name as receiver_name 
                    FROM couriers c
                    LEFT JOIN customers s ON c.sender_id = s.id
                    LEFT JOIN customers r ON c.receiver_id = r.id
                    WHERE c.sender_id = ? OR c.receiver_id = ?
                    ORDER BY c.id DESC LIMIT 5";
    $stmt = $conn->prepare($recent_query);
    $stmt->bind_param("ii", $customer_id, $customer_id);
    $stmt->execute();
    $recent_result = $stmt->get_result();
} else {
    $recent_result = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Track & View Shipments</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --primary-blue: #0b1c2d;
            --secondary-blue: #1a365d;
            --accent-orange: #ff7a00;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --sidebar-width: 250px;
        }
        
        /* ===== BASIC RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            overflow-x: hidden;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-blue);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .logo img {
            max-width: 150px;
            height: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--accent-orange);
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
        }
        
        .user-info-sidebar {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
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
            font-size: 18px;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }
        
        /* ===== HEADER ===== */
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-orange);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        /* ===== STATS CARDS ===== */
        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .stats-card:hover {
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
        }
        
        .stat-count {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        
        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        /* ===== BADGES ===== */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .badge-booked {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-transit {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        /* ===== BUTTONS ===== */
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
        
        .action-btn {
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
            display: block;
            text-decoration: none;
            color: white;
            margin-bottom: 10px;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: white;
        }
        
        /* ===== MOBILE MENU BUTTON ===== */
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
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
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
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 20px 15px;
            }
            
            .stats-card,
            .table-card {
                padding: 20px 15px;
            }
            
            .stat-count {
                font-size: 24px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 12px;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        .no-data {
            padding: 40px 20px;
            text-align: center;
        }
        
        .no-data i {
            font-size: 48px;
            color: #6c757d;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <!-- Logo -->
        <div class="logo">
            <img src="../assets/images/new logo.png" alt="Courier Logo">
            <div class="mt-2 text-white-50 small">Customer Panel</div>
        </div>
        
        <!-- Navigation - REMOVED New Shipment link -->
        <div class="flex-grow-1 p-3">
            <nav class="nav flex-column">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="track.php" class="nav-link">
                    <i class="fas fa-search-location"></i> Track Courier
                </a>
                <a href="view-status.php" class="nav-link">
                    <i class="fas fa-boxes"></i> View Shipments
                </a>
                <a href="print.php" class="nav-link">
                    <i class="fas fa-print"></i> Print Invoice
                </a>
            </nav>
        </div>
        
        <!-- User Info -->
        <div class="user-info-sidebar">
            <div class="d-flex align-items-center mb-3">
                <div class="user-avatar me-3">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo htmlspecialchars($username); ?></h6>
                    <small class="text-white-50">Customer</small>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="fas fa-tachometer-alt me-2" style="color: var(--accent-orange);"></i>
                        Welcome, <?php echo htmlspecialchars($username); ?>!
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?php echo date('l, F j, Y'); ?>
                    </p>
                </div>
                <?php if($customer): ?>
                <div class="mt-2 mt-md-0">
                    <p class="mb-1">
                        <i class="fas fa-phone me-2"></i>
                        <?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?>
                    </p>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-envelope me-2"></i>
                        <?php echo htmlspecialchars($customer['email'] ?? ''); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-icon" style="background: #e3f2fd; color: #2196f3;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-count"><?php echo $sent_count; ?></div>
                    <div class="stat-label">Sent Shipments</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-icon" style="background: #e8f5e9; color: #4caf50;">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="stat-count"><?php echo $received_count; ?></div>
                    <div class="stat-label">Received Shipments</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-icon" style="background: #fff3e0; color: #ff9800;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-count"><?php echo $pending_count; ?></div>
                    <div class="stat-label">Pending Shipments</div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions - UPDATED to only Track, View, Print -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-card">
                    <h5 class="mb-4">
                        <i class="fas fa-bolt me-2" style="color: var(--accent-orange);"></i>
                        Quick Actions
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="track.php" class="action-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-search-location me-2"></i> Track Courier
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="view-status.php" class="action-btn" style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);">
                                <i class="fas fa-boxes me-2"></i> View Shipments
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="print.php" class="action-btn" style="background: linear-gradient(135deg, #ff7a00 0%, #cc6200 100%);">
                                <i class="fas fa-print me-2"></i> Print Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Shipments -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2" style="color: var(--accent-orange);"></i>
                            Recent Shipments
                        </h5>
                        <a href="view-status.php" class="btn btn-sm btn-orange">
                            <i class="fas fa-eye me-1"></i> View All
                        </a>
                    </div>
                    
                    <?php if($recent_result && $recent_result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tracking No</th>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $recent_result->fetch_assoc()): 
                                    $status_class = 'badge-booked';
                                    if($row['status'] == 'In Transit') $status_class = 'badge-transit';
                                    if($row['status'] == 'Delivered') $status_class = 'badge-delivered';
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['tracking_no']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['from_city']); ?> → <?php echo htmlspecialchars($row['to_city']); ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="track.php?tracking=<?php echo urlencode($row['tracking_no']); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="print.php?tracking=<?php echo urlencode($row['tracking_no']); ?>" 
                                               class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-box-open"></i>
                        <h5 class="mt-3">No shipments found</h5>
                        <p class="text-muted mb-4">You don't have any shipments yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted">© <?php echo date('Y'); ?> Courier Management System</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">Customer Dashboard - Track Only</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
            
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 991.98) {
                    if (!sidebar.contains(event.target) && 
                        !mobileMenuBtn.contains(event.target) && 
                        sidebar.classList.contains('show')) {
                        toggleSidebar();
                    }
                }
            });
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
            
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
            
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991.98) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
</body>
</html>