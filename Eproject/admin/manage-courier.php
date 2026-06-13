<?php
include("../includes/agent-auth.php");
include("../config/db.php");

// Only admin allowed
if ($_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

// Fetch all couriers
$result = mysqli_query($conn,"
SELECT c.*, s.name AS sender_name, r.name AS receiver_name, u.username AS agent_name
FROM couriers c
JOIN customers s ON c.sender_id = s.id
JOIN customers r ON c.receiver_id = r.id
JOIN agents a ON c.agent_id = a.id
JOIN users u ON a.user_id = u.id
ORDER BY c.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Couriers - Courier Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS matching add-agent.php theme -->
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
            text-align: center;
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
        
        .table-card {
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
        
        /* Table Styles */
        .table-custom thead {
            background: var(--primary-blue);
            color: white;
        }
        
        .table-custom thead th {
            padding: 15px;
            border: none;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(255, 122, 0, 0.05);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        /* Status Badges */
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 4px;
        }
        
        .bg-booked {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .bg-transit {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .bg-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .tracking-no {
            font-family: monospace;
            font-weight: 600;
            color: var(--primary-blue);
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
                padding-top: 70px; /* Space for mobile menu button */
            }
            
            .page-header {
                padding: 20px 15px;
                margin-top: 20px;
            }
            
            .table-card {
                padding: 20px 15px;
            }
            
            /* Search box responsive */
            .table-card .row {
                flex-direction: column;
            }
            
            .table-card .col-md-8,
            .table-card .col-md-4 {
                width: 100%;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .table-card {
                padding: 15px;
            }
            
            .page-header {
                padding: 15px;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 5px;
            }
            
            /* Table responsive */
            .table-responsive {
                font-size: 14px;
            }
            
            .table-custom th,
            .table-custom td {
                padding: 10px 8px;
            }
            
            /* Hide some columns on tablet */
            .table-custom th:nth-child(1), /* ID */
            .table-custom td:nth-child(1),
            .table-custom th:nth-child(5), /* Route */
            .table-custom td:nth-child(5) {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
                padding-top: 70px;
            }
            
            .table-card {
                padding: 15px 10px;
            }
            
            h1, .h1, h2, .h2, h3, .h3, h4, .h4 {
                font-size: 1.2rem;
            }
            
            .page-header {
                margin-bottom: 20px;
            }
            
            .tracking-no {
                font-size: 12px;
                word-break: break-all;
            }
            
            /* Hide more columns on mobile */
            .table-custom th:nth-child(4), /* Receiver */
            .table-custom td:nth-child(4),
            .table-custom th:nth-child(6), /* Agent */
            .table-custom td:nth-child(6) {
                display: none;
            }
        }
        
        /* Extra small screens - adjust table further */
        @media (max-width: 400px) {
            .table-custom th:nth-child(8), /* Price */
            .table-custom td:nth-child(8) {
                display: none;
            }
            
            .action-buttons {
                min-width: 100px;
            }
            
            .badge {
                padding: 4px 8px;
                font-size: 12px;
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
            
            .table-card {
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
                    <a class="nav-link active" href="manage-courier.php">
                        <i class="fas fa-boxes"></i> Manage Couriers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add-agent.php">
                        <i class="fas fa-user-plus"></i> Add Agent
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage-agent.php">
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
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
                </div>
                <div>
                    <h6 class="mb-0 text-white"><?php echo $_SESSION['username'] ?? 'Admin'; ?></h6>
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
                    <h1 class="h3 mb-2"><i class="fas fa-boxes text-orange me-2"></i>Manage Couriers</h1>
                    <p class="text-muted mb-0">View and manage all courier shipments</p>
                </div>
                <div>
                    <a href="add-courier.php" class="btn btn-orange">
                        <i class="fas fa-plus-circle me-1"></i> Add New Courier
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Search Box -->
        <div class="table-card mb-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by tracking number, sender, receiver...">
                        <button class="btn btn-orange" type="button" id="searchButton">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        <select class="form-control" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Booked">Booked</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Couriers Table -->
        <div class="table-card">
            <div class="mb-4">
                <h4 class="text-dark mb-3"><i class="fas fa-list me-2"></i>Couriers List</h4>
                <p class="text-muted">Total <?php echo mysqli_num_rows($result); ?> couriers found</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-custom" id="couriersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tracking No</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Route</th>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong>#<?= $row['id'] ?></strong></td>
                                    <td>
                                        <span class="tracking-no"><?= $row['tracking_no'] ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($row['sender_name']) ?></td>
                                    <td><?= htmlspecialchars($row['receiver_name']) ?></td>
                                    <td>
                                        <small><?= htmlspecialchars($row['from_city']) ?> → <?= htmlspecialchars($row['to_city']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($row['agent_name']) ?></td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        if($row['status'] == 'Booked') $status_class = 'bg-booked';
                                        if($row['status'] == 'In Transit') $status_class = 'bg-transit';
                                        if($row['status'] == 'Delivered') $status_class = 'bg-delivered';
                                        ?>
                                        <span class="badge <?= $status_class ?> status-badge">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td><strong>PKR <?= number_format($row['price'], 2) ?></strong></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a class="btn btn-sm btn-info" href="view-courier.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a class="btn btn-sm btn-warning" href="edit-courier.php?id=<?= $row['id'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-sm btn-danger delete-btn" 
                                               href="delete-courier.php?id=<?= $row['id'] ?>"
                                               data-id="<?= $row['id'] ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No couriers found. Add your first courier above.</p>
                                    <a href="add-courier.php" class="btn btn-orange mt-3">
                                        <i class="fas fa-plus-circle me-1"></i> Add First Courier
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted">© <?php echo date('Y'); ?> Courier Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">Admin Panel v1.0</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Responsive Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            // Function to show/hide sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            // Mobile menu button click
            mobileMenuBtn.addEventListener('click', toggleSidebar);
            
            // Overlay click to close sidebar
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 991.98) {
                    if (!sidebar.contains(event.target) && 
                        !mobileMenuBtn.contains(event.target) && 
                        sidebar.classList.contains('show')) {
                        toggleSidebar();
                    }
                }
            });
            
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
            
            // Search and Filter functionality
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const statusFilter = document.getElementById('statusFilter');
            const couriersTable = document.getElementById('couriersTable');
            
            function filterCouriers() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusTerm = statusFilter.value;
                const rows = couriersTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const statusBadge = row.querySelector('.status-badge');
                    const status = statusBadge ? statusBadge.textContent.trim() : '';
                    
                    const matchesSearch = text.includes(searchTerm);
                    const matchesStatus = !statusTerm || status === statusTerm;
                    
                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Search on button click
            if (searchButton) {
                searchButton.addEventListener('click', filterCouriers);
            }
            
            // Search on enter key
            if (searchInput) {
                searchInput.addEventListener('keyup', function(event) {
                    if (event.key === 'Enter') {
                        filterCouriers();
                    }
                });
            }
            
            // Filter on status change
            if (statusFilter) {
                statusFilter.addEventListener('change', filterCouriers);
            }
            
            // Search as you type (with delay)
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(filterCouriers, 300);
                });
            }
            
            // Enhanced delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const courierId = this.getAttribute('data-id');
                    const trackingNo = this.closest('tr').querySelector('.tracking-no').textContent;
                    
                    if (confirm(`Are you sure you want to delete courier #${courierId} (${trackingNo})? This action cannot be undone.`)) {
                        window.location.href = this.href;
                    }
                });
            });
            
            // Adjust table columns visibility on resize
            function adjustTableColumns() {
                const width = window.innerWidth;
                const tableHeaders = document.querySelectorAll('.table-custom thead th');
                const tableRows = document.querySelectorAll('.table-custom tbody tr');
                
                // Reset all cells first
                tableHeaders.forEach(th => th.style.display = '');
                tableRows.forEach(row => {
                    Array.from(row.children).forEach(td => td.style.display = '');
                });
                
                // Hide columns based on screen size
                if (width <= 768) {
                    // Hide ID and Route columns on tablet
                    if (tableHeaders[0]) tableHeaders[0].style.display = 'none';
                    if (tableHeaders[4]) tableHeaders[4].style.display = 'none';
                    tableRows.forEach(row => {
                        if (row.children[0]) row.children[0].style.display = 'none';
                        if (row.children[4]) row.children[4].style.display = 'none';
                    });
                }
                
                if (width <= 576) {
                    // Hide Receiver and Agent columns on mobile
                    if (tableHeaders[3]) tableHeaders[3].style.display = 'none';
                    if (tableHeaders[5]) tableHeaders[5].style.display = 'none';
                    tableRows.forEach(row => {
                        if (row.children[3]) row.children[3].style.display = 'none';
                        if (row.children[5]) row.children[5].style.display = 'none';
                    });
                }
                
                if (width <= 400) {
                    // Hide Price column on very small screens
                    if (tableHeaders[7]) tableHeaders[7].style.display = 'none';
                    tableRows.forEach(row => {
                        if (row.children[7]) row.children[7].style.display = 'none';
                    });
                }
            }
            
            // Initial adjustment
            adjustTableColumns();
            
            // Adjust on resize
            window.addEventListener('resize', adjustTableColumns);
            
            // Quick status filter buttons (optional enhancement)
            const quickFilterContainer = document.createElement('div');
            quickFilterContainer.className = 'd-flex gap-2 mb-3 flex-wrap';
            quickFilterContainer.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary active" data-status="">All</button>
                <button class="btn btn-sm btn-outline-warning" data-status="Booked">Booked</button>
                <button class="btn btn-sm btn-outline-primary" data-status="In Transit">In Transit</button>
                <button class="btn btn-sm btn-outline-success" data-status="Delivered">Delivered</button>
            `;
            
            // Add quick filters before the table
            const tableCard = document.querySelector('.table-card');
            if (tableCard) {
                const tableCardBody = tableCard.querySelector('.mb-4');
                if (tableCardBody) {
                    tableCardBody.appendChild(quickFilterContainer);
                    
                    // Add event listeners to quick filter buttons
                    const quickFilterButtons = quickFilterContainer.querySelectorAll('button');
                    quickFilterButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            // Update active state
                            quickFilterButtons.forEach(btn => {
                                btn.classList.remove('active', 'btn-primary');
                                btn.classList.add('btn-outline-secondary', 'btn-outline-warning', 'btn-outline-primary', 'btn-outline-success');
                            });
                            this.classList.remove('btn-outline-secondary', 'btn-outline-warning', 'btn-outline-primary', 'btn-outline-success');
                            this.classList.add('active', 'btn-primary');
                            
                            // Set status filter
                            const status = this.getAttribute('data-status');
                            if (statusFilter) {
                                statusFilter.value = status;
                                filterCouriers();
                            }
                        });
                    });
                }
            }
        });
    </script>
</body>
</html>