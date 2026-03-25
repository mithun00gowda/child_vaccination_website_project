<?php 
require('../config/autoload.php'); 
include("header_health.php"); 

if(!isset($_SESSION['h_email'])) { echo "<script>location.replace('healthlogin.php');</script>"; exit; }

$dao = new DataAccess();
$hid = isset($_SESSION['hid']) ? intval($_SESSION['hid']) : 0; 
$today = date('Y-m-d');

// ACTION: Mark as Completed
if(isset($_GET['mark_id']) && !empty($_GET['mark_id'])) {
    $bid = intval($_GET['mark_id']);
    
    // BACKEND SECURITY: Ensure the booking is actually for today before allowing the update
    $check_booking = $dao->query("SELECT b.book_date, b.cfirstname, c.pid, v.vname FROM book b JOIN child c ON b.cid = c.cid JOIN vaccine v ON b.vid = v.vid WHERE b.bid=$bid");
    
    if(is_array($check_booking) && !empty($check_booking) && isset($check_booking[0]['book_date']) && $check_booking[0]['book_date'] <= $today) {
        
        $data = array('status' => 0);
        $condition = 'bid=' . $bid;
        
        if($dao->update($data, 'book', $condition)) {
            
            // --- NOTIFICATION IMPLEMENTATION ---
            $pid = intval($check_booking[0]['pid']);
            $parent_data = $dao->query("SELECT username FROM registration WHERE pid=$pid");
            
            if(!empty($parent_data)) {
                $parent_email = $parent_data[0]['username'];
                $child_name = addslashes($check_booking[0]['cfirstname']);
                $vaccine_name = addslashes($check_booking[0]['vname']);
                
                $msg = "Update: $child_name has successfully received the $vaccine_name vaccine. View your reports for details.";
                $dao->query("INSERT INTO notifications (user_email, message) VALUES ('$parent_email', '$msg')");
            }
            // ------------------------------------

            echo "<script>alert('Vaccination Marked as Completed & Parent Notified!'); location.replace('h_bookings.php');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Action Restricted! You can only mark appointments as completed on or after their scheduled date.'); location.replace('h_bookings.php');</script>";
        exit;
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
        <table class="app-table" style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: lightskyblue; color: black;">
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Slot No.</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Date</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Child Name</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">DOB</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Vaccine</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ccc;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($bookings) || !is_array($bookings)) { 
                    echo "<tr><td colspan='6' align='center' style='padding: 20px;'>No pending bookings.</td></tr>"; 
                } else { 
                    foreach($bookings as $row) { ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px; color: black;"><b>#<?php echo $row['bid']; ?></b></td>
                        <td style="padding: 12px; color: black;"><?php echo date('d M Y', strtotime($row['book_date'])); ?></td>
                        <td style="padding: 12px; color: black;"><b><?php echo ucfirst($row['cfirstname']); ?></b><br><small><?php echo strtoupper($row['gender']); ?></small></td>
                        <td style="padding: 12px; color: black;"><?php echo date('d M Y', strtotime($row['dob'])); ?></td>
                        <td style="padding: 12px; color:blue;"><b><?php echo $row['vname']; ?></b></td>
                        <td style="padding: 12px;">
                            <?php if($row['book_date'] <= $today): ?>
                                <a href="h_bookings.php?mark_id=<?php echo $row['bid']; ?>" class="button" style="background-color: #4CAF50; color: white; padding: 6px 15px; border-radius: 4px; border: none; font-weight: bold; text-decoration: none;" onclick="return confirm('Confirm Vaccination Complete? Parent will be notified.');">
                                    Mark Complete
                                </a>
                            <?php else: ?>
                                <span style="background-color: #e0e0e0; color: #888; padding: 6px 15px; border-radius: 4px; font-weight: bold; cursor: not-allowed; display: inline-block;">
                                    Upcoming
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("footer2.php"); ?>