<?php 
/**
 * Cleanup Script - Removes wishlist entries for inactive properties
 * This script can be run manually or scheduled as a cron job
 */

// This script can only be run from command line or with a special key for security
$secret_key = 'cleanup_wishlists_2025';

// Check if running from command line or with correct key
if (php_sapi_name() !== 'cli') {
    if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
        http_response_code(403);
        die('Access denied');
    }
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Remove all wishlist entries for properties with status = 0
$query = "DELETE w FROM wishlist w 
          JOIN property p ON w.pid = p.pid 
          WHERE p.status = 0";

$result = $dao->query($query);

if ($result) {
    $deleted_count = mysqli_affected_rows($dao->_con);
    echo "Cleanup completed successfully. Removed $deleted_count wishlist entries for inactive properties.\n";
} else {
    echo "Error during cleanup: " . $dao->getErrors() . "\n";
}

?>