<?php
session_start();
require('../config/autoload.php'); // database connection

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit;
}

$dao = new DataAccess();

// Seller ID from session
$seller_id = $_SESSION['seller_id'];

// Fetch properties for this seller
$properties = $dao->getData('*', 'property', 'sid=' . $seller_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Properties - Realty Hub</title>
  
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
    
    .properties-container {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 1rem;
    }
    
    .properties-header {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .properties-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--secondary);
      font-size: 2.5rem;
      margin-bottom: 1rem;
      position: relative;
      display: inline-block;
    }
    
    .properties-header h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-radius: 2px;
    }
    
    .properties-header p {
      color: #6c757d;
      font-size: 1.2rem;
      max-width: 700px;
      margin: 1.5rem auto 0;
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
    
    .property-details {
      margin-bottom: 1.2rem;
    }
    
    .property-detail {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 0;
      border-bottom: 1px solid #eee;
    }
    
    .property-detail:last-child {
      border-bottom: none;
    }
    
    .detail-label {
      color: #6c757d;
      font-weight: 500;
    }
    
    .detail-value {
      font-weight: 600;
      color: var(--secondary);
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
    
    .btn-edit {
      background-color: rgba(103, 58, 183, 0.1);
      color: var(--accent);
    }
    
    .btn-edit:hover {
      background-color: var(--accent);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(103, 58, 183, 0.3);
    }
    
    .btn-delete {
      background-color: rgba(244, 67, 54, 0.1);
      color: var(--danger);
    }
    
    .btn-delete:hover {
      background-color: var(--danger);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
    }
    
    .no-properties {
      background: white;
      border-radius: 16px;
      padding: 4rem;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      animation: fadeIn 1s ease;
      grid-column: 1 / -1;
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
      
      .properties-header h2 {
        font-size: 2rem;
      }
      
      .properties-container {
        margin: 2rem auto;
        padding: 0 0.5rem;
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

  <div class="properties-container">
    <div class="properties-header">
      <h2>My Properties</h2>
      <p>Manage and view all your property listings in one place</p>
    </div>
    
    <div class="property-grid">
      <?php if (!empty($properties)) { ?>
        <?php foreach ($properties as $property) { 
          // Get category name
          $category = $dao->getData('cname', 'category', 'cid=' . $property['cid']);
          $categoryName = !empty($category) ? $category[0]['cname'] : 'Unknown';
          
          // Get district name
          $district = $dao->getData('dname', 'district', 'did=' . $property['did']);
          $districtName = !empty($district) ? $district[0]['dname'] : 'Unknown';
          
          // Construct the full image path
          $imagePath = "../uploads/" . $property['pimage'];
          $fullImagePath = (!empty($property['pimage']) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/tonyMCA/uploads/" . $property['pimage'])) 
                          ? $imagePath 
                          : '';
          ?>
          <div class="property-card animate__animated animate__fadeInUp">
            <div class="property-image">
              <?php if (!empty($fullImagePath)) { ?>
                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" alt="Property Image">
              <?php } else { ?>
                <i class="fas fa-home fa-4x"></i>
              <?php } ?>
            </div>
            <div class="property-content">
              <div class="property-price">₹<?php echo number_format($property['pprice']); ?></div>
              <h3 class="property-title"><?php echo htmlspecialchars($property['plocation'] ?? 'Property'); ?></h3>
              
              <div class="property-details">
                <div class="property-detail">
                  <span class="detail-label">Category:</span>
                  <span class="detail-value"><?php echo htmlspecialchars($categoryName); ?></span>
                </div>
                <div class="property-detail">
                  <span class="detail-label">District:</span>
                  <span class="detail-value"><?php echo htmlspecialchars($districtName); ?></span>
                </div>
                <div class="property-detail">
                  <span class="detail-label">BHK:</span>
                  <span class="detail-value"><?php echo $property['pbhk']; ?></span>
                </div>
                <div class="property-detail">
                  <span class="detail-label">Sqft:</span>
                  <span class="detail-value"><?php echo $property['psqft']; ?></span>
                </div>
                <div class="property-detail">
                  <span class="detail-label">Status:</span>
                  <span class="detail-value">
                    <?php 
                    if ($property['status'] == 1) {
                      echo '<span class="badge bg-success">Active</span>';
                    } else {
                      echo '<span class="badge bg-danger">Inactive</span>';
                    }
                    ?>
                  </span>
                </div>
              </div>
              
              <div class="property-actions">
                <a href="edit_property.php?pid=<?php echo $property['pid']; ?>" class="btn-action btn-edit">
                  <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="delete_property.php?pid=<?php echo $property['pid']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this property?')">
                  <i class="fas fa-trash me-1"></i> Delete
                </a>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <div class="no-properties">
          <i class="fas fa-home"></i>
          <h3>No Properties Listed</h3>
          <p>You haven't added any properties yet. Start by adding your first property.</p>
          <a href="property.php" class="btn btn-primary-custom">
            <i class="fas fa-plus me-2"></i>Add Your First Property
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>