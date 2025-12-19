<?php 
require('../config/autoload.php'); 
include("header.php"); 
$dao = new DataAccess();

// ADD SLOTS (SCHEDULE)
if(isset($_POST['add_schedule'])) {
    
    // We check if a schedule already exists for this hospital+vaccine+date
    $hname = $_POST['hname'];
    $vname = $_POST['vname'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];

    // Optional: Check duplication
    $check = $dao->query("SELECT * FROM schedule WHERE hname='$hname' AND vname='$vname' AND date='$date'");

    if(!empty($check)) {
        // If exists, Update quantity
        $new_qty = $check[0]['quantity'] + $quantity;
        $sid = $check[0]['sid'];
        $dao->query("UPDATE schedule SET quantity=$new_qty WHERE sid=$sid");
        echo "<script>alert('Slots updated for existing schedule!'); location.replace('schedule.php');</script>";
    } else {
        // If new, Insert
        $data = array(
            'hname' => $hname,
            'vname' => $vname,
            'date' => $date,
            'quantity' => $quantity
        );
        if($dao->insert($data, 'schedule')) {
            echo "<script>alert('New Slots Added Successfully!'); location.replace('schedule.php');</script>";
        }
    }
}

// DELETE SCHEDULE
if(isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $dao->query("DELETE FROM schedule WHERE sid=$id");
    echo "<script>alert('Schedule Deleted'); location.replace('schedule.php');</script>";
}

// Fetch Lists
$hospitals = $dao->query("SELECT hname FROM healthcenter");
$vaccines = $dao->query("SELECT vname FROM vaccine");
$schedules = $dao->query("SELECT * FROM schedule ORDER BY date DESC");
?>

<div class="row">
    <!-- ADD SCHEDULE FORM -->
    <div class="col-md-4">
        <div class="card-box">
            <h4><i class="fa fa-calendar-plus-o"></i> Add Slots</h4>
            <form method="POST">
                
                <div class="form-group">
                    <label>Select Hospital</label>
                    <select name="hname" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php if(!empty($hospitals)) { foreach($hospitals as $h) { echo "<option>{$h['hname']}</option>"; } } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Select Vaccine</label>
                    <select name="vname" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php if(!empty($vaccines)) { foreach($vaccines as $v) { echo "<option>{$v['vname']}</option>"; } } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label>Quantity (Slots)</label>
                    <input type="number" name="quantity" class="form-control" placeholder="e.g. 50" required>
                </div>

                <button name="add_schedule" class="btn btn-success btn-block">Create Slots</button>
            </form>
        </div>
    </div>

    <!-- SCHEDULE LIST -->
    <div class="col-md-8">
        <div class="card-box">
            <h4><i class="fa fa-list-alt"></i> Active Slots</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Hospital</th>
                            <th>Vaccine</th>
                            <th>Slots Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($schedules)) { foreach($schedules as $s) { ?>
                        <tr>
                            <td><?php echo $s['date']; ?></td>
                            <td style="color:lightskyblue; font-weight:bold;"><?php echo $s['hname']; ?></td>
                            <td><?php echo $s['vname']; ?></td>
                            <td><span class="label label-success" style="font-size:12px;"><?php echo $s['quantity']; ?></span></td>
                            <td>
                                <a href="schedule.php?del_id=<?php echo $s['sid']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Remove these slots?')">Delete</a>
                            </td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='5' align='center'>No slots added yet.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>