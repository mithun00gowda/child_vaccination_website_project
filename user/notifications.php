<?php 
require('../config/autoload.php'); 

// Check who is logged in
$is_healthworker = false;
if(isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'healthworker') {
    include("header_health.php");
    $user_email = $_SESSION['h_email']; // Health workers use 'h_email'
    $is_healthworker = true;
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

// --- NEW FEATURE: Clear All Notifications ---
if(isset($_POST['clear_all'])) {
    $dao->query("DELETE FROM notifications WHERE user_email='$user_email'");
    echo "<script>location.replace('notifications.php');</script>";
    exit;
}
// ------------------------------------------

// Mark all as read when they visit this page
$dao->query("UPDATE notifications SET is_read=1 WHERE user_email='$user_email'");

// Fetch notifications
$q = "SELECT * FROM notifications WHERE user_email='$user_email' ORDER BY date_created DESC";
$notifications = $dao->query($q);
?>

<style>
    /* Modern Dashboard Styling */
    .notif-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        margin-top: -50px; /* Overlaps the header banner for a modern look */
        position: relative;
        z-index: 10;
        margin-bottom: 50px;
    }
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #f4f6f8;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .notif-header h3 {
        margin: 0;
        color: #2c3e50;
        font-weight: bold;
    }
    .clear-btn {
        background-color: #fee2e2;
        color: #ef4444;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .clear-btn:hover {
        background-color: #fca5a5;
        color: #b91c1c;
    }
    .notif-item {
        background: #fafbfc;
        border: 1px solid #e2e8f0;
        border-left: 5px solid lightskyblue;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .notif-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transform: translateY(-2px);
        background: #fff;
    }
    .notif-item.health-alert {
        border-left-color: #f59e0b; /* Orange accent for Health Workers */
    }
    .notif-item.parent-alert {
        border-left-color: #10b981; /* Green accent for Parents */
    }
    .notif-text {
        font-size: 16px;
        color: #334155;
        line-height: 1.6;
        margin: 0 0 12px 0;
    }
    .notif-meta {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        font-weight: 500;
    }
    .notif-meta i {
        margin-right: 6px;
    }
    .page-head {
        padding: 60px 0 100px 0 !important; /* Extra padding to accommodate the overlapping card */
    }
</style>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <?php if($is_healthworker): ?>
            <h2 class="page-title" style="color: white; margin-bottom: 10px;">Health Center Alerts</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 16px;">Important updates regarding patient bookings and schedules.</p>
        <?php else: ?>
            <h2 class="page-title" style="color: white; margin-bottom: 10px;">Your Inbox</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 16px;">Updates, confirmations, and alerts regarding your child's health.</p>
        <?php endif; ?>
    </div>
</div>

<div class="fullwidth-block" style="background-color: #f8fafc; padding-top: 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                
                <div class="notif-container">
                    <div class="notif-header">
                        <h3><i class="fa fa-bell-o" style="color: <?php echo $is_healthworker ? '#f59e0b' : 'lightskyblue'; ?>; margin-right: 10px;"></i> Recent Alerts</h3>
                        
                        <?php if(!empty($notifications) && is_array($notifications)): ?>
                            <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete all notifications? This cannot be undone.');">
                                <button type="submit" name="clear_all" class="clear-btn">
                                    <i class="fa fa-trash-o"></i> Clear All
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if(empty($notifications) || !is_array($notifications)): ?>
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                <i class="fa fa-check" style="font-size: 40px; color: #cbd5e1;"></i>
                            </div>
                            <h4 style="color: #475569; font-weight: bold; margin-bottom: 10px;">You're all caught up!</h4>
                            <p style="color: #94a3b8; font-size: 16px;">There are no new notifications in your inbox right now.</p>
                        </div>
                    <?php else: ?>
                        <div class="notif-list">
                            <?php foreach($notifications as $notif): ?>
                                <?php 
                                    // Set dynamic class based on user type for color coding
                                    $alert_class = $is_healthworker ? 'health-alert' : 'parent-alert'; 
                                ?>
                                <div class="notif-item <?php echo $alert_class; ?>">
                                    <p class="notif-text">
                                        <!-- Note: We are echoing directly instead of htmlspecialchars so <b> and emojis render properly -->
                                        <?php echo $notif['message']; ?>
                                    </p>
                                    <div class="notif-meta">
                                        <i class="fa fa-clock-o"></i> 
                                        <?php 
                                            // Format date nicely (e.g., "12 Oct 2025 at 02:30 PM")
                                            echo date('d M Y \a\t h:i A', strtotime($notif['date_created'])); 
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php 
if($is_healthworker) {
    // Health Worker footer logic (if you have one, or leave blank)
} else {
    include("footer2.php"); 
}
?>