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

// Get courier ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if(!$id) {
    header("Location: manage-courier.php");
    exit();
}

// Fetch courier details
$courier = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT c.*, 
           s.name as sender_name, s.phone as sender_phone,
           r.name as receiver_name, r.phone as receiver_phone,
           u.username as agent_name
    FROM couriers c
    JOIN customers s ON c.sender_id = s.id
    JOIN customers r ON c.receiver_id = r.id
    JOIN agents a ON c.agent_id = a.id
    JOIN users u ON a.user_id = u.id
    WHERE c.id = $id
"));

if(!$courier) {
    die("Courier not found! <a href='manage-courier.php'>Go back</a>");
}

// Get all agents for dropdown
$agents = mysqli_query($conn, "
    SELECT a.id, u.username 
    FROM agents a
    JOIN users u ON a.user_id = u.id
");

// Handle update
if(isset($_POST['update'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $agent_id = (int)$_POST['agent_id'];
    
    mysqli_query($conn, "UPDATE couriers SET status='$status', agent_id='$agent_id' WHERE id=$id");
    
    if(mysqli_error($conn)) {
        $msg = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
    } else {
        $msg = '<div class="alert alert-success">Courier updated successfully!</div>';
        
        // Refresh data
        $courier = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT c.*, 
                   s.name as sender_name, s.phone as sender_phone,
                   r.name as receiver_name, r.phone as receiver_phone,
                   u.username as agent_name
            FROM couriers c
            JOIN customers s ON c.sender_id = s.id
            JOIN customers r ON c.receiver_id = r.id
            JOIN agents a ON c.agent_id = a.id
            JOIN users u ON a.user_id = u.id
            WHERE c.id = $id
        "));
    }
}

// Status badge class
$status_class = '';
if($courier['status'] == 'Booked') $status_class = 'bg-booked';
if($courier['status'] == 'In Transit') $status_class = 'bg-transit';
if($courier['status'] == 'Delivered') $status_class = 'bg-delivered';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Courier - Admin</title>
    
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
        
        .badge {
            padding: 8px 16px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .bg-booked { background-color: #fef3c7; color: #92400e; }
        .bg-transit { background-color: #dbeafe; color: #1e40af; }
        .bg-delivered { background-color: #d1fae5; color: #065f46; }
        
        .tracking-no {
            font-family: monospace;
            font-weight: 700;
            font-size: 24px;
            color: var(--primary-blue);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .info-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 600;
            font-size: 16px;
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
            
            .info-grid {
                grid-template-columns: 1fr;
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
            
            .tracking-no {
                font-size: 20px;
            }
            
            .info-box {
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
                <li class="nav-item"><a class="nav-link active" href="manage-courier.php"><i class="fas fa-boxes"></i> Manage Couriers</a></li>
                <li class="nav-item"><a class="nav-link" href="add-agent.php"><i class="fas fa-user-plus"></i> Add Agent</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-agent.php"><i class="fas fa-users"></i> Manage Agents</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php"><i class="fas fa-user-friends"></i> Customers</a></li>
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
                    <h3><i class="fas fa-edit text-orange me-2"></i>Edit Courier</h3>
                    <p class="text-muted">Update courier #<?php echo $id; ?></p>
                </div>
                <div>
                    <a href="manage-courier.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php echo $msg; ?>
        
        <!-- Tracking Info -->
        <div class="info-card">
            <div class="text-center">
                <h5><i class="fas fa-barcode me-2"></i>Tracking Number</h5>
                <div class="tracking-no"><?= $courier['tracking_no'] ?></div>
                <span class="badge <?= $status_class ?> mt-2">
                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                    <?= $courier['status'] ?>
                </span>
            </div>
        </div>
        
        <!-- Courier Info Grid -->
        <div class="info-card">
            <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Courier Details</h5>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label"><i class="fas fa-user me-1"></i> Sender</div>
                    <div class="info-value"><?= htmlspecialchars($courier['sender_name']) ?></div>
                    <small><?= $courier['sender_phone'] ?></small>
                </div>
                
                <div class="info-box">
                    <div class="info-label"><i class="fas fa-user-friends me-1"></i> Receiver</div>
                    <div class="info-value"><?= htmlspecialchars($courier['receiver_name']) ?></div>
                    <small><?= $courier['receiver_phone'] ?></small>
                </div>
                
                <div class="info-box">
                    <div class="info-label"><i class="fas fa-route me-1"></i> Route</div>
                    <div class="info-value"><?= $courier['from_city'] ?> → <?= $courier['to_city'] ?></div>
                </div>
                
                <div class="info-box">
                    <div class="info-label"><i class="fas fa-money-bill me-1"></i> Price</div>
                    <div class="info-value">PKR <?= number_format($courier['price'], 2) ?></div>
                </div>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="form-card">
            <h4><i class="fas fa-cog me-2"></i>Update Status & Agent</h4>
            
            <form method="POST">
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assign Agent *</label>
                        <select name="agent_id" class="form-control" required>
                            <?php mysqli_data_seek($agents, 0); while($row = mysqli_fetch_assoc($agents)): ?>
                                <option value="<?= $row['id'] ?>" <?= $row['id'] == $courier['agent_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['username']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="Booked" <?= $courier['status']=='Booked'?'selected':'' ?>>Booked</option>
                            <option value="In Transit" <?= $courier['status']=='In Transit'?'selected':'' ?>>In Transit</option>
                            <option value="Delivered" <?= $courier['status']=='Delivered'?'selected':'' ?>>Delivered</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-orange">
                        <i class="fas fa-save me-2"></i>Update Courier
                    </button>
                    <a href="manage-courier.php" class="btn btn-outline-secondary ms-2">
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
        
        // Update badge on status change
        const statusSelect = document.querySelector('select[name="status"]');
        if(statusSelect) {
            statusSelect.addEventListener('change', function() {
                const badge = document.querySelector('.badge');
                const classes = ['bg-booked', 'bg-transit', 'bg-delivered'];
                badge.classList.remove(...classes);
                
                if(this.value === 'Booked') badge.classList.add('bg-booked');
                if(this.value === 'In Transit') badge.classList.add('bg-transit');
                if(this.value === 'Delivered') badge.classList.add('bg-delivered');
                
                badge.innerHTML = '<i class="fas fa-circle me-1" style="font-size: 8px;"></i>' + this.value;
            });
        }
    </script>
</body>
</html>