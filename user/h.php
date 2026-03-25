<?php 
require('../config/autoload.php'); 
include("header_health.php"); // Assuming header_health.php is the health worker header

if(!isset($_SESSION['h_email'])) { 
    echo "<script>location.replace('healthlogin.php');</script>"; 
    exit; 
}

$dao = new DataAccess();

// Ensure hid is available (fallback to 0 if missing to prevent SQL errors)
$hid = isset($_SESSION['hid']) ? $_SESSION['hid'] : 0; 
$today = date('Y-m-d');

// STATS
$count_today = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND book_date='$today' AND status=1")[0]['count'];
$count_pending = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND status=1")[0]['count'];
$count_completed = $dao->query("SELECT count(*) as count FROM book WHERE hid=$hid AND status=0")[0]['count'];

// FETCH ONLY TODAY'S ACTIVE OPTIONS TO SHOW IN A TABLE
$q_active_today = "SELECT b.*, v.vname FROM book b INNER JOIN vaccine v ON b.vid = v.vid WHERE b.hid=$hid AND b.book_date='$today' AND b.status=1";
$active_today_list = $dao->query($q_active_today);
?>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Dashboard Overview</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <!-- Dashboard Stat Boxes -->
        <div class="row">
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee; border-radius: 8px;">
                    <h1 style="color:lightskyblue; font-size:40px; margin-bottom: 10px;"><?php echo $count_today; ?></h1>
                    <h3 style="color: black;">Appointments Today</h3>
                    <p style="color: gray;">Active bookings for <?php echo date('d M Y'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee; border-radius: 8px;">
                    <h1 style="color:orange; font-size:40px; margin-bottom: 10px;"><?php echo $count_pending; ?></h1>
                    <h3 style="color: black;">Total Pending</h3>
                    <a href="h_bookings.php" style="color:blue;">Manage All Bookings &rarr;</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-boxed" style="text-align:center; padding:30px; border:1px solid #eee; box-shadow:0 0 10px #eee; border-radius: 8px;">
                    <h1 style="color:green; font-size:40px; margin-bottom: 10px;"><?php echo $count_completed; ?></h1>
                    <h3 style="color: black;">Vaccinations Done</h3>
                    <a href="healthreport.php" style="color:blue;">View Reports &rarr;</a>
                </div>
            </div>
        </div>

        <!-- NEW: Only Today's Active Options Show -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3 style="color: black; margin-bottom: 20px; border-bottom: 2px solid lightskyblue; padding-bottom: 10px;">
                    <i class="fa fa-calendar-check-o" style="color: lightskyblue;"></i> Today's Active Appointments
                </h3>
                
                <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; padding: 20px;">
                    <?php if(empty($active_today_list)): ?>
                        <p style="text-align: center; color: gray; font-size: 16px; margin: 20px 0;">
                            🎉 Great! There are no pending appointments for today.
                        </p>
                    <?php else: ?>
                        <table style="width: 100%; text-align: left; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: lightskyblue; color: black;">
                                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Booking ID</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Child Name</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Vaccine</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Date</th>
                                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 0;
                                while($i < count($active_today_list)): 
                                    $booking = $active_today_list[$i];
                                ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; color: black;"><b>#<?= $booking['bid'] ?></b></td>
                                    <td style="padding: 12px; color: black;"><?= ucfirst($booking['cfirstname']) ?></td>
                                    <td style="padding: 12px; color: black;"><?= $booking['vname'] ?></td>
                                    <td style="padding: 12px; color: black;"><?= date('d M Y', strtotime($booking['book_date'])) ?></td>
                                    <td style="padding: 12px;">
                                        <!-- Replace 'complete_booking.php' with your actual script that marks status=0 -->
                                         <a href="h_bookings.php?mark_id=<?= $booking['bid'] ?>" class="button" style="background-color: #4CAF50; color: white; padding: 6px 15px; border-radius: 4px; border: none; font-weight: bold;" onclick="return confirm('Confirm Vaccination Complete? Parent will be notified.');">
                                            Mark Done
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    $i++;
                                endwhile; 
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include("footer2.php"); ?>