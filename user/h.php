<?php 
require('../config/autoload.php'); 
include("header_health.php"); 

if(!isset($_SESSION['h_email'])) { echo "<script>location.replace('healthlogin.php');</script>"; exit; }

$dao = new DataAccess();
$hid = $_SESSION['hid'];
$today = date('Y-m-d');

// STATS
$count_today = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND book_date='$today' AND status=1")[0]['count'];
$count_pending = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND status=1")[0]['count'];
$count_completed = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND status=0")[0]['count'];
?>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Dashboard Overview</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee;">
                    <h1 style="color:lightskyblue; font-size:40px;"><?php echo $count_today; ?></h1>
                    <h3>Appointments Today</h3>
                    <a href="h_bookings.php" style="color:blue;">View Details &rarr;</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee;">
                    <h1 style="color:orange; font-size:40px;"><?php echo $count_pending; ?></h1>
                    <h3>Total Pending</h3>
                    <a href="h_bookings.php" style="color:blue;">Manage Bookings &rarr;</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee;">
                    <h1 style="color:green; font-size:40px;"><?php echo $count_completed; ?></h1>
                    <h3>Vaccinations Done</h3>
                    <a href="healthreport.php" style="color:blue;">View Reports &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer2.php"); ?>