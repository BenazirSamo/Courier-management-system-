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

// Get customer ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if(!$id) {
    header("Location: customers.php");
    exit();
}

// Fetch customer
$customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE id = $id"));

if(!$customer) {
    die("Customer not found! <a href='customers.php'>Go back</a>");
}

// Handle update
if(isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    mysqli_query($conn, "UPDATE customers SET 
                        name='$name', 
                        phone='$phone', 
                        email='$email', 
                        address='$address' 
                        WHERE id=$id");
    
    if(mysqli_error($conn)) {
        $msg = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
    } else {
        $msg = '<div class="alert alert-success">Customer updated successfully!</div>';
        
        // Refresh data
        $customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE id = $id"));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer - Admin</title>
    
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
        
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
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
        
        .customer-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            margin: 0 auto 20px;
        }
        
        .customer-info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        
        .customer-info-box i {
            width: 30px;
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
            
            .info-card {
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
            
            .customer-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .customer-info-box {
                padding: 15px;
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
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="add-courier.php"><i class="fas fa-plus-circle"></i> Add Courier</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-courier.php"><i class="fas fa-boxes"></i> Manage Couriers</a></li>
                <li class="nav-item"><a class="nav-link" href="add-agent.php"><i class="fas fa-user-plus"></i> Add Agent</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-agent.php"><i class="fas fa-users"></i> Manage Agents</a></li>
                <li class="nav-item"><a class="nav-link active" href="customers.php"><i class="fas fa-user-friends"></i> Customers</a></li>
                <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="sms-booking.php"><i class="fas fa-sms"></i> Booking SMS</a></li>
                <li class="nav-item"><a class="nav-link" href="sms-delivery.php"><i class="fas fa-truck"></i> Delivery SMS</a></li>
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
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h3><i class="fas fa-user-edit text-orange me-2"></i>Edit Customer</h3>
                    <p class="text-muted">Update customer #<?php echo $id; ?></p>
                </div>
                <div>
                    <a href="customers.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $msg; ?>
        
        <!-- Customer Info -->
        <div class="info-card">
            <div class="text-center">
                <div class="customer-avatar">
                    <?php echo strtoupper(substr($customer['name'], 0, 1)); ?>
                </div>
                <h4><?= htmlspecialchars($customer['name']) ?></h4>
                <p class="text-muted">ID: #<?= $customer['id'] ?></p>
            </div>
            
            <div class="customer-info-box">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-phone text-orange me-2"></i>
                        <strong>Phone:</strong> <?= htmlspecialchars($customer['phone']) ?>
                    </div>
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-envelope text-orange me-2"></i>
                        <strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?>
                    </div>
                    <div class="col-12">
                        <i class="fas fa-map-marker-alt text-orange me-2"></i>
                        <strong>Address:</strong> <?= htmlspecialchars($customer['address']) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="form-card">
            <h4><i class="fas fa-edit me-2"></i>Update Details</h4>
            
            <form method="POST">
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name *</label>
                        <input class="form-control" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone *</label>
                        <input class="form-control" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email *</label>
                        <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="1"><?= htmlspecialchars($customer['address']) ?></textarea>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-orange">
                        <i class="fas fa-save me-2"></i>Update Customer
                    </button>
                    <a href="customers.php" class="btn btn-outline-secondary ms-2">
                        Cancel
                    </a>
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
    </script>
</body>
</html>