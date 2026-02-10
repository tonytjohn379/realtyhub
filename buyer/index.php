<?php
session_start();

// If buyer is already logged in, redirect to dashboard
if (isset($_SESSION['buyer_id'])) {
    header("Location: template/index.php");
    exit;
}

// Otherwise, redirect to login page
header("Location: buyer_login.php");
exit;
?>