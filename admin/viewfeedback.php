<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Fetch all feedback with user details
$seller_feedbacks = $dao->query("SELECT sf.*, s.sname 
                                 FROM seller_feedback sf 
                                 LEFT JOIN seller s ON sf.sid = s.sid 
                                 ORDER BY sf.created_at DESC");

$buyer_feedbacks = $dao->query("SELECT bf.*, b.bname 
                                FROM feedback1 bf 
                                LEFT JOIN buyer b ON bf.bid = b.bid 
                                ORDER BY bf.created_at DESC");

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'View Feedback';
    document.getElementById('menu-feedback').classList.add('active');
</script>

<div class="page-header">
    <h2>Feedback Management</h2>
</div>

<!-- Seller Feedback -->
<div class="data-table">
    <h5><i class="fas fa-star"></i> Seller Feedback</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Seller Name</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($seller_feedbacks)) {
                    foreach ($seller_feedbacks as $row) {
                        $stars = str_repeat('⭐', $row['rating']);
                        echo "<tr>
                                <td><strong>#{$row['fid']}</strong></td>
                                <td>{$row['sname']}</td>
                                <td>{$stars} ({$row['rating']}/5)</td>
                                <td>" . htmlspecialchars($row['feedback_text']) . "</td>
                                <td>" . date('d M Y', strtotime($row['created_at'])) . "</td>
                                <td>
                                    <a href='del_feedback.php?fid={$row['fid']}&type=seller' 
                                       class='btn-action btn-delete' 
                                       onclick=\"return confirm('Are you sure you want to delete this feedback?');\">
                                       <i class='fas fa-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>No seller feedback found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Buyer Feedback -->
<div class="data-table mt-4">
    <h5><i class="fas fa-comment-dots"></i> Buyer Feedback</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Buyer Name</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($buyer_feedbacks)) {
                    foreach ($buyer_feedbacks as $row) {
                        $stars = str_repeat('⭐', $row['rating']);
                        echo "<tr>
                                <td><strong>#{$row['fid']}</strong></td>
                                <td>{$row['bname']}</td>
                                <td>{$stars} ({$row['rating']}/5)</td>
                                <td>" . htmlspecialchars($row['feedback_text']) . "</td>
                                <td>" . date('d M Y', strtotime($row['created_at'])) . "</td>
                                <td>
                                    <a href='del_feedback.php?fid={$row['fid']}&type=buyer' 
                                       class='btn-action btn-delete' 
                                       onclick=\"return confirm('Are you sure you want to delete this feedback?');\">
                                       <i class='fas fa-trash'></i> Delete
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>No buyer feedback found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/admin_footer.php'); ?>