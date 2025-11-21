<?php 
require('../config/autoload.php'); 
// Ensure session is started (autoload usually does this, but safe to check)
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dao = new DataAccess();

// ---------------------------------------------------------
// 1. CAPTURE VACCINE ID (FIX FOR "Vaccine Not Selected")
// ---------------------------------------------------------
if(isset($_GET['id'])) {
    $_SESSION['vid'] = $_GET['id']; // Save Vaccine ID to Session
}

// Check if we actually have the Vaccine ID now
if(!isset($_SESSION['vid']) || empty($_SESSION['vid'])) {
    echo "<script>alert('Please select a vaccine first.'); location.replace('displayvaccine1.php');</script>";
    exit;
}
// ---------------------------------------------------------

include("header2.php");   
?>

<div class="page-head" data-bg-image="images/5570834.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Available Health Centers</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <?php
            // Fetch all health centers
            $q = "SELECT * from healthcenter";
            $info = $dao->query($q);

            if(empty($info)) {
                echo "<h3 style='margin:20px;'>No Health Center Available</h3>";
            } else {
                $i = 0;
                while($i < count($info)) {
            ?>
            
            <div class="col-md-6">
                <div class="health-center-info" style="margin-bottom: 30px;">
                    <a href="selectchild.php?id=<?= $info[$i]["hid"]?>">
                        <img style="width:100%; height:250px; object-fit:cover;" src="<?php echo BASE_URL."upload/".$info[$i]["himage"]; ?>" alt="Health Center Image" class="img-responsive" />
                    </a>
                    
                    <div class="health-center-details" style="padding-top: 15px;">
                        <h3>
                            <a href="selectchild.php?id=<?= $info[$i]["hid"]?>">
                                <?php echo $info[$i]["hname"]?>
                            </a>
                        </h3>
                        <p><strong>Location: <?php echo $info[$i]["hregion"]; ?></strong></p>
                        <p><?php echo $info[$i]["description"]?></p>
                    </div>
                </div>
            </div>

            <?php 
                    $i++;
                }
            } 
            ?>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>