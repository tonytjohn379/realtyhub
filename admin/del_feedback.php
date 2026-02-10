<?php	
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include("dbconn.php");

if(isset($_GET['fid'])) {
    $id = intval($_GET['fid']);
    
    // Determine which table to delete from based on type parameter
    if (isset($_GET['type']) && $_GET['type'] == 'seller') {
        $sql = "DELETE FROM seller_feedback WHERE fid=" . $id;
    } else {
        $sql = "DELETE FROM feedback1 WHERE fid=" . $id;
    }
    
    if($conn->query($sql)) {
        header('Location: viewfeedback.php');
        exit;
    } else {
        echo "Error deleting feedback: " . $conn->error;
    }
} else {
    header('Location: viewfeedback.php');
    exit;
}
?>