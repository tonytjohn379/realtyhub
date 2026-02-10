<?php
session_start();

$conn = new mysqli("localhost", "root", "", "realestate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bmail = trim($_POST['bmail']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM buyer WHERE bmail=? AND password=? AND status=1");
    $stmt->bind_param("ss", $bmail, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $buyer = $res->fetch_assoc();
        $_SESSION['buyer_id'] = $buyer['bid'];
        $_SESSION['bname'] = $buyer['bname'];
        header("Location: template/index.php");
        exit;
    } else {
        echo "<script>alert('Invalid email or password');</script>";
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
    <meta name="keywords" content="real estate, property, buyer login, property marketplace" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Buyer Login | Realty Hub</title>
    
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
        
        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            animation: fadeInUp 0.8s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            animation: slideInLeft 1s ease;
        }
        
        .login-header p {
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
        
        .btn-login {
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
            box-shadow: 0 10px 25px rgba(25, 118, 210, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(1px);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-footer a:hover {
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
            .login-container {
                padding: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-header">
            <h1><i class="fas fa-sign-in-alt me-2"></i>Buyer Login</h1>
            <p>Welcome back! Please enter your credentials</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="bmail"><i class="fas fa-envelope me-2"></i>Email Address</label>
                <input type="email" name="bmail" id="bmail" class="form-control" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Account
            </button>
        </form>
        
        <div class="login-footer">
            Don't have an account? <a href="buyer_register.php">Register Now</a>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
