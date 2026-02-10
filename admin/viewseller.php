<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Fetch all sellers with their property count
$sellers = $dao->query("SELECT s.*, COUNT(p.pid) as property_count 
                        FROM seller s 
                        LEFT JOIN property p ON s.sid = p.sid 
                        GROUP BY s.sid 
                        ORDER BY s.sid DESC");

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'Manage Sellers';
    document.getElementById('menu-sellers').classList.add('active');
</script>

<div class="page-header">
    <h2>Seller Management</h2>
</div>

<div class="data-table">
    <h5><i class="fas fa-user-tie"></i> All Sellers</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Seller ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Properties</th>
                    <th>Registered On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($sellers)) {
                    foreach ($sellers as $seller) {
                        $status_badge = $seller['status'] == 1 ? 'badge-active' : 'badge-inactive';
                        $status_text = $seller['status'] == 1 ? 'Active' : 'Inactive';
                        
                        echo "<tr>
                                <td><strong>#{$seller['sid']}</strong></td>
                                <td>{$seller['sname']}</td>
                                <td>{$seller['sphone']}</td>
                                <td>{$seller['smail']}</td>
                                <td><span class='badge-status badge-active'>{$seller['property_count']}</span></td>
                                <td>" . date('d M Y', strtotime($seller['created_at'])) . "</td>
                                <td><span class='badge-status {$status_badge}'>{$status_text}</span></td>
                                <td>
                                    <a href='del_seller.php?sid={$seller['sid']}' 
                                       class='btn-action btn-delete' 
                                       onclick=\"return confirm('Are you sure you want to delete this seller?');\">
                                       <i class='fas fa-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center text-muted'>No sellers found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/admin_footer.php'); ?>