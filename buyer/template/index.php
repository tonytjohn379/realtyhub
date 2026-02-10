<?php
session_start();

// Check if buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: ../buyer_login.php");
    exit;
}

require('../../config/autoload.php');

$dao = new DataAccess();
$buyer_id = $_SESSION['buyer_id'] ?? 0;

// Fetch buyer's wishlist properties
$wishlist_count = $dao->count('wid', 'wishlist', 'bid=' . $buyer_id);

// Fetch total properties available
$total_properties = $dao->count('pid', 'property', 'status=1');

// Fetch buyer's bookings
$booking_count = $dao->count('booking_id', 'booking', 'bid=' . $buyer_id);

// Fetch latest properties
$properties = $dao->getData('*', 'property', 'status=1 ORDER BY pid DESC LIMIT 6');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Realty Hub" />
    <link rel="shortcut icon" href="favicon.png" />
    <meta name="description" content="Realty Hub - Professional Property Marketplace" />
    <meta name="keywords" content="real estate, property, buyer dashboard, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Buyer Dashboard | Realty Hub</title>
    
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
        
        .navbar-nav .nav-link::after {
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
        
        .navbar-nav .nav-link:hover::after {
            width: 80%;
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
        
        .welcome-section {
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
        
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .welcome-section h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .welcome-section p {
            color: #6c757d;
            font-size: 1.1rem;
            animation: slideInRight 1s ease;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.8rem;
            margin: 2.5rem 0;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-top: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
            animation: zoomIn 0.6s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            background: rgba(25, 118, 210, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .stat-icon {
            transform: rotate(10deg) scale(1.1);
        }
        
        .stat-icon i {
            font-size: 32px;
            color: var(--primary);
        }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .stat-value {
            color: var(--accent);
            transform: scale(1.05);
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2.5rem 0 1.8rem;
            animation: fadeInDown 0.8s ease;
        }
        
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--secondary);
            font-size: 2rem;
            margin: 0;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-primary-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-primary-custom:active {
            transform: translateY(1px);
        }
        
        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .property-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: fadeInUp 0.8s ease;
        }
        
        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .property-image {
            height: 220px;
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            position: relative;
            overflow: hidden;
        }
        
        .property-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(25, 118, 210, 0.1), rgba(103, 58, 183, 0.1));
        }
        
        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .property-card:hover .property-image img {
            transform: scale(1.05);
        }
        
        .property-content {
            padding: 1.8rem;
        }
        
        .property-price {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .property-card:hover .property-price {
            color: var(--accent);
            transform: scale(1.02);
        }
        
        .property-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.8rem;
        }
        
        .property-location {
            display: flex;
            align-items: center;
            color: #6c757d;
            margin-bottom: 1.2rem;
        }
        
        .property-location i {
            margin-right: 10px;
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .property-actions {
            display: flex;
            gap: 0.8rem;
        }
        
        .btn-action {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn-view {
            background-color: rgba(25, 118, 210, 0.1);
            color: var(--primary);
        }
        
        .btn-view:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 118, 210, 0.3);
        }
        
        .btn-book {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }
        
        .btn-book:hover {
            background-color: var(--success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .no-properties {
            background: white;
            border-radius: 16px;
            padding: 4rem;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            animation: fadeIn 1s ease;
        }
        
        .no-properties i {
            font-size: 5rem;
            color: #bbdefb;
            margin-bottom: 2rem;
            animation: bounce 2s infinite;
        }
        
        .no-properties h3 {
            color: var(--secondary);
            margin-bottom: 1.2rem;
            font-size: 2rem;
        }
        
        .no-properties p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .footer {
            background: linear-gradient(135deg, var(--secondary), #1a2530);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }
        
        .footer h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 0.8rem;
        }
        
        .footer a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            margin-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
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
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
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
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .property-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
            
            .welcome-section {
                padding: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand animate__animated animate__pulse animate__infinite" href="index.php">
                    <i class="fas fa-building me-2"></i>Realty Hub
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active animate__animated animate__fadeInDown" href="index.php" style="animation-delay: 0.1s;">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle animate__animated animate__fadeInDown" href="#" data-bs-toggle="dropdown" style="animation-delay: 0.2s;">
                                Properties
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="../view_property.php">Browse Properties</a></li>
                                <li><a class="dropdown-item" href="../wishlist.php">My Wishlist</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="../viewbooking.php" style="animation-delay: 0.3s;">My Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="services.html" style="animation-delay: 0.4s;">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link animate__animated animate__fadeInDown" href="about.html" style="animation-delay: 0.5s;">About</a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link dropdown-toggle animate__animated animate__fadeInDown" href="#" data-bs-toggle="dropdown" style="animation-delay: 0.6s;">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['bname'] ?? 'Buyer'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../buyer_profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['bname'] ?? 'Buyer'); ?>!</h1>
            <p class="mb-0">Manage your property searches and track your bookings from this dashboard.</p>
        </div>
        
        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-card animate__animated animate__fadeInUp">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-value"><?php echo $total_properties; ?></div>
                <div class="stat-label">Properties Available</div>
            </div>
            
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value"><?php echo $wishlist_count; ?></div>
                <div class="stat-label">Wishlist Items</div>
            </div>
            
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value"><?php echo $booking_count; ?></div>
                <div class="stat-label">Bookings</div>
            </div>
        </div>
        
        <!-- Properties Section -->
        <div class="section-header">
            <h2 class="section-title">Latest Properties</h2>
            <a href="../view_property.php" class="btn btn-primary-custom animate__animated animate__fadeIn">
                <i class="fas fa-search me-2"></i>Browse Properties
            </a>
        </div>
        
        <?php if (!empty($properties)) { ?>
            <div class="property-grid">
                <?php foreach ($properties as $property) { ?>
                <div class="property-card animate__animated animate__fadeInUp">
                    <div class="property-image">
                        <?php if (!empty($property['pimage'])) { ?>
                            <img src="../../uploads/<?php echo htmlspecialchars($property['pimage']); ?>" alt="Property Image">
                        <?php } else { ?>
                            <i class="fas fa-home fa-4x"></i>
                        <?php } ?>
                    </div>
                    <div class="property-content">
                        <div class="property-price">₹<?php echo number_format($property['pprice']); ?></div>
                        <h3 class="property-title"><?php echo htmlspecialchars($property['plocation'] ?? 'Property'); ?></h3>
                        <div class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($property['pdescription']); ?>
                        </div>
                        <div class="property-actions">
                            <a href="../view_property.php?pid=<?php echo $property['pid']; ?>" class="btn-action btn-view">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <a href="../book_property.php?pid=<?php echo $property['pid']; ?>" class="btn-action btn-book">
                                <i class="fas fa-calendar-check me-1"></i> Book
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="no-properties">
                <i class="fas fa-home"></i>
                <h3>No Properties Available</h3>
                <p>There are currently no properties listed. Please check back later.</p>
                <a href="../view_property.php" class="btn btn-primary-custom">
                    <i class="fas fa-search me-2"></i>Browse Properties
                </a>
            </div>
        <?php } ?>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Realty Hub</h5>
                    <p>Professional real estate management platform for sellers and buyers.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="../view_property.php">Properties</a></li>
                        <li><a href="../wishlist.php">Wishlist</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:support@realtyhub.com">support@realtyhub.com</a></li>
                        <li><a href="tel:+9122550369">+91 2255 0369</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Realty Hub. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add hover effect to property cards
        document.addEventListener('DOMContentLoaded', function() {
            const propertyCards = document.querySelectorAll('.property-card');
            propertyCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add animation to stat cards on scroll
            const statCards = document.querySelectorAll('.stat-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, { threshold: 0.1 });
            
            statCards.forEach(card => {
                card.style.animationPlayState = 'paused';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>