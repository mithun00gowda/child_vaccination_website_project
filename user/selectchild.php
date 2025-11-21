<?php 
require('../config/autoload.php'); 

// Ensure session is started
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("header.php"); 

$dao = new DataAccess();

// ---------------------------------------------------------
// 1. CAPTURE HEALTH CENTER ID
// ---------------------------------------------------------
// The previous page (displayhealthcenter1.php) sent the ID here.
// We must save it to the session so booking.php can use it.
if(isset($_GET['id'])) {
    $_SESSION['hid'] = $_GET['id'];
}
// ---------------------------------------------------------
?>

<style>
    div.a { text-transform: uppercase; }
    .boxed-icon {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Select Child for Vaccination</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <?php
            if(isset($_SESSION['pid'])) {
                $pid = $_SESSION['pid'];
                
                // Fetch children belonging to this parent
                $q = "select * from child where pid='".$pid."'";
                $info = $dao->query($q);

                if(empty($info)) {
                    // NO CHILDREN FOUND
                    ?>
                    <div class="col-md-12">
                        <div class="feature" style="text-align:center; padding:50px;">
                             <h3 style="color: black">No children registered.</h3>
                             <p>Please add your child details first in the dashboard.</p>
                        </div>
                    </div>
                    <?php
                } else {
                    // CHILDREN FOUND - LOOP THROUGH THEM
                    $i = 0;  
                    while($i < count($info)) {
                        // 2. SIMPLIFIED GENDER IMAGE LOGIC
                        // If gender is 'm', use boy.jpg, otherwise use girl.jpg
                        $childImage = ($info[$i]["gender"] == 'm') ? 'images/boy.jpg' : 'images/girl.jpg';
            ?>
                        <div class="col-md-6">
                            <div class="boxed-icon">
                                <img src="<?php echo $childImage; ?>" alt="Child Image" class="icon" style="width:100px; height:100px; border-radius:50%;">
                                
                                <h3 style="color: black">
                                    <?php echo $info[$i]["cfirstname"] . " " . $info[$i]["clastname"]; ?>
                                </h3> 
                                
                                <table style="color: black; margin: 0 auto; text-align: left;"> 
                                    <tr>
                                        <td style="padding-right:10px;"><b>DOB:</b></td>
                                        <td><?php echo $info[$i]["dob"]; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-right:10px;"><b>Sex:</b></td>
                                        <td><div class="a"><?php echo $info[$i]["gender"]; ?></div></td>
                                    </tr>
                                </table>
                                <br>
                                
                                <a href="booking.php?id=<?= $info[$i]["cid"];?>" class="button" style="background-color: lightskyblue; color: black; padding: 10px 20px; border-radius: 4px; text-decoration:none;">
                                    Select & Book
                                </a>
                            </div>
                        </div>
            <?php 
                        $i++;
                    } // End While
                } // End Else
            } else {
                echo "<script>alert('Session expired. Please login.'); location.replace('login.php');</script>";
            }
            ?>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>