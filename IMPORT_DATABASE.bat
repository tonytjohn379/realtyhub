@echo off
echo ========================================
echo Database Import Script
echo ========================================
echo.

echo Importing database...
wsl -d Debian bash -c "mysql -u root realestate < '/opt/lampp/htdocs/tonyMCA/realestate (3).sql'"

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo Database imported successfully!
    echo ========================================
) else (
    echo.
    echo ========================================
    echo Error importing database!
    echo Make sure MySQL is running first.
    echo Run START_SERVER.bat first!
    echo ========================================
)

echo.
pause
