<?php 
require('../config/autoload.php'); 

// Check who is logged in
if(isset($_SESSION['email'])) {
    include("header2.php");
    $user_email = $_SESSION['email'];
} elseif (isset($_SESSION['h_email'])) {
    include("header_health.php");
    $user_email = $_SESSION['h_email'];
} else {
    echo "<script>location.replace('index.php');</script>";
    exit;
}

$dao = new DataAccess();

// Mark all as read when they visit this page
$dao->query("UPDATE notifications SET is_read=1 WHERE user_email='$user_email'");

// Fetch notifications
$q = "SELECT * FROM notifications WHERE user_email='$user_email' ORDER BY date_created DESC";
$notifications = $dao->query($q);
?>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Your Notifications</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    
                    <?php if(empty($notifications)): ?>
                        <p style="text-align: center; color: gray; font-size: 18px; padding: 30px;">You have no new notifications.</p>
                    <?php else: ?>
                        <ul style="list-style-type: none; padding: 0;">
                            <?php foreach($notifications as $notif): ?>
                                <li style="border-bottom: 1px solid #eee; padding: 15px 10px;">
                                    <p style="margin: 0; font-size: 16px; color: #333;">
                                        <i class="fa fa-bell" style="color: lightskyblue; margin-right: 10px;"></i>
                                        <?php echo $notif['message']; ?>
                                    </p>
                                    <small style="color: #999; margin-left: 25px;">
                                        <?php echo date('d M Y, h:i A', strtotime($notif['date_created'])); ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer2.php"); ?>