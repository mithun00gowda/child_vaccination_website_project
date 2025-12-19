<?php 
require('../config/autoload.php'); 
include("header_health.php"); 

if(!isset($_SESSION['h_email'])) { echo "<script>location.replace('healthlogin.php');</script>"; exit; }

$dao = new DataAccess();
$hid = $_SESSION['hid'];

// FETCH COMPLETED (Status = 0)
$q = "SELECT b.bid, b.book_date, b.cfirstname, c.gender, c.dob, v.vname, b.cur_date
      FROM book b 
      JOIN child c ON b.cid = c.cid 
      JOIN vaccine v ON b.vid = v.vid 
      WHERE b.hid = $hid AND b.status = 0 
      ORDER BY b.book_date DESC";
$reports = $dao->query($q);
?>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Vaccination Reports</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        
        <div style="text-align:right; margin-bottom:10px;">
            <button onclick="window.print()" class="btn-print"><i class="fa fa-print"></i> Print Report</button>
        </div>

        <div id="printableArea">
            <h3 style="text-align:center;">Vaccination Report - <?php echo date('Y-m-d'); ?></h3>
            <table class="app-table" border="1">
                <thead>
                    <tr>
                        <th>Completed Date</th>
                        <th>Child Name</th>
                        <th>Gender</th>
                        <th>Vaccine Given</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($reports)) { echo "<tr><td colspan='5' align='center'>No records found.</td></tr>"; } else { 
                        foreach($reports as $row) { ?>
                        <tr>
                            <td><?php echo $row['book_date']; ?></td>
                            <td><?php echo $row['cfirstname']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['vname']; ?></td>
                            <td style="color:green; font-weight:bold;">COMPLETED</td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php include("footer2.php"); ?>