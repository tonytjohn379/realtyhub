<?php
session_start();
require('../config/autoload.php');

// Set a test seller ID for testing
$_SESSION['seller_id'] = 22; // Using existing seller ID from the data

$dao = new DataAccess();

// Test data
$fields = [
    'sid' => 22,
    'cid' => 12,
    'did' => 17,
    'pcent' => 1500,
    'psqft' => 2500,
    'pbhk' => 3,
    'plocation' => 'Test Location',
    'pdescription' => 'Test property description',
    'pprice' => 5000000,
    'pimage' => '',
    'status' => 1
];

echo "<pre>";
echo "Attempting to insert test property...\n";
echo "Data: " . print_r($fields, true) . "\n";

$result = $dao->insert($fields, 'property');
if ($result) {
    echo "Property inserted successfully!\n";
} else {
    echo "Error inserting property: " . $dao->getErrors() . "\n";
    // Let's also check what the last query was
    echo "Last query: " . $dao->last_query() . "\n";
}
echo "</pre>";
?>