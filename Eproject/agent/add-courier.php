<?php
session_start();
include("../config/db.php");

// Check login
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get agent
$agent = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT u.id as user_id, a.id as agent_id, a.branch_name, a.city 
     FROM users u 
     LEFT JOIN agents a ON u.id = a.user_id 
     WHERE u.username='$username' AND u.role='3'"
));

if(!$agent) {
    header("Location: ../public/login.php");
    exit();
}

// Create agent if not exists
if(!$agent['agent_id']) {
    mysqli_query($conn, "INSERT INTO agents (user_id,branch_name,city) VALUES ('{$agent['user_id']}','Main Branch','Karachi')");
    $agent['agent_id'] = mysqli_insert_id($conn);
    $agent['branch_name'] = 'Main Branch';
    $agent['city'] = 'Karachi';
}

// Initialize variables
$sender_name = $sender_phone = $sender_address = $sender_email = '';
$receiver_name = $receiver_phone = $receiver_address = $receiver_email = '';
$from_city = $to_city = $price = '';
$message = '';
$message_type = '';

// Handle form
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_name = mysqli_real_escape_string($conn, $_POST['sender_name']);
    $sender_phone = mysqli_real_escape_string($conn, $_POST['sender_phone']);
    $sender_address = mysqli_real_escape_string($conn, $_POST['sender_address']);
    $sender_email = mysqli_real_escape_string($conn, $_POST['sender_email']);
    
    $receiver_name = mysqli_real_escape_string($conn, $_POST['receiver_name']);
    $receiver_phone = mysqli_real_escape_string($conn, $_POST['receiver_phone']);
    $receiver_address = mysqli_real_escape_string($conn, $_POST['receiver_address']);
    $receiver_email = mysqli_real_escape_string($conn, $_POST['receiver_email']);
    
    $from_city = mysqli_real_escape_string($conn, $_POST['from_city']);
    $to_city = mysqli_real_escape_string($conn, $_POST['to_city']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    
    if(empty($sender_name) || empty($sender_phone) || empty($receiver_name) || empty($receiver_phone) || empty($from_city) || empty($to_city) || empty($price)) {
        $message = "Please fill all required fields!";
        $message_type = 'error';
    }
    elseif($from_city == $to_city) {
        $message = "From City and To City cannot be same!";
        $message_type = 'error';
    }
    else {
        // Generate tracking number
        do {
            $tracking = 'TRK' . date('Ymd') . rand(1000, 9999);
            $check = mysqli_query($conn, "SELECT id FROM couriers WHERE tracking_no='$tracking'");
        } while(mysqli_num_rows($check) > 0);
        
        mysqli_begin_transaction($conn);
        
        // Insert sender
        mysqli_query($conn, "INSERT INTO customers (name, phone, address, email) VALUES ('$sender_name', '$sender_phone', '$sender_address', '$sender_email')");
        $sender_id = mysqli_insert_id($conn);
        
        // Insert receiver
        mysqli_query($conn, "INSERT INTO customers (name, phone, address, email) VALUES ('$receiver_name', '$receiver_phone', '$receiver_address', '$receiver_email')");
        $receiver_id = mysqli_insert_id($conn);
        
        // Insert courier
        mysqli_query($conn, "INSERT INTO couriers (tracking_no, sender_id, receiver_id, from_city, to_city, agent_id, price, booking_date, status) VALUES ('$tracking', '$sender_id', '$receiver_id', '$from_city', '$to_city', '{$agent['agent_id']}', '$price', CURDATE(), 'Booked')");
        $courier_id = mysqli_insert_id($conn);
        
        // Insert status
        mysqli_query($conn, "INSERT INTO courier_status (courier_id, status, location) VALUES ('$courier_id', 'Booked', '$from_city')");
        
        if(mysqli_error($conn)) {
            mysqli_rollback($conn);
            $message = "Error adding courier: " . mysqli_error($conn);
            $message_type = 'error';
        } else {
            mysqli_commit($conn);
            $message = "Courier added successfully!<br><strong>Tracking Number: $tracking</strong><br>Keep this number for tracking.";
            $message_type = 'success';
            
            // Clear form
            $sender_name = $sender_phone = $sender_address = $sender_email = '';
            $receiver_name = $receiver_phone = $receiver_address = $receiver_email = '';
            $from_city = $to_city = $price = '';
        }
    }
}

// Get recent customers
$recent_customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC LIMIT 10");

// Cities list
$cities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Sialkot', 'Gujranwala'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Courier - Agent Panel</title>
    
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
        
        .section-title {
            color: var(--primary-blue);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: 600;
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
        
        @media (max-width: 991.98px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link i { margin-right: 0; font-size: 20px; }
            .main-content { margin-left: 70px; }
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
                    <a class="nav-link active" href="add-courier.php">
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
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $_SESSION['username']; ?></h6>
                    <small class="text-white-50"><?php echo $agent['branch_name']; ?></small>
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
                    <h1 class="h3 mb-2"><i class="fas fa-plus-circle text-orange me-2"></i>Add New Courier</h1>
                    <p class="text-muted mb-0">Create a new courier shipment from <?php echo $agent['branch_name']; ?></p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Agent:</strong> <?php echo $_SESSION['username']; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
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
        
        <form method="POST" action="" id="courierForm">
            <div class="form-card">
                <h4 class="section-title"><i class="fas fa-user me-2"></i> Sender Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sender_name" class="form-label">Sender Name *</label>
                            <input type="text" class="form-control" id="sender_name" name="sender_name" 
                                   value="<?php echo htmlspecialchars($sender_name); ?>" required
                                   placeholder="Enter sender's full name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sender_phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="sender_phone" name="sender_phone" 
                                   value="<?php echo htmlspecialchars($sender_phone); ?>" required
                                   placeholder="03XXXXXXXXX">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sender_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="sender_email" name="sender_email" 
                                   value="<?php echo htmlspecialchars($sender_email); ?>"
                                   placeholder="sender@example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sender_address" class="form-label">Address *</label>
                            <textarea class="form-control" id="sender_address" name="sender_address" 
                                      rows="2" required placeholder="Full address with city"><?php echo htmlspecialchars($sender_address); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <h4 class="section-title mt-5"><i class="fas fa-user-friends me-2"></i> Receiver Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiver_name" class="form-label">Receiver Name *</label>
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" 
                                   value="<?php echo htmlspecialchars($receiver_name); ?>" required
                                   placeholder="Enter receiver's full name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiver_phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="receiver_phone" name="receiver_phone" 
                                   value="<?php echo htmlspecialchars($receiver_phone); ?>" required
                                   placeholder="03XXXXXXXXX">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiver_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="receiver_email" name="receiver_email" 
                                   value="<?php echo htmlspecialchars($receiver_email); ?>"
                                   placeholder="receiver@example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receiver_address" class="form-label">Address *</label>
                            <textarea class="form-control" id="receiver_address" name="receiver_address" 
                                      rows="2" required placeholder="Full address with city"><?php echo htmlspecialchars($receiver_address); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <h4 class="section-title mt-5"><i class="fas fa-shipping-fast me-2"></i> Courier Details</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_city" class="form-label">From City *</label>
                            <select class="form-control" id="from_city" name="from_city" required>
                                <option value="">Select Origin City</option>
                                <?php foreach($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" 
                                        <?php echo ($from_city == $city || $agent['city'] == $city) ? 'selected' : ''; ?>>
                                        <?php echo $city; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_city" class="form-label">To City *</label>
                            <select class="form-control" id="to_city" name="to_city" required>
                                <option value="">Select Destination City</option>
                                <?php foreach($cities as $city): ?>
                                    <option value="<?php echo $city; ?>" <?php echo ($to_city == $city) ? 'selected' : ''; ?>>
                                        <?php echo $city; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="form-label">Price (PKR) *</label>
                            <div class="input-group">
                                <span class="input-group-text">PKR</span>
                                <input type="number" class="form-control" id="price" name="price" 
                                       step="0.01" min="1" value="<?php echo htmlspecialchars($price); ?>" required
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Branch</label>
                            <input type="text" class="form-control" value="<?php echo $agent['branch_name']; ?>" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-redo me-2"></i> Clear Form
                            </button>
                            <button type="submit" class="btn btn-orange btn-lg px-5">
                                <i class="fas fa-save me-2"></i> Create Courier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('courierForm').addEventListener('submit', function(e) {
            const fromCity = document.getElementById('from_city').value;
            const toCity = document.getElementById('to_city').value;
            const price = document.getElementById('price').value;
            
            if(fromCity === toCity) {
                e.preventDefault();
                alert('Error: From City and To City cannot be the same!');
                return false;
            }
            
            if(parseFloat(price) <= 0) {
                e.preventDefault();
                alert('Error: Price must be greater than 0!');
                return false;
            }
        });
    </script>
</body>
</html>