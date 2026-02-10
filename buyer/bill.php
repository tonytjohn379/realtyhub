<?php
session_start();

require('../config/autoload.php');

$dao = new DataAccess();

// Check login
if (!isset($_SESSION['buyer_id'])) {
    echo "<script>alert('Please login'); window.location.href='../buyer/buyer_login.php';</script>";
    exit;
}

// Get parameters from URL
if (!isset($_GET['property_id']) || !isset($_GET['token'])) {
    die("Invalid parameters.");
}

// Use session buyer_id for security, but get property_id from URL
$buyer_id = $_SESSION['buyer_id'];
$property_id = intval($_GET['property_id']);
$token = intval($_GET['token']);

// Fetch buyer details (column is `bid`)
$buyer = $dao->getData('*', 'buyer', 'bid=' . $buyer_id);
if (empty($buyer)) {
    die("Buyer not found.");
}

// Fetch property details
$property = $dao->getData('*', 'property', 'pid=' . $property_id);
if (empty($property)) {
    die("Property not found.");
}

// Map property type IDs to text by fetching from category table
$category = $dao->getData('cname', 'category', 'cid=' . $property[0]['cid']);
$property_type = !empty($category) ? $category[0]['cname'] : 'Unknown';

// Fetch booking record (column buyer_id in booking table might be bid)
$booking = $dao->getData('*', 'booking', "bid=$buyer_id AND pid=$property_id ORDER BY booking_id DESC LIMIT 1");
if (empty($booking)) {
    die("Booking record not found.");
}

// Fetch payment record
$payment = $dao->getData('*', 'payment', "bid=$buyer_id AND pid=$property_id ORDER BY payment_id DESC LIMIT 1");
if (empty($payment)) {
    die("Payment record not found.");
}

// Update property status to 0 (booked) after successful payment
$update_data = array('status' => 0);
$updated = $dao->update($update_data, 'property', 'pid=' . $property_id);

if (!$updated) {
    // Log error but don't stop the page from loading
    error_log("Failed to update property status for property ID: " . $property_id);
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
    <meta name="keywords" content="real estate, property, bill, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Bill / Invoice | Realty Hub</title>
    
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
        
        .bill-container {
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
        
        .bill-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .bill-header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1.5rem;
        }
        
        .bill-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .bill-header h2 {
            color: var(--secondary);
            font-size: 1.5rem;
            margin-bottom: 0;
        }
        
        .bill-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .bill-section h3 {
            color: var(--primary-dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.8rem;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 180px;
            color: var(--secondary);
        }
        
        .info-value {
            flex: 1;
        }
        
        .bill-total {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-align: right;
            padding: 1rem;
            background: rgba(25, 118, 210, 0.1);
            border-radius: 10px;
            margin: 1rem 0;
        }
        
        .btn-print {
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
        
        .btn-print::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-print:hover::before {
            left: 100%;
        }
        
        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-print:active {
            transform: translateY(1px);
        }
        
        .btn-home {
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
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(44, 62, 80, 0.4);
        }
        
        .btn-home:active {
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
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
            }
            
            .bill-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
        
        @media (max-width: 768px) {
            .bill-container {
                padding: 1.8rem;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                margin-bottom: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header no-print">
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
        <div class="bill-container">
            <div class="bill-header">
                <h1><i class="fas fa-file-invoice me-2"></i>Invoice / Bill</h1>
                <h2>Transaction Details</h2>
            </div>
            
            <div class="bill-section">
                <h3><i class="fas fa-user me-2"></i>Buyer Details</h3>
                <div class="info-row">
                    <div class="info-label">Buyer ID:</div>
                    <div class="info-value"><?php echo $buyer[0]['bid']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value"><?php echo htmlspecialchars($buyer[0]['bname']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($buyer[0]['bmail']); ?></div>
                </div>
            </div>
            
            <div class="bill-section">
                <h3><i class="fas fa-home me-2"></i>Property Details</h3>
                <div class="info-row">
                    <div class="info-label">Property ID:</div>
                    <div class="info-value"><?php echo $property[0]['pid']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Type:</div>
                    <div class="info-value"><?php echo $property_type; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Location:</div>
                    <div class="info-value"><?php echo htmlspecialchars($property[0]['plocation']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Description:</div>
                    <div class="info-value"><?php echo htmlspecialchars($property[0]['pdescription']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Price:</div>
                    <div class="info-value">₹<?php echo number_format($property[0]['pprice']); ?></div>
                </div>
            </div>
            
            <div class="bill-section">
                <h3><i class="fas fa-receipt me-2"></i>Booking & Payment Details</h3>
                <div class="info-row">
                    <div class="info-label">Booking ID:</div>
                    <div class="info-value"><?php echo $booking[0]['booking_id']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Token Paid:</div>
                    <div class="info-value">₹<?php echo number_format($payment[0]['token_amount']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Payment Date:</div>
                    <div class="info-value"><?php echo $payment[0]['payment_date']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Payment Status:</div>
                    <div class="info-value"><?php echo $payment[0]['status']; ?></div>
                </div>
            </div>
            
            <div class="bill-total">
                <i class="fas fa-rupee-sign me-1"></i><?php echo number_format($payment[0]['token_amount']); ?> Paid
            </div>
            
            <div class="row mt-4 no-print">
                <div class="col-md-6 mb-3">
                    <button onclick="window.print();" class="btn-print">
                        <i class="fas fa-print me-2"></i>Print Bill
                    </button>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="template/index.php" class="btn-home">
                        <i class="fas fa-home me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>