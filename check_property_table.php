<?php
require('config/autoload.php');

$dao = new DataAccess();

// Get table structure
echo "<pre>";
echo "Property Table Structure:\n";
echo "========================\n";

// Get column information
$columns = $dao->query("DESCRIBE property");
if ($columns) {
    foreach ($columns as $column) {
        echo "Field: " . $column['Field'] . " | ";
        echo "Type: " . $column['Type'] . " | ";
        echo "Null: " . $column['Null'] . " | ";
        echo "Key: " . $column['Key'] . " | ";
        echo "Default: " . $column['Default'] . " | ";
        echo "Extra: " . $column['Extra'] . "\n";
    }
} else {
    echo "Error getting table structure: " . $dao->getErrors() . "\n";
}

echo "\nTesting field validation:\n";
echo "========================\n";

// Test field validation
$test_fields = [
    'sid' => 22,
    'cid' => 12,
    'did' => 17,
    'plocation' => 'Test Location',
    'pdescription' => 'Test property description',
    'pprice' => 5000000,
    'pimage' => '',
    'status' => 1
];

echo "Testing fields:\n";
print_r($test_fields);

// Check if fields are valid
$check_result = $dao->insert($test_fields, 'property');
if ($check_result) {
    echo "SUCCESS: Property inserted!\n";
} else {
    echo "ERROR: " . $dao->getErrors() . "\n";
    echo "Last query: " . $dao->last_query() . "\n";
}

// Let's also test what fields the DataAccess class thinks exist
echo "\nChecking field validation manually:\n";
echo "==================================\n";

$field_check = $dao->_checkFields($test_fields, 'property');
echo "Field check result: " . ($field_check ? 'Valid' : 'Invalid') . "\n";
if (!$field_check) {
    echo "Errors: " . $dao->getErrors() . "\n";
}

echo "</pre>";
?>