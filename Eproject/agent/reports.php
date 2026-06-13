<?php
session_start();
include("../config/db.php");

// Check login
if(!isset($_SESSION['username']) || $_SESSION['role'] != '3') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Get agent details
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

if(!$agent['agent_id']) {
    mysqli_query($conn, "INSERT INTO agents (user_id, branch_name, city) 
                         VALUES ('{$agent['user_id']}', 'Main Branch', 'Karachi')");
    $agent['agent_id'] = mysqli_insert_id($conn);
    $agent['branch_name'] = 'Main Branch';
    $agent['city'] = 'Karachi';
}

$agent_name = $agent['username'];
$agent_id = $agent['agent_id'];
$branch_name = $agent['branch_name'];

// Get date range
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$report_type = $_GET['report_type'] ?? 'daily_summary';

// ============== CHECK IF EXPORT ==============
if(isset($_GET['export'])) {
    $export_type = $_GET['export'];
    
    if($export_type == 'detailed') {
        $query = "SELECT 
            c.tracking_no, c.booking_date,
            s.name as sender_name, s.phone as sender_phone, s.address as sender_address,
            r.name as receiver_name, r.phone as receiver_phone, r.address as receiver_address,
            c.from_city, c.to_city, c.status, c.price
            FROM couriers c
            LEFT JOIN customers s ON c.sender_id = s.id
            LEFT JOIN customers r ON c.receiver_id = r.id
            WHERE c.agent_id='$agent_id' 
            AND DATE(c.booking_date) BETWEEN '$start_date' AND '$end_date'
            ORDER BY c.booking_date DESC";
            
        $filename = "detailed_report_".date('Y_m_d').".csv";
        $headers = ['Tracking No','Date','Sender Name','Sender Phone','Sender Address',
                   'Receiver Name','Receiver Phone','Receiver Address',
                   'From City','To City','Status','Price'];
    }
    elseif($export_type == 'summary') {
        $query = "SELECT 
            DATE(booking_date) as date,
            COUNT(*) as total_couriers,
            SUM(CASE WHEN status='Delivered' THEN 1 ELSE 0 END) as delivered,
            SUM(price) as revenue
            FROM couriers 
            WHERE agent_id='$agent_id' 
            AND DATE(booking_date) BETWEEN '$start_date' AND '$end_date'
            GROUP BY DATE(booking_date)";
            
        $filename = "summary_report_".date('Y_m_d').".csv";
        $headers = ['Date','Total Couriers','Delivered','Revenue'];
    }
    elseif($export_type == 'customers') {
        $query = "SELECT 
            c.name, c.phone, c.email, c.address,
            COUNT(cr.id) as total_orders,
            SUM(cr.price) as total_spent
            FROM customers c
            JOIN couriers cr ON (c.id = cr.sender_id OR c.id = cr.receiver_id)
            WHERE cr.agent_id='$agent_id' 
            AND DATE(cr.booking_date) BETWEEN '$start_date' AND '$end_date'
            GROUP BY c.id";
            
        $filename = "customers_report_".date('Y_m_d').".csv";
        $headers = ['Name','Phone','Email','Address','Total Orders','Total Spent'];
    }
    
    // Export to CSV
    $result = mysqli_query($conn, $query);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, $headers);
    
    while($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// ============== SUMMARY STATISTICS ==============
$summary = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT 
        COUNT(*) as total_couriers,
        SUM(CASE WHEN status='Booked' THEN 1 ELSE 0 END) as booked,
        SUM(CASE WHEN status='In Transit' THEN 1 ELSE 0 END) as in_transit,
        SUM(CASE WHEN status='Delivered' THEN 1 ELSE 0 END) as delivered,
        SUM(CASE WHEN status='Cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(price) as total_revenue,
        AVG(price) as avg_price
    FROM couriers 
    WHERE agent_id='$agent_id' 
    AND DATE(booking_date) BETWEEN '$start_date' AND '$end_date'"
));

$summary = $summary ?: [
    'total_couriers'=>0, 'booked'=>0, 'in_transit'=>0, 
    'delivered'=>0, 'cancelled'=>0, 'total_revenue'=>0, 'avg_price'=>0
];

// ============== DAILY STATS ==============
$daily = mysqli_query($conn,
    "SELECT DATE(booking_date) as date, COUNT(*) as count, SUM(price) as revenue
     FROM couriers 
     WHERE agent_id='$agent_id' 
     AND DATE(booking_date) BETWEEN '$start_date' AND '$end_date'
     GROUP BY DATE(booking_date) ORDER BY date"
);

$daily_labels = []; $daily_counts = []; $daily_revenue = [];
while($row = mysqli_fetch_assoc($daily)) {
    $daily_labels[] = date('d M', strtotime($row['date']));
    $daily_counts[] = $row['count'];
    $daily_revenue[] = $row['revenue'] ?? 0;
}

// ============== STATUS DISTRIBUTION ==============
$status_data = mysqli_query($conn,
    "SELECT status, COUNT(*) as count
     FROM couriers 
     WHERE agent_id='$agent_id' 
     AND DATE(booking_date) BETWEEN '$start_date' AND '$end_date'
     GROUP BY status"
);

$status_labels = []; $status_counts = [];
$status_colors = [
    'Booked' => '#fef3c7', 'In Transit' => '#dbeafe',
    'Delivered' => '#d1fae5', 'Cancelled' => '#fecaca'
];

while($row = mysqli_fetch_assoc($status_data)) {
    $status_labels[] = $row['status'];
    $status_counts[] = $row['count'];
}

// ============== DETAILED TRANSACTIONS ==============
$detailed = [];
if($report_type == 'detailed_transactions') {
    $result = mysqli_query($conn,
        "SELECT c.*, s.name as sender_name, s.phone as sender_phone,
                r.name as receiver_name, r.phone as receiver_phone
         FROM couriers c
         LEFT JOIN customers s ON c.sender_id = s.id
         LEFT JOIN customers r ON c.receiver_id = r.id
         WHERE c.agent_id='$agent_id' 
         AND DATE(c.booking_date) BETWEEN '$start_date' AND '$end_date'
         ORDER BY c.booking_date DESC"
    );
    while($row = mysqli_fetch_assoc($result)) {
        $detailed[] = $row;
    }
}

// ============== TOP CUSTOMERS ==============
$top_customers = mysqli_query($conn,
    "SELECT c.name, COUNT(cr.id) as courier_count, SUM(cr.price) as total_spent
     FROM customers c
     JOIN couriers cr ON (c.id = cr.sender_id OR c.id = cr.receiver_id)
     WHERE cr.agent_id='$agent_id' 
     AND DATE(cr.booking_date) BETWEEN '$start_date' AND '$end_date'
     GROUP BY c.id ORDER BY courier_count DESC LIMIT 10"
);

// ============== POPULAR ROUTES ==============
$popular_routes = mysqli_query($conn,
    "SELECT from_city, to_city, COUNT(*) as courier_count, SUM(price) as revenue
     FROM couriers 
     WHERE agent_id='$agent_id' 
     AND DATE(booking_date) BETWEEN '$start_date' AND '$end_date'
     GROUP BY from_city, to_city ORDER BY courier_count DESC LIMIT 10"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Agent Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        .btn-export {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        
        .btn-export:hover {
            background-color: #218838;
            border-color: #1e7e34;
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
        
        .stats-card, .filter-card, .report-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .filter-card { padding: 20px; }
        
        .section-title {
            color: var(--primary-blue);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
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
        
        .report-table {
            width: 100%;
            font-size: 14px;
        }
        
        .report-table th {
            background-color: var(--primary-blue);
            color: white;
            padding: 12px 15px;
            text-align: left;
        }
        
        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .revenue-highlight { color: #28a745; font-weight: bold; }
        .route-arrow { color: var(--accent-orange); margin: 0 5px; }
        .summary-number { font-size: 28px; font-weight: bold; color: var(--primary-blue); }
        
        .export-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        @media (max-width: 991.98px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link i { margin-right: 0; font-size: 20px; }
            .main-content { margin-left: 70px; }
            .chart-container { height: 250px; }
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
                    <a class="nav-link active" href="reports.php">
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
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2"><i class="fas fa-chart-bar text-orange me-2"></i>Reports & Analytics</h1>
                    <p class="text-muted mb-0">Analyze your courier business performance</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Branch:</strong> <?php echo $branch_name; ?></p>
                    <small class="text-muted"><?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>
        </div>
        
        <!-- Export Buttons -->
        <div class="export-buttons">
            <a href="?export=summary&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
               class="btn btn-export">
                <i class="fas fa-file-csv me-2"></i>Export Summary Report
            </a>
            <a href="?export=detailed&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
               class="btn btn-export">
                <i class="fas fa-file-csv me-2"></i>Export Detailed Report
            </a>
            <a href="?export=customers&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
               class="btn btn-export">
                <i class="fas fa-file-csv me-2"></i>Export Customers Report
            </a>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" action="">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?php echo $start_date; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="<?php echo $end_date; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" name="report_type" onchange="this.form.submit()">
                            <option value="daily_summary" <?php echo $report_type == 'daily_summary' ? 'selected' : ''; ?>>Daily Summary</option>
                            <option value="detailed_transactions" <?php echo $report_type == 'detailed_transactions' ? 'selected' : ''; ?>>Detailed Transactions</option>
                            <option value="performance_analysis" <?php echo $report_type == 'performance_analysis' ? 'selected' : ''; ?>>Performance Analysis</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-orange w-100">
                            <i class="fas fa-filter me-2"></i> Generate
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Quick Summary -->
        <div class="stats-card">
            <h5 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Quick Summary</h5>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                        <div class="stat-icon" style="background: #2196f3;">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value"><?php echo $summary['total_couriers']; ?></div>
                        <div class="stat-label">Total Couriers</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                        <div class="stat-icon" style="background: #4caf50;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value"><?php echo $summary['delivered']; ?></div>
                        <div class="stat-label">Delivered</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #fff3e0, #ffe0b2);">
                        <div class="stat-icon" style="background: #ff9800;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-value">PKR <?php echo number_format($summary['total_revenue'] ?? 0, 2); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-box" style="background: linear-gradient(135deg, #f3e5f5, #e1bee7);">
                        <div class="stat-icon" style="background: #9c27b0;">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-value">PKR <?php echo number_format($summary['avg_price'] ?? 0, 2); ?></div>
                        <div class="stat-label">Avg. Price</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-chart-line me-2"></i> Daily Performance</h5>
                    <div class="chart-container">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5 class="mb-4"><i class="fas fa-chart-pie me-2"></i> Status Distribution</h5>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Transactions -->
        <?php if($report_type == 'detailed_transactions' && !empty($detailed)): ?>
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i> Detailed Transactions</h5>
                <a href="?export=detailed&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
                   class="btn btn-export btn-sm">
                    <i class="fas fa-file-csv me-1"></i> Export Current Report
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tracking No</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Route</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_amount = 0;
                        foreach($detailed as $row): 
                            $total_amount += $row['price'];
                            $status_class = 'bg-booked';
                            if($row['status'] == 'In Transit') $status_class = 'bg-transit';
                            if($row['status'] == 'Delivered') $status_class = 'bg-delivered';
                            if($row['status'] == 'Cancelled') $status_class = 'bg-cancelled';
                        ?>
                            <tr>
                                <td><strong><?php echo $row['tracking_no']; ?></strong></td>
                                <td>
                                    <div><?php echo htmlspecialchars($row['sender_name']); ?></div>
                                    <small class="text-muted"><?php echo $row['sender_phone']; ?></small>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($row['receiver_name']); ?></div>
                                    <small class="text-muted"><?php echo $row['receiver_phone']; ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['from_city']); ?>
                                    <span class="route-arrow">→</span>
                                    <?php echo htmlspecialchars($row['to_city']); ?>
                                </td>
                                <td class="revenue-highlight">PKR <?php echo number_format($row['price'], 2); ?></td>
                                <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Performance Analysis -->
        <?php if($report_type == 'performance_analysis'): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="report-card">
                    <h5 class="section-title"><i class="fas fa-users me-2"></i> Top Customers</h5>
                    <?php if(mysqli_num_rows($top_customers) > 0): ?>
                        <table class="report-table">
                            <thead>
                                <tr><th>Customer</th><th>Couriers</th><th>Total Spent</th></tr>
                            </thead>
                            <tbody>
                                <?php while($customer = mysqli_fetch_assoc($top_customers)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                        <td><?php echo $customer['courier_count']; ?></td>
                                        <td class="revenue-highlight">PKR <?php echo number_format($customer['total_spent'] ?? 0, 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <div class="text-end mt-3">
                        <a href="?export=customers&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" 
                           class="btn btn-export btn-sm">
                            <i class="fas fa-file-csv me-1"></i> Export Customers
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="report-card">
                    <h5 class="section-title"><i class="fas fa-route me-2"></i> Popular Routes</h5>
                    <?php if(mysqli_num_rows($popular_routes) > 0): ?>
                        <table class="report-table">
                            <thead>
                                <tr><th>Route</th><th>Couriers</th><th>Revenue</th></tr>
                            </thead>
                            <tbody>
                                <?php while($route = mysqli_fetch_assoc($popular_routes)): ?>
                                    <tr>
                                        <td><?php echo $route['from_city']; ?> → <?php echo $route['to_city']; ?></td>
                                        <td><?php echo $route['courier_count']; ?></td>
                                        <td class="revenue-highlight">PKR <?php echo number_format($route['revenue'] ?? 0, 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Charts Script -->
    <script>
        // Daily Performance Chart
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($daily_labels); ?>,
                datasets: [
                    {
                        label: 'Number of Couriers',
                        data: <?php echo json_encode($daily_counts); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Revenue (PKR)',
                        data: <?php echo json_encode($daily_revenue); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
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

        // Status Distribution Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($status_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($status_counts); ?>,
                    backgroundColor: [
                        <?php foreach($status_labels as $label): echo "'" . $status_colors[$label] . "',"; endforeach; ?>
                    ],
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</body>
</html>