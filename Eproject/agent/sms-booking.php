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
$agent_query = "SELECT u.id as agent_id, username as agent_name 
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
$branch_query = "SELECT branch_name FROM agents WHERE user_id = '$agent_id'";
$branch_result = mysqli_query($conn, $branch_query);
if($branch_result && mysqli_num_rows($branch_result) > 0) {
    $branch_data = mysqli_fetch_assoc($branch_result);
    $branch_name = $branch_data['branch_name'] ?? '';
} else {
    $branch_name = 'Main Branch';
}

// Initialize variables
$message = '';
$message_type = '';
$phone_number = '';
$courier_id = '';
$selected_courier = null;

// Get couriers for this agent
$couriers_query = "SELECT c.id, c.tracking_no, s.name as sender_name, s.phone as sender_phone, 
                          r.name as receiver_name, r.phone as receiver_phone, c.status,
                          c.from_city, c.to_city, c.booking_date
                   FROM couriers c
                   LEFT JOIN customers s ON c.sender_id = s.id
                   LEFT JOIN customers r ON c.receiver_id = r.id
                   WHERE c.agent_id = '$agent_id' 
                   ORDER BY c.id DESC LIMIT 50";
$couriers_result = mysqli_query($conn, $couriers_query);

// Handle SMS sending
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_sms'])) {
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $courier_id = mysqli_real_escape_string($conn, $_POST['courier_id']);
    $sms_type = mysqli_real_escape_string($conn, $_POST['sms_type']);
    $custom_message = mysqli_real_escape_string($conn, trim($_POST['custom_message']));
    
    // Get courier details if ID is provided
    if(!empty($courier_id) && $courier_id != 'custom') {
        $courier_details_query = "SELECT c.*, s.name as sender_name, s.phone as sender_phone, 
                                         r.name as receiver_name, r.phone as receiver_phone
                                  FROM couriers c
                                  LEFT JOIN customers s ON c.sender_id = s.id
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
        
        if($sms_type == 'booking_confirmation' && $selected_courier) {
            $sms_message = "Dear " . $selected_courier['sender_name'] . ", your courier has been booked successfully. ";
            $sms_message .= "Tracking No: " . $selected_courier['tracking_no'] . ". ";
            $sms_message .= "From: " . $selected_courier['from_city'] . " to " . $selected_courier['to_city'] . ". ";
            $sms_message .= "Booked on: " . date('d M Y', strtotime($selected_courier['booking_date'])) . ". ";
            $sms_message .= "Thank you for choosing " . $branch_name . " Courier Service.";
        } 
        elseif($sms_type == 'tracking_info' && $selected_courier) {
            $sms_message = "Your courier tracking information:\n";
            $sms_message .= "Tracking No: " . $selected_courier['tracking_no'] . "\n";
            $sms_message .= "Status: " . $selected_courier['status'] . "\n";
            $sms_message .= "Route: " . $selected_courier['from_city'] . " to " . $selected_courier['to_city'] . "\n";
            $sms_message .= "Booked on: " . date('d M Y', strtotime($selected_courier['booking_date'])) . "\n";
            $sms_message .= "Contact " . $branch_name . " for updates.";
        }
        elseif($sms_type == 'custom') {
            $sms_message = $custom_message;
        }
        
        // Add signature if message exists
        if(!empty($sms_message)) {
            $sms_message = trim($sms_message);
            
            // Simulate SMS sending (In real application, integrate with SMS gateway)
            // For demo purposes, we'll just show success message
            
            // We won't log to database since sms_logs table doesn't exist
            // In a real application, you would create this table
            
            $message = "SMS sent successfully to $phone_number!";
            $message_type = 'success';
            
            // Clear form except phone number
            $courier_id = '';
            $custom_message = '';
        } else {
            $message = "Could not generate SMS message. Please check your selections.";
            $message_type = 'error';
        }
    }
}

// Check if tracking number is passed via URL
$tracking_no = isset($_GET['tracking']) ? mysqli_real_escape_string($conn, $_GET['tracking']) : '';

// Get recent bookings for quick selection (instead of SMS logs)
$recent_bookings_query = "SELECT c.id, c.tracking_no, s.name as sender_name, s.phone as sender_phone, 
                                 c.booking_date
                          FROM couriers c
                          LEFT JOIN customers s ON c.sender_id = s.id
                          WHERE c.agent_id = '$agent_id'
                          ORDER BY c.booking_date DESC 
                          LIMIT 5";
$recent_bookings_result = mysqli_query($conn, $recent_bookings_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Booking - Agent Panel</title>
    
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
        
        /* SMS Preview Box */
        .sms-preview {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
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
        
        /* Courier Card */
        .courier-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .courier-card:hover {
            background-color: #f8f9fa;
            border-color: var(--accent-orange);
        }
        
        .tracking-badge {
            background-color: var(--primary-blue);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Recent Bookings */
        .recent-booking-item {
            border-left: 3px solid var(--accent-orange);
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
        }
        
        .recent-booking-item:hover {
            background-color: #f8f9fa;
        }
        
        .booking-phone {
            font-weight: 600;
            color: var(--primary-blue);
        }
        
        .booking-tracking {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: var(--accent-orange);
        }
        
        .booking-time {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
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
            transform: translateY(-5px);
        }
        
        .template-card.active {
            border-color: var(--accent-orange);
            background-color: rgba(255, 122, 0, 0.05);
        }
        
        .template-icon {
            font-size: 40px;
            color: var(--accent-orange);
            margin-bottom: 15px;
        }
        
        .template-title {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }
        
        .template-desc {
            font-size: 14px;
            color: #6c757d;
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
        
        /* Quick Stats */
        .stat-card {
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
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
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
        }
        
        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }
            
            .template-card {
                margin-bottom: 15px;
            }
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
                    <a class="nav-link active" href="sms-booking.php">
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
                    <h1 class="h3 mb-2"><i class="fas fa-sms text-orange me-2"></i>SMS Booking</h1>
                    <p class="text-muted mb-0">Send booking confirmation and tracking SMS to customers</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Branch:</strong> <?php echo $branch_name; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
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
                    <h4 class="section-title"><i class="fas fa-paper-plane me-2"></i>Send SMS</h4>
                    
                    <form method="POST" action="" id="smsForm">
                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Phone Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($phone_number); ?>" 
                                       placeholder="03XXXXXXXXX" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="fillSenderPhone()">
                                    <i class="fas fa-user me-1"></i> Sender
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="fillReceiverPhone()">
                                    <i class="fas fa-user-friends me-1"></i> Receiver
                                </button>
                            </div>
                            <small class="text-muted">Enter 11-digit Pakistani mobile number (e.g., 03001234567)</small>
                        </div>
                        
                        <!-- Courier Selection -->
                        <div class="mb-4">
                            <label class="form-label">Select Courier (Optional)</label>
                            <select class="form-select" id="courier_id" name="courier_id" onchange="loadCourierDetails()">
                                <option value="">Select a courier to auto-fill message</option>
                                <option value="custom">Custom Message (No courier)</option>
                                <?php if(mysqli_num_rows($couriers_result) > 0): ?>
                                    <?php while($courier = mysqli_fetch_assoc($couriers_result)): ?>
                                        <option value="<?php echo $courier['id']; ?>" 
                                            <?php echo ($courier_id == $courier['id']) ? 'selected' : ''; ?>
                                            data-sender-phone="<?php echo $courier['sender_phone']; ?>"
                                            data-receiver-phone="<?php echo $courier['receiver_phone']; ?>">
                                            #<?php echo $courier['id']; ?> - <?php echo $courier['tracking_no']; ?> 
                                            (<?php echo $courier['sender_name']; ?> → <?php echo $courier['receiver_name']; ?>)
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Selecting a courier will auto-fill SMS templates with courier details</small>
                        </div>
                        
                        <!-- SMS Template Selection -->
                        <div class="mb-4">
                            <label class="form-label">Select SMS Template</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('booking_confirmation')" 
                                         id="template_booking">
                                        <div class="template-icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="template-title">Booking Confirmation</div>
                                        <div class="template-desc">Notify sender about successful booking</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('tracking_info')" 
                                         id="template_tracking">
                                        <div class="template-icon">
                                            <i class="fas fa-search-location"></i>
                                        </div>
                                        <div class="template-title">Tracking Information</div>
                                        <div class="template-desc">Share tracking details with customer</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="template-card" onclick="selectTemplate('custom')" 
                                         id="template_custom">
                                        <div class="template-icon">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <div class="template-title">Custom Message</div>
                                        <div class="template-desc">Write your own custom message</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="sms_type" id="sms_type" value="booking_confirmation">
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
                                    Loading preview...
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
            
            <!-- Right Column: Recent Bookings & Quick Stats -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="form-card mb-4">
                    <h5 class="section-title"><i class="fas fa-chart-line me-2"></i>Quick Stats</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-card">
                                <?php 
                                $today_bookings = mysqli_query($conn, 
                                    "SELECT COUNT(*) as count FROM couriers 
                                     WHERE agent_id = '$agent_id' 
                                     AND DATE(booking_date) = CURDATE()");
                                $today = mysqli_fetch_assoc($today_bookings);
                                ?>
                                <div class="stat-value"><?php echo $today['count'] ?? 0; ?></div>
                                <div class="stat-label">Today's Bookings</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <?php 
                                $total_bookings = mysqli_query($conn, 
                                    "SELECT COUNT(*) as count FROM couriers 
                                     WHERE agent_id = '$agent_id'");
                                $total = mysqli_fetch_assoc($total_bookings);
                                ?>
                                <div class="stat-value"><?php echo $total['count'] ?? 0; ?></div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <div class="form-card">
                    <h5 class="section-title"><i class="fas fa-history me-2"></i>Recent Bookings</h5>
                    
                    <?php if($recent_bookings_result && mysqli_num_rows($recent_bookings_result) > 0): ?>
                        <div class="recent-bookings">
                            <?php while($booking = mysqli_fetch_assoc($recent_bookings_result)): ?>
                                <div class="recent-booking-item" 
                                     onclick="selectRecentBooking('<?php echo $booking['id']; ?>', '<?php echo addslashes($booking['sender_phone']); ?>')">
                                    <div class="booking-phone">
                                        <?php echo htmlspecialchars($booking['sender_name']); ?>
                                    </div>
                                    <div class="booking-tracking">
                                        <?php echo $booking['tracking_no']; ?>
                                    </div>
                                    <div class="booking-time">
                                        <i class="far fa-clock me-1"></i>
                                        <?php echo date('d M Y', strtotime($booking['booking_date'])); ?>
                                    </div>
                                    <small class="text-muted">Click to select</small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent bookings found</p>
                        </div>
                    <?php endif; ?>
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
                        <i class="fas fa-info-circle me-1"></i>SMS functionality is in demo mode
                    </p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
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
            
            // Show/hide custom message field
            const customContainer = document.getElementById('custom_message_container');
            if(template === 'custom') {
                customContainer.style.display = 'block';
            } else {
                customContainer.style.display = 'none';
            }
            
            // Generate preview
            generatePreview();
        }
        
        // Generate SMS preview
        function generatePreview() {
            const phone = document.getElementById('phone_number').value || '03XXXXXXXXX';
            const template = document.getElementById('sms_type').value;
            const courierSelect = document.getElementById('courier_id');
            const selectedCourierId = courierSelect.value;
            
            document.getElementById('preview_phone').textContent = phone;
            
            let previewText = '';
            const branchName = '<?php echo $branch_name; ?>';
            
            if(template === 'booking_confirmation') {
                previewText = `Dear Customer, your courier has been booked successfully. \n`;
                previewText += `Tracking No: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001 \n`;
                previewText += `From: City A to City B \n`;
                previewText += `Booked on: ${new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}. \n`;
                previewText += `Thank you for choosing ${branchName} Courier Service.`;
            } 
            else if(template === 'tracking_info') {
                previewText = `Your courier tracking information:\n`;
                previewText += `Tracking No: TRK${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}001\n`;
                previewText += `Status: Booked\n`;
                previewText += `Route: City A to City B\n`;
                previewText += `Booked on: ${new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}\n`;
                previewText += `Contact ${branchName} for updates.`;
            }
            else if(template === 'custom') {
                previewText = document.getElementById('custom_message').value || 
                             'Type your custom message...';
            }
            
            document.getElementById('sms_preview').textContent = previewText;
            
            // Update character counter for custom messages
            updateCharCounter();
        }
        
        // Load courier details when selected
        function loadCourierDetails() {
            const courierId = document.getElementById('courier_id').value;
            if(courierId && courierId !== 'custom') {
                // In a real application, you would fetch courier details via AJAX
                // For now, we'll just trigger preview update
                generatePreview();
            }
        }
        
        // Fill sender phone from selected courier
        function fillSenderPhone() {
            const courierSelect = document.getElementById('courier_id');
            const selectedOption = courierSelect.options[courierSelect.selectedIndex];
            const senderPhone = selectedOption.getAttribute('data-sender-phone');
            
            if(senderPhone) {
                document.getElementById('phone_number').value = senderPhone;
                generatePreview();
            } else {
                alert('Please select a courier first to fill sender phone.');
            }
        }
        
        // Fill receiver phone from selected courier
        function fillReceiverPhone() {
            const courierSelect = document.getElementById('courier_id');
            const selectedOption = courierSelect.options[courierSelect.selectedIndex];
            const receiverPhone = selectedOption.getAttribute('data-receiver-phone');
            
            if(receiverPhone) {
                document.getElementById('phone_number').value = receiverPhone;
                generatePreview();
            } else {
                alert('Please select a courier first to fill receiver phone.');
            }
        }
        
        // Select recent booking
        function selectRecentBooking(courierId, phoneNumber) {
            document.getElementById('courier_id').value = courierId;
            document.getElementById('phone_number').value = phoneNumber;
            selectTemplate('booking_confirmation');
            generatePreview();
            
            // Show success message
            showToast('Booking selected successfully!');
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
                document.getElementById('courier_id').value = '';
                document.getElementById('custom_message_container').style.display = 'none';
                document.querySelectorAll('.template-card').forEach(card => {
                    card.classList.remove('active');
                });
                document.getElementById('template_booking').classList.add('active');
                document.getElementById('sms_type').value = 'booking_confirmation';
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
            const template = document.getElementById('sms_type').value;
            const customMessage = document.getElementById('custom_message').value;
            
            // Validate phone number
            const phoneRegex = /^0[0-9]{10}$/;
            if(!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 11-digit Pakistani phone number starting with 0 (e.g., 03001234567).');
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
            if(!confirm(`Send SMS to ${phone}?`)) {
                e.preventDefault();
                return false;
            }
            
            return true;
        });
        
        // Auto-select booking template on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('template_booking').classList.add('active');
            generatePreview();
            
            // Set up event listeners
            document.getElementById('phone_number').addEventListener('input', generatePreview);
            document.getElementById('custom_message').addEventListener('input', function() {
                generatePreview();
                updateCharCounter();
            });
            
            // Auto-select courier if tracking number is in URL
            <?php if($tracking_no): ?>
                // Look for courier with this tracking number
                const options = document.getElementById('courier_id').options;
                for(let i = 0; i < options.length; i++) {
                    if(options[i].text.includes('<?php echo $tracking_no; ?>')) {
                        options[i].selected = true;
                        loadCourierDetails();
                        showToast('Tracking number <?php echo $tracking_no; ?> selected');
                        break;
                    }
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>