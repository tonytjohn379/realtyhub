<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['did'])) {
    $id = intval($_GET['did']);
    $sql = "DELETE FROM district WHERE did=" . $id;
    
    if($conn->query($sql)) {
        header('Location: viewdistrict.php');
        exit;
    } else {
        echo "Error deleting district: " . $conn->error;
    }
} else {
    header('Location: viewdistrict.php');
    exit;
}
?>