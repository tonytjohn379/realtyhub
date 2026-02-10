<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();
$buyer_id = isset($_SESSION['buyer_id']) ? $_SESSION['buyer_id'] : 0;

// Redirect if not logged in (optional but recommended for actions like wishlist)
if (!$buyer_id) {
    // You might redirect to a login page or just keep buyer_id=0
    // For this change, we'll assume the user might not be logged in, but actions will fail/be blocked later.
}

// Get all properties with status = 1 (available properties only)
$properties = $dao->getData('*', 'property', 'status=1');

// Fetch wishlist for logged-in buyer
$wishlist = [];
if ($buyer_id) {
    // The previous getData logic was correct, but we'll re-verify it's fetching property_id correctly
    $wish = $dao->getData('*','wishlist',"bid=$buyer_id");
    if(!empty($wish)){
        foreach($wish as $w){
            $wishlist[] = $w['pid'];
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
    <meta name="keywords" content="real estate, property, browse properties, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Browse Properties | Realty Hub</title>
    
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
        
        .properties-container {
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
        
        .properties-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 0 2rem;
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
        
        .property-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--secondary);
        }
        
        .detail-value {
            color: #6c757d;
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
        
        .btn-wishlist {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }
        
        .btn-wishlist:hover {
            background-color: var(--warning);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }
        
        .btn-wishlist.added {
            background-color: var(--success) !important;
            color: white !important;
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
            color: white;
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
            .property-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
            
            .properties-container {
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
        <div class="properties-container">
            <div class="section-header">
                <h2 class="section-title">Browse Properties</h2>
                <a href="template/index.php" class="btn btn-primary-custom animate__animated animate__fadeIn">
                    <i class="fas fa-home me-2"></i>Back to Dashboard
                </a>
            </div>
            
            <?php if (empty($properties)): ?>
                <div class="no-properties">
                    <i class="fas fa-home"></i>
                    <h3>No Properties Available</h3>
                    <p>There are currently no properties listed. Please check back later.</p>
                    <a href="template/index.php" class="btn btn-primary-custom">
                        <i class="fas fa-home me-2"></i>Back to Dashboard
                    </a>
                </div>
            <?php else: ?>
                <div class="property-grid">
                    <?php foreach ($properties as $property): 
                        $is_wish = in_array($property['pid'], $wishlist);
                    ?>
                        <div class="property-card animate__animated animate__fadeInUp">
                            <div class="property-image">
                                <?php if (!empty($property['pimage'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($property['pimage']); ?>" alt="Property Image">
                                <?php else: ?>
                                    <i class="fas fa-home fa-4x"></i>
                                <?php endif; ?>
                            </div>
                            <div class="property-content">
                                <div class="property-price">₹<?php echo number_format($property['pprice']); ?></div>
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                <h3 class="property-title"><?php echo htmlspecialchars($property['plocation']); ?></h3>
                                </div>
                                    <?php echo htmlspecialchars($property['pdescription']); ?>
                                
                                
                                <div class="property-details">
                                    <div class="detail-item">
                                        <span class="detail-label">BHK:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($property['pbhk']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Sqft:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($property['psqft']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="property-actions">
                                    <?php if ($buyer_id): // Only show button if a buyer is logged in ?>
                                        <button type="button" 
                                                class="btn-action btn-wishlist wishlist-btn<?php echo $is_wish ? ' added' : ''; ?>"
                                                data-property-id="<?php echo $property['pid']; ?>">
                                            <i class="fas fa-heart me-1"></i>
                                            <?php echo $is_wish ? 'Wishlisted' : 'Add to Wishlist'; ?>
                                        </button>
                                    <?php else: ?>
                                        <a href="buyer_login.php" class="btn-action btn-wishlist">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login to Add
                                        </a>
                                    <?php endif; ?>
                                    <a href="book_property.php?pid=<?php echo $property['pid']; ?>" class="btn-action btn-book">
                                        <i class="fas fa-calendar-check me-1"></i> Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            // Ensure this AJAX call points to the correct file you will create/modify
            $('.wishlist-btn').click(function(){
                var btn = $(this);
                var property_id = btn.data('property-id');
                
                // Prevent action if not logged in (optional check, but good practice)
                <?php if (!$buyer_id): ?>
                    alert('Please login to add items to your wishlist.');
                    window.location.href = 'buyer_login.php'; // Redirect to login
                    return; 
                <?php endif; ?>

                $.ajax({
                    url: 'toggle_wishlist.php', // This is the file you must create/edit
                    type: 'POST',
                    data: { property_id: property_id },
                    success: function(response){
                        // Trim the response to handle potential white space
                        var result = response.trim(); 
                        if(result == 'added'){
                            btn.addClass('added').html('<i class="fas fa-heart me-1"></i> Wishlisted');
                        } else if(result == 'removed'){
                            btn.removeClass('added').html('<i class="fas fa-heart me-1"></i> Add to Wishlist');
                        } else if(result == 'not_logged_in'){
                            alert('Please login to add to wishlist.');
                            // Optional: redirect to login page
                            // window.location.href = 'buyer_login.php'; 
                        } else {
                            alert('Error updating wishlist: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " + error);
                        alert('An AJAX error occurred. Check the console for details.');
                    }
                });
            });
        });
    </script>
</body>
</html>