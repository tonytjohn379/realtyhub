<?php
require('./config/autoload.php');

$dao = new DataAccess();

// Test data with a long description
$fields = [
    'sid' => 19,
    'cid' => 3,
    'did' => 18,
    'pcent' => 0,
    'psqft' => 1200,
    'pbhk' => 3,
    'plocation' => 'cherthala',
    'pdescription' => 'semi furnished well water gated community having amenities like pool,gym,park 100 meter to super market',
    'pprice' => 4000000,
    'pimage' => null,
    'status' => 1
];

echo "<pre>";
echo "Testing property insertion with long description...\n";
echo "Data: " . print_r($fields, true) . "\n";

$result = $dao->insert($fields, 'property');
if ($result) {
    echo "SUCCESS: Property inserted!\n";
    
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