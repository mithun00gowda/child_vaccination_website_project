<?php 
require('../config/autoload.php'); 
include('header2.php'); 

// Check if parent is logged in
if(!isset($_SESSION['pid'])) {
    echo "<script>alert('Please log in to view reports.'); location.replace('login.php');</script>";
    exit;
}

$dao = new DataAccess();
$pid = intval($_SESSION['pid']);

// Fetch all children for this parent
$children = $dao->query("SELECT * FROM child WHERE pid = $pid");
?>

<style>
    .report-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .child-header {
        border-bottom: 2px solid lightskyblue;
        padding-bottom: 10px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    .status-completed { background-color: #d4edda; color: #155724; }
    .status-pending { background-color: #ffeeba; color: #856404; }
    
    /* Print specific styles */
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .site-header, .site-footer, .page-head { display: none !important; }
        .report-card { box-shadow: none; border: 1px solid #000; }
    }
</style>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Vaccination Reports</h2>
        <p style="color: white">Comprehensive medical history for your children.</p>
    </div>
</div>

<div class="fullwidth-block" id="printable-area">
    <div class="container">
        
        <div class="row no-print" style="margin-bottom: 20px;">
            <div class="col-md-12 text-right">
                <button onclick="window.print()" class="button" style="background-color: #333; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold;">
                    <i class="fa fa-print"></i> Print Report
                </button>
            </div>
        </div>

        <?php if(empty($children) || !is_array($children)): ?>
            <div class="alert alert-info text-center" style="background: #e9f7fd; padding: 30px; border-radius: 8px;">
                <h3 style="color: #31708f;">No Children Registered</h3>
                <p>You have not registered any children yet. <a href="addchild.php">Click here to add a child</a>.</p>
            </div>
        <?php else: ?>
            
            <?php foreach($children as $child): 
                $cid = intval($child['cid']);
                // Fetch bookings for this specific child
                $bookings = $dao->query("SELECT b.bid, b.book_date, b.status, v.vname, h.hname 
                                         FROM book b 
                                         JOIN vaccine v ON b.vid = v.vid 
                                         JOIN healthcenter h ON b.hid = h.hid 
                                         WHERE b.cid = $cid 
                                         ORDER BY b.book_date ASC");
            ?>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="report-card">
                            <div class="child-header">
                                <div>
                                    <h3 style="color: #333; margin: 0; text-transform: capitalize;">
                                        <i class="fa fa-child" style="color: lightskyblue;"></i> 
                                        <?php echo $child['cfirstname'] . ' ' . $child['clastname']; ?>
                                    </h3>
                                    <p style="color: gray; margin: 5px 0 0 0;">
                                        <strong>DOB:</strong> <?php echo date('d M Y', strtotime($child['dob'])); ?> | 
                                        <strong>Gender:</strong> <?php echo strtoupper($child['gender']) == 'M' ? 'Male' : 'Female'; ?>
                                    </p>
                                </div>
                            </div>

                            <table class="table" style="width: 100%; text-align: left; border-collapse: collapse;">
                                <thead>
                                    <tr style="background-color: #f9f9f9; border-bottom: 2px solid #ddd;">
                                        <th style="padding: 10px;">Vaccine</th>
                                        <th style="padding: 10px;">Health Center</th>
                                        <th style="padding: 10px;">Scheduled Date</th>
                                        <th style="padding: 10px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($bookings) || !is_array($bookings)): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 15px; text-align: center; color: #888;">No vaccination records found for this child.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($bookings as $row): ?>
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 10px; color: #0056b3; font-weight: bold;"><?php echo $row['vname']; ?></td>
                                                <td style="padding: 10px;"><?php echo $row['hname']; ?></td>
                                                <td style="padding: 10px;"><?php echo date('d M Y', strtotime($row['book_date'])); ?></td>
                                                <td style="padding: 10px;">
                                                    <?php if($row['status'] == 1): ?>
                                                        <span class="status-badge status-pending">Upcoming</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-completed"><i class="fa fa-check"></i> Administered</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php include('footer2.php'); ?>