@echo off
echo ========================================
echo Real Estate Management System - Startup
echo ========================================
echo.

echo Starting LAMPP services...
wsl -d Debian sudo /opt/lampp/lampp start

echo.
echo Waiting for services to start...
timeout /t 3 >nul

echo.
echo Checking service status...
wsl -d Debian sudo /opt/lampp/lampp status

echo.
echo ========================================
echo Services Started!
echo ========================================
echo.
echo Access URLs:
echo   Admin:  http://localhost/tonyMCA/admin/login.php
echo   Test:   http://localhost/tonyMCA/admin/test.php
echo   Buyer:  http://localhost/tonyMCA/buyer/buyer_login.php
echo   Seller: http://localhost/tonyMCA/seller/seller_login.php
echo.
echo Admin Login Credentials:
echo   Username: admin
echo   Password: 12345
echo.
echo Press any key to open admin panel in browser...
pause >nul
start http://localhost/tonyMCA/admin/login.php
