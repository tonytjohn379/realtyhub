<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Schema Check</h1>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>✅ Database Connected</h2>";

// Check property table structure
echo "<h3>Property Table Columns:</h3>";
$result = $conn->query("DESCRIBE property");
if ($result) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><strong>{$row['Field']}</strong></td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Check category table
echo "<h3>Category Table Columns:</h3>";
$result = $conn->query("DESCRIBE category");
if ($result) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><strong>{$row['Field']}</strong></td><td>{$row['Type']}</td></tr>";
    }
    echo "</table>";
}

// Check district table
echo "<h3>District Table Columns:</h3>";
$result = $conn->query("DESCRIBE district");
if ($result) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><strong>{$row['Field']}</strong></td><td>{$row['Type']}</td></tr>";
    }
    echo "</table>";
}

// Check seller table
echo "<h3>Seller Table Columns:</h3>";
$result = $conn->query("DESCRIBE seller");
if ($result) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><strong>{$row['Field']}</strong></td><td>{$row['Type']}</td></tr>";
    }
    echo "</table>";
}

$conn->close();
?>
