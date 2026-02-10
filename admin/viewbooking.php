<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require('../config/autoload.php'); 
$dao = new DataAccess();

// Fetch all bookings with property and buyer details
$bookings = $dao->query("SELECT b.*, p.plocation, p.pprice, p.pimage, bu.bname, bu.bmail, bu.bphone 
                         FROM booking b 
                         LEFT JOIN property p ON b.pid = p.pid 
                         LEFT JOIN buyer bu ON b.bid = bu.bid 
                         ORDER BY b.booking_id DESC");

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'View Bookings';
    document.getElementById('menu-bookings').classList.add('active');
</script>

<div class="page-header">
    <h2>Booking Management</h2>
</div>

<div class="data-table">
    <h5><i class="fas fa-calendar-check"></i> All Bookings</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Property ID</th>
                    <th>Buyer Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Booking Date</th>
                    <th>Token Amount</th>
                    <th>Property Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach($bookings as $booking): ?>
                    <tr>
                        <td><strong>#<?php echo $booking['booking_id']; ?></strong></td>
                        <td><strong>#<?php echo $booking['pid']; ?></strong></td>
                        <td><?php echo htmlspecialchars($booking['bname'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($booking['bphone'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($booking['bmail'] ?? 'N/A'); ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['date'])); ?></td>
                        <td><span class="badge-status badge-active">₹<?php echo number_format($booking['token']); ?></span></td>
                        <td>₹<?php echo number_format($booking['pprice']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/admin_footer.php'); ?>
