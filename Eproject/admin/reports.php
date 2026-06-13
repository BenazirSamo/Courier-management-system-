<?php
session_start();
include("../config/db.php");

// Check admin login
if(!isset($_SESSION['username']) || $_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];

// Date range
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Top 5 Agents
$top_agents = mysqli_query($conn, "
    SELECT u.username, COUNT(c.id) as total_couriers, SUM(c.price) as total_revenue
    FROM couriers c
    JOIN agents a ON c.agent_id = a.id
    JOIN users u ON a.user_id = u.id
    WHERE DATE(c.booking_date) BETWEEN '$start_date' AND '$end_date'
    GROUP BY a.id
    ORDER BY total_revenue DESC
    LIMIT 5
");

// Top 5 Customers
$top_customers = mysqli_query($conn, "
    SELECT c.name, COUNT(cou.id) as total_shipments, SUM(cou.price) as total_spent
    FROM customers c
    JOIN couriers cou ON c.id = cou.sender_id
    WHERE DATE(cou.booking_date) BETWEEN '$start_date' AND '$end_date'
    GROUP BY c.id
    ORDER BY total_spent DESC
    LIMIT 5
");

// Statistics
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        COUNT(*) as total_couriers,
        SUM(price) as total_revenue,
        SUM(CASE WHEN status = 'Booked' THEN 1 ELSE 0 END) as booked,
        SUM(CASE WHEN status = 'In Transit' THEN 1 ELSE 0 END) as in_transit,
        SUM(CASE WHEN status = 'Delivered' THEN 1 ELSE 0 END) as delivered
    FROM couriers
    WHERE DATE(booking_date) BETWEEN '$start_date' AND '$end_date'
"));

// Detailed report
$detailed = mysqli_query($conn, "
    SELECT 
        c.tracking_no,
        c.booking_date,
        s.name as sender_name,
        r.name as receiver_name,
        u.username as agent_name,
        c.from_city,
        c.to_city,
        c.status,
        c.price
    FROM couriers c
    JOIN customers s ON c.sender_id = s.id
    JOIN customers r ON c.receiver_id = r.id
    JOIN agents a ON c.agent_id = a.id
    JOIN users u ON a.user_id = u.id
    WHERE DATE(c.booking_date) BETWEEN '$start_date' AND '$end_date'
    ORDER BY c.booking_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
    
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
        
        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .stat-box {
            text-align: center;
            padding: 25px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
        }
        
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
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .bg-booked { background: #fef3c7; color: #92400e; }
        .bg-transit { background: #dbeafe; color: #1e40af; }
        .bg-delivered { background: #d1fae5; color: #065f46; }
        
        .top-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .rank {
            width: 30px;
            height: 30px;
            background: var(--accent-orange);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
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
            
            .stats-card {
                padding: 20px 15px;
            }
            
            .table-card {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .stat-value {
                font-size: 24px;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .table-responsive {
                font-size: 14px;
            }
            
            .table th,
            .table td {
                padding: 10px 8px;
            }
            
            .table th:nth-child(4),
            .table td:nth-child(4),
            .table th:nth-child(5),
            .table td:nth-child(5) {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }
            
            .stats-card {
                padding: 15px 10px;
            }
            
            .table-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .stat-value {
                font-size: 20px;
            }
            
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(6),
            .table td:nth-child(6) {
                display: none;
            }
            
            .row .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .top-list-item {
                padding: 10px 8px;
                font-size: 14px;
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
                <li class="nav-item"><a class="nav-link active" href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="sms-booking.php"><i class="fas fa-sms"></i> Booking SMS</a></li>
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
                    <h3><i class="fas fa-chart-bar text-orange me-2"></i>Reports</h3>
                    <p class="text-muted">View detailed reports and statistics</p>
                </div>
                <div>
                    <a href="export.php?start=<?= $start_date ?>&end=<?= $end_date ?>" class="btn btn-orange">
                        <i class="fas fa-file-export me-1"></i> Export
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Date Filter -->
        <div class="stats-card">
            <h5><i class="fas fa-calendar me-2"></i>Date Range</h5>
            <form method="GET" class="row mt-3">
                <div class="col-md-4">
                    <label>From</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                </div>
                <div class="col-md-4">
                    <label>To</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-orange w-100">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                </div>
            </form>
            <div class="mt-2">
                <span class="badge bg-info">
                    <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?>
                </span>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-card">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-box" style="background:#e3f2fd">
                        <div class="stat-icon" style="background:#2196f3"><i class="fas fa-box"></i></div>
                        <div class="stat-value"><?= $stats['total_couriers'] ?? 0 ?></div>
                        <div class="stat-label">Total Couriers</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box" style="background:#e8f5e9">
                        <div class="stat-icon" style="background:#4caf50"><i class="fas fa-money-bill"></i></div>
                        <div class="stat-value">PKR <?= number_format($stats['total_revenue'] ?? 0, 0) ?></div>
                        <div class="stat-label">Revenue</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box" style="background:#fff3e0">
                        <div class="stat-icon" style="background:#ff9800"><i class="fas fa-clock"></i></div>
                        <div class="stat-value"><?= $stats['booked'] ?? 0 ?></div>
                        <div class="stat-label">Booked</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box" style="background:#e8eaf6">
                        <div class="stat-icon" style="background:#3f51b5"><i class="fas fa-truck"></i></div>
                        <div class="stat-value"><?= $stats['in_transit'] ?? 0 ?></div>
                        <div class="stat-label">In Transit</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Performers -->
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card">
                    <h5><i class="fas fa-trophy me-2"></i>Top Agents</h5>
                    <?php if(mysqli_num_rows($top_agents) > 0): ?>
                        <?php $rank=1; while($a = mysqli_fetch_assoc($top_agents)): ?>
                        <div class="top-list-item">
                            <div class="d-flex">
                                <div class="rank"><?= $rank++ ?></div>
                                <div>
                                    <strong><?= $a['username'] ?></strong><br>
                                    <small><?= $a['total_couriers'] ?> shipments</small>
                                </div>
                            </div>
                            <strong>PKR <?= number_format($a['total_revenue'], 0) ?></strong>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No data</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <h5><i class="fas fa-star me-2"></i>Top Customers</h5>
                    <?php if(mysqli_num_rows($top_customers) > 0): ?>
                        <?php $rank=1; while($c = mysqli_fetch_assoc($top_customers)): ?>
                        <div class="top-list-item">
                            <div class="d-flex">
                                <div class="rank"><?= $rank++ ?></div>
                                <div>
                                    <strong><?= $c['name'] ?></strong><br>
                                    <small><?= $c['total_shipments'] ?> shipments</small>
                                </div>
                            </div>
                            <strong>PKR <?= number_format($c['total_spent'], 0) ?></strong>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No data</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Detailed Report -->
        <div class="table-card">
            <h5><i class="fas fa-list me-2"></i>Detailed Report</h5>
            <p class="text-muted">Total <?= mysqli_num_rows($detailed) ?> records</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Tracking</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Agent</th>
                            <th>Route</th>
                            <th>Status</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($detailed) > 0): ?>
                            <?php while($r = mysqli_fetch_assoc($detailed)): 
                                $class = $r['status']=='Booked'?'bg-booked':($r['status']=='In Transit'?'bg-transit':'bg-delivered');
                            ?>
                            <tr>
                                <td><?= date('d M', strtotime($r['booking_date'])) ?></td>
                                <td><strong><?= $r['tracking_no'] ?></strong></td>
                                <td><?= $r['sender_name'] ?></td>
                                <td><?= $r['receiver_name'] ?></td>
                                <td><?= $r['agent_name'] ?></td>
                                <td><?= $r['from_city'] ?>→<?= $r['to_city'] ?></td>
                                <td><span class="badge <?= $class ?>"><?= $r['status'] ?></span></td>
                                <td>PKR <?= number_format($r['price'], 0) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center py-4">No data found</td></tr>
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
        
        // Date validation
        const start = document.querySelector('input[name="start_date"]');
        const end = document.querySelector('input[name="end_date"]');
        
        if(start && end) {
            start.addEventListener('change', () => {
                if(start.value > end.value) end.value = start.value;
            });
            end.addEventListener('change', () => {
                if(end.value < start.value) start.value = end.value;
            });
        }
    </script>
</body>
</html>