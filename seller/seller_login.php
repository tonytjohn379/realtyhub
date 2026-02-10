<?php
session_start();

$conn = new mysqli("localhost", "root", "", "realestate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $smail = trim($_POST['smail']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM seller WHERE smail=? AND password=? AND status=1");
    $stmt->bind_param("ss", $smail, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $seller = $res->fetch_assoc();
        $_SESSION['seller_id'] = $seller['sid'];
        $_SESSION['sname'] = $seller['sname'];
        header("Location:template/index.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Login - Realty Hub</title>
  
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
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .login-container {
      width: 100%;
      max-width: 450px;
      margin: 20px auto;
    }

    .login-box {
      background-color: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      color: var(--secondary);
      animation: fadeIn 0.6s ease forwards;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 25px;
    }
    
    .login-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 8px;
      font-size: 1.8rem;
    }
    
    .login-header p {
      color: #6c757d;
      font-size: 1rem;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--secondary);
      font-size: 0.95rem;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 2px solid #e1e5eb;
      border-radius: 10px;
      font-size: 15px;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
      outline: none;
    }

    .input-group-text {
      border-radius: 10px 0 0 10px;
      border-right: 0;
      background-color: #f8f9fa;
    }

    .input-group .form-control {
      border-radius: 0 10px 10px 0;
      margin-bottom: 0;
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      border: none;
      border-radius: 10px;
      padding: 12px 25px;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 5px 15px rgba(25, 118, 210, 0.2);
      position: relative;
      overflow: hidden;
      font-size: 1rem;
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
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: 0.4s;
    }
    
    .btn-primary-custom:hover::before {
      left: 100%;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
    }
    
    .btn-primary-custom:active {
      transform: translateY(0);
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid #e9ecef;
      font-size: 0.9rem;
    }

    .footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .footer a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }
    
    .alert {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      background: var(--danger);
      color: white;
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      animation: slideInRight 0.5s ease;
    }
    
    @keyframes slideInRight {
      from { transform: translateX(100%); }
      to { transform: translateX(0); }
    }
    
    @media (max-width: 576px) {
      .login-box {
        padding: 20px;
      }
      
      .login-header h2 {
        font-size: 1.5rem;
      }
      
      .form-control {
        padding: 10px 12px;
        font-size: 14px;
      }
      
      .btn-primary-custom {
        padding: 10px 20px;
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box animate__animated animate__fadeInUp">
      <div class="login-header">
        <h2>Seller Login</h2>
        <p>Welcome back! Please login to your account</p>
      </div>
      
      <form method="POST">
        <div class="mb-3">
          <label for="smail" class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
              <i class="fas fa-envelope text-muted"></i>
            </span>
            <input type="email" name="smail" id="smail" class="form-control border-start-0" placeholder="Enter your email" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
              <i class="fas fa-lock text-muted"></i>
            </span>
            <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Enter your password" required>
            <span class="input-group-text bg-light border-start-0 password-toggle" style="cursor: pointer;">
              <i class="fas fa-eye text-muted" id="togglePassword"></i>
            </span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary-custom animate__animated animate__fadeInUp">
          <i class="fas fa-sign-in-alt me-2"></i>Login to Account
        </button>
      </form>

      <div class="footer">
        Don't have an account? <a href="seller_register.php">Register</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Password visibility toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function (e) {
      // Toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      // Toggle the eye icon
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>