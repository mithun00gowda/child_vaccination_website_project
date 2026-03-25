<?php 
require('../config/autoload.php'); 

// Check who is logged in - FIX FOR SESSION BUG
// Prioritizing 'usertype' ensures lingering Parent sessions don't override the Health Worker view
if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'healthworker') {
    include("header_health.php");
    $user_email = $_SESSION['h_email']; // Health workers use 'h_email'
} elseif(isset($_SESSION['username'])) {
    include("header2.php");
    $user_email = $_SESSION['username']; // Parents use 'username' during login
} elseif(isset($_SESSION['email'])) {
    include("header2.php");
    $user_email = $_SESSION['email']; 
} else {
    echo "<script>alert('Please login to view notifications.'); location.replace('index.php');</script>";
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
        <?php if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'healthworker'): ?>
            <!-- Header for Health Care Workers -->
            <h2 class="page-title" style="color: white">Health Center Alerts</h2>
            <p style="color: white">Important updates regarding patient bookings and schedules.</p>
        <?php else: ?>
            <!-- Header for Logged-In Users (Parents) -->
            <h2 class="page-title" style="color: white">Your Notifications</h2>
            <p style="color: white">Important updates regarding your child's vaccination bookings.</p>
        <?php endif; ?>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    
                    <h3 style="color: black; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                        <i class="fa fa-bell" style="color: lightskyblue;"></i> Recent Alerts
                    </h3>

                    <?php if(empty($notifications) || !is_array($notifications)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <i class="fa fa-bell-slash" style="font-size: 50px; color: #ccc; margin-bottom: 15px;"></i>
                            <p style="color: gray; font-size: 18px;">You have no new notifications.</p>
                        </div>
                    <?php else: ?>
                        <ul style="list-style-type: none; padding: 0;">
                            <?php foreach($notifications as $notif): ?>
                                <li style="border-bottom: 1px solid #eee; padding: 15px 10px; transition: background 0.3s;">
                                    <p style="margin: 0; font-size: 16px; color: #333;">
                                        <i class="fa fa-check-circle" style="color: #4CAF50; margin-right: 10px;"></i>
                                        <?php echo htmlspecialchars($notif['message']); ?>
                                    </p>
                                    <small style="color: #999; margin-left: 25px; display: block; margin-top: 5px;">
                                        <i class="fa fa-clock-o"></i> <?php echo date('d M Y, h:i A', strtotime($notif['date_created'])); ?>
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

<?php 
if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'healthworker') {
    // Cannot include footer2 for health workers as they might have a different setup
} else {
    include("footer2.php"); 
}
?>