<?php
session_start();
include("../config/db.php");

// Check if user is logged in and is agent
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get agent ID
$agent_query = "SELECT u.id as agent_id, u.username as agent_name 
                FROM users u 
                WHERE u.username = '$username' AND u.role = '3'";
$agent_result = mysqli_query($conn, $agent_query);

if(!$agent_result || mysqli_num_rows($agent_result) == 0) {
    header("Location: ../public/login.php");
    exit();
}

$agent_data = mysqli_fetch_assoc($agent_result);
$agent_id = $agent_data['agent_id'] ?? 0;
$agent_name = $agent_data['agent_name'] ?? '';

// Get branch info
$branch_query = "SELECT branch_name, city FROM agents WHERE user_id = '$agent_id'";
$branch_result = mysqli_query($conn, $branch_query);
if($branch_result && mysqli_num_rows($branch_result) > 0) {
    $branch_data = mysqli_fetch_assoc($branch_result);
    $branch_name = $branch_data['branch_name'] ?? '';
    $branch_city = $branch_data['city'] ?? '';
} else {
    $branch_name = 'Main Branch';
    $branch_city = 'Karachi';
}

// Initialize variables
$message = '';
$message_type = '';
$phone_number = '';
$courier_id = '';
$selected_courier = null;
$rider_name = 'Rider Ali';
$rider_phone = '0300-1234567';

// Get couriers ready for delivery
$delivery_couriers_query = "SELECT c.id, c.tracking_no, c.status, c.from_city, c.to_city,
                                   s.name as sender_name, s.phone as sender_phone, 
                                   r.name as receiver_name, r.phone as receiver_phone,
                                   r.address as receiver_address, c.booking_date
                            FROM couriers c
                            LEFT JOIN customers s ON c.sender_id = s.id
                            LEFT JOIN customers r ON c.receiver_id = r.id
                            WHERE c.agent_id = '$agent_id' 
                            AND c.status IN ('In Transit', 'Out for Delivery', 'Booked')
                            ORDER BY 
                                CASE 
                                    WHEN c.status = 'Out for Delivery' THEN 1
                                    WHEN c.status = 'In Transit' THEN 2
                                    ELSE 3
                                END,
                                c.id DESC
                            LIMIT 50";
$delivery_couriers_result = mysqli_query($conn, $delivery_couriers_query);

// Get recently delivered couriers
$delivered_couriers_query = "SELECT c.id, c.tracking_no, c.delivery_date,
                                    r.name as receiver_name, r.phone as receiver_phone,
                                    c.from_city, c.to_city
                             FROM couriers c
                             LEFT JOIN customers r ON c.receiver_id = r.id
                             WHERE c.agent_id = '$agent_id' 
                             AND c.status = 'Delivered'
                             ORDER BY c.delivery_date DESC 
                             LIMIT 10";
$delivered_couriers_result = mysqli_query($conn, $delivered_couriers_query);

// Handle SMS sending
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_sms'])) {
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $courier_id = mysqli_real_escape_string($conn, $_POST['courier_id']);
    $sms_type = mysqli_real_escape_string($conn, $_POST['sms_type']);
    $delivery_time = mysqli_real_escape_string($conn, trim($_POST['delivery_time']));
    $delivery_address = mysqli_real_escape_string($conn, trim($_POST['delivery_address']));
    $rider_name = mysqli_real_escape_string($conn, trim($_POST['rider_name']));
    $rider_phone = mysqli_real_escape_string($conn, trim($_POST['rider_phone']));
    $custom_message = mysqli_real_escape_string($conn, trim($_POST['custom_message']));
    
    // Get courier details if ID is provided
    if(!empty($courier_id) && $courier_id != 'custom') {
        $courier_details_query = "SELECT c.*, r.name as receiver_name, r.phone as receiver_phone,
                                         r.address as receiver_address
                                  FROM couriers c
                                  LEFT JOIN customers r ON c.receiver_id = r.id
                                  WHERE c.id = '$courier_id' AND c.agent_id = '$agent_id'";
        $courier_details_result = mysqli_query($conn, $courier_details_query);
        $selected_courier = mysqli_fetch_assoc($courier_details_result);
    }
    
    // Validate phone number
    if(empty($phone_number)) {
        $message = "Please enter a phone number!";
        $message_type = 'error';
    } elseif(!preg_match('/^0[0-9]{10}$/', $phone_number)) {
        $message = "Please enter a valid 11-digit phone number starting with 0!";
        $message_type = 'error';
    } else {
        // Generate SMS message based on type
        $sms_message = '';
        
        if($sms_type == 'out_for_delivery' && $selected_courier) {
            $sms_message = "Dear " . $selected_courier['receiver_name'] . ", your courier is out for delivery. ";
            $sms_message .= "Tracking: " . $selected_courier['tracking_no'] . ". ";
            if($rider_name) {
                $sms_message .= "Rider: " . $rider_name . " (" . $rider_phone . "). ";
            }
            if($delivery_time) {
                $sms_message .= "Expected delivery: " . $delivery_time . ". ";
            }
            $sms_message .= "Please keep your phone available. - " . $branch_name;
        } 
        elseif($sms_type == 'delivery_confirmation' && $selected_courier) {
            $sms_message = "Dear " . $selected_courier['receiver_name'] . ", your courier has been delivered successfully! ";
            $sms_message .= "Tracking: " . $selected_courier['tracking_no'] . ". ";
            $sms_message .= "Delivered on: " . date('d M Y') . ". ";
            $sms_message .= "Thank you for choosing " . $branch_name . ".";
        }
        elseif($sms_type == 'delivery_failed' && $selected_courier) {
            $sms_message = "Dear " . $selected_courier['receiver_name'] . ", we attempted to deliver your courier but you were unavailable. ";
            $sms_message .= "Tracking: " . $selected_courier['tracking_no'] . ". ";
            $sms_message .= "Please contact us to reschedule delivery: " . $branch_name . " - " . $branch_city . ".";
        }
        elseif($sms_type == 'pickup_schedule' && $selected_courier) {
            $sms_message = "Dear " . $selected_courier['sender_name'] . ", pickup for your courier has been scheduled. ";
            $sms_message .= "Tracking: " . $selected_courier['tracking_no'] . ". ";
            if($delivery_time) {
                $sms_message .= "Pickup time: " . $delivery_time . ". ";
            }
            $sms_message .= "Please have the package ready. - " . $branch_name;
        }
        elseif($sms_type == 'custom') {
            $sms_message = $custom_message;
        }
        
        // Add signature if message exists
        if(!empty($sms_message)) {
            $sms_message = trim($sms_message);
            
            // Simulate SMS sending
            $message = "SMS sent successfully to $phone_number!";
            $message_type = 'success';
            
            // If delivery confirmation, update courier status
            if($sms_type == 'delivery_confirmation' && $selected_courier) {
                $update_query = "UPDATE couriers SET status = 'Delivered', delivery_date = CURDATE() WHERE id = '$courier_id'";
                mysqli_query($conn, $update_query);
                
                // Add to status history
                $history_query = "INSERT INTO courier_status (courier_id, status, location) 
                                 VALUES ('$courier_id', 'Delivered', '$branch_city')";
                mysqli_query($conn, $history_query);
            }
            
            // If out for delivery, update status
            if($sms_type == 'out_for_delivery' && $selected_courier && $selected_courier['status'] != 'Out for Delivery') {
                $update_query = "UPDATE couriers SET status = 'Out for Delivery' WHERE id = '$courier_id'";
                mysqli_query($conn, $update_query);
                
                // Add to status history
                $history_query = "INSERT INTO courier_status (courier_id, status, location, remarks) 
                                 VALUES ('$courier_id', 'Out for Delivery', '$branch_city', 'SMS sent to receiver')";
                mysqli_query($conn, $history_query);
            }
            
            // Clear form except phone number
            $courier_id = '';
            $custom_message = '';
            $delivery_time = '';
            $delivery_address = '';
        } else {
            $message = "Could not generate SMS message. Please check your selections.";
            $message_type = 'error';
        }
    }
}

// Get delivery statistics
$today_delivery_count = mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM couriers 
     WHERE agent_id = '$agent_id' 
     AND status = 'Delivered'
     AND DATE(delivery_date) = CURDATE()");
$today_delivery = mysqli_fetch_assoc($today_delivery_count);

$out_for_delivery_count = mysqli_query($conn, 
    "SELECT COUNT(*) as count FROM couriers 
     WHERE agent_id = '$agent_id' 
     AND status = 'Out for Delivery'");
$out_for_delivery = mysqli_fetch_assoc($out_for_delivery_count);

// Get cities list for delivery address suggestions
$cities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Sialkot', 'Gujranwala'];

// Default delivery times
$delivery_times = [
    '9:00 AM - 12:00 PM',
    '12:00 PM - 3:00 PM', 
    '3:00 PM - 6:00 PM',
    '6:00 PM - 9:00 PM',
    'Tomorrow Morning',
    'Tomorrow Afternoon',
    'Tomorrow Evening'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Delivery - Agent Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
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
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
        }
        
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-orange);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .section-title {
            color: var(--primary-blue);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        /* Delivery Stats */
        .delivery-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            flex: 1;
            min-width: 200px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
        }
        
        .stat-item.delivered {
            border-top: 4px solid #28a745;
        }
        
        .stat-item.out-for-delivery {
            border-top: 4px solid #ffc107;
        }
        
        .stat-item.in-transit {
            border-top: 4px solid #17a2b8;
        }
        
        /* Courier Cards */
        .courier-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .courier-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: var(--accent-orange);
        }
        
        .courier-card.selected {
            background-color: #e3f2fd;
            border-color: var(--primary-blue);
        }
        
        .tracking-badge {
            background-color: var(--primary-blue);
            color: white;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .receiver-info {
            margin-bottom: 10px;
        }
        
        .receiver-name {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }
        
        .receiver-phone {
            color: #6c757d;
            font-size: 14px;
        }
        
        .route-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .route-arrow {
            color: var(--accent-orange);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-out-for-delivery {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-in-transit {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-booked {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* SMS Template Cards */
        .template-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }
        
        .template-card:hover {
            border-color: var(--accent-orange);
            transform: translateY(-3px);
        }
        
        .template-card.active {
            border-color: var(--accent-orange);
            background-color: rgba(255, 122, 0, 0.05);
        }
        
        .template-icon {
            font-size: 35px;
            color: var(--accent-orange);
            margin-bottom: 15px;
        }
        
        .template-title {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .template-desc {
            font-size: 13px;
            color: #6c757d;
        }
        
        /* Delivery Details Form */
        .delivery-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .detail-group {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary-blue);
            margin-bottom: 5px;
        }
        
        /* SMS Preview */
        .sms-preview {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            border-left: 4px solid var(--accent-orange);
        }
        
        .sms-header {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .sms-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            min-height: 100px;
        }
        
        /* Recent Deliveries */
        .delivery-history-item {
            border-left: 3px solid #28a745;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .delivery-tracking {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: var(--primary-blue);
        }
        
        .delivery-receiver {
            font-weight: 600;
            margin-top: 5px;
        }
        
        .delivery-date {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* User Avatar */
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
        
        /* Character Counter */
        .char-counter {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
        }
        
        .char-counter.warning {
            color: #ff9800;
        }
        
        .char-counter.danger {
            color: #dc3545;
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .nav-link span {
                display: none;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .delivery-stats {
                flex-direction: column;
            }
            
            .stat-item {
                min-width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }
            
            .template-card {
                margin-bottom: 15px;
            }
        }
        
        /* Rider Info */
        .rider-info {
            display: flex;
            gap: 15px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .rider-avatar {
            width: 50px;
            height: 50px;
            background: var(--accent-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        
        .rider-details h6 {
            margin-bottom: 5px;
            color: var(--primary-blue);
        }
        
        .rider-details p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <!-- Logo -->
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" 
                 alt="Courier MS Logo"
                 style="max-width: 140px; height: auto;"
                 class="mb-2">
            <div class="text-white-50 small">Agent Panel</div>
        </div>
        
        <!-- Navigation -->
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
                    <a class="nav-link" href="view-courier.php">
                        <i class="fas fa-eye"></i> <span>View Courier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-booking.php">
                        <i class="fas fa-sms"></i> <span>SMS Booking</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="sms-delivery.php">
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
        
        <!-- User Info -->
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
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-truck text-orange me-2"></i>SMS Delivery</h1>
                    <p class="text-muted mb-0">Send delivery updates and notifications to customers</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Branch:</strong> <?php echo $branch_name; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
        <!-- Delivery Statistics -->
        <div class="delivery-stats">
            <div class="stat-item delivered">
                <div class="stat-value"><?php echo $today_delivery['count'] ?? 0; ?></div>
                <div class="stat-label">Delivered Today</div>
            </div>
            <div class="stat-item out-for-delivery">
                <div class="stat-value"><?php echo $out_for_delivery['count'] ?? 0; ?></div>
                <div class="stat-label">Out for Delivery</div>
            </div>
            <div class="stat-item in-transit">
                <div class="stat-value">
                    <?php 
                    $in_transit_count = mysqli_query($conn, 
                        "SELECT COUNT(*) as count FROM couriers 
                         WHERE agent_id = '$agent_id' 
                         AND status = 'In Transit'");
                    $in_transit = mysqli_fetch_assoc($in_transit_count);
                    echo $in_transit['count'] ?? 0;
                    ?>
                </div>
                <div class="stat-label">In Transit</div>
            </div>
        </div>
        
        <!-- Message Alert -->
        <?php if($message): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show mb-4" role="alert">
                <i class="fas <?php echo $message_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1"><?php echo $message_type == 'success' ? 'Success!' : 'Error!'; ?></h5>
                    <p class="mb-0"><?php echo $message; ?></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Left Column: SMS Form -->
            <div class="col-lg-8">
                <div class="form-card">
                    <h4 class="section-title"><i class="fas fa-paper-plane me-2"></i>Send Delivery SMS</h4>
                    
                    <form method="POST" action="" id="smsForm">
                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Phone Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($phone_number); ?>" 
                                       placeholder="03XXXXXXXXX" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="fillReceiverPhone()">
                                    <i class="fas fa-user-friends me-1"></i> Receiver
                                </button>
                            </div>
                            <small class="text-muted">Enter 11-digit Pakistani mobile number (e.g., 03001234567)</small>
                        </div>
                        
                        <!-- Select Courier -->
                        <div class="mb-4">
                            <label class="form-label">Select Courier *</label>
                            <div class="couriers-list">
                                <?php if(mysqli_num_rows($delivery_couriers_result) > 0): ?>
                                    <?php while($courier = mysqli_fetch_assoc($delivery_couriers_result)): 
                                        $status_class = 'status-booked';
                                        if($courier['status'] == 'Out for Delivery') $status_class = 'status-out-for-delivery';
                                        if($courier['status'] == 'In Transit') $status_class = 'status-in-transit';
                                    ?>
                                        <div class="courier-card" 
                                             onclick="selectCourier(<?php echo $courier['id']; ?>, '<?php echo addslashes($courier['receiver_phone']); ?>')"
                                             id="courier_<?php echo $courier['id']; ?>">
                                            <div class="tracking-badge">
                                                <?php echo $courier['tracking_no']; ?>
                                            </div>
                                            <div class="receiver-info">
                                                <div class="receiver-name"><?php echo htmlspecialchars($courier['receiver_name']); ?></div>
                                                <div class="receiver-phone"><?php echo htmlspecialchars($courier['receiver_phone']); ?></div>
                                            </div>
                                            <div class="route-info">
                                                <span><?php echo htmlspecialchars($courier['from_city']); ?></span>
                                                <span class="route-arrow"><i class="fas fa-arrow-right"></i></span>
                                                <span><?php echo htmlspecialchars($courier['to_city']); ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="status-badge <?php echo $status_class; ?>">
                                                    <?php echo $courier['status']; ?>
                                                </span>
                                                <small class="text-muted">
                                                    <?php echo date('d M Y', strtotime($courier['booking_date'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">No couriers available for delivery</p>
                                        <a href="add-courier.php" class="btn btn-orange">
                                            <i class="fas fa-plus-circle me-2"></i> Add New Courier
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="courier_id" id="courier_id" value="">
                        </div>
                        
                        <!-- SMS Template Selection -->
                        <div class="mb-4">
                            <label class="form-label">Select SMS Type</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('out_for_delivery')" 
                                         id="template_out_for_delivery">
                                        <div class="template-icon">
                                            <i class="fas fa-motorcycle"></i>
                                        </div>
                                        <div class="template-title">Out for Delivery</div>
                                        <div class="template-desc">Notify receiver about delivery</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('delivery_confirmation')" 
                                         id="template_delivery_confirmation">
                                        <div class="template-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="template-title">Delivery Confirmed</div>
                                        <div class="template-desc">Confirm successful delivery</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('delivery_failed')" 
                                         id="template_delivery_failed">
                                        <div class="template-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="template-title">Delivery Failed</div>
                                        <div class="template-desc">Notify about failed delivery</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('pickup_schedule')" 
                                         id="template_pickup_schedule">
                                        <div class="template-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="template-title">Pickup Scheduled</div>
                                        <div class="template-desc">Schedule package pickup</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('custom')" 
                                         id="template_custom">
                                        <div class="template-icon">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <div class="template-title">Custom Message</div>
                                        <div class="template-desc">Write your own message</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="sms_type" id="sms_type" value="out_for_delivery">
                        </div>
                        
                        <!-- Delivery Details (Initially Hidden) -->
                        <div class="delivery-details" id="delivery_details" style="display: none;">
                            <h6><i class="fas fa-info-circle me-2"></i>Delivery Details</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label for="delivery_time" class="detail-label">Expected Delivery Time</label>
                                        <select class="form-select" id="delivery_time" name="delivery_time">
                                            <option value="">Select time slot</option>
                                            <?php foreach($delivery_times as $time): ?>
                                                <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label for="delivery_address" class="detail-label">Delivery Address</label>
                                        <input type="text" class="form-control" id="delivery_address" name="delivery_address" 
                                               placeholder="Enter delivery address">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Rider Information -->
                            <div class="rider-info">
                                <div class="rider-avatar">
                                    <?php echo strtoupper(substr($rider_name, 0, 1)); ?>
                                </div>
                                <div class="rider-details">
                                    <h6>Rider Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control mb-2" name="rider_name" 
                                                   value="<?php echo htmlspecialchars($rider_name); ?>" 
                                                   placeholder="Rider Name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control mb-2" name="rider_phone" 
                                                   value="<?php echo htmlspecialchars($rider_phone); ?>" 
                                                   placeholder="Rider Phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Message (Initially Hidden) -->
                        <div class="mb-4" id="custom_message_container" style="display: none;">
                            <label for="custom_message" class="form-label">Custom Message</label>
                            <textarea class="form-control" id="custom_message" name="custom_message" 
                                      rows="4" placeholder="Type your custom message here..."><?php echo htmlspecialchars($custom_message ?? ''); ?></textarea>
                            <div class="char-counter" id="char_counter">0/160 characters</div>
                        </div>
                        
                        <!-- SMS Preview -->
                        <div class="mb-4">
                            <label class="form-label">SMS Preview</label>
                            <div class="sms-preview">
                                <div class="sms-header">
                                    <i class="fas fa-mobile-alt me-1"></i>
                                    To: <span id="preview_phone"><?php echo htmlspecialchars($phone_number ?: '03XXXXXXXXX'); ?></span>
                                </div>
                                <div class="sms-content" id="sms_preview">
                                    Select a courier and SMS type to see preview...
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i> Reset Form
                            </button>
                            <button type="submit" name="send_sms" class="btn btn-orange btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i> Send SMS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Column: Recent Deliveries -->
            <div class="col-lg-4">
                <!-- Recent Deliveries -->
                <div class="form-card">
                    <h5 class="section-title"><i class="fas fa-history me-2"></i>Recent Deliveries</h5>
                    
                    <?php if($delivered_couriers_result && mysqli_num_rows($delivered_couriers_result) > 0): ?>
                        <div class="delivery-history">
                            <?php while($delivery = mysqli_fetch_assoc($delivered_couriers_result)): ?>
                                <div class="delivery-history-item">
                                    <div class="delivery-tracking">
                                        <?php echo $delivery['tracking_no']; ?>
                                    </div>
                                    <div class="delivery-receiver">
                                        <?php echo htmlspecialchars($delivery['receiver_name']); ?>
                                    </div>
                                    <div class="route-info small">
                                        <span><?php echo htmlspecialchars($delivery['from_city']); ?></span>
                                        <span class="route-arrow"><i class="fas fa-arrow-right"></i></span>
                                        <span><?php echo htmlspecialchars($delivery['to_city']); ?></span>
                                    </div>
                                    <div class="delivery-date">
                                        <i class="far fa-calendar me-1"></i>
                                        Delivered: <?php echo date('d M Y', strtotime($delivery['delivery_date'])); ?>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" 
                                            onclick="useRecentDelivery('<?php echo $delivery['receiver_phone']; ?>')">
                                        <i class="fas fa-reply me-1"></i> Send Again
                                    </button>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-truck-loading fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent deliveries found</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Tips -->
                <div class="form-card mt-4">
                    <h5 class="section-title"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h5>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Best Practices:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Send "Out for Delivery" SMS 1-2 hours before delivery</li>
                            <li>Always include tracking number in SMS</li>
                            <li>Keep customers updated about delays</li>
                            <li>Ask for feedback after delivery</li>
                            <li>Verify phone numbers before sending</li>
                        </ul>
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
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Delivery SMS help improve customer satisfaction
                    </p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        let selectedCourierId = null;
        let selectedTemplate = 'out_for_delivery';
        
        // Template selection
        function selectTemplate(template) {
            // Update active template
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('active');
            });
            
            const templateCard = document.getElementById('template_' + template);
            if(templateCard) {
                templateCard.classList.add('active');
            }
            
            // Update hidden input
            document.getElementById('sms_type').value = template;
            selectedTemplate = template;
            
            // Show/hide delivery details
            const deliveryDetails = document.getElementById('delivery_details');
            const customContainer = document.getElementById('custom_message_container');
            
            if(template === 'custom') {
                deliveryDetails.style.display = 'none';
                customContainer.style.display = 'block';
            } else {
                deliveryDetails.style.display = 'block';
                customContainer.style.display = 'none';
            }
            
            // Generate preview
            generatePreview();
        }
        
        // Select courier
        function selectCourier(courierId, receiverPhone) {
            selectedCourierId = courierId;
            
            // Update active courier card
            document.querySelectorAll('.courier-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            const courierCard = document.getElementById('courier_' + courierId);
            if(courierCard) {
                courierCard.classList.add('selected');
            }
            
            // Update hidden input
            document.getElementById('courier_id').value = courierId;
            
            // Fill phone number
            document.getElementById('phone_number').value = receiverPhone;
            
            // Generate preview
            generatePreview();
        }
        
        // Fill receiver phone
        function fillReceiverPhone() {
            if(selectedCourierId) {
                const courierCard = document.getElementById('courier_' + selectedCourierId);
                if(courierCard) {
                    const receiverPhone = courierCard.querySelector('.receiver-phone').textContent;
                    document.getElementById('phone_number').value = receiverPhone.trim();
                    generatePreview();
                }
            } else {
                alert('Please select a courier first.');
            }
        }
        
        // Use recent delivery
        function useRecentDelivery(phoneNumber) {
            document.getElementById('phone_number').value = phoneNumber;
            generatePreview();
            showToast('Phone number filled from recent delivery');
        }
        
        // Generate SMS preview
        function generatePreview() {
            const phone = document.getElementById('phone_number').value || '03XXXXXXXXX';
            const template = selectedTemplate;
            const deliveryTime = document.getElementById('delivery_time')?.value || '';
            const riderName = document.querySelector('input[name="rider_name"]')?.value || 'Rider Ali';
            const riderPhone = document.querySelector('input[name="rider_phone"]')?.value || '0300-1234567';
            
            document.getElementById('preview_phone').textContent = phone;
            
            let previewText = '';
            const branchName = '<?php echo $branch_name; ?>';
            const branchCity = '<?php echo $branch_city; ?>';
            
            if(template === 'out_for_delivery') {
                previewText = `Dear Customer, your courier is out for delivery. \n`;
                previewText += `Tracking: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001 \n`;
                previewText += `Rider: ${riderName} (${riderPhone}). \n`;
                if(deliveryTime) {
                    previewText += `Expected delivery: ${deliveryTime}. \n`;
                }
                previewText += `Please keep your phone available. - ${branchName}`;
            } 
            else if(template === 'delivery_confirmation') {
                previewText = `Dear Customer, your courier has been delivered successfully! \n`;
                previewText += `Tracking: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001\n`;
                previewText += `Delivered on: ${new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}. \n`;
                previewText += `Thank you for choosing ${branchName}.`;
            }
            else if(template === 'delivery_failed') {
                previewText = `Dear Customer, we attempted to deliver your courier but you were unavailable. \n`;
                previewText += `Tracking: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001\n`;
                previewText += `Please contact us to reschedule delivery: ${branchName} - ${branchCity}.`;
            }
            else if(template === 'pickup_schedule') {
                previewText = `Dear Customer, pickup for your courier has been scheduled. \n`;
                previewText += `Tracking: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001\n`;
                if(deliveryTime) {
                    previewText += `Pickup time: ${deliveryTime}. \n`;
                }
                previewText += `Please have the package ready. - ${branchName}`;
            }
            else if(template === 'custom') {
                previewText = document.getElementById('custom_message').value || 
                             'Type your custom message...';
            }
            
            document.getElementById('sms_preview').textContent = previewText;
            
            // Update character counter for custom messages
            updateCharCounter();
        }
        
        // Character counter for custom message
        function updateCharCounter() {
            const customMessage = document.getElementById('custom_message');
            const charCounter = document.getElementById('char_counter');
            
            if(customMessage && charCounter) {
                const length = customMessage.value.length;
                charCounter.textContent = `${length}/160 characters`;
                
                // Update color based on length
                if(length > 160) {
                    charCounter.className = 'char-counter danger';
                } else if(length > 140) {
                    charCounter.className = 'char-counter warning';
                } else {
                    charCounter.className = 'char-counter';
                }
            }
        }
        
        // Reset form
        function resetForm() {
            if(confirm('Are you sure you want to reset the form?')) {
                document.getElementById('smsForm').reset();
                selectedCourierId = null;
                document.querySelectorAll('.courier-card').forEach(card => {
                    card.classList.remove('selected');
                });
                document.getElementById('courier_id').value = '';
                document.getElementById('delivery_details').style.display = 'block';
                document.getElementById('custom_message_container').style.display = 'none';
                document.querySelectorAll('.template-card').forEach(card => {
                    card.classList.remove('active');
                });
                document.getElementById('template_out_for_delivery').classList.add('active');
                document.getElementById('sms_type').value = 'out_for_delivery';
                selectedTemplate = 'out_for_delivery';
                generatePreview();
            }
        }
        
        // Show toast notification
        function showToast(message) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            
            toast.innerHTML = `
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            // Add toast to body
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
        
        // Form validation
        document.getElementById('smsForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone_number').value;
            const courierId = document.getElementById('courier_id').value;
            const template = selectedTemplate;
            const customMessage = document.getElementById('custom_message').value;
            
            // Validate phone number
            const phoneRegex = /^0[0-9]{10}$/;
            if(!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 11-digit Pakistani phone number starting with 0 (e.g., 03001234567).');
                return false;
            }
            
            // Validate courier selection
            if(!courierId && template !== 'custom') {
                e.preventDefault();
                alert('Please select a courier for delivery SMS.');
                return false;
            }
            
            // Validate custom message
            if(template === 'custom' && customMessage.trim().length === 0) {
                e.preventDefault();
                alert('Please enter a custom message.');
                return false;
            }
            
            // Warn if message is too long
            if(template === 'custom' && customMessage.length > 160) {
                if(!confirm('Message exceeds 160 characters and may be sent as multiple SMS. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Show sending confirmation
            if(!confirm(`Send ${template.replace('_', ' ')} SMS to ${phone}?`)) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
        
        // Auto-select out for delivery template on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('template_out_for_delivery').classList.add('active');
            generatePreview();
            
            // Set up event listeners
            document.getElementById('phone_number').addEventListener('input', generatePreview);
            document.getElementById('delivery_time').addEventListener('change', generatePreview);
            document.querySelectorAll('input[name="rider_name"], input[name="rider_phone"]').forEach(input => {
                input.addEventListener('input', generatePreview);
            });
            document.getElementById('custom_message').addEventListener('input', function() {
                generatePreview();
                updateCharCounter();
            });
            
            // Auto-select first courier if available
            const firstCourierCard = document.querySelector('.courier-card');
            if(firstCourierCard && !selectedCourierId) {
                const courierId = firstCourierCard.id.replace('courier_', '');
                const receiverPhone = firstCourierCard.querySelector('.receiver-phone').textContent.trim();
                selectCourier(courierId, receiverPhone);
            }
        });
    </script>
</body>
</html>