<?php
// 1. Start the session to access current session variables
session_start();

// 2. Unset specific session variables related to the health worker
if(isset($_SESSION['h_email'])) {
    unset($_SESSION['h_email']);
}
if(isset($_SESSION['hwid'])) {
    unset($_SESSION['hwid']);
}
if(isset($_SESSION['hid'])) {
    unset($_SESSION['hid']);
}
if(isset($_SESSION['usertype'])) {
    unset($_SESSION['usertype']);
}

// 3. Destroy the entire session (Optional but recommended for full logout)
session_destroy();

// 4. Redirect the user back to the Health Worker Login page
echo "<script> location.replace('healthlogin.php'); </script>";
exit;
?>