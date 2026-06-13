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

// Add Customer
//mysqli_real_escape_string Security: SQL injection se bachata hai (special characters ko escape karta hai)
if(isset($_POST['add_customer'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    mysqli_query($conn, "INSERT INTO customers (name, phone, email, address) 
                        VALUES ('$name', '$phone', '$email', '$address')");
    
    $msg = '<div class="alert alert-success">Customer added successfully!</div>';
}

// Get all customers
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
$total_customers = mysqli_num_rows($customers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin</title>
    
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
        
        .table-custom thead {
            background: var(--primary-blue);
            color: white;
        }
        
        .table-custom thead th {
            padding: 15px;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(255, 122, 0, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
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
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
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
        <div class="page-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h3><i class="fas fa-user-friends text-orange me-2"></i>Customer Management</h3>
                    <p class="text-muted">Manage your customer database</p>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $msg; ?>
        
        <!-- Add Customer Form -->
        <div class="form-card">
            <h4><i class="fas fa-user-plus me-2"></i>Add New Customer</h4>
            <form method="POST">
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name <span class="required-star">*</span></label>
                        <input class="form-control" type="text" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="required-star">*</span></label>
                        <input class="form-control" type="text" name="phone" placeholder="03XXXXXXXXX" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="required-star">*</span></label>
                        <input class="form-control" type="email" name="email" placeholder="customer@example.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" placeholder="Complete address" rows="1"></textarea>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-orange" name="add_customer" type="submit">
                        <i class="fas fa-plus-circle me-1"></i> Add Customer
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Customers List -->
        <div class="table-card">
            <h4><i class="fas fa-list me-2"></i>Customers List</h4>
            <p class="text-muted">Total <?php echo $total_customers; ?> customers found</p>
            
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($total_customers > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($customers)): ?>
                                <tr>
                                    <td><strong>#<?= $row['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['phone']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['address']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a class="btn btn-sm btn-warning" href="edit-customer.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn-danger" href="delete-customer.php?id=<?= $row['id'] ?>"
                                               onclick="return confirm('Delete this customer?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">No customers found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
    </script>
</body>
</html>