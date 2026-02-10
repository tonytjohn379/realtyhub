<?php
session_start();

$conn = new mysqli("localhost", "root", "", "realestate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bname = trim($_POST['bname']);
    $bphone = trim($_POST['bphone']);
    $bmail = trim($_POST['bmail']);
    $password = trim($_POST['password']);

    $check = $conn->prepare("SELECT * FROM buyer WHERE bmail=?");
    $check->bind_param("s", $bmail);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "<script>alert('Email already registered');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO buyer (bname, bphone, password, bmail, status) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $bname, $bphone, $password, $bmail);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='buyer_login.php';</script>";
        } else {
            echo "<script>alert('Error during registration');</script>";
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
    <meta name="keywords" content="real estate, property, buyer registration, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Buyer Registration | Realty Hub</title>
    
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
            animation: fadeInUp 0.8s ease;
            position: relative;
            overflow: hidden;
        }
        
        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .register-header p {
            color: #6c757d;
            font-size: 1.1rem;
            animation: slideInRight 1s ease;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--secondary);
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
            outline: 0;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 15px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
            width: 100%;
            color: white;
            font-size: 1.1rem;
        }
        
        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }
        
        .btn-register:hover::before {
            left: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-register:active {
            transform: translateY(1px);
        }
        
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .register-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Animations */
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
        
        @media (max-width: 768px) {
            .register-container {
                padding: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container animate__animated animate__fadeIn">
        <div class="register-header">
            <h1><i class="fas fa-user-plus me-2"></i>Buyer Registration</h1>
            <p>Create your account to start browsing properties</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="bname"><i class="fas fa-user me-2"></i>Full Name</label>
                <input type="text" name="bname" id="bname" class="form-control" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label for="bphone"><i class="fas fa-phone me-2"></i>Phone Number</label>
                <input type="text" name="bphone" id="bphone" class="form-control" required pattern="\d{10}" maxlength="10"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);" 
                       title="Enter a valid 10-digit phone number" placeholder="Enter your phone number">
            </div>
            
            <div class="form-group">
                <label for="bmail"><i class="fas fa-envelope me-2"></i>Email Address</label>
                <input type="email" name="bmail" id="bmail" class="form-control" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Create a password">
            </div>
            
            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </form>
        
        <div class="register-footer">
            Already have an account? <a href="buyer_login.php">Login Now</a>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
