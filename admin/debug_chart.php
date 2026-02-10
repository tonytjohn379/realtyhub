<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../config/autoload.php');
$dao = new DataAccess();

// Debug the data
echo "<h2>Chart Debug Information</h2>";

// Check if we have districts
echo "<h3>Districts in Database:</h3>";
$districts = $dao->getData('*', 'district');
if (!empty($districts)) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Status</th></tr>";
    foreach($districts as $dist) {
        echo "<tr>";
        echo "<td>{$dist['did']}</td>";
        echo "<td>{$dist['dname']}</td>";
        echo "<td>{$dist['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No districts found!</p>";
}

// Check if we have properties
echo "<h3>Properties in Database:</h3>";
$properties = $dao->getData('*', 'property');
if (!empty($properties)) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>SID</th><th>DID</th><th>Location</th><th>Price</th></tr>";
    foreach($properties as $prop) {
        echo "<tr>";
        echo "<td>{$prop['pid']}</td>";
        echo "<td>{$prop['sid']}</td>";
        echo "<td>{$prop['did']}</td>";
        echo "<td>{$prop['plocation']}</td>";
        echo "<td>{$prop['pprice']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No properties found!</p>";
}

// Check the query we're using for the chart
echo "<h3>Chart Query Results:</h3>";
$chart_data = $dao->query("SELECT d.dname, COUNT(p.pid) as count 
                           FROM district d 
                           LEFT JOIN property p ON d.did = p.did 
                           WHERE d.status = 1
                           GROUP BY d.did, d.dname
                           HAVING count > 0
                           ORDER BY count DESC");

if (!empty($chart_data)) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>District</th><th>Property Count</th></tr>";
    foreach($chart_data as $row) {
        echo "<tr>";
        echo "<td>{$row['dname']}</td>";
        echo "<td>{$row['count']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>JSON Data for Chart:</h3>";
    echo "<p>Labels: " . json_encode(array_column($chart_data, 'dname')) . "</p>";
    echo "<p>Data: " . json_encode(array_column($chart_data, 'count')) . "</p>";
} else {
    echo "<p>No data for chart!</p>";
}

echo "<hr>";
echo "<a href='index.php'>Back to Dashboard</a>";
?>