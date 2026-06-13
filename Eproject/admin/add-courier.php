<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$error = "";
$success = "";

// Get agents list
$agents = mysqli_query($conn, "SELECT a.id, u.username FROM agents a JOIN users u ON a.user_id = u.id");

// Get customers list
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY name");

// Handle form submission
if(isset($_POST['add_courier'])) {
    $sender_id = mysqli_real_escape_string($conn, $_POST['sender_id']);
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);
    $from_city = mysqli_real_escape_string($conn, $_POST['from_city']);
    $to_city = mysqli_real_escape_string($conn, $_POST['to_city']);
    $agent_id = mysqli_real_escape_string($conn, $_POST['agent_id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    
    // Generate tracking number
    $tracking = 'TRK' . time() . rand(100, 999);
    
    $query = "INSERT INTO couriers (tracking_no, sender_id, receiver_id, from_city, to_city, agent_id, price, booking_date, status) 
              VALUES ('$tracking', '$sender_id', '$receiver_id', '$from_city', '$to_city', '$agent_id', '$price', CURDATE(), 'Booked')";
    
    if(mysqli_query($conn, $query)) {
        $success = '<div class="alert alert-success">Courier added! Tracking: <strong>' . $tracking . '</strong></div>';
    } else {
        $error = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Courier - Admin</title>
    
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .mobile-menu-btn:hover {
            background: var(--secondary-blue);
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
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
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
        
        .required-star {
            color: #dc3545;
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
        }
        
        @media (max-width: 768px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
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
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" alt="Logo" style="max-width: 140px;" class="mb-2">
            <div class="text-white-50 small">Admin Panel</div>
        </div>
        
        <div class="flex-grow-1 p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="add-courier.php"><i class="fas fa-plus-circle"></i> Add Courier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-courier.php"><i class="fas fa-boxes"></i> Manage Couriers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-agent.php"><i class="fas fa-user-plus"></i> Add Agent</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-agent.php"><i class="fas fa-users"></i> Manage Agents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customers.php"><i class="fas fa-user-friends"></i> Customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-booking.php"><i class="fas fa-sms"></i> Booking SMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-delivery.php"><i class="fas fa-truck"></i> Delivery SMS</a>
                </li>
            </ul>
        </div>
        
        <div class="p-3 border-top mt-auto">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-3">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $username; ?></h6>
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
        <div class="page-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h3><i class="fas fa-plus-circle text-orange me-2"></i>Add New Courier</h3>
                    <p class="text-muted">Create a new courier shipment</p>
                </div>
                <div>
                    <a href="manage-courier.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $error . $success; ?>
        
        <!-- Form -->
        <div class="form-card">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sender <span class="required-star">*</span></label>
                        <select name="sender_id" class="form-control" required>
                            <option value="">Select Sender</option>
                            <?php mysqli_data_seek($customers, 0); while($row = mysqli_fetch_assoc($customers)): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= $row['phone'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Receiver <span class="required-star">*</span></label>
                        <select name="receiver_id" class="form-control" required>
                            <option value="">Select Receiver</option>
                            <?php mysqli_data_seek($customers, 0); while($row = mysqli_fetch_assoc($customers)): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= $row['phone'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From City <span class="required-star">*</span></label>
                        <input type="text" name="from_city" class="form-control" placeholder="Origin city" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">To City <span class="required-star">*</span></label>
                        <input type="text" name="to_city" class="form-control" placeholder="Destination city" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent <span class="required-star">*</span></label>
                        <select name="agent_id" class="form-control" required>
                            <option value="">Select Agent</option>
                            <?php mysqli_data_seek($agents, 0); while($row = mysqli_fetch_assoc($agents)): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (PKR) <span class="required-star">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="Amount" required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-orange" name="add_courier" type="submit">
                        <i class="fas fa-plus-circle me-1"></i> Create Courier
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                </div>
            </form>
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
        
        // Auto-close on resize
        window.addEventListener('resize', () => {
            if(window.innerWidth > 991) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let from = document.querySelector('[name="from_city"]').value;
            let to = document.querySelector('[name="to_city"]').value;
            
            if(from.toLowerCase() === to.toLowerCase()) {
                e.preventDefault();
                alert('From City and To City cannot be the same!');
            }
        });
    </script>
</body>
</html>