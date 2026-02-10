<?php
session_start();
require('../config/autoload.php'); // database connection

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_login.php'); // redirect if not logged in
    exit;
}

$dao = new DataAccess(); // Initialize DataAccess object
$seller_id = $_SESSION['seller_id'];

// Check if property ID is provided
if (!isset($_GET['pid'])) {
    header('Location: view_property.php'); // redirect if no property selected
    exit;
}

$pid = intval($_GET['pid']); // sanitize input

// Fetch property to verify ownership
$property = $dao->getData('*', 'property', "pid=$pid AND sid=$seller_id");

$message = '';
$messageType = '';

if (!empty($property)) {
    // First delete any wishlist entries for this property (due to foreign key constraint)
    $dao->delete('wishlist', 'pid=' . $pid);
    
    // Then delete any bookings for this property
    $dao->delete('booking', 'property_id=' . $pid);
    
    // Delete property from database
    if ($dao->delete('property', "pid=$pid")) {

        // Delete property image from uploads folder
        $imagePath = realpath(__DIR__ . '/../uploads/' . $property[0]['pimage']);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $message = 'Property deleted successfully!';
        $messageType = 'success';
    } else {
        $message = 'Failed to delete property.';
        $messageType = 'danger';
    }
} else {
    $message = 'Property not found or you do not have permission to delete it.';
    $messageType = 'danger';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Property - Realty Hub</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
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
    
    .delete-container {
      max-width: 700px;
      margin: 3rem auto;
      padding: 0 1rem;
    }
    
    .delete-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      animation: fadeInUp 0.8s ease;
    }
    
    .delete-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 2.5rem;
      text-align: center;
      position: relative;
    }
    
    .delete-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent), transparent);
    }
    
    .delete-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 2.2rem;
    }
    
    .delete-header p {
      opacity: 0.9;
      font-size: 1.1rem;
    }
    
    .delete-body {
      padding: 2.5rem;
    }
    
    .alert {
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 25px;
      animation: fadeIn 0.5s ease;
    }
    
    .alert-success {
      background: rgba(76, 175, 80, 0.15);
      color: var(--success);
      border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .alert-danger {
      background: rgba(244, 67, 54, 0.15);
      color: var(--danger);
      border: 1px solid rgba(244, 67, 54, 0.3);
    }
    
    .btn-group {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-top: 2rem;
    }
    
    .btn-custom {
      flex: 1;
      min-width: 150px;
      text-align: center;
      padding: 12px 20px;
      border-radius: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
      border: none;
      font-size: 1rem;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
    }
    
    .btn-secondary-custom {
      background: linear-gradient(135deg, var(--secondary), #1a2530);
      color: white;
    }
    
    .btn-secondary-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
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
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @media (max-width: 768px) {
      .delete-header, .delete-body {
        padding: 1.8rem;
      }
      
      .btn-group {
        flex-direction: column;
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
          </ul>
          
          <ul class="navbar-nav">
            <li class="nav-item dropdown user-dropdown">
              <a class="nav-link dropdown-toggle animate__animated animate__fadeInDown" href="#" data-bs-toggle="dropdown" style="animation-delay: 0.3s;">
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

  <div class="delete-container">
    <div class="delete-card">
      <div class="delete-header">
        <h2>Delete Property</h2>
        <p>Property deletion confirmation</p>
      </div>
      
      <div class="delete-body">
        <?php if(!empty($message)): ?>
          <div class="alert alert-<?php echo $messageType; ?> animate__animated animate__fadeIn">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        
        <div class="btn-group">
          <a href="view_property.php" class="btn btn-custom btn-primary-custom animate__animated animate__fadeInUp">
            <i class="fas fa-arrow-left me-2"></i>Back to Properties
          </a>
          <a href="template/index.php" class="btn btn-custom btn-secondary-custom animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <i class="fas fa-home me-2"></i>Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <?php if(!empty($message) && $messageType == 'success'): ?>
    <script>
      // Redirect to view properties after 3 seconds if deletion was successful
      setTimeout(function() {
        window.location.href = 'view_property.php';
      }, 3000);
    </script>
  <?php endif; ?>
</body>
</html>