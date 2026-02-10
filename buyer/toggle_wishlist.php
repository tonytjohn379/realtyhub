<?php
session_start();
require('../config/autoload.php'); // Adjust path as necessary

$dao = new DataAccess();
$buyer_id = isset($_SESSION['buyer_id']) ? $_SESSION['buyer_id'] : 0;

header('Content-Type: text/plain'); // Ensure the response is treated as plain text

// 1. Check if the user is logged in
if (!$buyer_id) {
    echo 'not_logged_in';
    exit();
}

// 2. Check for the property_id POST data
if (!isset($_POST['property_id']) || !is_numeric($_POST['property_id'])) {
    echo 'error: invalid property_id';
    exit();
}

$property_id = (int)$_POST['property_id'];

// Check if property exists and is active before any operation
$property_exists = $dao->getData('pid', 'property', "pid = $property_id AND status = 1");

if (empty($property_exists)) {
    echo 'error: property does not exist or is inactive';
    exit();
}

// 3. Check if the property is already in the wishlist
$condition = "bid = $buyer_id AND pid = $property_id";
$check_wishlist = $dao->getData('wid', 'wishlist', $condition);

if (!empty($check_wishlist)) {
    // 4. Property exists: REMOVE from wishlist
    if ($dao->delete('wishlist', $condition)) {
        echo 'removed';
    } else {
        echo 'error: removal failed';
    }
} else {
    // 5. Property does not exist: ADD to wishlist
    $data = array(
        'bid' => $buyer_id,
        'pid' => $property_id,
        // 'added_on' field is a timestamp and often auto-set in SQL, 
        // but can be added here if needed: 'added_on' => date('Y-m-d H:i:s')
    );
    
    if ($dao->insert($data, 'wishlist')) {
        echo 'added';
    } else {
        echo 'error: insertion failed';
    }
}
?>