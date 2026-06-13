<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: ../public/login.php");
      exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? '';

// Get customer ID for security check
$customer_id = 0;
if ($email) {
    $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $customer = $stmt->get_result()->fetch_assoc();
    $customer_id = $customer['id'] ?? 0;
}

$courier = null;
$error = "";
$tracking_no = "";

if (isset($_GET['tracking']) || isset($_POST['tracking_no'])) {
    $tracking_no = $_GET['tracking'] ?? $_POST['tracking_no'];
    $tracking_no = trim($tracking_no);
    
    // Use prepared statement to prevent SQL injection
    $query = "
        SELECT c.*, 
               s.name AS sender_name, s.phone AS sender_phone, s.email AS sender_email,
               r.name AS receiver_name, r.phone AS receiver_phone, r.email AS receiver_email
        FROM couriers c
        LEFT JOIN customers s ON c.sender_id = s.id
        LEFT JOIN customers r ON c.receiver_id = r.id
        WHERE c.tracking_no = ?
        LIMIT 1
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tracking_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $courier = $result->fetch_assoc();
    } else {
        $error = "No courier found with this tracking number.";
    }
}

// Get status history if courier found
$status_history = [];
if ($courier) {
    $status_query = "SELECT status, location, updated_at FROM courier_status WHERE courier_id = ? ORDER BY updated_at DESC";
    $stmt = $conn->prepare($status_query);
    $stmt->bind_param("i", $courier['id']);
    $stmt->execute();
    $status_history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Courier</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary-blue: #0b1c2d;
    --accent-orange: #ff7a00;
    --light-gray: #f8f9fa;
}
body {
    background: var(--light-gray);
    font-family: 'Segoe UI', sans-serif;
    overflow-x: hidden;
}

.mobile-menu-btn {
    display: none;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1050;
    background: var(--accent-orange);
    color: white;
    border: none;
    border-radius: 5px;
    width: 45px;
    height: 45px;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

.sidebar-overlay.show {
    display: block;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: var(--primary-blue);
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transition: left 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.sidebar .logo {
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0;
}

.sidebar .logo img {
    max-width: 80%;
    height: auto;
}

.sidebar .nav-container {
    flex-grow: 1;
    overflow: hidden;
    padding: 10px 0;
}

.sidebar .nav-container .nav {
    height: 100%;
    overflow-y: auto;
}

.sidebar .nav-link {
    color: rgba(255,255,255,0.8);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.sidebar .nav-link i {
    width: 25px;
    margin-right: 10px;
    flex-shrink: 0;
}

.sidebar .nav-link.active,
.sidebar .nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: white;
    border-left: 3px solid var(--accent-orange);
}

.user-info-sidebar {
    padding: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.2);
    flex-shrink: 0;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: var(--accent-orange);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    flex-shrink: 0;
}

.main-content {
    margin-left: 250px;
    padding: 30px;
    transition: margin-left 0.3s ease;
    min-height: 100vh;
}

.topbar {
    background: #fff;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.menu-btn {
    font-size: 22px;
    cursor: pointer;
    color: var(--primary-blue);
    background: none;
    border: none;
    padding: 5px;
    display: none;
}

.card-box {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.badge-booked { 
    background: #fef3c7;
    color: #92400e;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 500;
}
.badge-transit { 
    background: #dbeafe;
    color: #1e40af;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 500;
}
.badge-delivered { 
    background: #d1fae5;
    color: #065f46;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.btn-orange {
    background-color: var(--accent-orange);
    border-color: var(--accent-orange);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn-orange:hover {
    background-color: #e56a00;
    border-color: #e56a00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 122, 0, 0.3);
}

/* Status Timeline */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: 8px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--accent-orange);
    border: 3px solid white;
    box-shadow: 0 0 0 2px var(--accent-orange);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

@media(max-width: 768px) {
    .mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sidebar {
        left: -250px;
        box-shadow: 2px 0 15px rgba(0,0,0,0.3);
    }
    
    .sidebar.show {
        left: 0;
    }
    
    .main-content {
        margin-left: 0;
        padding: 20px 15px;
        padding-top: 80px;
    }
    
    .topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        margin: 0;
        border-radius: 0;
        z-index: 100;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .topbar h5 {
        font-size: 18px;
        margin: 0;
    }
    
    .menu-btn {
        display: block;
    }
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
<div class="sidebar d-flex flex-column">
    <div class="logo">
        <img src="../assets/images/new logo.png" alt="Courier Logo">
        <div class="mt-2 text-white-50 small">Customer Panel</div>
    </div>
    
    <div class="nav-container">
        <nav class="nav flex-column">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="track.php" class="nav-link active">
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
    <div class="topbar">
        <button class="menu-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h5 class="mb-0">Track Courier</h5>
    </div>

    <div class="card-box">
        <h4>
            <i class="fas fa-search-location me-2" style="color:var(--accent-orange)"></i>
            Track Your Courier
        </h4>
        <p class="text-muted">Enter tracking number to check shipment status</p>
        <form method="POST" class="row g-3 mt-2">
            <div class="col-md-9">
                <input type="text" name="tracking_no" class="form-control form-control-lg"
                       placeholder="Enter Tracking Number (e.g., TRK20250207ABC123)" 
                       value="<?php echo htmlspecialchars($tracking_no); ?>" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-orange w-100 h-100">
                    <i class="fas fa-search"></i> Track Now
                </button>
            </div>
        </form>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($courier): 
        $statusClass = 'badge-booked';
        if($courier['status'] == 'In Transit') $statusClass = 'badge-transit';
        if($courier['status'] == 'Delivered') $statusClass = 'badge-delivered';
    ?>
    <div class="card-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-package me-2" style="color:var(--accent-orange)"></i>
                Shipment Details
            </h5>
            <span class="badge <?php echo $statusClass; ?> p-3">
                <i class="fas fa-circle me-1"></i>
                <?php echo htmlspecialchars($courier['status']); ?>
            </span>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block">Tracking Number</small>
                    <strong class="fs-5"><?php echo htmlspecialchars($courier['tracking_no']); ?></strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block">Booking Date</small>
                    <strong><?php echo date('d M Y, h:i A', strtotime($courier['booking_date'])); ?></strong>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="border-start border-4 border-primary ps-3">
                    <h6 class="text-primary">From</h6>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($courier['from_city']); ?></strong></p>
                    <p class="mb-0 text-muted"><?php echo htmlspecialchars($courier['from_address'] ?? ''); ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border-start border-4 border-success ps-3">
                    <h6 class="text-success">To</h6>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($courier['to_city']); ?></strong></p>
                    <p class="mb-0 text-muted"><?php echo htmlspecialchars($courier['to_address'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="customer-box p-3 bg-light rounded">
                    <h6><i class="fas fa-user me-2"></i> Sender Details</h6>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($courier['sender_name']); ?></strong></p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($courier['sender_phone']); ?></p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($courier['sender_email'] ?? ''); ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="customer-box p-3 bg-light rounded">
                    <h6><i class="fas fa-user me-2"></i> Receiver Details</h6>
                    <p class="mb-1"><strong><?php echo htmlspecialchars($courier['receiver_name']); ?></strong></p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($courier['receiver_phone']); ?></p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($courier['receiver_email'] ?? ''); ?></p>
                </div>
            </div>
        </div>
        
        <?php if (!empty($status_history)): ?>
        <hr class="my-4">
        <h6 class="mb-3"><i class="fas fa-history me-2"></i> Tracking History</h6>
        <div class="timeline">
            <?php foreach ($status_history as $index => $status_item): ?>
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                        <strong><?php echo htmlspecialchars($status_item['status']); ?></strong>
                        <small class="text-muted"><?php echo date('d M Y, h:i A', strtotime($status_item['updated_at'])); ?></small>
                    </div>
                    <?php if (!empty($status_item['location'])): ?>
                    <p class="mb-0 mt-1">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <?php echo htmlspecialchars($status_item['location']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <hr>

        <!-- Action Buttons - Only Print and New Search -->
        <div class="d-flex gap-2 mt-4">
            <a href="print.php?tracking=<?php echo urlencode($courier['tracking_no']); ?>" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-print"></i> Print Invoice
            </a>
            <a href="track.php" class="btn btn-outline-secondary">
                <i class="fas fa-sync"></i> New Search
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Help Section -->
    <div class="card-box bg-light">
        <h6><i class="fas fa-info-circle me-2 text-info"></i> Where to find tracking number?</h6>
        <p class="text-muted mb-0">Your tracking number is provided at the time of booking. You can also find it in your email confirmation or on the physical receipt.</p>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuBtn = document.querySelector('.menu-btn');
    
    window.toggleSidebar = function() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
    };
    
    if (overlay) {
        overlay.addEventListener('click', window.toggleSidebar);
    }
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', window.toggleSidebar);
    }
    
    if (menuBtn) {
        menuBtn.addEventListener('click', window.toggleSidebar);
    }
    
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !mobileMenuBtn.contains(event.target) &&
            !menuBtn.contains(event.target) &&
            sidebar.classList.contains('show')) {
            window.toggleSidebar();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && sidebar.classList.contains('show')) {
            window.toggleSidebar();
        }
    });
    
    const trackingInput = document.querySelector('input[name="tracking_no"]');
    if(trackingInput && !trackingInput.value) {
        trackingInput.focus();
    }
});
</script>

</body>
</html>