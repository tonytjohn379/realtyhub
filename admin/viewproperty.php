<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

include('includes/admin_header.php');

// Handle messages
$message = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'status_updated':
            $message = '<div class="alert alert-success">Property status updated successfully!</div>';
            break;
        case 'status_error':
            $message = '<div class="alert alert-danger">Error updating property status!</div>';
            break;
    }
}
?>

<script>
    document.getElementById('page-title').innerText = 'View Properties';
    document.getElementById('menu-properties').classList.add('active');
</script>

<div class="page-header">
    <h2>Property Management</h2>
</div>

<?php echo $message; ?>

<table class="table table-bordered table-striped text-center">
    <thead style="background-color: darkgreen; color: white;">
        <tr>
            <th>Property ID</th>
            <th>Seller</th>
            <th>Type</th>
            <th>District</th>
            <th>Cent</th>
            <th>Sqft</th>
            <th>BHK</th>
            <th>Location</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $properties = $dao->getData('*', 'property'); // fetch all from property table
    if(!empty($properties)) {
        foreach($properties as $prop) {
            // Get category name
            $category = $dao->getData('cname', 'category', 'cid=' . $prop['cid']);
            $category_name = !empty($category) ? $category[0]['cname'] : 'Unknown';
            
            // Get district name
            $district = $dao->getData('dname', 'district', 'did=' . $prop['did']);
            $district_name = !empty($district) ? $district[0]['dname'] : 'Unknown';
            
            // Get seller name
            $seller = $dao->getData('sname', 'seller', 'sid=' . $prop['sid']);
            $seller_name = !empty($seller) ? $seller[0]['sname'] : 'Unknown';
            
            // Status display
            $status_text = $prop['status'] == 1 ? 'Active' : 'Inactive';
            $status_class = $prop['status'] == 1 ? 'success' : 'danger';
            
            // Action button text
            $action_text = $prop['status'] == 1 ? 'Deactivate' : 'Activate';
            $action_class = $prop['status'] == 1 ? 'warning' : 'success';
            $new_status = $prop['status'] == 1 ? 0 : 1;
            
            echo "<tr>
                <td>{$prop['pid']}</td>
                <td>{$seller_name} (#{$prop['sid']})</td>
                <td>{$category_name}</td>
                <td>{$district_name}</td>
                <td>{$prop['pcent']}</td>
                <td>{$prop['psqft']}</td>
                <td>{$prop['pbhk']}</td>
                <td>{$prop['plocation']}</td>
                <td>{$prop['pdescription']}</td>
                <td>{$prop['pprice']}</td>
                <td><img src='../uploads/{$prop['pimage']}' width='100'></td>
                <td><span class='badge bg-{$status_class}'>{$status_text}</span></td>
                <td>
                    <div class='property-actions'>
                        <form method='POST' action='change_property_status.php' style='display:inline;'>
                            <input type='hidden' name='pid' value='{$prop['pid']}'>
                            <input type='hidden' name='status' value='{$new_status}'>
                            <input type='submit' name='update_status' value='{$action_text}' class='btn btn-{$action_class} btn-sm'>
                        </form>
                        <a href='del_property.php?pid={$prop['pid']}' class='btn btn-danger btn-sm'
                            onclick=\"return confirm('Are you sure you want to delete this property?');\">Delete</a>
                    </div>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='13' class='text-center text-danger'>No properties found</td></tr>";
    }
    ?>
    </tbody>
</table>
</div>

<?php include('includes/admin_footer.php'); ?>