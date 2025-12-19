<?php 
require('../config/autoload.php'); 
include("header.php"); 
$dao = new DataAccess();

// 1. ADD VACCINE
if(isset($_POST['add_vaccine'])) {
    
    $vname = $_POST['vname'];
    $vage = $_POST['vage']; // Age in months
    $description = $_POST['description']; // Get Description
    $filename = "default_vaccine.jpg";

    // Handle Image Upload
    if(isset($_FILES['vimage']['name']) && $_FILES['vimage']['name'] != "") {
        $target_dir = "../upload/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        $filename = time() . "_" . basename($_FILES["vimage"]["name"]);
        move_uploaded_file($_FILES["vimage"]["tmp_name"], $target_dir . $filename);
    }

    $data = array(
        'vname' => $vname,
        'vage' => $vage,
        'description' => $description,
        'vimage' => $filename
    );

    if($dao->insert($data, 'vaccine')) {
        echo "<script>alert('Vaccine Added Successfully!'); location.replace('vaccine.php');</script>";
    } else {
        echo "<script>alert('Failed to add vaccine.');</script>";
    }
}

// 2. DELETE VACCINE
if(isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $dao->query("DELETE FROM vaccine WHERE vid=$id");
    echo "<script>alert('Vaccine Deleted'); location.replace('vaccine.php');</script>";
}

$vaccines = $dao->query("SELECT * FROM vaccine ORDER BY vid DESC");
?>

<div class="row">
    <!-- ADD VACCINE FORM -->
    <div class="col-md-4">
        <div class="card-box">
            <h4><i class="fa fa-plus-circle"></i> Add New Vaccine</h4>
            <form method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Vaccine Name</label>
                    <input type="text" name="vname" class="form-control" placeholder="e.g. Polio, BCG" required>
                </div>

                <div class="form-group">
                    <label>Age Required (Months)</label>
                    <input type="number" name="vage" class="form-control" placeholder="e.g. 0, 6, 12" required>
                    <small class="text-muted">Enter 0 for birth dose</small>
                </div>

                <div class="form-group">
                    <label>Vaccine Image</label>
                    <input type="file" name="vimage" class="form-control" style="padding-top: 5px;">
                </div>

                <!-- DESCRIPTION FIELD -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Brief details about the vaccine..." required></textarea>
                </div>

                <button name="add_vaccine" class="btn btn-primary btn-block">Add Vaccine</button>
            </form>
        </div>
    </div>

    <!-- VACCINE LIST -->
    <div class="col-md-8">
        <div class="card-box">
            <h4><i class="fa fa-list"></i> Available Vaccines</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Age (Months)</th>
                            <th>Description</th> <!-- Added Column -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($vaccines)) { foreach($vaccines as $v) { ?>
                        <tr>
                            <td><img src="../upload/<?php echo $v['vimage']; ?>" width="50" height="50" style="object-fit:cover; border-radius:4px;"></td>
                            <td style="font-weight:bold; color:#007bff;"><?php echo $v['vname']; ?></td>
                            <td><?php echo $v['vage']; ?></td>
                            <td>
                                <!-- Display truncated description -->
                                <small style="color:#555;">
                                    <?php echo substr($v['description'], 0, 80); ?>...
                                </small>
                            </td>
                            <td>
                                <a href="vaccine.php?del_id=<?php echo $v['vid']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this vaccine?')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='5' align='center'>No vaccines added.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>