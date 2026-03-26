<?php 
require('../config/autoload.php'); 
include("header2.php");

$dao = new DataAccess();

// 1. SQL INJECTION PATCH: Strictly cast incoming IDs to integers
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$hid = isset($_SESSION['hid']) ? intval($_SESSION['hid']) : 0;
$vid = isset($_SESSION['vid']) ? intval($_SESSION['vid']) : 0;

if($id === 0 || $hid === 0 || $vid === 0) {
    echo "<script>alert('Invalid booking parameters.'); location.replace('displayvaccine1.php');</script>";
    exit;
}

// 2. FETCH DATA SAFELY
$info3 = $dao->query("SELECT * FROM child WHERE cid=$id");
$info1 = $dao->query("SELECT * FROM healthcenter WHERE hid=$hid");
$info2 = $dao->query("SELECT * FROM vaccine WHERE vid=$vid");

if(empty($info3) || empty($info1) || empty($info2)) {
    echo "<script>alert('Error retrieving booking details.'); location.replace('displayvaccine1.php');</script>";
    exit;
}

$hname = $info1[0]['hname'];
$vname = $info2[0]['vname'];

// 3. FETCH SCHEDULE
$hname_safe = addslashes($hname); 
$vname_safe = addslashes($vname);
$info = $dao->query("SELECT * FROM schedule WHERE hname='$hname_safe' AND vname='$vname_safe'");

$slots_available = empty($info) ? 0 : intval($info[0]['quantity']);
$date1 = date('Y-m-d', time());

// Added parent_email to elements
$elements = array("cfirstname"=>"", "book_date"=>"", "parent_email"=>"");
$form = new FormAssist($elements, $_POST);

$labels = array('cfirstname'=>"Child firstname", 'book_date'=>"booking date", 'parent_email'=>"Email Address");
$rules = array(
    "cfirstname" => array("required"=>true),
    "book_date" => array("required"=>true),
    "parent_email" => array("required"=>true)
);
$validator = new FormValidator($rules, $labels);

if(isset($_POST["book"]))
{
    // Check if a valid booking already exists (status 1 = pending, status 0 = complete)
    $existing_booking = $dao->query("SELECT * FROM book WHERE cid=$id AND vid=$vid AND status IN (0, 1)");
    
    if(!empty($existing_booking)) {
        echo "<script> alert('This child is already booked or vaccinated for this specific vaccine.');</script> ";
    }
    else 
    {
        if($validator->validate($_POST))
        {
            // RACE CONDITION PATCH: Atomic Update Query
            $atomic_update_query = "UPDATE schedule SET quantity = quantity - 1 WHERE hname='$hname_safe' AND vname='$vname_safe' AND quantity > 0";
            
            if($dao->query($atomic_update_query)) 
            {
                 $data = array(
                    'cid' => $id,
                    'vid' => $vid,
                    'hid' => $hid,
                    'cfirstname' => $_POST['cfirstname'],
                    'cur_date' => $date1,
                    'book_date' => $_POST['book_date'],
                    'status' => 1
                );
            
                if($dao->insert($data, "book"))
                {
                    // --- DETAILED NOTIFICATIONS SYSTEM ---
                    $child_name = addslashes($_POST['cfirstname']);
                    $book_date = addslashes($_POST['book_date']);
                    $formatted_date = date('d M Y', strtotime($book_date));
                    $session_user = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_SESSION['email']) ? $_SESSION['email'] : '');
                    $provided_email = addslashes($_POST['parent_email']);
                    
                    // 1. DETAILED IN-APP NOTIFICATION: Notify Parent
                    if($session_user != '') {
                        $msg_parent = "✅ <b>Booking Confirmed!</b> Your child, <b>$child_name</b>, is successfully scheduled for the <b>$vname_safe</b> vaccine on <b>$formatted_date</b> at <b>$hname_safe</b>. Please arrive 15 minutes prior to the appointment.";
                        $dao->query("INSERT INTO notifications (user_email, message) VALUES ('$session_user', '$msg_parent')");
                    }

                    // 2. DETAILED IN-APP NOTIFICATION: Notify Health Workers
                    $hw_list = $dao->query("SELECT h_email FROM healthworker WHERE hid=$hid");
                    if(!empty($hw_list)) {
                        foreach($hw_list as $hw) {
                            $hw_email = $hw['h_email'];
                            $msg_hw = "📅 <b>New Appointment Alert:</b> <b>$child_name</b> has been booked for the <b>$vname_safe</b> vaccine on <b>$formatted_date</b>. Parent Contact Email: $provided_email.";
                            $dao->query("INSERT INTO notifications (user_email, message) VALUES ('$hw_email', '$msg_hw')");
                        }
                    }
                    // -------------------------------------------

                    echo "<script> alert('Booking Success! Detailed notifications have been sent to your inbox.'); location.replace('viewbooking.php'); </script>";
                }
                else
                {
                    // Rollback the slot if insertion fails
                    $dao->query("UPDATE schedule SET quantity = quantity + 1 WHERE hname='$hname_safe' AND vname='$vname_safe'");
                    echo "<script> alert('Database insertion failed. Please try again.');</script> ";
                }
            }
            else 
            {
                echo "<script> alert('Sorry, someone just booked the last available slot!');</script> ";
            }
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Form</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="cssb/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="cssb/style.css" />
</head>

<body>
    <div id="booking" class="section">
        <div class="section-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="booking-cta">
                            <h1>Book your vaccines</h1>
                            <p>Ensure your child's health by booking a slot today.</p>
                        </div>
                    </div>
                    <div class="col-md-7 col-md-offset-1">
                        <div class="booking-form">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Child Name</span>
                                            <input class="form-control" name="cfirstname" type="text" value="<?php echo htmlspecialchars($info3[0]['cfirstname']); ?>" required readonly >
                                            <?= $validator->error('cfirstname'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Vaccine</span>
                                            <input class="form-control" name="vid_name" type="text" value="<?php echo htmlspecialchars($vname); ?>" required readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Health Center</span>
                                            <input class="form-control" name="hid_name" type="text" value="<?php echo htmlspecialchars($hname); ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Booking Date</span>
                                            <input class="form-control" name="book_date" type="date" min="<?php echo date('Y-m-d');?>" required>
                                            <?= $validator->error('book_date'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <span class="form-label">Parent Contact Email (Saved in health center records)</span>
                                            <?php $default_email = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>
                                            <input class="form-control" name="parent_email" type="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($default_email); ?>" required>
                                            <?= $validator->error('parent_email'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                            <span class="form-label">Available Slots</span>
                                            <input class="form-control" type="text" value="<?php echo $slots_available; ?>" required readonly <?php if($slots_available <= 0) echo 'style="color: red; font-weight: bold;"'; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <button class="submit-btn" name="book" <?php if($slots_available <= 0) echo 'disabled style="background-color: #999; cursor: not-allowed;"'; ?>>Book Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>