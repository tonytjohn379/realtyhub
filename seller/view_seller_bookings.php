<?php
session_start();

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit;
}

require('../config/autoload.php');

$dao = new DataAccess();
$seller_id = $_SESSION['seller_id'];

// Fetch bookings for this seller's properties with property and buyer details
$bookings = $dao->query("SELECT b.*, p.plocation, p.pprice, p.pimage, bu.bname, bu.bmail, bu.bphone 
                         FROM booking b 
                         LEFT JOIN property p ON b.pid = p.pid 
                         LEFT JOIN buyer bu ON b.bid = bu.bid 
                         WHERE p.sid = $seller_id
                         ORDER BY b.booking_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Realty Hub" />
    <link rel="shortcut icon" href="template/favicon.png" />
    <meta name="description" content="Realty Hub - Professional Property Marketplace" />
    <meta name="keywords" content="real estate, property, view bookings, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Property Bookings | Realty Hub</title>
    
    <style>
        :root {
            --primary: #1976D2;
            --primary-dark: #1565C0;
            --secondary: #2c3e50;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4CAF50;
            --info: #2196F3;
            --warning: #FFC107;
            --danger: #F44336;
            --accent: #673AB7;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7f1 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            margin: 0 5px;
            transition: all 0.3s;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }
        
        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .user-dropdown .nav-link {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 8px 15px !important;
            transition: all 0.3s ease;
        }
        
        .user-dropdown .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .page-header h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .data-table {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.8s ease;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: rgba(25, 118, 210, 0.05);
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .badge-active {
            background: rgba(76, 175, 80, 0.15);
            color: var(--success);
        }
        
        .property-image {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }
        
        .no-bookings {
            background: white;
            border-radius: 16px;
            padding: 4rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            animation: fadeIn 1s ease;
        }
        
        .no-bookings i {
            font-size: 5rem;
            color: #bbdefb;
            margin-bottom: 2rem;
            animation: bounce 2s infinite;
        }
        
        .no-bookings h3 {
            color: var(--secondary);
            margin-bottom: 1.2rem;
            font-size: 2rem;
        }
        
        .no-bookings p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
        
        @media (max-width: 768px) {
            .page-header, .data-table {
                padding: 1.5rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .table thead th, .table tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand animate__animated animate__pulse animate__infinite" href="template/index.php">
                    <i class="fas fa-building me-2"></i>Realty Hub
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="template/index.php" style="animation-delay: 0.1s;">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle animate__animated animate__fadeInDown" href="#" data-bs-toggle="dropdown" style="animation-delay: 0.2s;">
                                Properties
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="property.php">Add Property</a></li>
                                <li><a class="dropdown-item" href="view_property.php">My Properties</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active animate__animated animate__fadeInDown" href="view_seller_bookings.php" style="animation-delay: 0.3s;">Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="template/services.html" style="animation-delay: 0.4s;">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="template/about.html" style="animation-delay: 0.5s;">About</a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link dropdown-toggle animate__animated animate__fadeInDown" href="#" data-bs-toggle="dropdown" style="animation-delay: 0.6s;">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['sname'] ?? 'Seller'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="seller_profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <div class="page-header">
            <h2><i class="fas fa-calendar-check me-2"></i>Property Bookings</h2>
            <p class="mb-0">View and manage bookings for your properties</p>
        </div>
        
        <?php if (!empty($bookings)): ?>
            <div class="data-table">
                <h5><i class="fas fa-list me-2"></i>Booking History</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Property</th>
                                <th>Buyer Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Booking Date</th>
                                <th>Token Amount</th>
                                <th>Property Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $booking): ?>
                            <tr>
                                <td><strong>#<?php echo $booking['booking_id']; ?></strong></td>
                                <td>
                                    <?php if (!empty($booking['pimage'])): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($booking['pimage']); ?>" alt="Property" class="property-image me-2">
                                    <?php else: ?>
                                        <div class="property-image bg-light d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-home text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span><?php echo htmlspecialchars($booking['plocation'] ?? 'Property'); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($booking['bname'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($booking['bphone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($booking['bmail'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['date'])); ?></td>
                                <td><span class="badge-status badge-active">₹<?php echo number_format($booking['token']); ?></span></td>
                                <td>₹<?php echo number_format($booking['pprice']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="no-bookings">
                <i class="fas fa-calendar-times"></i>
                <h3>No Bookings Found</h3>
                <p>There are no bookings for your properties yet.</p>
                <a href="property.php" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; border-radius: 10px; padding: 12px 28px; font-weight: 600;">
                    <i class="fas fa-plus me-2"></i>Add Property
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>