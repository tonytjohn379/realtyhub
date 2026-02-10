<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

try {
    require('../config/autoload.php'); 
    $dao = new DataAccess();
} catch (Exception $e) {
    die("Error loading config: " . $e->getMessage());
}

try {
    // Fetch statistics
    $total_properties = $dao->count('pid', 'property');
    $total_sellers = $dao->count('sid', 'seller');
    $total_buyers = $dao->count('bid', 'buyer');
    $total_bookings = $dao->count('booking_id', 'booking');
    $total_categories = $dao->count('cid', 'category', 'status=1');
    $total_districts = $dao->count('did', 'district', 'status=1');
} catch (Exception $e) {
    // Set defaults if query fails
    $total_properties = 0;
    $total_sellers = 0;
    $total_buyers = 0;
    $total_bookings = 0;
    $total_categories = 0;
    $total_districts = 0;
    error_log("Error fetching statistics: " . $e->getMessage());
}

try {
    // Get recent properties
    $recent_properties = $dao->query("SELECT p.*, c.cname, d.dname, s.sname 
                                      FROM property p 
                                      LEFT JOIN category c ON p.cid = c.cid 
                                      LEFT JOIN district d ON p.did = d.did 
                                      LEFT JOIN seller s ON p.sid = s.sid 
                                      ORDER BY p.pid DESC LIMIT 5");
} catch (Exception $e) {
    $recent_properties = [];
    error_log("Error fetching recent properties: " . $e->getMessage());
}

try {
    // Get recent bookings
    $recent_bookings = $dao->query("SELECT b.*, p.plocation, p.pprice, bu.bname 
                                    FROM booking b 
                                    LEFT JOIN property p ON b.pid = p.pid 
                                    LEFT JOIN buyer bu ON b.bid = bu.bid 
                                    ORDER BY b.booking_id DESC LIMIT 5");
} catch (Exception $e) {
    $recent_bookings = [];
    error_log("Error fetching recent bookings: " . $e->getMessage());
}

try {
    // Get district-wise property registration count directly from property table
    $property_by_district_all = $dao->query("SELECT d.dname, COUNT(p.pid) as count 
                                              FROM district d 
                                              LEFT JOIN property p ON d.did = p.did 
                                              WHERE d.status = 1
                                              GROUP BY d.did, d.dname
                                              HAVING count > 0
                                              ORDER BY count DESC");
} catch (Exception $e) {
    $property_by_district_all = [];
    error_log("Error fetching district property stats: " . $e->getMessage());
}

include('includes/admin_header.php');
?>

<script>
    document.getElementById('page-title').innerText = 'Dashboard';
    document.getElementById('menu-dashboard').classList.add('active');
</script>

<!-- Dashboard Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
            <div class="stats-icon">
                <i class="fas fa-home"></i>
            </div>
            <h3><?php echo $total_properties; ?></h3>
            <p>Total Properties</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="stats-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3><?php echo $total_sellers; ?></h3>
            <p>Total Sellers</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card info">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3><?php echo $total_buyers; ?></h3>
            <p>Total Buyers</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="stats-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3><?php echo $total_bookings; ?></h3>
            <p>Total Bookings</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="chart-card">
            <h5><i class="fas fa-chart-pie"></i> District-wise Property Registration</h5>
            <div style="height: 400px; position: relative;">
                <canvas id="propertyDistrictChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Properties -->
<div class="data-table">
    <h5><i class="fas fa-home"></i> Recent Properties</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>District</th>
                    <th>Seller</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_properties)): ?>
                    <?php foreach($recent_properties as $property): ?>
                    <tr>
                        <td><?php echo $property['pid']; ?></td>
                        <td><?php echo htmlspecialchars($property['plocation'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($property['cname'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($property['dname'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($property['sname'] ?? 'Unknown'); ?></td>
                        <td>₹<?php echo number_format($property['pprice']); ?></td>
                        <td>
                            <a href="viewproperty.php" class="btn-action btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No properties found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Bookings -->
<div class="data-table">
    <h5><i class="fas fa-calendar-check"></i> Recent Bookings</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Property ID</th>
                    <th>Buyer</th>
                    <th>Date</th>
                    <th>Token</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_bookings)): ?>
                    <?php foreach($recent_bookings as $booking): ?>
                    <tr>
                        <td>#<?php echo $booking['booking_id']; ?></td>
                        <td>#<?php echo $booking['pid']; ?></td>
                        <td><?php echo htmlspecialchars($booking['bname'] ?? 'Unknown'); ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['date'])); ?></td>
                        <td>₹<?php echo number_format($booking['token']); ?></td>
                        <td><span class="badge-status badge-active">Booked</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Stats Row -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="chart-card">
            <h5><i class="fas fa-info-circle"></i> System Overview</h5>
            <div class="row">
                <div class="col-6 mb-3">
                    <div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px;">
                        <h4 class="text-primary-custom"><?php echo $total_categories; ?></h4>
                        <p class="mb-0">Categories</p>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px;">
                        <h4 class="text-primary-custom"><?php echo $total_districts; ?></h4>
                        <p class="mb-0">Districts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="chart-card">
            <h5><i class="fas fa-tasks"></i> Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="category.php" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Add Category
                </a>
                <a href="district.php" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Add District
                </a>
                <a href="viewfeedback.php" class="btn btn-outline-info">
                    <i class="fas fa-comments"></i> View Feedback
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Prepare data for district-wise property registration chart
const propertyDistrictLabels = <?php echo json_encode(array_column($property_by_district_all, 'dname')); ?>;
const propertyDistrictData = <?php echo json_encode(array_map('intval', array_column($property_by_district_all, 'count'))); ?>;

console.log('Property Labels:', propertyDistrictLabels);
console.log('Property Data:', propertyDistrictData);

// Function to create the chart
function createPropertyChart() {
    // Check if canvas exists
    const canvas = document.getElementById('propertyDistrictChart');
    if (!canvas) {
        console.error('Canvas element not found!');
        return;
    }
    
    const ctx = canvas.getContext('2d');
    if (!ctx) {
        console.error('Could not get 2D context!');
        return;
    }
    
    // Only create chart if there's data
    if (propertyDistrictData.length > 0 && propertyDistrictData.reduce((a, b) => a + b, 0) > 0) {
        // Clear any existing chart
        if (window.propertyChart) {
            window.propertyChart.destroy();
        }
        
        // District-wise Property Registration Chart (Pie Chart)
        window.propertyChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: propertyDistrictLabels,
                datasets: [{
                    data: propertyDistrictData,
                    backgroundColor: [
                        '#036621',
                        '#28a745',
                        '#17a2b8',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#6610f2',
                        '#e83e8c'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            font: {
                                size: 14
                            },
                            usePointStyle: true
                        }
                    },
                    title: {
                        display: true,
                        text: 'Total Properties: ' + propertyDistrictData.reduce((a, b) => a + b, 0),
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        },
                        color: '#036621'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' properties (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else {
        // Show message if no data
        canvas.parentElement.innerHTML = 
            '<div class="text-center text-muted p-5">' +
            '<i class="fas fa-chart-pie fa-4x mb-3" style="opacity: 0.3;"></i>' +
            '<h5>No Property Data Available</h5>' +
            '<p>Add properties to see the district-wise distribution</p>' +
            '</div>';
    }
}

// Create the chart when page loads
document.addEventListener('DOMContentLoaded', function() {
    createPropertyChart();
});
</script>

<?php include('includes/admin_footer.php'); ?>
