<?php
session_start();
require('../config/autoload.php'); 

$dao = new DataAccess();
$file = new FileUpload(); // Initialize FileUpload object

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    echo "<div class='alert alert-danger'>Error: You must log in first!</div>";
    exit;
}

// Get property ID from URL
$pid = $_GET['pid'];

// Fetch existing property
$info = $dao->getData('*','property','pid='.$pid);

// Initialize form elements with existing values
$elements = [
    "cid"=>$info[0]['cid'],
    "did"=>$info[0]['did'],
    "pcent"=>$info[0]['pcent'],
    "psqft"=>$info[0]['psqft'],
    "pbhk"=>$info[0]['pbhk'],
    "plocation"=>$info[0]['plocation'],
    "pdescription"=>$info[0]['pdescription'],
    "pprice"=>$info[0]['pprice'],
    "pimage"=>$info[0]['pimage']
];
$form = new FormAssist($elements, $_POST);

// Validation rules (image is optional in edit mode)
$labels = [
    'cid'=>"Property Type",
    'did'=>"Property District",
    'pcent'=>"Property Cent",
    'psqft'=>"Square Feet",
    'pbhk'=>"BHK",
    'plocation'=>"Location Link",
    'pdescription'=>"Description",
    'pprice'=>"Price",
    'pimage'=>"Property Image"
];

$rules = [
    "cid"=>["required"=>true],
    "did"=>["required"=>true],
    "psqft"=>["required"=>true,"integeronly"=>true],
    "pbhk"=>["required"=>true,"integeronly"=>true],
    "pcent"=>["integeronly"=>true],
    "plocation"=>["required"=>true,"minlength"=>3,"maxlength"=>100],
    "pdescription"=>["required"=>true,"minlength"=>3,"maxlength"=>500],
    "pprice"=>["required"=>true,"integeronly"=>true]
];

$validator = new FormValidator($rules, $labels);

// Handle form submission
if(isset($_POST["btn_update"])) {
    if($validator->validate($_POST)) {

        $data = [
            'cid'=>$_POST['cid'],
            'did'=>$_POST['did'],
            'pcent'=>$_POST['pcent'],
            'psqft'=>$_POST['psqft'],
            'pbhk'=>$_POST['pbhk'],
            'plocation'=>$_POST['plocation'],
            'pdescription'=>$_POST['pdescription'],
            'pprice'=>$_POST['pprice']
        ];

        // Handle image upload
        if(!empty($_FILES['pimage']['name'])) {
            $uploadPath = '../uploads';
            if($fileName = $file->doUploadRandom($_FILES['pimage'], ['.jpg','.png','.jpeg','.PNG','.JPG'], 100000, 5, $uploadPath)) {
                $data['pimage'] = $fileName;
            } else {
                // Handle upload error with detailed message
                $error = $file->errors();
                error_log("File upload error in edit_property.php: " . $error);
                echo "<script>alert('Image upload failed: " . addslashes($error) . "'); window.location='edit_property.php?pid=" . $pid . "';</script>";
                exit;
            }
        }

        // Get current property status before update
        $current_property = $dao->getData('*', 'property', 'pid='.$pid);
        $current_status = $current_property[0]['status'];
        
        // Check if status is being changed from 1 (active) to 0 (inactive)
        if(isset($_POST['status']) && $_POST['status'] == 0 && $current_status == 1) {
            // Remove this property from all wishlists
            $dao->delete('wishlist', 'pid='.$pid);
        }
        
        // Add status to data if it's set
        if(isset($_POST['status'])) {
            $data['status'] = $_POST['status'];
        }

        // Update property
        if($dao->update($data, 'property', 'pid='.$pid)) {
            // redirect immediately
            header('Location: view_property.php');
            exit(); // make sure script stops after redirect
        } else {
            echo "<script>alert('Error updating property!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Property - Realty Hub</title>
  
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
    
    .img-current {
      max-width: 100%;
      height: auto;
      max-height: 300px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .img-container {
      text-align: center;
      margin: 1.5rem 0;
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
    
    .error-text {
      color: var(--danger);
      font-size: 0.9rem;
      margin-top: 0.5rem;
      display: block;
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
        <h2>Edit Property</h2>
        <p>Update your property details</p>
      </div>
      
      <div class="property-body">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp">
                <label class="form-label">Type</label>
                <?php
                // Fetch categories
                $categories = $dao->getData('*', 'category', 'status=1');
                $category_options = array();
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $category_options[$category['cname']] = $category['cid'];
                    }
                }
                echo $form->dropDownList('cid', $category_options, ['class'=>'form-select'], 'Select Category'); ?>
                <span class="error-text"><?= $validator->error('cid'); ?></span>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <label class="form-label">District</label>
                <?php
                // Fetch districts
                $districts = $dao->getData('*', 'district', 'status=1');
                $district_options = array();
                if (!empty($districts)) {
                    foreach ($districts as $district) {
                        $district_options[$district['dname']] = $district['did'];
                    }
                }
                echo $form->dropDownList('did', $district_options, ['class'=>'form-select'], 'Select District'); ?>
                <span class="error-text"><?= $validator->error('did'); ?></span>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <label class="form-label">Cent</label>
                <?= $form->textBox('pcent',['class'=>'form-control']); ?>
                <span class="error-text"><?= $validator->error('pcent'); ?></span>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <label class="form-label">Sqft</label>
                <?= $form->textBox('psqft',['class'=>'form-control']); ?>
                <span class="error-text"><?= $validator->error('psqft'); ?></span>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <label class="form-label">BHK</label>
                <?= $form->textBox('pbhk',['class'=>'form-control']); ?>
                <span class="error-text"><?= $validator->error('pbhk'); ?></span>
              </div>
            </div>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
            <label class="form-label">Location Link</label>
            <?= $form->textBox('plocation',['class'=>'form-control']); ?>
            <span class="error-text"><?= $validator->error('plocation'); ?></span>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <label class="form-label">Description</label>
            <?= $form->textBox('pdescription',['class'=>'form-control']); ?>
            <span class="error-text"><?= $validator->error('pdescription'); ?></span>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
            <label class="form-label">Price</label>
            <?= $form->textBox('pprice',['class'=>'form-control']); ?>
            <span class="error-text"><?= $validator->error('pprice'); ?></span>
          </div>
          
          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="1" <?php echo ($info[0]['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($info[0]['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
            </select>
          </div>

          <div class="form-group animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
            <label class="form-label">Current Image</label>
            <div class="img-container">
              <img src="../uploads/<?= $info[0]['pimage']; ?>" class="img-current">
            </div>
            <label class="form-label">Change Image</label>
            <?= $form->fileField('pimage',['class'=>'form-control']); ?>
          </div>
          
          <button type="submit" name="btn_update" class="btn btn-primary-custom animate__animated animate__fadeInUp" style="animation-delay: 1.0s;">
            <i class="fas fa-save me-2"></i>Update Property
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>