<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../public/login.php");
    exit();
}

$email = $_SESSION['email'] ?? '';
$username = $_SESSION['username'];

// Get customer
$stmt = $conn->prepare("SELECT * FROM customers WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$customer_id = $customer['id'] ?? 0;

$tracking_no = $_GET['tracking'] ?? '';
$error = '';
$courier = null;
$status = null;

// If tracking number is provided
if ($tracking_no != '') {
    // Get courier (security check)
    $sql = "SELECT c.*, 
                   s.name AS sender_name, s.phone AS sender_phone,
                   r.name AS receiver_name, r.phone AS receiver_phone,
                   a.branch_name AS agent_branch
            FROM couriers c
            LEFT JOIN customers s ON c.sender_id = s.id
            LEFT JOIN customers r ON c.receiver_id = r.id
            LEFT JOIN agents a ON c.agent_id = a.id
            WHERE c.tracking_no = ?
LIMIT 1"
;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tracking_no);
    $stmt->execute();
    $courier = $stmt->get_result()->fetch_assoc();

    if (!$courier) {
        $error = "No courier found with this tracking number or you don't have access.";
    } else {
        // Latest status
        $stmt = $conn->prepare(
            "SELECT status, location, updated_at 
             FROM courier_status 
             WHERE courier_id=? 
             ORDER BY updated_at DESC LIMIT 1"
        );
        $stmt->bind_param("i", $courier['id']);
        $stmt->execute();
        $status = $stmt->get_result()->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Same CSS as Dashboard -->
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
        
        /* ===== TABLE CARD ===== */
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
            
            .table-card {
                padding: 20px 15px;
            }
        }
        
        /* ===== INVOICE SPECIFIC STYLES ===== */
        .invoice-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .invoice-header {
            border-bottom: 3px solid var(--accent-orange);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .status-badge {
            background: var(--accent-orange);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-blue);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .customer-box {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--accent-orange);
        }
        
        /* ===== PRINT SPECIFIC STYLES ===== */
        @media print {
            .sidebar,
            .mobile-menu-btn,
            .sidebar-overlay,
            .page-header,
            footer,
            .print-btn,
            .no-print,
            .btn {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            
            .table-card {
                margin: 0 !important;
                padding: 20px !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                border: 1px solid #ddd !important;
            }
            
            @page {
                size: A4;
                margin: 0.5in;
            }
            
            body {
                background: white !important;
                color: black !important;
                font-size: 12pt !important;
            }
            
            .invoice-container {
                box-shadow: none !important;
                border: none !important;
                padding: 20px !important;
            }
            
            * {
                visibility: visible !important;
            }
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
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
    
    <!-- Sidebar - REMOVED New Shipment link -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <!-- Logo -->
        <div class="logo">
            <img src="../assets/images/new logo.png" alt="Courier Logo">
            <div class="mt-2 text-white-50 small">Customer Panel</div>
        </div>
        
        <!-- Navigation -->
        <div class="flex-grow-1 p-3">
            <nav class="nav flex-column">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="track.php" class="nav-link">
                    <i class="fas fa-search-location"></i> Track Courier
                </a>
                <a href="view-status.php" class="nav-link">
                    <i class="fas fa-boxes"></i> View Shipments
                </a>
                <a href="print.php" class="nav-link active">
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
                        <i class="fas fa-print me-2" style="color: var(--accent-orange);"></i>
                        Print Invoice
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?php echo date('l, F j, Y'); ?>
                    </p>
                </div>
                <?php if($customer): ?>
                <div class="mt-2 mt-md-0">
                    <p class="mb-1">
                        <i class="fas fa-user me-2"></i>
                        <?php echo htmlspecialchars($customer['name'] ?? 'N/A'); ?>
                    </p>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-envelope me-2"></i>
                        <?php echo htmlspecialchars($customer['email'] ?? ''); ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if($tracking_no == ''): ?>
        <!-- Show tracking form when no tracking number -->
        <div class="table-card">
            <h5 class="mb-4">
                <i class="fas fa-search me-2" style="color: var(--accent-orange);"></i>
                Enter Tracking Number
            </h5>
            <p class="text-muted mb-4">Enter your courier tracking number to print invoice</p>
            
            <form method="GET" action="" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-barcode"></i>
                        </span>
                        <input type="text" name="tracking" class="form-control form-control-lg" 
                               placeholder="Enter Tracking Number (e.g., TRK20250207ABC123)" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-orange w-100 h-100">
                        <i class="fas fa-search me-2"></i> Get Invoice
                    </button>
                </div>
            </form>
            
            <div class="mt-4 p-3 bg-light rounded">
                <h6><i class="fas fa-info-circle me-2 text-primary"></i> How to print:</h6>
                <ul class="text-muted mb-0">
                    <li>Enter your tracking number above</li>
                    <li>Click "Get Invoice" to view the invoice</li>
                    <li>Click "Print Invoice" button to print</li>
                    <li>Or press <kbd>Ctrl + P</kbd> to print directly</li>
                </ul>
            </div>
        </div>
        
        <?php elseif($error): ?>
        <!-- Show error -->
        <div class="table-card">
            <div class="alert alert-danger">
                <h5 class="mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error
                </h5>
                <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
            </div>
            <a href="print.php" class="btn btn-orange">
                <i class="fas fa-arrow-left me-2"></i> Try Another Tracking Number
            </a>
        </div>
        
        <?php elseif($courier): ?>
        <!-- Invoice Content -->
        <div class="table-card">
            <!-- Invoice Header -->
            <div class="invoice-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="company-logo mb-2">COURIER EXPRESS</div>
                        <p class="mb-1 text-muted">Courier Management System</p>
                        <p class="mb-1 small">
                            <i class="fas fa-map-marker-alt me-1"></i> 123 Main Street, Karachi
                        </p>
                        <p class="mb-0 small">
                            <i class="fas fa-phone me-1"></i> 021-1234567 | 
                            <i class="fas fa-envelope me-1 ms-2"></i> info@courierexpress.com
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="invoice-title mb-2">COURIER INVOICE</div>
                        <div class="status-badge mb-2">
                            <?php echo htmlspecialchars($status['status'] ?? $courier['status']); ?>
                        </div>
                        <p class="mb-1">
                            <strong>Invoice Date:</strong> <?php echo date("d M Y"); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Invoice #:</strong> <?php echo htmlspecialchars($courier['tracking_no']); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Tracking Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="section-title">TRACKING INFORMATION</div>
                    <div class="customer-box">
                        <p class="mb-1"><strong>Tracking Number:</strong> <?php echo htmlspecialchars($courier['tracking_no']); ?></p>
                        <p class="mb-1"><strong>Booking Date:</strong> <?php echo date("d M Y", strtotime($courier['booking_date'])); ?></p>
                        <p class="mb-0"><strong>Route:</strong> <?php echo htmlspecialchars($courier['from_city']); ?> → <?php echo htmlspecialchars($courier['to_city']); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-title">AGENT INFORMATION</div>
                    <div class="customer-box">
                        <p class="mb-1"><strong>Agent Branch:</strong> <?php echo htmlspecialchars($courier['agent_branch'] ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Status:</strong> <?php echo htmlspecialchars($status['status'] ?? $courier['status']); ?></p>
                        <p class="mb-0"><strong>Last Updated:</strong> <?php echo date("d M Y h:i A", strtotime($status['updated_at'] ?? $courier['booking_date'])); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Sender & Receiver -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="section-title">SENDER DETAILS</div>
                    <div class="customer-box">
                        <p class="mb-1"><strong><?php echo htmlspecialchars($courier['sender_name']); ?></strong></p>
                        <p class="mb-1">
                            <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($courier['sender_phone']); ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($courier['from_city']); ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-title">RECEIVER DETAILS</div>
                    <div class="customer-box">
                        <p class="mb-1"><strong><?php echo htmlspecialchars($courier['receiver_name']); ?></strong></p>
                        <p class="mb-1">
                            <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($courier['receiver_phone']); ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($courier['to_city']); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Shipment Details Table -->
            <div class="mb-4">
                <div class="section-title">SHIPMENT DETAILS</div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>From City</th>
                                <th>To City</th>
                                <th>Price (Rs)</th>
                                <th>Delivery Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Courier Service</td>
                                <td><?php echo htmlspecialchars($courier['from_city']); ?></td>
                                <td><?php echo htmlspecialchars($courier['to_city']); ?></td>
                                <td>Rs. <?php echo number_format($courier['price'], 2); ?></td>
                                <td>
                                    <?php
                                        echo $courier['delivery_date']
                                        ? date("d M Y", strtotime($courier['delivery_date']))
                                        : "To be confirmed";
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Payment Summary -->
            <div class="row">
                <div class="col-md-6">
                    <div class="section-title">PAYMENT SUMMARY</div>
                    <table class="table">
                        <tr>
                            <td><strong>Base Fare:</strong></td>
                            <td class="text-end">Rs. <?php echo number_format($courier['price'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tax (0%):</strong></td>
                            <td class="text-end">Rs. 0.00</td>
                        </tr>
                        <tr class="table-active">
                            <th><strong>Total Amount:</strong></th>
                            <th class="text-end">Rs. <?php echo number_format($courier['price'], 2); ?></th>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="section-title">NOTES</div>
                    <div class="customer-box">
                        <p class="mb-2"><strong>Payment Terms:</strong></p>
                        <p class="mb-1 small">• Payment due upon delivery</p>
                        <p class="mb-1 small">• Cash on delivery accepted</p>
                        <p class="mb-0 small">• Online payment available</p>
                    </div>
                </div>
            </div>
            
            <!-- Barcode & Footer -->
            <div class="mt-4 pt-3 border-top">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>SCAN TO TRACK</strong></p>
                        <div class="text-center p-3 border rounded">
                            <div style="font-family: 'Courier New', monospace; font-size: 20px; letter-spacing: 2px;">
                                || <?php echo htmlspecialchars($courier['tracking_no']); ?> ||
                            </div>
                            <small class="text-muted">Track online: localhost/courier/track.php?tracking=<?php echo urlencode($courier['tracking_no']); ?></small>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-2"><strong>AUTHORIZED SIGNATURE</strong></p>
                        <div class="text-center p-3 border rounded d-inline-block">
                            <p class="mb-0">_________________________</p>
                            <small class="text-muted">Courier Express</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-4 pt-3 border-top text-center">
                <p class="mb-1 text-muted">Thank you for choosing Courier Express!</p>
                <p class="mb-0 small text-muted">This is a computer generated invoice, no signature required.</p>
                <p class="mb-0 small text-muted">Printed on: <?php echo date("d M Y h:i A"); ?></p>
            </div>
            
            <!-- Print Button -->
            <div class="mt-4 text-center no-print">
                <button onclick="window.print()" class="btn btn-orange btn-lg">
                    <i class="fas fa-print me-2"></i> Print Invoice
                </button>
                <a href="track.php?tracking=<?php echo urlencode($courier['tracking_no']); ?>" class="btn btn-outline-primary btn-lg ms-2">
                    <i class="fas fa-eye me-2"></i> View Details
                </a>
                <a href="print.php" class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="fas fa-sync me-2"></i> New Search
                </a>
            </div>
        </div>
        
        <!-- Floating Print Button -->
        <div class="print-btn no-print">
            <button onclick="window.print()" class="btn btn-orange btn-lg shadow">
                <i class="fas fa-print me-2"></i> Print
            </button>
        </div>
        <?php endif; ?>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 border-top no-print">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted">© <?php echo date('Y'); ?> Courier Management System</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">Print Invoice v1.0</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Same JavaScript as Dashboard -->
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
            
            const trackingInput = document.querySelector('input[name="tracking"]');
            if (trackingInput) {
                trackingInput.focus();
            }
        });
    </script>
</body>
</html>