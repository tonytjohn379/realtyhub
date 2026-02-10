<?php
require('config/database.php');

echo "<pre>";
echo "Database Configuration:\n";
echo "=====================\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_NAME: " . DB_NAME . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_PASSWORD: " . (DB_PASSWORD ? '****' : '(empty)') . "\n";

echo "\nTesting connection:\n";
echo "==================\n";

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error . "\n";
} else {
    echo "Connection successful!\n";
    
    // Test a simple query
    $result = $conn->query("SELECT COUNT(*) as count FROM property");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Property count: " . $row['count'] . "\n";
    } else {
        echo "Query failed: " . $conn->error . "\n";
    }
    
    $conn->close();
}
echo "</pre>";
?>