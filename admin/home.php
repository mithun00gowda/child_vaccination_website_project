<?php 
require('../config/autoload.php'); 
include("header.php"); 
$dao = new DataAccess();

// Get Counts
$c_hospitals = $dao->query("SELECT count(*) as c FROM healthcenter")[0]['c'];
$c_children = $dao->query("SELECT count(*) as c FROM child")[0]['c'];
$c_bookings = $dao->query("SELECT count(*) as c FROM book")[0]['c'];
?>

<div class="row">
    <div class="col-md-12">
        <h2>Welcome, Admin!</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1 class="text-primary"><?php echo $c_hospitals; ?></h1>
            <h4>Hospitals Active</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1 class="text-success"><?php echo $c_children; ?></h1>
            <h4>Registered Children</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1 class="text-warning"><?php echo $c_bookings; ?></h1>
            <h4>Total Bookings</h4>
        </div>
    </div>
</div>

</body>
</html>