<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Migration Script</h1>";
echo "<p>This will update your property table columns from 'l*' to 'p*' format</p>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Step 1: Checking current property table structure...</h2>";

// Check if old columns exist
$result = $conn->query("DESCRIBE property");
$columns = [];
while($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

echo "<p>Current columns: " . implode(", ", $columns) . "</p>";

// Determine if we need to migrate
$needsMigration = in_array('lid', $columns);
$alreadyMigrated = in_array('pid', $columns);

if ($alreadyMigrated && !$needsMigration) {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<h3>✅ Database is already up to date!</h3>";
    echo "<p>Your property table already uses the correct column names (pid, ptype, etc.)</p>";
    echo "<p><a href='index.php'>Go to Dashboard</a></p>";
    echo "</div>";
    exit;
}

if (!$needsMigration && !$alreadyMigrated) {
    echo "<div style='background: #fff3cd; padding: 15px; border: 1px solid #ffeaa7; border-radius: 5px;'>";
    echo "<h3>⚠️ Property table structure is unclear</h3>";
    echo "<p>Please check your database manually or import the correct SQL file.</p>";
    echo "</div>";
    exit;
}

echo "<h2>Step 2: Starting migration...</h2>";

// SQL commands to rename columns
$migrations = [
    "ALTER TABLE `property` CHANGE `lid` `pid` INT(11) NOT NULL AUTO_INCREMENT",
    "ALTER TABLE `property` CHANGE `ltype` `ptype` INT(11) NOT NULL",
    "ALTER TABLE `property` CHANGE `ldistrict` `pdistrict` INT(11) NOT NULL",
    "ALTER TABLE `property` CHANGE `lcent` `pcent` INT(11) DEFAULT NULL",
    "ALTER TABLE `property` CHANGE `lsqft` `psqft` INT(11) DEFAULT NULL",
    "ALTER TABLE `property` CHANGE `lbhk` `pbhk` INT(11) DEFAULT NULL",
    "ALTER TABLE `property` CHANGE `llocation` `plocation` VARCHAR(50) NOT NULL",
    "ALTER TABLE `property` CHANGE `ldescription` `pdescription` VARCHAR(100) NOT NULL",
    "ALTER TABLE `property` CHANGE `lprice` `pprice` INT(11) NOT NULL",
    "ALTER TABLE `property` CHANGE `limage` `pimage` VARCHAR(500) NOT NULL"
];

$success = 0;
$errors = 0;

foreach ($migrations as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✅ " . htmlspecialchars($sql) . "</p>";
        $success++;
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
        echo "<p style='color: red;'>SQL: " . htmlspecialchars($sql) . "</p>";
        $errors++;
    }
}

echo "<h2>Migration Complete!</h2>";
echo "<p><strong>Successful:</strong> $success</p>";
echo "<p><strong>Errors:</strong> $errors</p>";

if ($errors == 0) {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3>✅ Migration Successful!</h3>";
    echo "<p>Your database has been updated. Column names changed:</p>";
    echo "<ul>";
    echo "<li>lid → pid</li>";
    echo "<li>ltype → ptype</li>";
    echo "<li>ldistrict → pdistrict</li>";
    echo "<li>lcent → pcent</li>";
    echo "<li>lsqft → psqft</li>";
    echo "<li>lbhk → pbhk</li>";
    echo "<li>llocation → plocation</li>";
    echo "<li>ldescription → pdescription</li>";
    echo "<li>lprice → pprice</li>";
    echo "<li>limage → pimage</li>";
    echo "</ul>";
    echo "<p><strong>Next step:</strong> <a href='index.php' style='font-size: 18px; color: #036621;'>Go to Admin Dashboard</a></p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3>⚠️ Migration completed with errors</h3>";
    echo "<p>Some columns may not have been updated. Check the errors above.</p>";
    echo "<p>You may need to manually update the database or reimport the SQL file.</p>";
    echo "</div>";
}

$conn->close();
?>

<hr>
<p><a href="check_db.php">Check Database Structure</a> | <a href="test.php">Run Test</a> | <a href="login.php">Login</a></p>
