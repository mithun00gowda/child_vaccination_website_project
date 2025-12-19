<?php 
require('../config/autoload.php'); 
include("header.php"); 
$dao = new DataAccess();

// 1. ADD NEW STAFF
if(isset($_POST['add_staff'])) {
    $hname = $_POST['hname'];       // Staff Name
    $h_email = $_POST['h_email'];   // Login Email
    $password = $_POST['password']; // Login Password
    $hid = $_POST['hid'];           // Selected Hospital ID

    // Check if email already exists
    $check = $dao->query("SELECT * FROM healthworker WHERE h_email='$h_email'");
    
    if(!empty($check)) {
        echo "<script>alert('This email is already registered!');</script>";
    } else {
        $data = array(
            'hname' => $hname,
            'h_email' => $h_email,
            'password' => $password,
            'hid' => $hid
        );
        
        if($dao->insert($data, 'healthworker')) {
            echo "<script>alert('Staff Account Created Successfully!'); location.replace('staff.php');</script>";
        } else {
            echo "<script>alert('Failed to create account.');</script>";
        }
    }
}

// 2. DELETE STAFF
if(isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $dao->query("DELETE FROM healthworker WHERE hwid=$id");
    echo "<script>alert('Staff Account Deleted'); location.replace('staff.php');</script>";
}

// FETCH LISTS
// Get all hospitals for the dropdown
$hospitals = $dao->query("SELECT hid, hname FROM healthcenter");

// Get all staff with their hospital names (using JOIN)
$staff_list = $dao->query("SELECT w.hwid, w.hname as worker_name, w.h_email, h.hname as hospital_name 
                           FROM healthworker w 
                           JOIN healthcenter h ON w.hid = h.hid 
                           ORDER BY w.hwid DESC");
?>

<div class="row">
    <!-- FORM TO ADD STAFF -->
    <div class="col-md-4">
        <div class="card-box">
            <h4><i class="fa fa-user-plus"></i> Create Staff Login</h4>
            <form method="POST">
                
                <div class="form-group">
                    <label>Staff Name</label>
                    <input type="text" name="hname" class="form-control" placeholder="e.g. Dr. Ramesh" required>
                </div>

                <div class="form-group">
                    <label>Assign Hospital</label>
                    <select name="hid" class="form-control" required>
                        <option value="">-- Select Hospital --</option>
                        <?php 
                        if(!empty($hospitals)) {
                            foreach($hospitals as $h) { 
                                echo "<option value='".$h['hid']."'>".$h['hname']."</option>"; 
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Login Email</label>
                    <input type="email" name="h_email" class="form-control" placeholder="staff@hospital.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" class="form-control" placeholder="Enter Password" required>
                </div>

                <button name="add_staff" class="btn btn-primary btn-block">Create Account</button>
            </form>
        </div>
    </div>

    <!-- LIST OF EXISTING STAFF -->
    <div class="col-md-8">
        <div class="card-box">
            <h4><i class="fa fa-users"></i> Existing Staff Accounts</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Hospital</th>
                            <th>Login Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($staff_list)) { foreach($staff_list as $s) { ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo $s['worker_name']; ?></td>
                            <td style="color: lightskyblue;"><?php echo $s['hospital_name']; ?></td>
                            <td><?php echo $s['h_email']; ?></td>
                            <td>
                                <a href="staff.php?del_id=<?php echo $s['hwid']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this account?')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='4' class='text-center'>No staff accounts found.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>