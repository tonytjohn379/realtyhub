<?php
session_start();
require('../config/autoload.php'); // !! CHECK THIS PATH !!

$dao = new DataAccess();

// 1. Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: ../buyer_login.php");
    exit;
}

$buyer_id = (int)$_SESSION['buyer_id'];

// 2. Check for the wishlist ID (wid)
if (!isset($_POST['wid']) || !is_numeric($_POST['wid'])) {
    header("Location: wishlist.php?msg=invalid_id");
    exit;
}

$wid = (int)$_POST['wid'];

// 3. Delete the item, ensuring it belongs to the logged-in buyer for SECURITY
$condition = "wid = $wid AND bid = $buyer_id";

if ($dao->delete('wishlist', $condition)) {
    // Success: Redirect back to the wishlist page with a success message
    header("Location: wishlist.php?msg=removed");
} else {
    // Failure: Redirect back with an error message
    header("Location: wishlist.php?msg=error_removal");
}
exit();
?>