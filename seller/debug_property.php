<?php
session_start();
// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    // Set a test seller ID for debugging
    $_SESSION['seller_id'] = 22;
    $_SESSION['sname'] = 'Test Seller';
}

require('../config/autoload.php');

$dao = new DataAccess();

// Fetch active categories and districts
$categories = $dao->getData('*', 'category', 'status=1');
$districts = $dao->getData('*', 'district', 'status=1');

echo "<pre>";
echo "Debug Information:\n";
echo "==================\n";
echo "Seller ID: " . (isset($_SESSION['seller_id']) ? $_SESSION['seller_id'] : 'Not set') . "\n";
echo "Categories count: " . count($categories) . "\n";
echo "Districts count: " . count($districts) . "\n";

// Display first category and district for reference
if (!empty($categories)) {
    echo "First category: " . $categories[0]['cname'] . " (ID: " . $categories[0]['cid'] . ")\n";
}
if (!empty($districts)) {
    echo "First district: " . $districts[0]['dname'] . " (ID: " . $districts[0]['did'] . ")\n";
}

// Handle form submission
if (isset($_POST['submit'])) {
    echo "\nForm Submission Data:\n";
    echo "====================\n";
    print_r($_POST);
    echo "\nFile Upload Data:\n";
    echo "================\n";
    print_r($_FILES);
    
    $sid = $_SESSION['seller_id'];
    $cid = isset($_POST['cid']) ? (int)$_POST['cid'] : 0;
    $did = isset($_POST['did']) ? (int)$_POST['did'] : 0;
    $pcent = isset($_POST['pcent']) ? (int)$_POST['pcent'] : 0;
    $psqft = isset($_POST['psqft']) ? (int)$_POST['psqft'] : 0;
    $pbhk = isset($_POST['pbhk']) ? (int)$_POST['pbhk'] : 0;
    $plocation = isset($_POST['plocation']) ? trim($_POST['plocation']) : '';
    $pdescription = isset($_POST['pdescription']) ? trim($_POST['pdescription']) : '';
    $pprice = isset($_POST['pprice']) ? (int)$_POST['pprice'] : 0;

    echo "\nProcessed Data:\n";
    echo "===============\n";
    echo "sid: $sid\n";
    echo "cid: $cid\n";
    echo "did: $did\n";
    echo "pcent: $pcent\n";
    echo "psqft: $psqft\n";
    echo "pbhk: $pbhk\n";
    echo "plocation: $plocation\n";
    echo "pdescription: $pdescription\n";
    echo "pprice: $pprice\n";

    // Validate required fields
    if (empty($cid) || empty($did) || empty($plocation) || empty($pdescription) || empty($pprice)) {
        echo "ERROR: Please fill in all required fields!\n";
    } else {
        // File upload
        $file = new FileUpload();
        $pimage = '';
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            echo "\nAttempting file upload...\n";
            $pimage = $file->doUploadRandom($_FILES['file'], ['.jpg', '.jpeg', '.png','.JPG','.PNG',], 5000, 0, '../uploads/');
            
            // Check for upload errors
            if ($pimage === false) {
                $uploadError = $file->errors();
                echo "FILE UPLOAD ERROR: " . $uploadError . "\n";
            } else {
                echo "File uploaded successfully: $pimage\n";
            }
        } else {
            echo "No file uploaded or file upload error\n";
            if (isset($_FILES['file'])) {
                echo "File error code: " . $_FILES['file']['error'] . "\n";
            }
        }

        $fields = [
            'sid' => $sid,
            'cid' => $cid,
            'did' => $did,
            'pcent' => $pcent,
            'psqft' => $psqft,
            'pbhk' => $pbhk,
            'plocation' => $plocation,
            'pdescription' => $pdescription,
            'pprice' => $pprice,
            'pimage' => $pimage,
            'status' => 1
        ];

        echo "\nInserting data:\n";
        echo "===============\n";
        print_r($fields);

        $result = $dao->insert($fields, 'property');
        if ($result) {
            echo "SUCCESS: Property added successfully!\n";
        } else {
            $error = $dao->getErrors();
            echo "ERROR: Failed to add property: " . $error . "\n";
            echo "Last query: " . $dao->last_query() . "\n";
        }
    }
}
echo "</pre>";

// Show form for testing
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Property Addition</title>
</head>
<body>
    <h2>Debug Property Form</h2>
    <form method="post" enctype="multipart/form-data">
        <p>
            <label>Category:</label>
            <select name="cid" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['cid'] ?>"><?= htmlspecialchars($cat['cname']) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label>District:</label>
            <select name="did" required>
                <option value="">-- Select District --</option>
                <?php foreach ($districts as $dist): ?>
                    <option value="<?= $dist['did'] ?>"><?= htmlspecialchars($dist['dname']) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label>Cent:</label>
            <input type="number" name="pcent" placeholder="Enter cent">
        </p>
        
        <p>
            <label>Sqft:</label>
            <input type="number" name="psqft" placeholder="Enter sqft">
        </p>
        
        <p>
            <label>BHK:</label>
            <input type="number" name="pbhk" placeholder="Enter number of BHK">
        </p>
        
        <p>
            <label>Location:</label>
            <input type="text" name="plocation" placeholder="Enter location" required>
        </p>
        
        <p>
            <label>Description:</label>
            <textarea name="pdescription" placeholder="Enter property description" required></textarea>
        </p>
        
        <p>
            <label>Price:</label>
            <input type="number" name="pprice" placeholder="Enter price" required>
        </p>
        
        <p>
            <label>Property Image:</label>
            <input type="file" name="file">
        </p>
        
        <p>
            <input type="submit" name="submit" value="Add Property">
        </p>
    </form>
</body>
</html>