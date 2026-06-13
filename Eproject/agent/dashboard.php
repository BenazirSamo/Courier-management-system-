<?php
session_start();
include("../config/db.php");

// Check login
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get agent details in one query
$agent = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT u.id as user_id, u.username, a.id as agent_id, a.branch_name, a.city 
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
    mysqli_query($conn, "INSERT INTO agents (user_id, branch_name, city) 
                         VALUES ('{$agent['user_id']}', 'Main Branch', 'Karachi')");
    $agent['agent_id'] = mysqli_insert_id($conn);
    $agent['branch_name'] = 'Main Branch';
    $agent['city'] = 'Karachi';
}

// Get all statistics in one query
$stats = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT 
        COUNT(*) as total_couriers,
        SUM(CASE WHEN status='Delivered' THEN 1 ELSE 0 END) as delivered_couriers,
        SUM(CASE WHEN status='In Transit' THEN 1 ELSE 0 END) as in_transit_couriers,
        SUM(CASE WHEN status='Booked' THEN 1 ELSE 0 END) as booked_couriers,
        SUM(CASE WHEN DATE(booking_date)=CURDATE() THEN 1 ELSE 0 END) as today_couriers,
        SUM(price) as total_revenue,
        AVG(CASE WHEN status='Delivered' AND delivery_date IS NOT NULL 
                 THEN DATEDIFF(delivery_date, booking_date) END) as avg_days
    FROM couriers 
    WHERE agent_id='{$agent['agent_id']}'"
));

// Set default values
$total_couriers = $stats['total_couriers'] ?? 0;
$delivered_couriers = $stats['delivered_couriers'] ?? 0;
$in_transit_couriers = $stats['in_transit_couriers'] ?? 0;
$booked_couriers = $stats['booked_couriers'] ?? 0;
$today_couriers = $stats['today_couriers'] ?? 0;
$pending_couriers = $booked_couriers + $in_transit_couriers;
$total_revenue = $stats['total_revenue'] ?? 0;
$avg_days = $stats['avg_days'] ?? 0;

// Get status distribution
$status_data = ['Booked' => 0, 'In Transit' => 0, 'Delivered' => 0, 'Cancelled' => 0];
$status_result = mysqli_query($conn,
    "SELECT status, COUNT(*) as count 
     FROM couriers 
     WHERE agent_id='{$agent['agent_id']}'
     GROUP BY status"
);
while($row = mysqli_fetch_assoc($status_result)) {
    $status_data[$row['status']] = $row['count'];
}

// Get recent couriers
$recent_result = mysqli_query($conn,
    "SELECT c.*, s.name as sender_name, r.name as receiver_name 
     FROM couriers c
     LEFT JOIN customers s ON c.sender_id = s.id
     LEFT JOIN customers r ON c.receiver_id = r.id
     WHERE c.agent_id='{$agent['agent_id']}'
     ORDER BY c.id DESC LIMIT 5"
);

// Monthly data
$month_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$monthly_counts = array_fill(0, 12, 0);
$monthly_revenue = array_fill(0, 12, 0);

$month_result = mysqli_query($conn,
    "SELECT MONTH(booking_date) as month_num,
            COUNT(*) as count,
            SUM(price) as revenue
     FROM couriers 
     WHERE agent_id='{$agent['agent_id']}' 
       AND YEAR(booking_date)=YEAR(CURDATE())
     GROUP BY MONTH(booking_date)"
);
while($row = mysqli_fetch_assoc($month_result)) {
    $month_index = $row['month_num'] - 1;
    $monthly_counts[$month_index] = $row['count'];
    $monthly_revenue[$month_index] = $row['revenue'] ?? 0;
}

// Calculate success rate
$success_rate = $total_couriers > 0 ? round(($delivered_couriers/$total_couriers)*100, 1) : 0;
$rate_color = $success_rate >= 80 ? 'success' : ($success_rate >= 60 ? 'warning' : 'danger');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Courier Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            text-align: center;
            padding: 25px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .stat-box:hover { transform: translateY(-5px); }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
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
        
        .bg-booked { background-color: #fef3c7; color: #92400e; }
        .bg-transit { background-color: #dbeafe; color: #1e40af; }
        .bg-delivered { background-color: #d1fae5; color: #065f46; }
        .bg-cancelled { background-color: #fecaca; color: #dc2626; }
        
        @media (max-width: 991.98px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link i { margin-right: 0; font-size: 20px; }
            .main-content { margin-left: 70px; }
            .stat-value { font-size: 24px; }
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
                    <a class="nav-link active" href="dashboard.php">
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
                    <?php echo strtoupper(substr($agent['username'], 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $agent['username']; ?></h6>
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
                    <h1 class="h3 mb-2"><i class="fas fa-tachometer-alt text-orange me-2"></i>Agent Dashboard</h1>
                    <p class="text-muted mb-0">Welcome back, <?php echo $agent['username']; ?>!</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Branch:</strong> <?php echo $agent['branch_name']; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-card">
            <h5 class="mb-4"><i class="fas fa-chart-pie me-2"></i> Quick Statistics</h5>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                        <div class="stat-icon" style="background: #2196f3;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value"><?php echo $total_couriers; ?></div>
                        <div class="stat-label">Total Couriers</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #fff3e0, #ffe0b2);">
                        <div class="stat-icon" style="background: #ff9800;">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-value"><?php echo $today_couriers; ?></div>
                        <div class="stat-label">Today's Couriers</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e8eaf6, #c5cae9);">
                        <div class="stat-icon" style="background: #3f51b5;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value"><?php echo $pending_couriers; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                        <div class="stat-icon" style="background: #4caf50;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value"><?php echo $delivered_couriers; ?></div>
                        <div class="stat-label">Delivered</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-chart-pie me-2"></i> Status Distribution</h5>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-chart-line me-2"></i> Monthly Performance (<?php echo date('Y'); ?>)</h5>
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Couriers -->
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Recent Couriers</h5>
                <a href="manage-courier.php" class="btn btn-orange btn-sm">
                    <i class="fas fa-eye me-1"></i> View All
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tracking No</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recent_result && mysqli_num_rows($recent_result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($recent_result)): ?>
                                <tr>
                                    <td><strong><?php echo $row['tracking_no'] ?? 'N/A'; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['sender_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['receiver_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['from_city'] ?? 'N/A'); ?> → 
                                        <?php echo htmlspecialchars($row['to_city'] ?? 'N/A'); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $row['status'] ?? 'Booked';
                                        $status_class = 'bg-booked';
                                        if($status == 'In Transit') $status_class = 'bg-transit';
                                        if($status == 'Delivered') $status_class = 'bg-delivered';
                                        if($status == 'Cancelled') $status_class = 'bg-cancelled';
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['booking_date'] ?? date('Y-m-d'))); ?></td>
                                    <td>
                                        <a href="view-courier.php?id=<?php echo $row['id'] ?? ''; ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No couriers found. Add your first courier.</p>
                                    <a href="add-courier.php" class="btn btn-orange">
                                        <i class="fas fa-plus-circle me-1"></i> Add First Courier
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="add-courier.php" class="btn btn-orange btn-lg">
                            <i class="fas fa-plus-circle me-2"></i> Add New Courier
                        </a>
                        <a href="manage-courier.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-boxes me-2"></i> Manage Couriers
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-chart-bar me-2"></i> Performance Summary</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="mb-1"><strong>Total Revenue:</strong></p>
                                <h4 class="text-success">PKR <?php echo number_format($total_revenue, 2); ?></h4>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1"><strong>Average Delivery Time:</strong></p>
                                <h4 class="text-info"><?php echo number_format($avg_days, 1); ?> days</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="mb-1"><strong>Success Rate:</strong></p>
                                <h4 class="text-<?php echo $rate_color; ?>"><?php echo $success_rate; ?>%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Status Distribution Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Booked', 'In Transit', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [
                        <?php echo $status_data['Booked'] ?? 0; ?>,
                        <?php echo $status_data['In Transit'] ?? 0; ?>,
                        <?php echo $status_data['Delivered'] ?? 0; ?>,
                        <?php echo $status_data['Cancelled'] ?? 0; ?>
                    ],
                    backgroundColor: ['#fef3c7', '#dbeafe', '#d1fae5', '#fecaca'],
                    borderColor: ['#92400e', '#1e40af', '#065f46', '#dc2626'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Monthly Performance Chart
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($month_labels); ?>,
                datasets: [
                    {
                        label: 'Number of Couriers',
                        data: <?php echo json_encode($monthly_counts); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Revenue (PKR)',
                        data: <?php echo json_encode($monthly_revenue); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Couriers' } },
                    y1: { position: 'right', beginAtZero: true, title: { display: true, text: 'Revenue (PKR)' } }
                }
            }
        });
    </script>
</body>
</html>