<?php
echo "<pre>";
echo "Current directory: " . getcwd() . "\n";
echo "Uploads directory exists: " . (is_dir('uploads') ? 'Yes' : 'No') . "\n";
echo "Uploads directory writable: " . (is_writable('uploads') ? 'Yes' : 'No') . "\n";
echo "Directory permissions: " . (is_dir('uploads') ? substr(sprintf('%o', fileperms('uploads')), -4) : 'N/A') . "\n";

// List files in uploads directory
if (is_dir('uploads')) {
    $files = scandir('uploads');
    echo "Files in uploads directory:\n";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}
echo "</pre>";
?>