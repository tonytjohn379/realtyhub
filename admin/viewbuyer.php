<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Fetch all buyers
$buyers = $dao->getData('*', 'buyer');

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'Manage Buyers';
    document.getElementById('menu-buyers').classList.add('active');
</script>

<div class="page-header">
    <h2>Buyer Management</h2>
</div>

<div class="data-table">
    <h5><i class="fas fa-users"></i> All Buyers</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Buyer ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Registered On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($buyers)) {
                    foreach ($buyers as $buyer) {
                        $status_badge = $buyer['status'] == 1 ? 'badge-active' : 'badge-inactive';
                        $status_text = $buyer['status'] == 1 ? 'Active' : 'Inactive';
                        
                        echo "<tr>
                                <td><strong>#{$buyer['bid']}</strong></td>
                                <td>{$buyer['bname']}</td>
                                <td>{$buyer['bphone']}</td>
                                <td>{$buyer['bmail']}</td>
                                <td>" . date('d M Y', strtotime($buyer['created_at'])) . "</td>
                                <td><span class='badge-status {$status_badge}'>{$status_text}</span></td>
                                <td>
                                    <a href='del_buyer.php?bid={$buyer['bid']}' 
                                       class='btn-action btn-delete' 
                                       onclick=\"return confirm('Are you sure you want to delete this buyer?');\">
                                       <i class='fas fa-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>No buyers found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/admin_footer.php'); ?>