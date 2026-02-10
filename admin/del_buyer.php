<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['bid'])) {
    $id = intval($_GET['bid']);
    
    // First delete any wishlist entries for this buyer (due to foreign key constraint)
    $sql_wishlist = "DELETE FROM wishlist WHERE bid=" . $id;
    $conn->query($sql_wishlist);
    
    // Then delete any buyer feedback entries
    $sql_feedback = "DELETE FROM feedback1 WHERE bid=" . $id;
    $conn->query($sql_feedback);
    
    // Then delete any buyer_feedback entries
    $sql_buyer_feedback = "DELETE FROM buyer_feedback WHERE bid=" . $id;
    $conn->query($sql_buyer_feedback);
    
    // Then delete any bookings for this buyer
    $sql_booking = "DELETE FROM booking WHERE bid=" . $id;
    $conn->query($sql_booking);
    
    // Finally delete the buyer itself
    $sql = "DELETE FROM buyer WHERE bid=" . $id;
    
    if($conn->query($sql)) {
        header('Location: viewbuyer.php');
        exit;
    } else {
        echo "Error deleting buyer: " . $conn->error;
    }
} else {
    header('Location: viewbuyer.php');
    exit;
}
?>