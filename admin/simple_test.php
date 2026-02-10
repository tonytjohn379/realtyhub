<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../config/autoload.php');
$dao = new DataAccess();

echo "<h1>Simple Chart Test</h1>";

// Simple test query
$result = $dao->query("SELECT d.dname, COUNT(p.pid) as count 
                       FROM district d 
                       LEFT JOIN property p ON d.did = p.did 
                       WHERE d.status = 1
                       GROUP BY d.did, d.dname
                       HAVING count > 0
                       ORDER BY count DESC");

echo "<h2>Query Results:</h2>";
if (!empty($result)) {
    echo "<ul>";
    foreach($result as $row) {
        echo "<li>" . $row['dname'] . ": " . $row['count'] . " properties</li>";
    }
    echo "</ul>";
    
    echo "<h3>JSON Data:</h3>";
    echo "<p>Labels: " . json_encode(array_column($result, 'dname')) . "</p>";
    echo "<p>Data: " . json_encode(array_column($result, 'count')) . "</p>";
} else {
    echo "<p>No data found!</p>";
}

echo "<hr>";
echo "<a href='debug_chart.php'>Full Debug</a> | <a href='index.php'>Dashboard</a>";
?>