<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Handle status update
if (isset($_POST['update_status'])) {
    $pid = (int)$_POST['pid'];
    $status = (int)$_POST['status'];
    
    // Get current status to check if it's changing from 1 to 0
    $property = $dao->getData('*', 'property', 'pid=' . $pid);
    $current_status = $property[0]['status'];
    
    // Update property status
    $data = ['status' => $status];
    if ($dao->update($data, 'property', 'pid=' . $pid)) {
        // If status is changing from 1 to 0, remove from all wishlists
        if ($current_status == 1 && $status == 0) {
            // Remove this property from all wishlists
            $dao->delete('wishlist', 'pid=' . $pid);
        }
        header("Location: viewproperty.php?msg=status_updated");
        exit;
    } else {
        header("Location: viewproperty.php?msg=status_error");
        exit;
    }
}

// Redirect back if no valid request
header("Location: viewproperty.php");
exit;
?>