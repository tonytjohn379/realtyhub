<?php
require('../config/autoload.php');

$dao = new DataAccess();

// Get the structure of the property table
$columns = $dao->query("DESCRIBE property");

echo "<h2>Property Table Structure:</h2>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

foreach ($columns as $column) {
    echo "<tr>";
    echo "<td>" . $column['Field'] . "</td>";
    echo "<td>" . $column['Type'] . "</td>";
    echo "<td>" . $column['Null'] . "</td>";
    echo "<td>" . $column['Key'] . "</td>";
    echo "<td>" . $column['Default'] . "</td>";
    echo "<td>" . $column['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Also check if seller_id exists
$seller_id_exists = false;
foreach ($columns as $column) {
    if ($column['Field'] == 'sid') {
        $seller_id_exists = true;
        echo "<p><strong>Seller ID field (sid) exists in the property table.</strong></p>";
        break;
    }
}

if (!$seller_id_exists) {
    echo "<p><strong>Seller ID field (sid) does NOT exist in the property table.</strong></p>";
}
?>