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

// Get booked couriers for SMS
$couriers = mysqli_query($conn, "
    SELECT c.*, s.name AS sender_name, s.phone AS sender_phone
    FROM couriers c
    JOIN customers s ON c.sender_id = s.id
    WHERE c.status = 'Booked'
    ORDER BY c.id DESC
    LIMIT 10
");

// Handle SMS sending
if(isset($_POST['send_sms'])){
    $courier_id = mysqli_real_escape_string($conn, $_POST['courier_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // In real project, you'd add SMS API here
    $msg = '<div class="alert alert-success">SMS sent successfully to ' . $phone . '!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking SMS - Admin</title>
    
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
        
        .bg-booked { background: #fef3c7; color: #92400e; }
        
        .sms-preview {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .sms-preview-content {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c8e6c9;
        }
        
        .char-counter {
            font-size: 12px;
            text-align: right;
            margin-top: 5px;
            color: #6c757d;
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
            
            .table th:nth-child(3),
            .table td:nth-child(3) {
                display: none;
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
            
            .table th:nth-child(2),
            .table td:nth-child(2) {
                display: none;
            }
            
            .template-card {
                margin-bottom: 15px;
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
                <li class="nav-item"><a class="nav-link active" href="sms-booking.php"><i class="fas fa-sms"></i> Booking SMS</a></li>
                <li class="nav-item"><a class="nav-link" href="sms-delivery.php"><i class="fas fa-truck"></i> Delivery SMS</a></li>
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
                    <h3><i class="fas fa-sms text-orange me-2"></i>Booking SMS</h3>
                    <p class="text-muted">Send SMS to customers about their booking</p>
                </div>
                <div>
                    <a href="sms-delivery.php" class="btn btn-outline-secondary">
                        <i class="fas fa-exchange-alt me-1"></i> Delivery SMS
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $msg; ?>
        
        <!-- Recent Bookings -->
        <div class="table-card">
            <h4><i class="fas fa-clock me-2"></i>Recent Bookings</h4>
            <p class="text-muted">Select a courier to send SMS</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tracking</th>
                            <th>Sender</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($couriers) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($couriers)): ?>
                                <tr>
                                    <td><strong><?= $row['tracking_no'] ?></strong></td>
                                    <td><?= $row['sender_name'] ?></td>
                                    <td><?= $row['sender_phone'] ?></td>
                                    <td><?= date('d M Y', strtotime($row['booking_date'])) ?></td>
                                    <td><span class="badge bg-booked">Booked</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-orange select-courier" 
                                                data-id="<?= $row['id'] ?>"
                                                data-tracking="<?= $row['tracking_no'] ?>"
                                                data-name="<?= $row['sender_name'] ?>"
                                                data-phone="<?= $row['sender_phone'] ?>">
                                            <i class="fas fa-paper-plane"></i> Select
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4">No bookings found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- SMS Form -->
        <div class="form-card">
            <h4><i class="fas fa-paper-plane me-2"></i>Send SMS</h4>
            
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
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="+92 300 1234567" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>Message <span id="charCount" class="float-end">0/160</span></label>
                    <textarea name="message" id="message" class="form-control" rows="4" 
                              placeholder="Type your message..." required oninput="updateCount()"></textarea>
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
                    <button type="button" class="btn btn-outline-success ms-2" onclick="useTemplate()">
                        <i class="fas fa-magic me-1"></i> Template
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
                        <h6><i class="fas fa-check-circle text-success"></i> Booking</h6>
                        <p class="small">Dear [Name], your courier [Tracking] booked successfully.</p>
                        <button class="btn btn-sm btn-outline-success" onclick="useTemplate(1)">Use</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="template-card p-3">
                        <h6><i class="fas fa-info-circle text-info"></i> Pickup</h6>
                        <p class="small">Your courier [Tracking] ready for pickup from branch.</p>
                        <button class="btn btn-sm btn-outline-info" onclick="useTemplate(2)">Use</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="template-card p-3">
                        <h6><i class="fas fa-phone text-warning"></i> Contact</h6>
                        <p class="small">Regarding courier [Tracking], please contact us.</p>
                        <button class="btn btn-sm btn-outline-warning" onclick="useTemplate(3)">Use</button>
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
        
        // Select courier buttons
        document.querySelectorAll('.select-courier').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('courier_id').value = this.dataset.id;
                document.getElementById('phone').value = this.dataset.phone;
                
                let msg = `Dear ${this.dataset.name}, your courier ${this.dataset.tracking} has been booked successfully.`;
                document.getElementById('message').value = msg;
                updateCount();
            });
        });
        
        // Character count and preview
        function updateCount() {
            let msg = document.getElementById('message');
            let count = msg.value.length;
            document.getElementById('charCount').innerHTML = count + '/160';
            document.getElementById('smsPreview').innerHTML = msg.value || 'Message preview...';
        }
        
        // Templates
        window.useTemplate = function(type) {
            let tracking = document.getElementById('courier_id').selectedOptions[0]?.text.split(' - ')[0] || 'TRK123';
            let msg = '';
            
            if(type === 1) msg = `Dear Customer, your courier ${tracking} booked successfully.`;
            if(type === 2) msg = `Your courier ${tracking} is ready for pickup from our branch.`;
            if(type === 3) msg = `Regarding courier ${tracking}, please contact us at 0300-1234567.`;
            
            document.getElementById('message').value = msg;
            updateCount();
        };
    </script>
</body>
</html>