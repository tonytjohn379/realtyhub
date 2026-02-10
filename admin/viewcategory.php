<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 

$dao = new DataAccess();
$categories = $dao->getData('*', 'category');

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'Manage Categories';
    document.getElementById('menu-category').classList.add('active');
</script>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2>Category Management</h2>
    <a href="category.php" class="btn btn-primary-custom">
        <i class="fas fa-plus"></i> Add New Category
    </a>
</div>

<div class="data-table">
    <h5><i class="fas fa-tags"></i> All Categories</h5>

    <table class="table table-bordered table-striped">
        <thead style="background-color: darkgreen; color: white;">
            <tr>
                <th>Sl.No</th>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($categories)) {
                $srno = 1;
                foreach ($categories as $row) {
                    echo "<tr>
                            <td>{$srno}</td>
                            <td>{$row['cid']}</td>
                            <td>{$row['cname']}</td>
                            <td>{$row['status']}</td>
                            <td>
                                <a href='edit_category.php?cid={$row['cid']}' 
                                   class='btn-action btn-edit'>
                                   <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='del_category.php?cid={$row['cid']}' 
                                   class='btn-action btn-delete' 
                                   onclick=\"return confirm('Are you sure you want to delete this Category?');\">
                                   <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                          </tr>";
                    $srno++;
                }
            } else {
                echo "<tr><td colspan='5' class='text-center text-danger'>No categories found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('includes/admin_footer.php'); ?>
