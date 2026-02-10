<?php
session_start();
require('../config/autoload.php'); 

$dao = new DataAccess();

// Assuming session holds seller ID
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit;
}

$sid = $_SESSION['seller_id'];
$seller = $dao->getData('*', 'seller', "sid=$sid");

if (empty($seller)) {
    echo "<h3>Profile not found.</h3>";
    exit;
}

$s = $seller[0];

// Update session variables to ensure they're current
$_SESSION['sname'] = $s['sname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Profile - Realty Hub</title>
  
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
    
    .profile-container {
      max-width: 800px;
      margin: 3rem auto;
      padding: 0 1rem;
    }
    
    .profile-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      animation: fadeInUp 0.8s ease;
    }
    
    .profile-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 2.5rem;
      text-align: center;
      position: relative;
    }
    
    .profile-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent), transparent);
    }
    
    .profile-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 2.2rem;
    }
    
    .profile-header p {
      opacity: 0.9;
      font-size: 1.1rem;
    }
    
    .profile-body {
      padding: 2.5rem;
    }
    
    .profile-info {
      margin-bottom: 2rem;
    }
    
    .info-row {
      display: flex;
      margin-bottom: 1.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid #e1e5eb;
    }
    
    .info-row:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    
    .info-label {
      font-weight: 600;
      color: var(--secondary);
      width: 150px;
      flex-shrink: 0;
    }
    
    .info-value {
      color: #555;
      flex: 1;
    }
    
    .btn-group {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
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
    
    .btn-danger-custom {
      background: linear-gradient(135deg, var(--danger), #d32f2f);
      color: white;
    }
    
    .btn-danger-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
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
    
    @media (max-width: 768px) {
      .profile-header, .profile-body {
        padding: 1.8rem;
      }
      
      .info-row {
        flex-direction: column;
        gap: 0.5rem;
      }
      
      .info-label {
        width: 100%;
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

  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-header">
        <h2>My Profile</h2>
        <p>Manage your account information</p>
      </div>
      
      <div class="profile-body">
        <div class="profile-info">
          <div class="info-row animate__animated animate__fadeInUp">
            <div class="info-label">Full Name</div>
            <div class="info-value"><?php echo htmlspecialchars($s['sname']); ?></div>
          </div>
          
          <div class="info-row animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="info-label">Phone</div>
            <div class="info-value"><?php echo htmlspecialchars($s['sphone']); ?></div>
          </div>
          
          <div class="info-row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="info-label">Email</div>
            <div class="info-value"><?php echo htmlspecialchars($s['smail']); ?></div>
          </div>
          
          <div class="info-row animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <div class="info-label">Member Since</div>
            <div class="info-value"><?php echo date('F j, Y', strtotime($s['created_at'])); ?></div>
          </div>
        </div>
        
        <div class="btn-group">
          <a href="edit_profile.php" class="btn btn-custom btn-primary-custom animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <i class="fas fa-edit me-2"></i>Edit Profile
          </a>
          <a href="feedback.php" class="btn btn-custom btn-secondary-custom animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
            <i class="fas fa-comment me-2"></i>Feedback
          </a>
          <a href="logout.php" class="btn btn-custom btn-danger-custom animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>