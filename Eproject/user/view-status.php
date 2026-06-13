<?php
session_start();
include("../config/db.php");

// User authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../public/login.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email    = $_SESSION['email'] ?? '';

// Get customer
$customer_q = "SELECT * FROM customers WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($customer_q);
$stmt->bind_param("s", $email);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$customer_id = $customer['id'] ?? 0;

// Get all shipments (sent + received)
$sql = "SELECT c.*, 
               s.name AS sender_name, 
               r.name AS receiver_name
        FROM couriers c
        LEFT JOIN customers s ON c.sender_id = s.id
        LEFT JOIN customers r ON c.receiver_id = r.id
        WHERE c.sender_id = ? OR c.receiver_id = ?
        ORDER BY c.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $customer_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Shipments</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

/* Table Styles */
.table-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

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
            <a href="view-status.php" class="nav-link active">
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
                    <i class="fas fa-boxes me-2" style="color: var(--accent-orange);"></i>
                    My Shipments
                </h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-list me-2"></i>
                    All your sent & received couriers
                </p>
            </div>
            <div class="mt-2 mt-md-0">
                <!-- REMOVED New Shipment button -->
                <a href="track.php" class="btn btn-outline-primary">
                    <i class="fas fa-search me-2"></i> Track Shipment
                </a>
            </div>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-shipping-fast me-2" style="color: var(--accent-orange);"></i>
                All Shipments (<?php echo $result->num_rows; ?>)
            </h5>
            <div>
                <a href="print.php" class="btn btn-sm btn-outline-info me-2">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </a>
                <a href="track.php" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-search me-1"></i> Track
                </a>
            </div>
        </div>
        
        <?php if($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tracking #</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Route</th>
                        <th>Status</th>
                        <th>Booked Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while($row = $result->fetch_assoc()): 
                        // Get latest status from courier_status table
                        $status_q = "SELECT status FROM courier_status 
                                    WHERE courier_id=? 
                                    ORDER BY updated_at DESC LIMIT 1";
                        $st = $conn->prepare($status_q);
                        $st->bind_param("i", $row['id']);
                        $st->execute();
                        $st_r = $st->get_result()->fetch_assoc();
                        $status = $st_r['status'] ?? $row['status'];

                        $badge = "badge-booked";
                        if($status == "In Transit") $badge = "badge-transit";
                        if($status == "Delivered") $badge = "badge-delivered";
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['tracking_no']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                        <td>
                            <i class="fas fa-route text-muted me-1"></i>
                            <?php echo htmlspecialchars($row['from_city']); ?> → <?php echo htmlspecialchars($row['to_city']); ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $badge; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-calendar text-muted me-1"></i>
                            <?php echo date("d M Y", strtotime($row['booking_date'])); ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="track.php?tracking=<?php echo urlencode($row['tracking_no']); ?>" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Track Shipment">
                                    <i class="fas fa-eye"></i> Track
                                </a>
                                <a href="print.php?tracking=<?php echo urlencode($row['tracking_no']); ?>" 
                                   class="btn btn-sm btn-outline-success"
                                   target="_blank"
                                   title="Print Invoice">
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
            <div class="d-flex gap-2 justify-content-center">
                <a href="track.php" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Track Shipment
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <footer class="mt-5 pt-4 border-top">
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted">© <?php echo date('Y'); ?> Courier Management System</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted">View Shipments - Track Only</p>
            </div>
        </div>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Fixed JavaScript -->
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