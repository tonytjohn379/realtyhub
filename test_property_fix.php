<?php
session_start();
require('./config/autoload.php');

// Set a test seller ID for testing
$_SESSION['seller_id'] = 22; // Using existing seller ID from the data

$dao = new DataAccess();

// Test data without image (should work now with NULL pimage)
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
    'pimage' => null,
    'status' => 1
];

echo "<pre>";
echo "Testing property insertion without image...\n";
echo "Data: " . print_r($fields, true) . "\n";

$result = $dao->insert($fields, 'property');
if ($result) {
    echo "SUCCESS: Property inserted without image!\n";
    
    // Get the last inserted ID
    $lastId = $dao->getLastId('pid', 'property');
    echo "Inserted property ID: " . $lastId . "\n";
    
    // Clean up - delete the test property
    $dao->delete('property', "pid = $lastId");
    echo "Cleaned up test property.\n";
} else {
    echo "ERROR: " . $dao->getErrors() . "\n";
    echo "Last query: " . $dao->last_query() . "\n";
}

// Test with image
echo "\nTesting property insertion with image...\n";
$fields['pimage'] = 'default.jpg';

$result = $dao->insert($fields, 'property');
if ($result) {
    echo "SUCCESS: Property inserted with image!\n";
    
    // Get the last inserted ID
    $lastId = $dao->getLastId('pid', 'property');
    echo "Inserted property ID: " . $lastId . "\n";
    
    // Clean up - delete the test property
    $dao->delete('property', "pid = $lastId");
    echo "Cleaned up test property.\n";
} else {
    echo "ERROR: " . $dao->getErrors() . "\n";
    echo "Last query: " . $dao->last_query() . "\n";
}

echo "</pre>";
?>