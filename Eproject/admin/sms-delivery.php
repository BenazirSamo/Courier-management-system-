<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$msg = "";

// Get in-transit couriers
$couriers = mysqli_query($conn, "
    SELECT c.*, s.name AS sender_name, s.phone AS sender_phone,
           r.name AS receiver_name, r.phone AS receiver_phone
    FROM couriers c
    JOIN customers s ON c.sender_id = s.id
    JOIN customers r ON c.receiver_id = r.id
    WHERE c.status = 'In Transit'
    ORDER BY c.id DESC
    LIMIT 10
");

// Handle SMS sending
if(isset($_POST['send_sms'])){
    $courier_id = mysqli_real_escape_string($conn, $_POST['courier_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $recipient_type = mysqli_real_escape_string($conn, $_POST['recipient_type']);
    
    $msg = '<div class="alert alert-success">Delivery SMS sent to ' . $phone . ' (' . $recipient_type . ')!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery SMS - Admin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Your Original CSS -->
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
        }
        
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
        }
        
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
        }
        
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
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
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .bg-transit { background: #dbeafe; color: #1e40af; }
        
        .sms-preview {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .sms-preview-content {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #bbdefb;
        }
        
        .char-counter {
            font-size: 12px;
            text-align: right;
            margin-top: 5px;
            color: #6c757d;
        }
        
        .courier-info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .template-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .template-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
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
            
            .form-card {
                padding: 20px 15px;
            }
            
            .table-card {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
            }
            
            .table th:nth-child(2),
            .table td:nth-child(2),
            .table th:nth-child(3),
            .table td:nth-child(3) {
                display: none;
            }
            
            .courier-info-box .row {
                flex-direction: column;
            }
            
            .courier-info-box .col-md-6 {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }
            
            .form-card {
                padding: 15px 10px;
            }
            
            .table-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .table th:nth-child(4),
            .table td:nth-child(4) {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" style="max-width: 140px;">
            <div class="text-white-50 small">Admin Panel</div>
        </div>
        
        <div class="flex-grow-1 p-3">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="add-courier.php"><i class="fas fa-plus-circle"></i> Add Courier</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-courier.php"><i class="fas fa-boxes"></i> Manage Couriers</a></li>
                <li class="nav-item"><a class="nav-link" href="add-agent.php"><i class="fas fa-user-plus"></i> Add Agent</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-agent.php"><i class="fas fa-users"></i> Manage Agents</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php"><i class="fas fa-user-friends"></i> Customers</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="sms-booking.php"><i class="fas fa-sms"></i> Booking SMS</a></li>
                <li class="nav-item"><a class="nav-link active" href="sms-delivery.php"><i class="fas fa-truck"></i> Delivery SMS</a></li>
            </ul>
        </div>
        
        <div class="p-3 border-top">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-2">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div>
                    <div class="text-white"><?php echo $username; ?></div>
                    <small class="text-white-50">Administrator</small>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-light w-100 mt-3">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h3><i class="fas fa-truck text-orange me-2"></i>Delivery SMS</h3>
                    <p class="text-muted">Send delivery updates to customers</p>
                </div>
                <div>
                    <a href="sms-booking.php" class="btn btn-outline-secondary">
                        <i class="fas fa-exchange-alt me-1"></i> Booking SMS
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $msg; ?>
        
        <!-- In Transit Couriers -->
        <div class="table-card">
            <h4><i class="fas fa-shipping-fast me-2"></i>In Transit Couriers</h4>
            <p class="text-muted">Select a courier to send delivery update</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tracking</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($couriers) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($couriers)): ?>
                                <tr>
                                    <td><strong><?= $row['tracking_no'] ?></strong></td>
                                    <td><?= $row['sender_name'] ?><br><small><?= $row['sender_phone'] ?></small></td>
                                    <td><?= $row['receiver_name'] ?><br><small><?= $row['receiver_phone'] ?></small></td>
                                    <td><?= $row['from_city'] ?>→<?= $row['to_city'] ?></td>
                                    <td><span class="badge bg-transit">In Transit</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-orange select-courier" 
                                                data-id="<?= $row['id'] ?>"
                                                data-tracking="<?= $row['tracking_no'] ?>"
                                                data-sender-name="<?= $row['sender_name'] ?>"
                                                data-sender-phone="<?= $row['sender_phone'] ?>"
                                                data-receiver-name="<?= $row['receiver_name'] ?>"
                                                data-receiver-phone="<?= $row['receiver_phone'] ?>">
                                            <i class="fas fa-paper-plane"></i> Select
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4">No couriers in transit</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Courier Info (hidden initially) -->
        <div class="courier-info-box" id="courierInfo" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <strong>Tracking:</strong> <span id="infoTracking"></span><br>
                    <strong>Sender:</strong> <span id="infoSender"></span><br>
                    <strong>Sender Phone:</strong> <span id="infoSenderPhone"></span>
                </div>
                <div class="col-md-6">
                    <strong>Receiver:</strong> <span id="infoReceiver"></span><br>
                    <strong>Receiver Phone:</strong> <span id="infoReceiverPhone"></span>
                </div>
            </div>
        </div>
        
        <!-- SMS Form -->
        <div class="form-card">
            <h4><i class="fas fa-truck-loading me-2"></i>Send Delivery Update</h4>
            
            <form method="POST" id="smsForm">
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label>Courier</label>
                        <select name="courier_id" id="courier_id" class="form-control" required>
                            <option value="">Select</option>
                            <?php mysqli_data_seek($couriers, 0); while($row = mysqli_fetch_assoc($couriers)): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['tracking_no'] ?> - <?= $row['sender_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Recipient</label>
                        <select name="recipient_type" id="recipient_type" class="form-control" required>
                            <option value="sender">Sender</option>
                            <option value="receiver">Receiver</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Status Template</label>
                        <select id="delivery_status" class="form-control">
                            <option value="dispatched">Dispatched</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>Message <span id="charCount" class="float-end">0/160</span></label>
                    <textarea name="message" id="message" class="form-control" rows="4" 
                              placeholder="Type message..." required oninput="updateCount()"></textarea>
                </div>
                
                <!-- Preview -->
                <div class="sms-preview">
                    <div class="sms-preview-header"><i class="fas fa-mobile-alt me-2"></i>Preview</div>
                    <div class="sms-preview-content" id="smsPreview">Message preview...</div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" name="send_sms" class="btn btn-orange">
                        <i class="fas fa-paper-plane me-1"></i> Send SMS
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Templates -->
        <div class="table-card">
            <h5><i class="fas fa-list-alt me-2"></i>Templates</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="template-card p-3">
                        <h6><i class="fas fa-shipping-fast text-primary"></i> Dispatched</h6>
                        <p class="small">Your courier [Tracking] has been dispatched.</p>
                        <button class="btn btn-sm btn-outline-primary" onclick="useTemplate('dispatched')">Use</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="template-card p-3">
                        <h6><i class="fas fa-truck text-info"></i> Out for Delivery</h6>
                        <p class="small">Your courier [Tracking] is out for delivery today.</p>
                        <button class="btn btn-sm btn-outline-info" onclick="useTemplate('out_for_delivery')">Use</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="template-card p-3">
                        <h6><i class="fas fa-check-circle text-success"></i> Delivered</h6>
                        <p class="small">Your courier [Tracking] has been delivered.</p>
                        <button class="btn btn-sm btn-outline-success" onclick="useTemplate('delivered')">Use</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu
        const menuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Select courier
        document.querySelectorAll('.select-courier').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('courier_id').value = this.dataset.id;
                document.getElementById('infoTracking').innerText = this.dataset.tracking;
                document.getElementById('infoSender').innerText = this.dataset.senderName;
                document.getElementById('infoSenderPhone').innerText = this.dataset.senderPhone;
                document.getElementById('infoReceiver').innerText = this.dataset.receiverName;
                document.getElementById('infoReceiverPhone').innerText = this.dataset.receiverPhone;
                document.getElementById('courierInfo').style.display = 'block';
                
                updatePhone();
                useTemplate('dispatched');
            });
        });
        
        // Update phone based on recipient
        function updatePhone() {
            let type = document.getElementById('recipient_type').value;
            if(type === 'sender') {
                document.getElementById('phone').value = document.getElementById('infoSenderPhone').innerText;
            } else if(type === 'receiver') {
                document.getElementById('phone').value = document.getElementById('infoReceiverPhone').innerText;
            } else {
                document.getElementById('phone').value = document.getElementById('infoSenderPhone').innerText + ', ' + 
                                                       document.getElementById('infoReceiverPhone').innerText;
            }
        }
        
        document.getElementById('recipient_type').addEventListener('change', updatePhone);
        
        // Character count and preview
        function updateCount() {
            let msg = document.getElementById('message');
            let count = msg.value.length;
            document.getElementById('charCount').innerHTML = count + '/160';
            document.getElementById('smsPreview').innerHTML = msg.value || 'Message preview...';
        }
        
        // Templates
        window.useTemplate = function(type) {
            let tracking = document.getElementById('infoTracking').innerText || 'TRK123';
            let msg = '';
            
            if(type === 'dispatched') msg = `Your courier ${tracking} has been dispatched and is on its way.`;
            if(type === 'out_for_delivery') msg = `Your courier ${tracking} is out for delivery today. Please be available.`;
            if(type === 'delivered') msg = `Great news! Your courier ${tracking} has been delivered successfully.`;
            
            document.getElementById('message').value = msg;
            updateCount();
        };
        
        document.getElementById('delivery_status').addEventListener('change', function() {
            useTemplate(this.value);
        });
    </script>
</body>
</html>