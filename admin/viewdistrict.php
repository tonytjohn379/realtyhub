<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 

$dao = new DataAccess();
$districts = $dao->getData('*', 'district');

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'Manage Districts';
    document.getElementById('menu-district').classList.add('active');
</script>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>District Management</h2>
    <a href="district.php" class="btn btn-primary-custom">
        <i class="fas fa-plus"></i> Add New District
    </a>
</div>

<div class="data-table">
    <h5><i class="fas fa-map-marked-alt"></i> All Districts</h5>

    <table class="table table-bordered table-striped text-center">
        <thead style="background-color: darkgreen; color: white;">
            <tr>
                <th>Sl.No</th>
                <th>District ID</th>
                <th>District Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($districts)) {
                $srno = 1;
                foreach ($districts as $row) {
                    echo "<tr>
                            <td>{$srno}</td>
                            <td>{$row['did']}</td>
                            <td>{$row['dname']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <a href='edit_district.php?did={$row['did']}' 
                                   class='btn-action btn-edit'>
                                   <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='del_district.php?did={$row['did']}' 
                                   class='btn-action btn-delete' 
                                   onclick=\"return confirm('Are you sure you want to delete this district?');\">
                                   <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                          </tr>";
                    $srno++;
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-danger'>No districts found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('includes/admin_footer.php'); ?>