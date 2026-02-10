<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['pid'])) {
    $id = intval($_GET['pid']);
    
    // First delete any wishlist entries for this property (due to foreign key constraint)
    $sql_wishlist = "DELETE FROM wishlist WHERE pid=" . $id;
    $conn->query($sql_wishlist);
    
    // Then delete any bookings for this property
    $sql_booking = "DELETE FROM booking WHERE pid=" . $id;
    $conn->query($sql_booking);
    
    // Finally delete the property itself
    $sql = "DELETE FROM property WHERE pid=" . $id;
    
    if($conn->query($sql)) {
        header('Location: viewproperty.php');
        exit;
    } else {
        echo "Error deleting property: " . $conn->error;
    }
} else {
    header('Location: viewproperty.php');
    exit;
}
?>