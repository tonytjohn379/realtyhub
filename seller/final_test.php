<?php
session_start();
$_SESSION['seller_id'] = 22; // Set test seller ID

require('../config/autoload.php');

$dao = new DataAccess();

// Test data - minimal required fields only
$fields = [
    'sid' => 22,
    'cid' => 12,
    'did' => 17,
    'plocation' => 'Final Test Location',
    'pdescription' => 'Final test property description',
    'pprice' => 5000000,
    'pimage' => '',
    'status' => 1
];

echo "<pre>";
echo "Attempting to insert final test property...\n";
echo "Data: " . print_r($fields, true) . "\n";

$result = $dao->insert($fields, 'property');
if ($result) {
    echo "SUCCESS: Property inserted successfully!\n";
    echo "Property ID: " . $dao->getLastId('pid', 'property') . "\n";
} else {
    echo "ERROR: Failed to insert property\n";
    echo "Error: " . $dao->getErrors() . "\n";
    echo "Last query: " . $dao->last_query() . "\n";
}
echo "</pre>";
?>