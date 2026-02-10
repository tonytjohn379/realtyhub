<?php
session_start();

// If seller is already logged in, redirect to dashboard
if (isset($_SESSION['seller_id'])) {
    header("Location: template/index.php");
    exit;
}

// Otherwise, redirect to login page
header("Location: seller_login.php");
exit;
?>