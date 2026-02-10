<?php
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard - Real Estate Management</title>
    
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="css/custom-admin.css">
    
    <style>
        :root {
            --primary-color: #036621;
            --primary-dark: #024518;
            --secondary-color: #28a745;
            --light-bg: #f8f9fa;
            --sidebar-width: 260px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 0;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar-header h3:hover {
            transform: scale(1.05);
        }
        
        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
            margin: 5px 0 0 0;
        }
        
        .sidebar-menu {
            padding: 15px 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            margin: 5px 10px;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar-menu li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .sidebar-menu li a:hover::after {
            width: 80%;
        }
        
        .sidebar-menu li a i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .menu-section-title {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 15px 20px 8px 20px;
            letter-spacing: 1px;
        }
        
        /* Main Content Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Top Navigation Bar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .top-navbar h4 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-info .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .admin-info .admin-avatar:hover {
            transform: scale(1.1);
        }
        
        .admin-info .admin-name {
            color: #333;
            font-weight: 500;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(220,53,69,0.3);
        }
        
        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .logout-btn:hover::before {
            left: 100%;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220,53,69,0.4);
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 30px;
        }
        
        /* Property Actions Alignment */
        .property-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
        }
        
        .property-actions form {
            margin: 0;
        }
        
        .property-actions .btn {
            white-space: nowrap;
            margin: 0.1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -var(--sidebar-width);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .top-navbar {
                padding: 15px 20px;
            }
            
            .top-navbar h4 {
                font-size: 1.2rem;
            }
            
            .property-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="animate__animated animate__pulse animate__infinite">
                <i class="fas fa-building"></i> Realty Hub
            </h3>
            <p>Management System</p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="index.php" id="menu-dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <div class="menu-section-title">Property Management</div>
            
            <li>
                <a href="viewproperty.php" id="menu-properties">
                    <i class="fas fa-home"></i>
                    <span>View Properties</span>
                </a>
            </li>
            
            <li>
                <a href="category.php" id="menu-category">
                    <i class="fas fa-tags"></i>
                    <span>Manage Categories</span>
                </a>
            </li>
            
            <li>
                <a href="district.php" id="menu-district">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Manage Districts</span>
                </a>
            </li>
            
            <div class="menu-section-title">User Management</div>
            
            <li>
                <a href="viewseller.php" id="menu-sellers">
                    <i class="fas fa-user-tie"></i>
                    <span>Manage Sellers</span>
                </a>
            </li>
            
            <li>
                <a href="viewbuyer.php" id="menu-buyers">
                    <i class="fas fa-users"></i>
                    <span>Manage Buyers</span>
                </a>
            </li>
            
            <div class="menu-section-title">Feedback Management</div>
            
            <li>
                <a href="viewbooking.php" id="menu-bookings">
                    <i class="fas fa-calendar-check"></i>
                    <span>View Bookings</span>
                </a>
            </li>
            
            <li>
                <a href="viewfeedback.php" id="menu-feedback">
                    <i class="fas fa-comments"></i>
                    <span>View Feedback</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navigation -->
        <div class="top-navbar">
            <h4 id="page-title">Dashboard</h4>
            
            <div class="admin-info">
                <div class="admin-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span class="admin-name">Administrator</span>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-wrapper">