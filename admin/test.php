<?php
// Simple test file to check PHP and database
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Test</h1>";

// Test 1: PHP is working
echo "<h2>✅ PHP is working!</h2>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 2: File path
echo "<h3>File Paths:</h3>";
echo "Current file: " . __FILE__ . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Test 3: Config file
echo "<h3>Testing Config:</h3>";
$config_path = '../config/autoload.php';
if (file_exists($config_path)) {
    echo "✅ Config file exists<br>";
    require($config_path);
    echo "✅ Config loaded successfully<br>";
    echo "BASE_PATH: " . BASE_PATH . "<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
} else {
    echo "❌ Config file not found at: " . realpath($config_path) . "<br>";
}

// Test 4: Database connection
echo "<h3>Testing Database:</h3>";
try {
    $dao = new DataAccess();
    echo "✅ DataAccess class instantiated<br>";
    
    // Test query
    $test = $dao->query("SELECT 1 as test");
    if ($test) {
        echo "✅ Database connection successful<br>";
    }
    
    // Count tables
    $properties = $dao->count('pid', 'property');
    echo "Total properties: " . $properties . "<br>";
    
    $sellers = $dao->count('sid', 'seller');
    echo "Total sellers: " . $sellers . "<br>";
    
    $buyers = $dao->count('bid', 'buyer');
    echo "Total buyers: " . $buyers . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test 5: Session
echo "<h3>Session Status:</h3>";
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    echo "✅ Admin is logged in<br>";
} else {
    echo "❌ Admin is NOT logged in<br>";
    echo "You need to login first at: <a href='login.php'>login.php</a><br>";
}

echo "<hr>";
echo "<a href='index.php'>Go to Dashboard</a> | <a href='login.php'>Login</a>";
?>
