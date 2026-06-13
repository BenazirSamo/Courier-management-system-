<?php
include("../includes/agent-auth.php");
include("../config/db.php");

// Only admin allowed
if ($_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

// Get courier ID from URL with validation
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0) {
    die("Invalid courier ID. <a href='manage-courier.php'>Go back</a>");
}

// Fetch courier details
$courier_query = mysqli_query($conn,"
SELECT c.*, s.name AS sender_name, s.phone AS sender_phone, s.address AS sender_address,
       r.name AS receiver_name, r.phone AS receiver_phone, r.address AS receiver_address,
       u.username AS agent_name, a.phone AS agent_phone
FROM couriers c
JOIN customers s ON c.sender_id = s.id
JOIN customers r ON c.receiver_id = r.id
JOIN agents a ON c.agent_id = a.id
JOIN users u ON a.user_id = u.id
WHERE c.id=$id
");

if(mysqli_num_rows($courier_query) == 0) {
    die("Courier not found. <a href='manage-courier.php'>Go back</a>");
}

$courier = mysqli_fetch_assoc($courier_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courier Details - Courier Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS matching add-agent.php theme -->
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
        
        .details-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .tracking-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #b6d4fe;
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
        
        /* Status Badges */
        .badge {
            padding: 8px 16px;
            font-weight: 500;
            border-radius: 4px;
            font-size: 14px;
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
        
        .tracking-no {
            font-family: monospace;
            font-weight: 700;
            font-size: 28px;
            color: var(--primary-blue);
            letter-spacing: 1px;
            text-align: center;
            margin: 15px 0;
            word-break: break-all;
        }
        
        /* Info Boxes */
        .info-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-title i {
            color: var(--accent-orange);
        }
        
        .info-content {
            font-size: 15px;
            color: var(--dark-gray);
        }
        
        .info-label {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 600;
            font-size: 16px;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            padding-left: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -8px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-orange);
        }
        
        .timeline-item:after {
            content: '';
            position: absolute;
            left: -3px;
            top: 17px;
            bottom: -20px;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item:last-child:after {
            display: none;
        }
        
        .timeline-date {
            font-size: 13px;
            color: #6c757d;
        }
        
        .timeline-text {
            font-size: 14px;
        }
        
        /* Price Box */
        .price-box {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        
        .price-label {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 10px;
        }
        
        .price-value {
            font-size: 36px;
            font-weight: 700;
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
                padding-top: 70px; /* Space for mobile menu button */
            }
            
            .page-header {
                padding: 20px 15px;
                margin-top: 20px;
            }
            
            .details-card {
                padding: 20px 15px;
            }
            
            .tracking-card {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .details-card {
                padding: 15px;
            }
            
            .tracking-card {
                padding: 15px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .d-grid.gap-2 .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .tracking-no {
                font-size: 22px;
            }
            
            .info-box {
                padding: 15px;
            }
            
            .price-value {
                font-size: 28px;
            }
            
            .timeline {
                padding-left: 20px;
            }
            
            .info-title {
                font-size: 15px;
            }
            
            .info-value {
                font-size: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }
            
            .details-card {
                padding: 15px 10px;
            }
            
            .tracking-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .page-header {
                margin-bottom: 20px;
            }
            
            .tracking-no {
                font-size: 20px;
            }
            
            .price-box {
                padding: 20px;
            }
            
            .price-value {
                font-size: 24px;
            }
            
            .info-label {
                font-size: 12px;
            }
            
            .timeline-text {
                font-size: 13px;
            }
            
            /* Adjust info boxes */
            .info-box .row .col-md-6,
            .info-box .row .col-12 {
                margin-bottom: 15px;
            }
            
            .badge {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 400px) {
            .tracking-no {
                font-size: 18px;
            }
            
            .timeline {
                padding-left: 15px;
            }
            
            .timeline-item {
                padding-left: 15px;
            }
            
            .info-box {
                padding: 12px;
            }
            
            .price-box {
                padding: 15px;
            }
            
            .price-value {
                font-size: 20px;
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
            
            .details-card, .tracking-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .btn, .d-grid, .page-header .btn {
                display: none !important;
            }
            
            .tracking-card {
                background: white !important;
                border: 1px solid #000 !important;
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
        <!-- Logo -->
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
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $_SESSION['username'] ?? 'Admin'; ?></h6>
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
                    <h1 class="h3 mb-2"><i class="fas fa-info-circle text-orange me-2"></i>Courier Details</h1>
                    <p class="text-muted mb-0">View detailed information about this courier</p>
                </div>
                <div>
                    <a href="manage-courier.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Couriers
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tracking Card -->
        <div class="tracking-card">
            <div class="text-center">
                <h5><i class="fas fa-barcode"></i> Tracking Information</h5>
                <div class="tracking-no"><?= $courier['tracking_no'] ?></div>
                <?php
                $status_class = '';
                $status_icon = '';
                if($courier['status'] == 'Booked') {
                    $status_class = 'bg-booked';
                    $status_icon = 'fa-clock';
                } elseif($courier['status'] == 'In Transit') {
                    $status_class = 'bg-transit';
                    $status_icon = 'fa-truck';
                } elseif($courier['status'] == 'Delivered') {
                    $status_class = 'bg-delivered';
                    $status_icon = 'fa-check-circle';
                }
                ?>
                <span class="badge <?= $status_class ?>">
                    <i class="fas <?= $status_icon ?> me-1"></i>
                    <?= htmlspecialchars($courier['status']) ?>
                </span>
                <div class="mt-2 text-muted">
                    <i class="fas fa-hashtag"></i> Courier ID: #<?= $id ?>
                </div>
            </div>
        </div>
        
        <!-- Courier Details -->
        <div class="details-card">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Sender Information -->
                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-user"></i> Sender Information
                        </div>
                        <div class="info-box">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">Name</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['sender_name']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['sender_phone']) ?></div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Address</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['sender_address']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Receiver Information -->
                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-user-friends"></i> Receiver Information
                        </div>
                        <div class="info-box">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">Name</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['receiver_name']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['receiver_phone']) ?></div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Address</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['receiver_address']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Route Information -->
                    <div class="info-section">
                        <div class="info-title">
                            <i class="fas fa-route"></i> Route Information
                        </div>
                        <div class="info-box">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">From City</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['from_city']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">To City</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['to_city']) ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Assigned Agent</div>
                                    <div class="info-value"><?= htmlspecialchars($courier['agent_name']) ?></div>
                                    <small class="text-muted">Phone: <?= htmlspecialchars($courier['agent_phone']) ?></small>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Booking Date</div>
                                    <div class="info-value">
                                        <?php 
                                        if(!empty($courier['booking_date']) && $courier['booking_date'] != '0000-00-00') {
                                            echo date('d M Y', strtotime($courier['booking_date']));
                                        } else {
                                            echo 'Not specified';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Price Information -->
                    <div class="price-box mb-4">
                        <div class="price-label">Total Price</div>
                        <div class="price-value">PKR <?= number_format($courier['price'], 2) ?></div>
                    </div>
                    
                    <!-- Delivery Timeline -->
                    <div class="info-box">
                        <div class="info-title">
                            <i class="fas fa-history"></i> Delivery Timeline
                        </div>
                        <div class="timeline mt-3">
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <?php 
                                    if(!empty($courier['booking_date']) && $courier['booking_date'] != '0000-00-00') {
                                        echo date('d M Y', strtotime($courier['booking_date']));
                                    } else {
                                        echo 'Date not set';
                                    }
                                    ?>
                                </div>
                                <div class="timeline-text">
                                    <strong>Courier Booked</strong>
                                    <p class="mb-0">Shipment registered in system</p>
                                </div>
                            </div>
                            
                            <?php if($courier['status'] == 'In Transit' || $courier['status'] == 'Delivered'): ?>
                            <div class="timeline-item">
                                <div class="timeline-date">In Progress</div>
                                <div class="timeline-text">
                                    <strong>In Transit</strong>
                                    <p class="mb-0">Package is on the way</p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($courier['status'] == 'Delivered'): ?>
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <?php 
                                    if(!empty($courier['delivery_date']) && $courier['delivery_date'] != '0000-00-00') {
                                        echo date('d M Y', strtotime($courier['delivery_date']));
                                    } else {
                                        echo 'Date not set';
                                    }
                                    ?>
                                </div>
                                <div class="timeline-text">
                                    <strong>Delivered</strong>
                                    <p class="mb-0">Package delivered successfully</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="info-box mt-4">
                        <div class="info-title">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="edit-courier.php?id=<?= $id ?>" class="btn btn-orange">
                                <i class="fas fa-edit me-2"></i> Edit Courier
                            </a>
                            <a href="manage-courier.php" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i> Back to List
                            </a>
                            <button class="btn btn-outline-info" onclick="window.print()">
                                <i class="fas fa-print me-2"></i> Print Details
                            </button>
                        </div>
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
            const mainContent = document.getElementById('mainContent');
            
            // Function to show/hide sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            // Mobile menu button click
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            
            // Overlay click to close sidebar
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 991.98) {
                    if (!sidebar.contains(event.target) && 
                        !mobileMenuBtn.contains(event.target) && 
                        sidebar.classList.contains('show')) {
                        toggleSidebar();
                    }
                }
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
            
            // Auto-hide sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
            
            // Print button functionality
            const printButton = document.querySelector('button[onclick="window.print()"]');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    // Add a small delay before print dialog appears
                    setTimeout(() => {
                        window.print();
                    }, 100);
                });
            }
            
            // Share tracking number (optional feature)
            const shareTrackingBtn = document.createElement('button');
            shareTrackingBtn.className = 'btn btn-outline-success w-100 mb-2';
            shareTrackingBtn.innerHTML = '<i class="fas fa-share-alt me-2"></i> Share Tracking';
            
            shareTrackingBtn.addEventListener('click', function() {
                const trackingNo = document.querySelector('.tracking-no').textContent;
                const url = window.location.href;
                
                if (navigator.share) {
                    // Web Share API (mobile devices)
                    navigator.share({
                        title: 'Courier Tracking',
                        text: `Track your courier: ${trackingNo}`,
                        url: url
                    });
                } else {
                    // Fallback for desktop
                    navigator.clipboard.writeText(`${trackingNo}\n${url}`).then(() => {
                        alert('Tracking number copied to clipboard!');
                    });
                }
            });
            
            // Add share button to quick actions
            const quickActionsDiv = document.querySelector('.d-grid.gap-2');
            if (quickActionsDiv) {
                quickActionsDiv.prepend(shareTrackingBtn);
            }
            
            // Status update notification
            const statusBadge = document.querySelector('.badge');
            if (statusBadge) {
                const statusText = statusBadge.textContent.trim();
                
                // Check if status needs update
                if (statusText === 'Booked' || statusText === 'In Transit') {
                    const statusAlert = document.createElement('div');
                    statusAlert.className = 'alert alert-info alert-dismissible fade show mt-3';
                    statusAlert.innerHTML = `
                        <i class="fas fa-info-circle me-2"></i>
                        This courier is currently <strong>${statusText}</strong>. 
                        You can update the status from the Edit Courier page.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    // Insert after tracking card
                    const trackingCard = document.querySelector('.tracking-card');
                    if (trackingCard) {
                        trackingCard.parentNode.insertBefore(statusAlert, trackingCard.nextSibling);
                    }
                }
            }
        });
    </script>
</body>
</html>