<?php
session_start();
require('../config/autoload.php');

$dao = new DataAccess();

// Seller ID from session
$seller_id = $_SESSION['seller_id'];

// Fetch properties for this seller
$properties = $dao->getData('*', 'property', 'sid=' . $seller_id);

echo "<h2>Property Image Test</h2>";

foreach ($properties as $property) {
    echo "<div style='margin: 20px 0; padding: 20px; border: 1px solid #ccc;'>";
    echo "<h3>Property ID: " . $property['pid'] . "</h3>";
    echo "<p>Image name in DB: " . $property['pimage'] . "</p>";
    
    $imagePath = "../uploads/" . $property['pimage'];
    echo "<p>Constructed path: " . $imagePath . "</p>";
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/tonyMCA/uploads/" . $property['pimage'])) {
        echo "<p style='color: green;'>File exists on server</p>";
        echo "<img src='" . $imagePath . "' style='max-width: 300px;'>";
    } else {
        echo "<p style='color: red;'>File does not exist on server</p>";
    }
    echo "</div>";
}
?>