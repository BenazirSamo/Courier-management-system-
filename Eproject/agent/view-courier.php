<?php
session_start();
include("../config/db.php");

// Check if user is logged in and is agent
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get user ID
$user_query = "SELECT id FROM users WHERE username = '$username' AND role = '3'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Get agent ID
$agent_query = "SELECT id, branch_name, city FROM agents WHERE user_id = '$user_id'";
$agent_result = mysqli_query($conn, $agent_query);
$agent_data = mysqli_fetch_assoc($agent_result);
$agent_id = $agent_data['id'];
$branch_name = $agent_data['branch_name'] ?? 'Main Branch';
$branch_city = $agent_data['city'] ?? 'Karachi';

// Get agent name
$name_query = "SELECT username FROM users WHERE id = '$user_id'";
$name_result = mysqli_query($conn, $name_query);
$name_data = mysqli_fetch_assoc($name_result);
$agent_name = $name_data['username'] ?? $username;

// Get courier ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage-courier.php");
    exit();
}

$courier_id = mysqli_real_escape_string($conn, $_GET['id']);

// Get courier details with sender/receiver info
$query = "SELECT c.*, 
                 s.name as sender_name, s.phone as sender_phone, s.address as sender_address, s.email as sender_email,
                 r.name as receiver_name, r.phone as receiver_phone, r.address as receiver_address, r.email as receiver_email
          FROM couriers c
          LEFT JOIN customers s ON c.sender_id = s.id
          LEFT JOIN customers r ON c.receiver_id = r.id
          WHERE c.id = '$courier_id' AND c.agent_id = '$agent_id'";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: manage-courier.php");
    exit();
}

$courier = mysqli_fetch_assoc($result);

// Get status history
$history_query = "SELECT * FROM courier_status 
                  WHERE courier_id = '$courier_id' 
                  ORDER BY updated_at DESC";
$history_result = mysqli_query($conn, $history_query);

// Handle status update
if(isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    
    // Update courier status
    $update_query = "UPDATE couriers SET status = '$new_status' WHERE id = '$courier_id'";
    if(mysqli_query($conn, $update_query)) {
     
        $history_insert = "INSERT INTO courier_status (courier_id, status, location) 
                          VALUES ('$courier_id', '$new_status', '$location')";
        mysqli_query($conn, $history_insert);
        
        // Update delivery date if status is Delivered
        if($new_status == 'Delivered') {
            $delivery_query = "UPDATE couriers SET delivery_date = CURDATE() WHERE id = '$courier_id'";
            mysqli_query($conn, $delivery_query);
        }
        
        $_SESSION['success_msg'] = "Status updated successfully!";
        header("Location: view-courier.php?id=$courier_id");
        exit();
    } else {
        $_SESSION['error_msg'] = "Error updating status: " . mysqli_error($conn);
    }
}

$cities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Sialkot', 'Gujranwala'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courier Details - Agent Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        }
        
        .text-orange { color: var(--accent-orange) !important; }
        
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
        
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--primary-blue);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-left: 3px solid transparent;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--accent-orange);
        }
        
        .sidebar .nav-link i { width: 24px; margin-right: 10px; }
        .main-content { margin-left: 250px; padding: 20px; }
        
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-orange);
        }
        
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-blue);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .tracking-box {
            background: linear-gradient(135deg, #0b1c2d, #1a365d);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .tracking-number {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            color: var(--accent-orange);
            letter-spacing: 2px;
            cursor: pointer;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .status-booked { background-color: #fef3c7; color: #92400e; }
        .status-transit { background-color: #dbeafe; color: #1e40af; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fecaca; color: #dc2626; }
        
        .info-table td { padding: 10px 0; }
        .info-label { font-weight: 600; color: var(--secondary-blue); width: 40%; }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-orange);
            border: 3px solid white;
            box-shadow: 0 0 0 3px var(--accent-orange);
        }
        
        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--accent-orange);
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
        
        .customer-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .customer-name {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .customer-detail { margin-bottom: 8px; display: flex; align-items: center; }
        .customer-detail i { width: 20px; color: var(--accent-orange); margin-right: 10px; }
        
        .route-display {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .route-city {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            border-radius: 50px;
            font-weight: 600;
        }
        
        .route-arrow {
            display: inline-block;
            margin: 0 20px;
            font-size: 24px;
            color: var(--accent-orange);
        }
        
        .btn-action { min-width: 120px; }
        
        @media (max-width: 991.98px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link i { margin-right: 0; font-size: 20px; }
            .main-content { margin-left: 70px; }
            .tracking-number { font-size: 18px; }
        }
        
        @media print {
            .sidebar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" 
                 alt="Courier MS Logo"
                 style="max-width: 140px; height: auto;"
                 class="mb-2">
            <div class="text-white-50 small">Agent Panel</div>
        </div>
        
        <div class="flex-grow-1 p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-courier.php">
                        <i class="fas fa-plus-circle"></i> <span>Add Courier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-courier.php">
                        <i class="fas fa-boxes"></i> <span>Manage Courier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="view-courier.php">
                        <i class="fas fa-eye"></i> <span>View Courier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-booking.php">
                        <i class="fas fa-sms"></i> <span>SMS Booking</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-delivery.php">
                        <i class="fas fa-truck"></i> <span>SMS Delivery</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar"></i> <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="p-3 border-top mt-auto">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-3">
                    <?php echo strtoupper(substr($agent_name, 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $agent_name; ?></h6>
                    <small class="text-white-50"><?php echo $branch_name; ?></small>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-light w-100 mt-3">
                <i class="fas fa-sign-out-alt me-1"></i> <span>Logout</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-eye text-orange me-2"></i>Courier Details</h1>
                    <p class="text-muted mb-0">Complete information for tracking number: <?php echo $courier['tracking_no']; ?></p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Agent:</strong> <?php echo $agent_name; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success_msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <!-- Tracking Info Box -->
        <div class="tracking-box">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="text-white mb-2"><i class="fas fa-barcode me-2"></i>Tracking Number</h5>
                    <div class="tracking-number" onclick="copyTrackingNumber()" title="Click to copy">
                        <?php echo $courier['tracking_no']; ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <?php
                    $status_class = 'status-booked';
                    if($courier['status'] == 'In Transit') $status_class = 'status-transit';
                    if($courier['status'] == 'Delivered') $status_class = 'status-delivered';
                    if($courier['status'] == 'Cancelled') $status_class = 'status-cancelled';
                    ?>
                    <span class="status-badge <?php echo $status_class; ?>">
                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                        <?php echo $courier['status']; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="info-card no-print">
            <div class="d-flex gap-2 flex-wrap">
                <a href="manage-courier.php" class="btn btn-secondary btn-action">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                <a href="add-courier.php" class="btn btn-success btn-action">
                    <i class="fas fa-plus-circle me-2"></i> Add New
                </a>
                <button onclick="window.print()" class="btn btn-info btn-action">
                    <i class="fas fa-print me-2"></i> Print
                </button>
                <a href="sms-booking.php?tracking=<?php echo $courier['tracking_no']; ?>" 
                   class="btn btn-warning btn-action">
                    <i class="fas fa-sms me-2"></i> Send SMS
                </a>
                <button type="button" class="btn btn-orange btn-action" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i class="fas fa-sync-alt me-2"></i> Update Status
                </button>
            </div>
        </div>
        
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Courier Information -->
                <div class="info-card">
                    <h4 class="section-title"><i class="fas fa-info-circle me-2"></i>Courier Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr><td class="info-label">Booking Date:</td><td><?php echo date('F j, Y', strtotime($courier['booking_date'])); ?></td></tr>
                                <tr><td class="info-label">Delivery Date:</td><td><?php echo $courier['delivery_date'] ? date('F j, Y', strtotime($courier['delivery_date'])) : '<span class="text-muted">Not delivered yet</span>'; ?></td></tr>
                                <tr><td class="info-label">Branch:</td><td><?php echo $branch_name; ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="info-table">
                                <tr><td class="info-label">Price:</td><td><strong class="text-success">PKR <?php echo number_format($courier['price'], 2); ?></strong></td></tr>
                                <tr><td class="info-label">Agent:</td><td><?php echo $agent_name; ?></td></tr>
                                <tr><td class="info-label">Branch City:</td><td><?php echo $branch_city; ?></td></tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="route-display">
                        <span class="route-city"><i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $courier['from_city']; ?></span>
                        <span class="route-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
                        <span class="route-city"><i class="fas fa-map-marker-alt text-success me-2"></i><?php echo $courier['to_city']; ?></span>
                    </div>
                </div>
                
                <!-- Status History -->
                <div class="info-card">
                    <h4 class="section-title"><i class="fas fa-history me-2"></i>Status History</h4>
                    <?php if(mysqli_num_rows($history_result) > 0): ?>
                        <div class="timeline">
                            <?php while($history = mysqli_fetch_assoc($history_result)): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('F j, Y h:i A', strtotime($history['updated_at'])); ?>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-status">
                                            <span class="status-badge <?php 
                                                if($history['status'] == 'In Transit') echo 'status-transit';
                                                elseif($history['status'] == 'Delivered') echo 'status-delivered';
                                                elseif($history['status'] == 'Cancelled') echo 'status-cancelled';
                                                else echo 'status-booked';
                                            ?> me-2">
                                                <?php echo $history['status']; ?>
                                            </span>
                                            <?php if($history['location']): ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i><?php echo $history['location']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <!-- 🔥 FIX: Remove remarks display -->
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No status history available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Sender Information -->
                <div class="info-card">
                    <h4 class="section-title"><i class="fas fa-user me-2"></i>Sender Information</h4>
                    <div class="customer-box">
                        <div class="customer-name">
                            <i class="fas fa-user-circle me-2"></i>
                            <?php echo htmlspecialchars($courier['sender_name']); ?>
                        </div>
                        <div class="customer-detail">
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($courier['sender_phone']); ?>
                        </div>
                        <?php if($courier['sender_email']): ?>
                        <div class="customer-detail">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($courier['sender_email']); ?>
                        </div>
                        <?php endif; ?>
                        <div class="customer-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <div><?php echo htmlspecialchars($courier['sender_address']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Receiver Information -->
                <div class="info-card">
                    <h4 class="section-title"><i class="fas fa-user-friends me-2"></i>Receiver Information</h4>
                    <div class="customer-box">
                        <div class="customer-name">
                            <i class="fas fa-user-circle me-2"></i>
                            <?php echo htmlspecialchars($courier['receiver_name']); ?>
                        </div>
                        <div class="customer-detail">
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($courier['receiver_phone']); ?>
                        </div>
                        <?php if($courier['receiver_email']): ?>
                        <div class="customer-detail">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($courier['receiver_email']); ?>
                        </div>
                        <?php endif; ?>
                        <div class="customer-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <div><?php echo htmlspecialchars($courier['receiver_address']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sync-alt me-2"></i>Update Courier Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">New Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Booked" <?php echo $courier['status'] == 'Booked' ? 'selected' : ''; ?>>Booked</option>
                                <option value="In Transit" <?php echo $courier['status'] == 'In Transit' ? 'selected' : ''; ?>>In Transit</option>
                                <option value="Delivered" <?php echo $courier['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="Cancelled" <?php echo $courier['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Current Location</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Select Location</option>
                                <?php foreach($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo ($courier['to_city'] == $city) ? 'selected' : ''; ?>>
                                        <?php echo $city; ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="In Warehouse">In Warehouse</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                            </select>
                        </div>
                        <!-- 🔥 FIX: Remove remarks field -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_status" class="btn btn-orange">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyTrackingNumber() {
            const trackingNo = '<?php echo $courier['tracking_no']; ?>';
            navigator.clipboard.writeText(trackingNo).then(() => {
                alert('Tracking number copied to clipboard: ' + trackingNo);
            });
        }
    </script>
</body>
</html>