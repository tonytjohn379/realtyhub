<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['sid'])) {
    $id = intval($_GET['sid']);
    $sql = "DELETE FROM seller WHERE sid=" . $id;
    
    if($conn->query($sql)) {
        header('Location: viewseller.php');
        exit;
    } else {
        echo "Error deleting seller: " . $conn->error;
    }
} else {
    header('Location: viewseller.php');
    exit;
}
?>