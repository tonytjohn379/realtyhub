<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit;
}

require('../config/autoload.php');

$dao = new DataAccess();

// Fetch active categories and districts
$categories = $dao->getData('*', 'category', 'status=1');
$districts = $dao->getData('*', 'district', 'status=1');

// Handle form submission
if (isset($_POST['submit'])) {
    $sid = $_SESSION['seller_id']; // Get seller ID from session
    $cid = isset($_POST['cid']) ? (int)$_POST['cid'] : 0;
    $did = isset($_POST['did']) ? (int)$_POST['did'] : 0;
    $pcent = isset($_POST['pcent']) ? (int)$_POST['pcent'] : 0;
    $psqft = isset($_POST['psqft']) ? (int)$_POST['psqft'] : 0;
    $pbhk = isset($_POST['pbhk']) ? (int)$_POST['pbhk'] : 0;
    $plocation = isset($_POST['plocation']) ? trim($_POST['plocation']) : '';
    $pdescription = isset($_POST['pdescription']) ? trim($_POST['pdescription']) : '';
    $pprice = isset($_POST['pprice']) ? (int)$_POST['pprice'] : 0;

    // Validate required fields
    if (empty($cid) || empty($did) || empty($plocation) || empty($pdescription) || empty($pprice)) {
        echo "<script>alert('Please fill in all required fields!');</script>";
    } else {
        // File upload
        $file = new FileUpload();
        $pimage = null; // Use NULL when no image is uploaded
        
        // Only attempt file upload if a file was provided
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $pimage = $file->doUploadRandom($_FILES['file'], ['.jpg', '.jpeg', '.png','.JPG','.PNG',], 5000, 0, '../uploads/');
            
            // Check for upload errors
            if ($pimage === false) {
                $uploadError = $file->errors();
                // Log the error for debugging
                error_log("File upload error: " . $uploadError);
                echo "<script>alert('File upload error: " . addslashes($uploadError) . "'); window.location='property.php';</script>";
                exit;
            }
        }

        $fields = [
            'sid' => $sid,
            'cid' => $cid,
            'did' => $did,
            'pcent' => $pcent,
            'psqft' => $psqft,
            'pbhk' => $pbhk,
            'plocation' => $plocation,
            'pdescription' => $pdescription,
            'pprice' => $pprice,
            'pimage' => $pimage,
            'status' => 1  // Always set new properties to active by default
        ];

        $result = $dao->insert($fields, 'property');
        if ($result) {
            $message = 'Property added successfully!';
            if (!empty($pimage)) {
                $message .= ' Image uploaded: ' . $pimage;
            }
            echo "<script>alert('" . addslashes($message) . "'); window.location='view_property.php';</script>";
        } else {
            $error = $dao->getErrors();
            error_log("Property insertion failed: " . $error);
            error_log("Last query: " . $dao->last_query());
            echo "<script>alert('Error adding property: " . addslashes($error) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Property - Realty Hub</title>
  
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
    
    .property-container {
      max-width: 900px;
      margin: 3rem auto;
      padding: 0 1rem;
    }
    
    .property-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      animation: fadeInUp 0.8s ease;
    }
    
    .property-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 2.5rem;
      text-align: center;
      position: relative;
    }
    
    .property-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent), transparent);
    }
    
    .property-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 2.2rem;
    }
    
    .property-header p {
      opacity: 0.9;
      font-size: 1.1rem;
    }
    
    .property-body {
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
    
    .form-control, .form-select {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid #e1e5eb;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
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
      .property-header, .property-body {
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

  <div class="property-container">
    <div class="property-card">
      <div class="property-header">
        <h2>Add New Property</h2>
        <p>List your property for potential buyers</p>
      </div>
      
      <div class="property-body">
        <form method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp">
                <label for="cid" class="form-label">Category</label>
                <select name="cid" id="cid" class="form-select" required>
                  <option value="">-- Select Category --</option>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['cid'] ?>"><?= htmlspecialchars($cat['cname']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <label for="did" class="form-label">District</label>
                <select name="did" id="did" class="form-select" required>
                  <option value="">-- Select District --</option>
                  <?php foreach ($districts as $dist): ?>
                    <option value="<?= $dist['did'] ?>"><?= htmlspecialchars($dist['dname']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <label class="form-label">Cent</label>
                <input type="number" name="pcent" class="form-control" placeholder="Enter cent">
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <label class="form-label">Sqft</label>
                <input type="number" name="psqft" class="form-control" placeholder="Enter sqft">
              </div>
            </div>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <label class="form-label">BHK</label>
            <input type="number" name="pbhk" class="form-control" placeholder="Enter number of BHK">
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
            <label class="form-label">Location</label>
            <input type="text" name="plocation" class="form-control" placeholder="Enter location" required>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <label class="form-label">Description</label>
            <textarea name="pdescription" class="form-control" rows="3" placeholder="Enter property description" required></textarea>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
            <label class="form-label">Price</label>
            <input type="number" name="pprice" class="form-control" placeholder="Enter price" required>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
            <label class="form-label">Property Image</label>
            <input type="file" name="file" class="form-control">
          </div>
          
          <button type="submit" name="submit" class="btn btn-primary-custom animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
            <i class="fas fa-plus-circle me-2"></i>Add Property
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>