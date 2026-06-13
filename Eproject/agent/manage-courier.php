<?php
session_start();
include("../config/db.php");

// Check if user is logged in and is agent
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get user ID
$user_query = "SELECT id FROM users WHERE username = '$username' AND role = '3'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['id'];

// Get agent ID
$agent_query = "SELECT id, branch_name FROM agents WHERE user_id = '$user_id'";
$agent_result = mysqli_query($conn, $agent_query);

if(!$agent_result || mysqli_num_rows($agent_result) == 0) {
    header("Location: ../public/login.php");
    exit();
}

$agent_data = mysqli_fetch_assoc($agent_result);
$agent_id = $agent_data['id'];
$branch_name = $agent_data['branch_name'] ?? 'Main Branch';

// Get agent name from users table
$name_query = "SELECT username FROM users WHERE id = '$user_id'";
$name_result = mysqli_query($conn, $name_query);
$name_data = mysqli_fetch_assoc($name_result);
$agent_name = $name_data['name'] ?? $username;

// Initialize search and filter variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Handle status update
if(isset($_POST['update_status'])) {
    $courier_id = mysqli_real_escape_string($conn, $_POST['courier_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    
    // Update courier status
    $update_query = "UPDATE couriers SET status = '$new_status' WHERE id = '$courier_id' AND agent_id = '$agent_id'";
    if(mysqli_query($conn, $update_query)) {
        // Insert status history
        $history_query = "INSERT INTO courier_status (courier_id, status, location) 
                         VALUES ('$courier_id', '$new_status', '$location')";
        mysqli_query($conn, $history_query);
        
        // Update delivery date if status is Delivered
        if($new_status == 'Delivered') {
            $delivery_query = "UPDATE couriers SET delivery_date = CURDATE() WHERE id = '$courier_id'";
            mysqli_query($conn, $delivery_query);
        }
        
        $_SESSION['success_msg'] = "Status updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Error updating status: " . mysqli_error($conn);
    }
    
    header("Location: manage-courier.php");
    exit();
}

// Handle delete courier
if(isset($_GET['delete'])) {
    $courier_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Check if courier belongs to this agent
    $check_query = "SELECT id FROM couriers WHERE id = '$courier_id' AND agent_id = '$agent_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        // Delete courier (status history will be auto-deleted due to ON DELETE CASCADE)
        $delete_query = "DELETE FROM couriers WHERE id = '$courier_id'";
        if(mysqli_query($conn, $delete_query)) {
            $_SESSION['success_msg'] = "Courier deleted successfully!";
        } else {
            $_SESSION['error_msg'] = "Error deleting courier: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_msg'] = "You are not authorized to delete this courier!";
    }
    
    header("Location: manage-courier.php");
    exit();
}

// Build query with filters
$query = "SELECT c.*, s.name as sender_name, s.phone as sender_phone, 
                 r.name as receiver_name, r.phone as receiver_phone
          FROM couriers c
          LEFT JOIN customers s ON c.sender_id = s.id
          LEFT JOIN customers r ON c.receiver_id = r.id
          WHERE c.agent_id = '$agent_id'";
          
// Add search filter
if(!empty($search)) {
    $query .= " AND (c.tracking_no LIKE '%$search%' 
                OR s.name LIKE '%$search%' 
                OR s.phone LIKE '%$search%'
                OR r.name LIKE '%$search%' 
                OR r.phone LIKE '%$search%')";
}

// Add status filter
if(!empty($status)) {
    $query .= " AND c.status = '$status'";
}

// Add date filter
if(!empty($from_date)) {
    $query .= " AND DATE(c.booking_date) >= '$from_date'";
}
if(!empty($to_date)) {
    $query .= " AND DATE(c.booking_date) <= '$to_date'";
}

// Order by
$query .= " ORDER BY c.id DESC";

// Get total count for pagination
$count_query = $query;
$count_result = mysqli_query($conn, $count_query);
$total_couriers = mysqli_num_rows($count_result);

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;
$total_pages = ceil($total_couriers / $per_page);

// Add pagination to query
$query .= " LIMIT $per_page OFFSET $offset";

// Execute query
$result = mysqli_query($conn, $query);

// Get status counts for statistics
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Booked' THEN 1 ELSE 0 END) as booked,
    SUM(CASE WHEN status = 'In Transit' THEN 1 ELSE 0 END) as in_transit,
    SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered,
    SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM couriers WHERE agent_id = '$agent_id'";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get cities for location dropdown
$cities = ['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta', 'Hyderabad', 'Sialkot', 'Gujranwala'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Couriers - Agent Panel</title>
    
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
        
        .text-orange { color: var(--accent-orange) !important; }
        
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
        
        .stats-card, .filter-card, .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .filter-card { padding: 20px; }
        
        .stat-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.3s;
        }
        
        .stat-box:hover { transform: translateY(-3px); }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 20px;
            color: white;
        }
        
        .stat-value { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        .stat-label { font-size: 13px; color: #6c757d; text-transform: uppercase; }
        
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
        
        .badge { padding: 6px 12px; font-weight: 500; border-radius: 4px; }
        .bg-booked { background-color: #fef3c7; color: #92400e; }
        .bg-transit { background-color: #dbeafe; color: #1e40af; }
        .bg-delivered { background-color: #d1fae5; color: #065f46; }
        .bg-cancelled { background-color: #fecaca; color: #dc2626; }
        
        .table th {
            background-color: var(--primary-blue);
            color: white;
            padding: 15px 12px;
        }
        
        .table td { padding: 15px 12px; vertical-align: middle; }
        
        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
        }
        
        .tracking-no {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: var(--primary-blue);
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
                    <a class="nav-link" href="add-courier.php">
                        <i class="fas fa-plus-circle"></i> <span>Add Courier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="manage-courier.php">
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
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-boxes text-orange me-2"></i>Manage Couriers</h1>
                    <p class="text-muted mb-0">View and manage all your courier shipments</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Total Couriers:</strong> <?php echo $total_couriers; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="stats-card">
            <h5 class="mb-4"><i class="fas fa-chart-bar me-2"></i> Quick Overview</h5>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                        <div class="stat-icon" style="background: #2196f3;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                        <div class="stat-label">Total Couriers</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #fff3e0, #ffe0b2);">
                        <div class="stat-icon" style="background: #ff9800;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value"><?php echo $stats['booked'] ?? 0; ?></div>
                        <div class="stat-label">Booked</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e8eaf6, #c5cae9);">
                        <div class="stat-icon" style="background: #3f51b5;">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="stat-value"><?php echo $stats['in_transit'] ?? 0; ?></div>
                        <div class="stat-label">In Transit</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                        <div class="stat-icon" style="background: #4caf50;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value"><?php echo $stats['delivered'] ?? 0; ?></div>
                        <div class="stat-label">Delivered</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" 
                                   value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search by tracking, name, phone...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="Booked" <?php echo $status == 'Booked' ? 'selected' : ''; ?>>Booked</option>
                            <option value="In Transit" <?php echo $status == 'In Transit' ? 'selected' : ''; ?>>In Transit</option>
                            <option value="Delivered" <?php echo $status == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="Cancelled" <?php echo $status == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="from_date" 
                               value="<?php echo $from_date; ?>" 
                               placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="to_date" 
                               value="<?php echo $to_date; ?>" 
                               placeholder="To Date">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-orange flex-grow-1">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                            <a href="manage-courier.php" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Messages -->
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success_msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $_SESSION['error_msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
        
        <!-- Couriers Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i> All Couriers</h5>
                <div>
                    <a href="add-courier.php" class="btn btn-orange btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add New
                    </a>
                </div>
            </div>
            
            <?php if($total_couriers > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tracking No</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Route</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $counter = ($page - 1) * $per_page + 1;
                            while($row = mysqli_fetch_assoc($result)): 
                                $status_class = 'bg-booked';
                                if($row['status'] == 'In Transit') $status_class = 'bg-transit';
                                if($row['status'] == 'Delivered') $status_class = 'bg-delivered';
                                if($row['status'] == 'Cancelled') $status_class = 'bg-cancelled';
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td>
                                        <span class="tracking-no"><?php echo $row['tracking_no']; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['sender_name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($row['sender_phone']); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['receiver_name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($row['receiver_phone']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['from_city']); ?> → 
                                        <?php echo htmlspecialchars($row['to_city']); ?>
                                    </td>
                                    <td>
                                        <strong class="text-success">PKR <?php echo number_format($row['price'], 2); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($row['booking_date'])); ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="view-courier.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-info btn-action" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-warning btn-action" 
                                                    title="Update Status"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal"
                                                    onclick="setCourierId(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>', '<?php echo $row['to_city']; ?>')">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <a href="manage-courier.php?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger btn-action" 
                                               title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this courier?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>&status=<?php echo $status; ?>&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No couriers found</h4>
                    <p class="text-muted">
                        <?php if($search || $status || $from_date || $to_date): ?>
                            No couriers match your search criteria.
                        <?php else: ?>
                            You haven't added any couriers yet.
                        <?php endif; ?>
                    </p>
                    <a href="add-courier.php" class="btn btn-orange mt-3">
                        <i class="fas fa-plus-circle me-2"></i> Add Your First Courier
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sync-alt me-2"></i>Update Courier Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="courier_id" id="courier_id">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">New Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Booked">Booked</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Current Location</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Select Location</option>
                                <?php foreach($cities as $city): ?>
                                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                                <?php endforeach; ?>
                                <option value="In Warehouse">In Warehouse</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_status" class="btn btn-orange">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setCourierId(id, currentStatus, currentLocation) {
            document.getElementById('courier_id').value = id;
            document.getElementById('status').value = currentStatus;
            
            const locationSelect = document.getElementById('location');
            for(let i = 0; i < locationSelect.options.length; i++) {
                if(locationSelect.options[i].value === currentLocation) {
                    locationSelect.value = currentLocation;
                    break;
                }
            }
        }
    </script>
</body>
</html>