<?php 
require('../config/autoload.php'); 
include("header_health.php"); 

if(!isset($_SESSION['h_email'])) { echo "<script>location.replace('healthlogin.php');</script>"; exit; }

$dao = new DataAccess();
$hid = $_SESSION['hid'];

// ACTION: Mark as Completed
if(isset($_GET['mark_id'])) {
    $bid = $_GET['mark_id'];
    if($dao->query("UPDATE book SET status=0 WHERE bid=$bid")) {
        echo "<script>alert('Vaccination Marked as Completed!'); location.replace('h_bookings.php');</script>";
    }
}

// FETCH PENDING (Status = 1)
$q = "SELECT b.bid, b.book_date, b.cfirstname, c.gender, c.dob, v.vname 
      FROM book b 
      JOIN child c ON b.cid = c.cid 
      JOIN vaccine v ON b.vid = v.vid 
      WHERE b.hid = $hid AND b.status = 1 
      ORDER BY b.book_date ASC";
$bookings = $dao->query($q);
?>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Pending Vaccinations</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Child Name</th>
                    <th>Age</th>
                    <th>Vaccine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($bookings)) { echo "<tr><td colspan='5' align='center'>No pending bookings.</td></tr>"; } else { 
                    foreach($bookings as $row) { ?>
                    <tr>
                        <td><?php echo $row['book_date']; ?></td>
                        <td><b><?php echo $row['cfirstname']; ?></b><br><small><?php echo $row['gender']; ?></small></td>
                        <td><?php echo $row['dob']; ?></td>
                        <td style="color:blue;"><?php echo $row['vname']; ?></td>
                        <td>
                            <a href="h_bookings.php?mark_id=<?php echo $row['bid']; ?>" class="btn-action" onclick="return confirm('Confirm Vaccination Complete?');">
                                Mark Complete
                            </a>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("footer2.php"); ?>