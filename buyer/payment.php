<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();

// Check login
if (!isset($_SESSION['buyer_id'])) {
    echo "<script>alert('Please login'); window.location.href='../buyer/buyer_login.php';</script>";
    exit;
}

// Get URL parameters
$buyer_id = intval($_GET['buyer_id']);
$property_id = intval($_GET['property_id']);
$token = intval($_GET['token']);

// Fetch the latest booking for this buyer and property to get booking_id
$booking = $dao->getData('*', 'booking', "bid=$buyer_id AND pid=$property_id ORDER BY booking_id DESC LIMIT 1");
if (empty($booking)) {
    die("Booking not found.");
}
$booking_id = $booking[0]['booking_id'];

// Fetch property details
$property = $dao->getData('*', 'property', 'pid=' . $property_id);
if (empty($property)) {
    die("Property not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get card details with validation
    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $expiry = isset($_POST['expiry']) ? trim($_POST['expiry']) : '';
    $cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';
    
    // Server-side validation
    $errors = [];
    
    // Validate card number (must be exactly 16 digits)
    if (empty($card_number)) {
        $errors[] = "Card number is required";
    } elseif (!preg_match('/^\d{16}$/', $card_number)) {
        $errors[] = "Card number must be exactly 16 digits";
    }
    
    // Validate expiry date (must be in MM/YY format with exactly 4 digits)
    if (empty($expiry)) {
        $errors[] = "Expiry date is required";
    } elseif (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
        $errors[] = "Expiry date must be in MM/YY format (e.g., 12/25)";
    } else {
        // Additional validation for month and year
        list($month, $year) = explode('/', $expiry);
        $month = intval($month);
        $year = intval($year);
        
        if ($month < 1 || $month > 12) {
            $errors[] = "Invalid month in expiry date";
        }
        
        // Check if the card is expired (simplified check)
        $currentYear = intval(date('y')); // Get last 2 digits of current year
        $currentMonth = intval(date('m'));
        
        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
            $errors[] = "Card has expired";
        }
    }
    
    // Validate CVV (must be exactly 3 digits)
    if (empty($cvv)) {
        $errors[] = "CVV is required";
    } elseif (!preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "CVV must be exactly 3 digits";
    }
    
    // If there are validation errors, display them
    if (!empty($errors)) {
        $error_message = implode("\\n", $errors);
        echo "<script>alert('$error_message');</script>";
    } else {
        // Store payment in DB
        $data = [
            'booking_id' => $booking_id,
            'bid' => $buyer_id,
            'pid' => $property_id,
            'token_amount' => $token,
            'status' => 'Completed'
        ];

        $inserted = $dao->insert($data, 'payment');

        if ($inserted) {
            // Set session variables to ensure user is logged in
            $_SESSION['buyer_id'] = $buyer_id;
            // Redirect to bill.php with necessary parameters
            header("Location: bill.php?property_id=$property_id&token=$token");
            exit;
        } else {
            echo "<script>alert('Payment failed! Try again.');</script>";
        }
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
    <meta name="keywords" content="real estate, property, payment, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Pay Token | Realty Hub</title>
    
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
        
        .payment-container {
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
        
        .payment-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .payment-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .property-image {
            height: 250px;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }
        
        .property-description {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .payment-amount {
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
        
        .btn-pay {
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
        
        .btn-pay::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-pay:hover::before {
            left: 100%;
        }
        
        .btn-pay:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-pay:active {
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
            .payment-container {
                padding: 1.8rem;
            }
            
            .property-image {
                height: 180px;
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
        <div class="payment-container">
            <div class="payment-header">
                <h1><i class="fas fa-credit-card me-2"></i>Pay Token</h1>
                <p class="mb-0">Complete your payment to confirm booking</p>
            </div>
            
            <div class="mb-4 text-center">
                <div class="property-image">
                    <img src="../uploads/<?php echo htmlspecialchars($property[0]['pimage']); ?>" alt="Property Image">
                </div>
                <h3 class="property-title"><?php echo htmlspecialchars($property[0]['plocation']); ?></h3>
                <p class="property-description"><?php echo htmlspecialchars($property[0]['pdescription']); ?></p>
                <div class="payment-amount">
                    <i class="fas fa-rupee-sign me-1"></i><?php echo number_format($token); ?>
                </div>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="card_number"><i class="fas fa-credit-card me-2"></i>Card Number</label>
                    <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Enter card number" required maxlength="16">
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiry"><i class="fas fa-calendar me-2"></i>Expiry Date</label>
                            <input type="text" name="expiry" id="expiry" class="form-control" placeholder="MM/YY" required maxlength="5">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cvv"><i class="fas fa-lock me-2"></i>CVV</label>
                            <input type="text" name="cvv" id="cvv" class="form-control" placeholder="123" required maxlength="3">
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn-pay">
                            <i class="fas fa-credit-card me-2"></i>Pay ₹<?php echo number_format($token); ?>
                        </button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="book_property.php?pid=<?php echo $property_id; ?>" class="btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Card number formatting and validation
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
            if (value.length > 16) {
                value = value.substring(0, 16); // Limit to 16 digits
            }
            e.target.value = value;
        });

        // Expiry date formatting and validation
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
            if (value.length > 4) {
                value = value.substring(0, 4); // Limit to 4 digits
            }
            
            // Add slash after MM
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            
            e.target.value = value;
        });

        // CVV formatting and validation
        document.getElementById('cvv').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
            if (value.length > 3) {
                value = value.substring(0, 3); // Limit to 3 digits
            }
            e.target.value = value;
        });

        // Form validation on submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const cardNumber = document.getElementById('card_number').value;
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;
            
            // Validate card number (16 digits)
            if (cardNumber.length !== 16) {
                alert('Card number must be 16 digits');
                e.preventDefault();
                return;
            }
            
            // Validate expiry date (MM/YY format)
            if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                alert('Expiry date must be in MM/YY format (4 digits with slash)');
                e.preventDefault();
                return;
            }
            
            // Validate CVV (3 digits)
            if (cvv.length !== 3) {
                alert('CVV must be 3 digits');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>