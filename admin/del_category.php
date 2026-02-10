<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['cid'])) {
    $id = intval($_GET['cid']);
    $sql = "DELETE FROM category WHERE cid=" . $id;
    
    if($conn->query($sql)) {
        header('Location: viewcategory.php');
        exit;
    } else {
        echo "Error deleting category: " . $conn->error;
    }
} else {
    header('Location: viewcategory.php');
    exit;
}
?>