<?php 
require('../config/autoload.php'); 

// Ensure session is started
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dao = new DataAccess();

// 1. CAPTURE VACCINE ID
if(isset($_GET['id'])) {
    $_SESSION['vid'] = $_GET['id']; // Save Vaccine ID to Session
}

// Check if we actually have the Vaccine ID now
if(!isset($_SESSION['vid']) || empty($_SESSION['vid'])) {
    echo "<script>alert('Please select a vaccine first.'); location.replace('displayvaccine1.php');</script>";
    exit;
}

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
                echo "<h3 style='margin:20px; text-align:center;'>No Health Center Available</h3>";
            } else {
                $i = 0;
                while($i < count($info)) {
                    // Check if image exists, otherwise use a default placeholder
                    $image_name = $info[$i]["himage"];
                    $image_path = "../upload/" . $image_name;
                    
                    // Fallback if image is empty
                    if(empty($image_name)) {
                        $image_path = "../images/hospital_default.jpg"; // Ensure you have a default image or it will show broken
                    }
            ?>
            
            <div class="col-md-6">
                <div class="health-center-info" style="margin-bottom: 30px; border: 1px solid #eee; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    
                    <a href="selectchild.php?id=<?= $info[$i]["hid"]?>">
                        <!-- FIX: Pointing to ../upload/ folder -->
                        <img style="width:100%; height:250px; object-fit:cover; border-radius: 4px;" 
                             src="<?php echo $image_path; ?>" 
                             alt="<?php echo $info[$i]["hname"]; ?>" 
                             class="img-responsive"
                             onerror="this.src='../images/hospital_default.jpg';" /> 
                             <!-- The onerror helps if the specific file is missing -->
                    </a>
                    
                    <div class="health-center-details" style="padding-top: 15px;">
                        <h3 style="margin-top: 0;">
                            <a href="selectchild.php?id=<?= $info[$i]["hid"]?>" style="color: #333; text-decoration: none;">
                                <?php echo $info[$i]["hname"]?>
                            </a>
                        </h3>
                        <p style="color: lightskyblue; font-weight: bold;"><i class="fa fa-map-marker"></i> <?php echo $info[$i]["hregion"]; ?></p>
                        <p style="color: #666;"><?php echo $info[$i]["description"]?></p>
                        
                        <a href="selectchild.php?id=<?= $info[$i]["hid"]?>" class="button" style="background-color: lightskyblue; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; display: inline-block; margin-top: 10px;">Select Hospital</a>
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