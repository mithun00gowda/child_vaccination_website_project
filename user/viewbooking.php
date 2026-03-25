<?php 
require('../config/autoload.php'); 
include('header2.php'); 

// Check if parent is logged in
if(!isset($_SESSION['pid'])) {
    echo "<script>alert('Please log in to view your bookings.'); location.replace('login.php');</script>";
    exit;
}

$dao = new DataAccess();
$pid = intval($_SESSION['pid']);

// Fetch bookings belonging to this parent's children
$q = "SELECT b.bid, b.cfirstname, v.vname, h.hname, b.book_date, b.status 
      FROM book b 
      JOIN child c ON b.cid = c.cid 
      JOIN vaccine v ON b.vid = v.vid 
      JOIN healthcenter h ON b.hid = h.hid 
      WHERE c.pid = $pid 
      ORDER BY b.book_date DESC";
      
$bookings = $dao->query($q);
?>

<div class="container_gray_bg" id="home_feat_1">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><br><br>
                <h3 style="color: black; margin-bottom: 20px;">My Child Vaccinations</h3>
                <table border="1" class="table" style="width:100%; text-align:left; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <style>
                        table, th, td { border: 1px solid #ddd; border-collapse: collapse; padding: 12px; }
                        th { background-color: lightskyblue; color: black; font-weight: bold; }
                        td { color: #333; }
                    </style>
                    <tr>
                        <th>Booking ID</th>
                        <th>Child Name</th>
                        <th>Vaccine Name</th>
                        <th>Healthcenter</th>
                        <th>Book Date</th>
                        <th>Status</th>
                    </tr>
                    
                    <?php if(empty($bookings) || !is_array($bookings)): ?>
                        <tr><td colspan="6" style="text-align:center; padding: 20px;">You have no bookings yet.</td></tr>
                    <?php else: ?>
                        <?php foreach($bookings as $row): ?>
                            <tr>
                                <td><b>#<?php echo $row['bid']; ?></b></td>
                                <td><?php echo ucfirst($row['cfirstname']); ?></td>
                                <td><span style="color:blue; font-weight:bold;"><?php echo $row['vname']; ?></span></td>
                                <td><?php echo $row['hname']; ?></td>
                                <td><?php echo date('d M Y', strtotime($row['book_date'])); ?></td>
                                <td>
                                    <?php if($row['status'] == 1): ?>
                                        <span style="background-color: #ffeeba; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">PENDING</span>
                                    <?php else: ?>
                                        <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">COMPLETED</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                </table>
            </div>    
        </div>
    </div>
</div>

<?php include('footer2.php'); ?>