<?php 
require('../config/autoload.php'); 
include("header2.php");

$dao = new DataAccess();

// 1. Check if Child ID is present in URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>alert('Child ID missing'); location.replace('displayvaccine1.php');</script>";
    exit;
}

// 2. Check if Vaccine and Healthcenter are in Session
if(isset($_SESSION['hid']) && isset($_SESSION['vid'])) {
    $hid = $_SESSION['hid'];
    $vid = $_SESSION['vid'];
} else {
    echo "<script>alert('Please select a vaccine first.'); location.replace('displayvaccine1.php');</script>";
    exit;
}

// 3. FETCH DATA
// 3. FETCH DATA

// A. Get Child Details
$q3 = "select * from child where cid=$id";
$info3 = $dao->query($q3);

// B. Get Health Center Details FIRST (to get the name)
$q1 = "Select * from healthcenter where hid=$hid";
$info1 = $dao->query($q1);
$hname = $info1[0]['hname']; // Store the name

// C. Get Vaccine Details FIRST (to get the name)
$q2 = "Select * from vaccine where vid=$vid";
$info2 = $dao->query($q2);
$vname = $info2[0]['vname']; // Store the name

// D. FETCH SCHEDULE (Using Names, not IDs)
// We use single quotes '$hname' because these are text strings in the database
$q = "select * from schedule where hname='$hname' and vname='$vname'";
$info = $dao->query($q);

// E. Check if a schedule actually exists
$slots_available = 0;
if(empty($info)) {
    // Optional: Redirect if no schedule found, or just show 0 slots
    // echo "<script>alert('No schedule found');</script>";
    $slots_available = 0; 
} else {
    // Your database uses 'quantity', not 'qnty'
    $slots_available = $info[0]['quantity']; 
}

$date1 = date('Y-m-d', time());
$elements = array(
    "cid"=>"", "vid"=>"", "slotid"=>"", "hid"=>"", "cfirstname"=>"", "book_date"=>""
);

$form = new FormAssist($elements, $_POST);

$labels = array('cid'=>"child Id", 'vid'=>"vaccine Id", 'hid'=>"healthcenter Id", 'cfirstname'=>"Child firstname", 'cur_date'=>"current date", 'book_date'=>"booking date");

$rules = array(
    "vid" => array("required"=>true, "minlength"=>1, "maxlength"=>20),
    "hid" => array("required"=>true, "minlength"=>1, "maxlength"=>20),
    "cfirstname" => array("required"=>true),
    "book_date" => array("required"=>true),
);
    
$validator = new FormValidator($rules, $labels);

if(isset($_POST["book"]))
{
    // Check for existing bookings
    $q4 = "Select * from book where cid=$id and vid=$vid";
    $count = $dao->query($q4);
    
    // Check for cancelled bookings (status=2)
    $q5 = "Select * from book where cid=$id and vid=$vid and status=2"; 
    $count1 = $dao->query($q5);

    // LOGIC FIX: If (No booking exists) OR (Booking exists but is cancelled)
    if(empty($count) || !empty($count1)) 
    {
        if($validator->validate($_POST))
        {
             $data = array(
                'cid' => $id,
                'vid' => $vid,
                'hid' => $hid,
                'cfirstname' => $_POST['cfirstname'],
                'cur_date' => $date1,
                'book_date' => $_POST['book_date'],
            );
        
            if($dao->insert($data, "book"))
            {
                echo "<script> alert('Booking Success');</script> ";
                echo "<script> location.replace('email.php'); </script>";
            }
            else
            {
                $msg = "Insertion failed"; 
                echo "<script> alert('$msg');</script> ";
            }
        }   
    }
    else
    {
        echo "<script> alert('Already Booked');</script> ";
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
                                            <input class="form-control" name="cfirstname" type="text" value="<?php echo $info3[0]['cfirstname']; ?>" required readonly >
                                            <?= $validator->error('cfirstname'); ?>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Vaccine</span>
                                            <input class="form-control" name="vid" type="text" value="<?php echo $info2[0]['vname']; ?>" required readonly >
                                            <?= $validator->error('vid'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Health Center</span>
                                            <input class="form-control" name="hid" type="text" value="<?php echo $info1[0]['hname']; ?>" required readonly>
                                            <?= $validator->error('hid'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="form-label">Booking Date</span>
                                            <input class="form-control" name="book_date" type="date" min="<?php echo date('Y-m-d',time());?>" required>
                                            <?= $validator->error('book_date'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                            <span class="form-label">Available Slots</span>
                                            <input class="form-control" type="text" value="<?php echo $slots_available; ?>" required readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-btn">
                                    <button class="submit-btn" name="book">Book Now</button>
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