<?php
require_once('../config/autoload.php');

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adlog'])) {
    $id = $_POST['adid'];
    $pass = $_POST['adpass'];
    
    if(empty($id) || empty($pass)) {
        $error = "Please fill in all fields";
    } else {
        // Note: This is a simplified example. In a real application, you should use prepared statements
        $query = "SELECT * FROM admin WHERE ADMIN_ID='$id'";
        $res = mysqli_query($con, $query);
        
        if($row = mysqli_fetch_assoc($res)) {
            $db_password = $row['ADMIN_PASSWORD'];
            if($pass == $db_password) {
                // Start session and redirect
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $id;
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Invalid admin ID";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login - Realty Hub</title>
  
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
  <style>
    :root {
      --primary-color: #036621;
      --primary-dark: #024518;
      --secondary-color: #28a745;
      --light-bg: #f8f9fa;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e7f1 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('images/adminbg2.jpg') no-repeat center center/cover;
      opacity: 0.1;
      z-index: -1;
    }
    
    .login-container {
      background: white;
      padding: 40px;
      border-radius: 16px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      border: 1px solid rgba(0,0,0,0.05);
      position: relative;
      overflow: hidden;
      animation: fadeInUp 0.6s ease;
    }
    
    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .login-header h2 {
      color: var(--primary-color);
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 10px;
      position: relative;
      display: inline-block;
    }
    
    .login-header h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }
    
    .login-header p {
      color: #6c757d;
      font-size: 1.1rem;
    }
    
    .login-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 8px 20px rgba(3, 102, 33, 0.3);
    }
    
    .login-icon i {
      font-size: 2.5rem;
      color: white;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #495057;
      font-size: 1.05rem;
    }
    
    .form-control {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid #e1e5eb;
      border-radius: 10px;
      font-size: 16px;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(3, 102, 33, 0.25);
      outline: none;
    }
    
    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      border-radius: 10px;
      padding: 14px 20px;
      font-weight: 600;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 8px 20px rgba(3, 102, 33, 0.3);
      position: relative;
      overflow: hidden;
      font-size: 1.1rem;
      width: 100%;
      color: white;
      margin-top: 10px;
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: 0.5s;
    }
    
    .btn-login:hover::before {
      left: 100%;
    }
    
    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 25px rgba(3, 102, 33, 0.4);
    }
    
    .btn-login:active {
      transform: translateY(1px);
    }
    
    .error {
      background: #f8d7da;
      color: #721c24;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #f5c6cb;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .error i {
      font-size: 1.2rem;
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
    
    @media (max-width: 576px) {
      .login-container {
        padding: 30px 20px;
        margin: 0 15px;
      }
      
      .login-header h2 {
        font-size: 1.7rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header animate__animated animate__fadeInDown">
      <div class="login-icon">
        <i class="fas fa-user-shield"></i>
      </div>
      <h2>Admin Login</h2>
      <p>Welcome to Realty Hub Management System</p>
    </div>
    
    <?php if (!empty($error)) echo "
    <div class='error animate__animated animate__fadeIn'>
      <i class='fas fa-exclamation-circle'></i>
      $error
    </div>"; ?>
    
    <form method="POST" class="animate__animated animate__fadeInUp">
      <div class="form-group">
        <label class="form-label" for="adid">Admin ID</label>
        <input type="text" id="adid" name="adid" class="form-control" placeholder="Enter your admin ID" required>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="adpass">Password</label>
        <input type="password" id="adpass" name="adpass" class="form-control" placeholder="Enter your password" required>
      </div>
      
      <button type="submit" name="adlog" class="btn-login">
        <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
      </button>
    </form>
  </div>
</body>
</html>