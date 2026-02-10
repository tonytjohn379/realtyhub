<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();

// Redirect if not logged in
if (!isset($_SESSION['buyer_id'])) {
    echo "<script>alert('Please login to book a property'); window.location.href='../buyer/buyer_login.php';</script>";
    exit;
}

// Get property ID from URL
if (!isset($_GET['pid'])) {
    die("Invalid property ID.");
}

$property_id = intval($_GET['pid']);
$buyer_id = $_SESSION['buyer_id'];

// Fetch property details
$property = $dao->getData('*', 'property', 'pid=' . $property_id);
if (empty($property)) {
    die("Property not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $token = 5000; // Automatically set token amount

    // Insert booking
    $data = [
        'pid' => $property_id,
        'bid' => $buyer_id,
        'date' => $date,
        'token' => $token
    ];

    $inserted = $dao->insert($data, 'booking');

    if ($inserted) {
        // Redirect to payment page
        header("Location: payment.php?buyer_id=$buyer_id&property_id=$property_id&token=$token");
        exit;
    } else {
        echo "<script>alert('Error while booking. Try again.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Realty Hub" />
    <link rel="shortcut icon" href="template/favicon.png" />
    <meta name="description" content="Realty Hub - Professional Property Marketplace" />
    <meta name="keywords" content="real estate, property, book property, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Book Property | Realty Hub</title>
    
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
        
        .booking-container {
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
        
        .booking-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .booking-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .booking-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .property-image {
            height: 300px;
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 1.5rem;
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
        
        .property-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }
        
        .property-description {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .property-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            text-align: center;
            padding: 1rem;
            background: rgba(25, 118, 210, 0.1);
            border-radius: 10px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--secondary);
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
            outline: 0;
        }
        
        .btn-book {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
            width: 100%;
            color: white;
            font-size: 1.1rem;
        }
        
        .btn-book::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-book:hover::before {
            left: 100%;
        }
        
        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-book:active {
            transform: translateY(1px);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, var(--secondary), #1a2530);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 15px rgba(44, 62, 80, 0.3);
            position: relative;
            overflow: hidden;
            width: 100%;
            color: white;
            font-size: 1.1rem;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(44, 62, 80, 0.4);
        }
        
        .btn-cancel:active {
            transform: translateY(1px);
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
            .booking-container {
                padding: 1.8rem;
            }
            
            .property-image {
                height: 200px;
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
        <div class="booking-container">
            <div class="booking-header">
                <h1><i class="fas fa-calendar-check me-2"></i>Book Property</h1>
                <p class="mb-0">Confirm your booking details below</p>
            </div>
            
            <div class="mb-4 text-center">
                <div class="property-image">
                    <img src="../uploads/<?php echo htmlspecialchars($property[0]['pimage']); ?>" alt="Property Image">
                </div>
                <h3 class="property-title"><?php echo htmlspecialchars($property[0]['plocation']); ?></h3>
                <p class="property-description"><?php echo htmlspecialchars($property[0]['pdescription']); ?></p>
                <div class="property-price">
                    <i class="fas fa-rupee-sign me-1"></i><?php echo number_format($property[0]['pprice']); ?>
                </div>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i><strong>Token Amount:</strong> ₹5000
                </div>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="date"><i class="fas fa-calendar me-2"></i>Booking Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
                
                <!-- Hidden token field -->
                <input type="hidden" name="token" value="5000">
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn-book">
                            <i class="fas fa-calendar-check me-2"></i>Confirm Booking
                        </button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="view_property.php" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>