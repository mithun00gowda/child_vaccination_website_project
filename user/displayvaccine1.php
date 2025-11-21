<?php require('../config/autoload.php'); ?>
<?php include("header2.php");   ?>

<?php
$dao=new DataAccess();

// 1. Securely get the Child ID and DOB from the URL
$childid = isset($_GET['id4']) ? $_GET['id4'] : '';
$dob = isset($_GET['dob']) ? $_GET['dob'] : '';

// 2. Save Child ID to session immediately
if($childid != '') {
    $_SESSION['cid'] = $childid;
}

// 3. Calculate Age in Months
if($dob != '') {
    $dob1 = new DateTime($dob);
    $now = new DateTime();
    $diff = $now->diff($dob1);
    $months = $diff->format('%m') + 12 * $diff->format('%y');
    $_SESSION['child_age_months'] = $months; // Saved for reference
} else {
    $months = 0;
}

// 4. Fetch Vaccines
// OPTIONAL: Added "WHERE vage <= $months" so you only see vaccines the child is old enough for
$q = "SELECT * from vaccine"; 
$info = $dao->query($q);
?>

<div class="page-head"  data-bg-image="images/5570834.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Available Vaccines</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <?php
            if(empty($info)) {
                echo "<h3>No Vaccines Available</h3>";
            } else {
                $i = 0;
                while($i < count($info)) {
            ?>
                <div class="col-md-6">
                    <div class="vaccine-info" style="margin-bottom: 30px;">
                        <a href="displayhealthcenter1.php?id=<?= $info[$i]["vid"]?>">
                            <img style="width:200px; height:200px; object-fit:cover;" src="<?php echo BASE_URL."upload/".$info[$i]["vimage"]; ?>" alt="Vaccine Image" class="img-responsive" />
                        </a>
                        <br>
                        <div class="vaccine-details">
                            <h3><a href="displayhealthcenter1.php?id=<?= $info[$i]["vid"]?>"><?php echo $info[$i]["vname"]?></a></h3>
                            <p><strong>Age Required: <?php echo $info[$i]["vage"]; ?> Months</strong></p>
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

<?php include("footer2.php"); ?>