<?php
session_start();
require('../config/autoload.php'); 
$dao = new DataAccess();

if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit;
}

$sid = $_SESSION['seller_id'];

// Fetch current data
$seller = $dao->getData('*', 'seller', "sid=$sid");
$s = $seller[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname = $_POST['sname'];
    $sphone = $_POST['sphone'];
    $smail = $_POST['smail'];
    $password = $_POST['password'];

    $updateData = [
        'sname' => $sname,
        'sphone' => $sphone,
        'smail' => $smail,
    ];

    if (!empty($password)) {
        $updateData['password'] = $password; // plaintext, no hashing
    }

    if ($dao->update($updateData, 'seller', "sid=$sid")) {
        // Update session variables with new data
        $_SESSION['sname'] = $sname;
        echo "<script>alert('Profile updated successfully!'); window.location='seller_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Realty Hub</title>
  
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
    
    .edit-container {
      max-width: 700px;
      margin: 3rem auto;
      padding: 0 1rem;
    }
    
    .edit-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      animation: fadeInUp 0.8s ease;
    }
    
    .edit-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 2.5rem;
      text-align: center;
      position: relative;
    }
    
    .edit-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent), transparent);
    }
    
    .edit-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 2.2rem;
    }
    
    .edit-header p {
      opacity: 0.9;
      font-size: 1.1rem;
    }
    
    .edit-body {
      padding: 2.5rem;
    }
    
    .form-group {
      margin-bottom: 1.8rem;
    }
    
    .form-label {
      display: block;
      margin-bottom: 0.8rem;
      font-weight: 600;
      color: var(--secondary);
      font-size: 1.1rem;
    }
    
    .form-control {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid #e1e5eb;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
      outline: none;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border: none;
      border-radius: 12px;
      padding: 14px 32px;
      font-weight: 600;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
      position: relative;
      overflow: hidden;
      font-size: 1.1rem;
      width: 100%;
      color: white;
      margin-top: 10px;
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
      box-shadow: 0 12px 25px rgba(25, 118, 210, 0.4);
    }
    
    .btn-primary-custom:active {
      transform: translateY(1px);
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
      .edit-header, .edit-body {
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

  <div class="edit-container">
    <div class="edit-card">
      <div class="edit-header">
        <h2>Edit Profile</h2>
        <p>Update your account information</p>
      </div>
      
      <div class="edit-body">
        <form method="POST">
          <div class="form-group animate__animated animate__fadeInUp">
            <label for="sname" class="form-label">Full Name</label>
            <input type="text" name="sname" id="sname" class="form-control" value="<?php echo htmlspecialchars($s['sname']); ?>" required>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <label for="sphone" class="form-label">Phone</label>
            <input type="text" name="sphone" id="sphone" class="form-control" value="<?php echo htmlspecialchars($s['sphone']); ?>" required>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <label for="smail" class="form-label">Email</label>
            <input type="email" name="smail" id="smail" class="form-control" value="<?php echo htmlspecialchars($s['smail']); ?>" required>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
            <label for="password" class="form-label">New Password (optional)</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
          </div>
          
          <button type="submit" class="btn btn-primary-custom animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <i class="fas fa-save me-2"></i>Update Profile
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>