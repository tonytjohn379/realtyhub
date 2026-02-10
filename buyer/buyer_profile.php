<?php
session_start();
require('../config/autoload.php'); 

$dao = new DataAccess();

// Assuming session holds buyer ID
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.php");
    exit;
}

$bid = $_SESSION['buyer_id'];
$buyer = $dao->getData('*', 'buyer', "bid=$bid");

if (empty($buyer)) {
    echo "<h3>Profile not found.</h3>";
    exit;
}

$s = $buyer[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Realty Hub" />
    <link rel="shortcut icon" href="template/favicon.png" />
    <meta name="description" content="Realty Hub - Professional Property Marketplace" />
    <meta name="keywords" content="real estate, property, buyer profile, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Buyer Profile | Realty Hub</title>
    
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
        
        .profile-container {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            margin: 2.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
        }
        
        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .profile-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .profile-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .profile-info h3 {
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary);
        }
        
        .info-row {
            display: flex;
            margin-bottom: 1rem;
            align-items: center;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 120px;
            color: var(--secondary);
        }
        
        .info-value {
            flex: 1;
            padding: 8px 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .btn-action {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
            color: white;
            margin: 0 10px 10px 0;
            display: inline-block;
            text-decoration: none;
        }
        
        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-action:hover::before {
            left: 100%;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-action:active {
            transform: translateY(1px);
        }
        
        .btn-secondary-action {
            background: linear-gradient(135deg, var(--secondary), #1a2530);
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
        
        @media (max-width: 768px) {
            .profile-container {
                padding: 1.8rem;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .info-label {
                margin-bottom: 5px;
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
                                <li><a class="dropdown-item" href="view_property.php">Browse Properties</a></li>
                                <li><a class="dropdown-item" href="wishlist.php">My Wishlist</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="viewbooking.php" style="animation-delay: 0.3s;">My Bookings</a>
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
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['bname'] ?? 'Buyer'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="buyer_profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
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
        <div class="profile-container">
            <div class="profile-header">
                <h1><i class="fas fa-user me-2"></i>My Profile</h1>
                <p class="mb-0">Manage your account information and preferences</p>
            </div>
            
            <div class="profile-info">
                <h3><i class="fas fa-info-circle me-2"></i>Personal Information</h3>
                <div class="info-row">
                    <div class="info-label">Full Name:</div>
                    <div class="info-value"><?= htmlspecialchars($s['bname']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phone Number:</div>
                    <div class="info-value"><?= htmlspecialchars($s['bphone']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email Address:</div>
                    <div class="info-value"><?= htmlspecialchars($s['bmail']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Member Since:</div>
                    <div class="info-value"><?= htmlspecialchars($s['created_at']); ?></div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="edit_profile.php" class="btn-action">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <a href="feedback.php" class="btn-action btn-secondary-action">
                    <i class="fas fa-comment me-2"></i>Submit Feedback
                </a>
                <a href="wishlist.php" class="btn-action">
                    <i class="fas fa-heart me-2"></i>View Wishlist
                </a>
                <a href="logout.php" class="btn-action btn-secondary-action">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
