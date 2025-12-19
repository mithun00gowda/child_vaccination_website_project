<?php 
require('../config/autoload.php'); 
include("header.php"); 
$dao = new DataAccess();

// 1. INSERT HOSPITAL WITH IMAGE
if(isset($_POST['add_hospital'])) {
    
    $hname = $_POST['hname'];
    $hregion = $_POST['hregion'];
    $description = $_POST['description'];
    $filename = "default.jpg"; // Default if no image uploaded

    // Check if image file is selected
    if(isset($_FILES['himage']['name']) && $_FILES['himage']['name'] != "") {
        $target_dir = "../upload/";
        
        // Create upload folder if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Generate a unique name to prevent overwriting: time + original name
        $filename = time() . "_" . basename($_FILES["himage"]["name"]);
        $target_file = $target_dir . $filename;

        // Move the file
        if (move_uploaded_file($_FILES["himage"]["tmp_name"], $target_file)) {
            // Upload successful
        } else {
            echo "<script>alert('Error uploading image.');</script>";
        }
    }

    $data = array(
        'hname' => $hname,
        'hregion' => $hregion,
        'description' => $description,
        'himage' => $filename
    );

    if($dao->insert($data, 'healthcenter')) {
        echo "<script>alert('Hospital Added Successfully!'); location.replace('hospital.php');</script>";
    } else {
        echo "<script>alert('Failed to add hospital.');</script>";
    }
}

// 2. DELETE HOSPITAL
if(isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    
    // Optional: Delete the image file from folder too (Advanced step)
    // $info = $dao->query("SELECT himage FROM healthcenter WHERE hid=$id");
    // if(!empty($info)) { unlink("../upload/".$info[0]['himage']); }

    $dao->query("DELETE FROM healthcenter WHERE hid=$id");
    echo "<script>alert('Hospital Deleted'); location.replace('hospital.php');</script>";
}

$hospitals = $dao->query("SELECT * FROM healthcenter ORDER BY hid DESC");
?>

<div class="row">
    <!-- FORM TO ADD HOSPITAL -->
    <div class="col-md-4">
        <div class="card-box">
            <h4><i class="fa fa-plus-circle"></i> Add New Hospital</h4>
            <!-- enctype is required for file uploads -->
            <form method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Hospital Name</label>
                    <input type="text" name="hname" class="form-control" placeholder="e.g. Victoria Hospital" required>
                </div>

                <div class="form-group">
                    <label>Region / City</label>
                    <input type="text" name="hregion" class="form-control" placeholder="e.g. Bengaluru" required>
                </div>

                <div class="form-group">
                    <label>Hospital Image</label>
                    <input type="file" name="himage" class="form-control" required style="padding-top: 5px;">
                    <small class="text-muted">Supports jpg, png, jpeg</small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Enter details about the hospital facilities..." required></textarea>
                </div>

                <button name="add_hospital" class="btn btn-primary btn-block">Add Hospital</button>
            </form>
        </div>
    </div>

    <!-- LIST OF HOSPITALS -->
    <div class="col-md-8">
        <div class="card-box">
            <h4><i class="fa fa-list"></i> Existing Hospitals</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Region</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($hospitals)) { foreach($hospitals as $h) { ?>
                        <tr>
                            <td style="width: 100px;">
                                <img src="../upload/<?php echo $h['himage']; ?>" width="80" height="60" style="object-fit: cover; border-radius: 4px;">
                            </td>
                            <td style="font-weight: bold; color: #333;"><?php echo $h['hname']; ?></td>
                            <td><?php echo $h['hregion']; ?></td>
                            <td>
                                <small><?php echo substr($h['description'], 0, 80); ?>...</small>
                            </td>
                            <td>
                                <a href="hospital.php?del_id=<?php echo $h['hid']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this hospital?')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='5' class='text-center'>No hospitals added yet.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>