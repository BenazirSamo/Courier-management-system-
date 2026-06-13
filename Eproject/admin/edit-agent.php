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

// Get agent ID from URL
//Check kar raha hai ke URL mein id parameter hai ya nahi
$agent_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if(!$agent_id) {
    header("Location: manage-agent.php");
    exit();
}

// Fetch agent data
$agent = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT a.id, a.branch_name, a.city, a.phone, 
           u.username, u.email, u.id as user_id
    FROM agents a
    JOIN users u ON a.user_id = u.id
    WHERE a.id = $agent_id
"));

if(!$agent) {
    die("Agent not found! <a href='manage-agent.php'>Go back</a>");
}

// Handle form submission
if(isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Update users table
    mysqli_query($conn, "UPDATE users SET 
                        username='$username', 
                        email='$email', 
                        city='$city' 
                        WHERE id={$agent['user_id']}");
    
    // Update agents table
    mysqli_query($conn, "UPDATE agents SET 
                        branch_name='$branch', 
                        city='$city', 
                        phone='$phone' 
                        WHERE id=$agent_id");
    
    if(mysqli_error($conn)) {
        $msg = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
    } else {
        $msg = '<div class="alert alert-success">Agent updated successfully!</div>';
        
        // Refresh data
        $agent = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT a.id, a.branch_name, a.city, a.phone, 
                   u.username, u.email, u.id as user_id
            FROM agents a
            JOIN users u ON a.user_id = u.id
            WHERE a.id = $agent_id
        "));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agent - Admin</title>
    
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
        
        /* Mobile Menu Button */
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
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        /* Overlay for mobile */
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
            transition: margin-left 0.3s ease;
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
        
        .info-card {
            background: white;
            padding: 25px;
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
        
        /* Agent Avatar */
        .agent-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 32px;
            margin: 0 auto 20px;
        }
        
        .agent-info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        /* Logo styling */
        .sidebar img {
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
        }
        
        /* Responsive Styles */
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
            
            .agent-info-box .col-md-6 {
                width: 100%;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .form-card {
                padding: 15px;
            }
            
            .info-card {
                padding: 15px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
            }
            
            .agent-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .agent-info-box {
                padding: 15px;
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
            
            .info-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .page-header {
                margin-bottom: 20px;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar, .mobile-menu-btn, .sidebar-overlay {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .form-card, .info-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
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
        <!-- Logo -->
        <div class="p-4 text-center border-bottom">
            <img src="../assets/images/new logo.png" 
                 alt="Courier MS Logo"
                 style="max-width: 140px; height: auto;"
                 class="mb-2">
            <div class="text-white-50 small">Admin Panel</div>
        </div>
        
        <!-- Navigation -->
        <div class="flex-grow-1 p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-courier.php">
                        <i class="fas fa-plus-circle"></i> Add Courier
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-courier.php">
                        <i class="fas fa-boxes"></i> Manage Couriers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-agent.php">
                        <i class="fas fa-user-plus"></i> Add Agent
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="manage-agent.php">
                        <i class="fas fa-users"></i> Manage Agents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="customers.php">
                        <i class="fas fa-user-friends"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-booking.php">
                        <i class="fas fa-sms"></i> Booking SMS
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sms-delivery.php">
                        <i class="fas fa-truck"></i> Delivery SMS
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- User Info -->
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
    <div class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-user-edit text-orange me-2"></i>Edit Agent</h1>
                    <p class="text-muted mb-0">Update agent information for ID #<?php echo $agent_id; ?></p>
                </div>
                <div>
                    <a href="manage-agent.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Agents
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php if ($msg): ?>
            <?php echo $msg; ?>
        <?php endif; ?>
        
        <!-- Agent Info -->
        <div class="info-card">
            <div class="text-center">
                <div class="agent-avatar">
                    <?php echo strtoupper(substr($agent['username'], 0, 1)); ?>
                </div>
                <h4><?= htmlspecialchars($agent['username']) ?></h4>
                <p class="text-muted">Agent ID: #<?= $agent['id'] ?></p>
            </div>
            
            <div class="agent-info-box">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-store text-orange me-3 fs-4"></i>
                            <div>
                                <div class="text-muted">Branch</div>
                                <div class="fw-bold"><?= htmlspecialchars($agent['branch_name']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-city text-orange me-3 fs-4"></i>
                            <div>
                                <div class="text-muted">City</div>
                                <div class="fw-bold"><?= htmlspecialchars($agent['city']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-orange me-3 fs-4"></i>
                            <div>
                                <div class="text-muted">Phone</div>
                                <div class="fw-bold"><?= htmlspecialchars($agent['phone']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-orange me-3 fs-4"></i>
                            <div>
                                <div class="text-muted">Email</div>
                                <div class="fw-bold"><?= htmlspecialchars($agent['email']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="form-card">
            <div class="mb-4">
                <h4 class="text-dark mb-3"><i class="fas fa-edit me-2"></i>Update Agent Details</h4>
                <p class="text-muted">Please update the agent information below</p>
            </div>
            
            <form method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" name="username" placeholder="Enter agent username" 
                                   value="<?= htmlspecialchars($agent['username']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input class="form-control" name="email" type="email" placeholder="agent@example.com" 
                                   value="<?= htmlspecialchars($agent['email']) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Name <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-store"></i></span>
                            <input class="form-control" name="branch" placeholder="Enter branch name" 
                                   value="<?= htmlspecialchars($agent['branch_name']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                            <input class="form-control" name="city" placeholder="Enter city" 
                                   value="<?= htmlspecialchars($agent['city']) ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input class="form-control" name="phone" placeholder="+92 300 1234567" 
                                   value="<?= htmlspecialchars($agent['phone']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Agent ID: #<?= $agent['id'] ?> cannot be changed
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <button class="btn btn-orange" name="update" type="submit">
                        <i class="fas fa-save me-1"></i> Update Agent
                    </button>
                    <a href="manage-agent.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Responsive Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Function to show/hide sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            // Mobile menu button click
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            
            // Overlay click to close sidebar
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
            
            // Auto-hide sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>