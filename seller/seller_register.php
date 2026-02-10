<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sname = $_POST['sname'];
    $sphone = $_POST['sphone'];
    $smail = $_POST['smail'];
    $password = $_POST['password'];

    // Check if email already exists
    $existing = $dao->getData('*', 'seller', "smail='$smail'");
    if (!empty($existing)) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $fields = [
            'sname' => $sname,
            'sphone' => $sphone,
            'smail' => $smail,
            'password' => $password,
            'status' => 1
        ];

        if ($dao->insert($fields, 'seller')) {
            echo "<script>alert('Registration successful! Please login.'); window.location='seller_login.php';</script>";
        } else {
            echo "<script>alert('Registration failed!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Registration - Realty Hub</title>
  
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

    .register-container {
      width: 100%;
      max-width: 500px;
      margin: 20px auto;
    }

    .register-box {
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

    .register-header {
      text-align: center;
      margin-bottom: 25px;
    }
    
    .register-header h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 8px;
      font-size: 1.8rem;
    }
    
    .register-header p {
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
      .register-box {
        padding: 20px;
      }
      
      .register-header h2 {
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
  <div class="register-container">
    <div class="register-box animate__animated animate__fadeInUp">
      <div class="register-header">
        <h2>Seller Registration</h2>
        <p>Create your account to start listing properties</p>
      </div>
      
      <form method="POST">
        <div class="mb-3">
          <label for="sname" class="form-label">Full Name</label>
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
              <i class="fas fa-user text-muted"></i>
            </span>
            <input type="text" name="sname" id="sname" class="form-control border-start-0" placeholder="Enter your full name" required>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="sphone" class="form-label">Phone Number</label>
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
              <i class="fas fa-phone text-muted"></i>
            </span>
            <input type="tel" name="sphone" id="sphone" class="form-control border-start-0" placeholder="Enter your phone number" maxlength="10" required>
          </div>
        </div>

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
            <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Create a password" required>
            <span class="input-group-text bg-light border-start-0 password-toggle" style="cursor: pointer;">
              <i class="fas fa-eye text-muted" id="togglePassword"></i>
            </span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary-custom animate__animated animate__fadeInUp">
          <i class="fas fa-user-plus me-2"></i>Create Account
        </button>
      </form>

      <div class="footer">
        Already have an account? <a href="seller_login.php">Login</a>
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
    
    // Restrict phone input to numbers only and maximum 10 digits
    document.getElementById('sphone').addEventListener('input', function(e) {
      // Remove any non-numeric characters
      this.value = this.value.replace(/[^0-9]/g, '');
      
      // Limit to 10 characters
      if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
      }
    });
  </script>
</body>
</html>