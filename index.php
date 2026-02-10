<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realty Hub - Real Estate Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            background: linear-gradient(135deg, #024f1a 0%, #036621 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            padding: 2rem 1rem;
            background: rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            flex: 1;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .welcome-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .welcome-section p {
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 1.5rem;
        }

        .user-options {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .user-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 2.5rem;
            width: 350px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .user-card i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .user-card h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .user-card p {
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #fff;
            color: #024f1a;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid #fff;
        }

        .btn:hover {
            background: transparent;
            color: #fff;
            transform: scale(1.05);
        }

        .btn-buyer {
            background: #024f1a;
            color: white;
        }

        .btn-buyer:hover {
            background: transparent;
            color: #024f1a;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #fff;
        }

        .feature-card h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            background: rgba(0, 0, 0, 0.2);
            margin-top: auto;
        }

        .footer p {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .user-options {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }

            .user-card {
                width: 100%;
                max-width: 350px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-building"></i> Realty Hub</h1>
        <p>Your Trusted Real Estate Management Platform</p>
    </div>

    <div class="container">
        <div class="welcome-section">
            <h2>Welcome to Realty Hub</h2>
            <p>Discover your dream property or list your real estate with our comprehensive platform. Whether you're looking to buy, sell, or rent properties, we provide the tools and expertise to make your real estate journey seamless.</p>
        </div>

        <div class="user-options">
            <div class="user-card">
                <i class="fas fa-user-tie"></i>
                <h3>Property Seller</h3>
                <p>List your properties, manage listings, and connect with potential buyers through our comprehensive seller dashboard.</p>
                <a href="seller/" class="btn btn-seller"><i class="fas fa-arrow-right"></i> Access Seller Portal</a>
            </div>

            <div class="user-card">
                <i class="fas fa-users"></i>
                <h3>Property Buyer</h3>
                <p>Browse listings, book properties, and manage your real estate transactions with our intuitive buyer platform.</p>
                <a href="buyer/" class="btn btn-buyer"><i class="fas fa-arrow-right"></i> Access Buyer Portal</a>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-search"></i>
                <h4>Advanced Search</h4>
                <p>Find properties with our powerful search filters and detailed property information.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-calendar-check"></i>
                <h4>Easy Booking</h4>
                <p>Schedule property visits and manage bookings with our streamlined booking system.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h4>Secure Transactions</h4>
                <p>Enjoy peace of mind with our secure payment system and verified user profiles.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Realty Hub. All Rights Reserved.</p>
    </div>
</body>
</html>