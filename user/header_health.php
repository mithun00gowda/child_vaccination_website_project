<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(isset($_SESSION['h_email'])) { 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Worker Portal</title>
    <link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
    <link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="style.css">
    <style>
        .app-table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 20px; }
        .app-table th, .app-table td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        .app-table th { background-color: lightskyblue; color: black; }
        .btn-action { padding: 5px 10px; background: #28a745; color: white; border-radius: 4px; text-decoration: none; }
        .btn-print { padding: 10px 20px; background: #007bff; color: white; border:none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="site-content">
    <header class="site-header">
        <div class="container">
            <div class="header-bar">
                <a href="h.php" class="branding">
                    <img src="images/childvaccinelogo.png" alt="" class="logo">
                    <div class="logo-type">
                        <h1 class="site-title">ChildHealthKarnataka</h1>
                        <small class="site-description">Health Worker Portal</small>
                    </div>
                </a>
                <nav class="main-navigation">
                    <button class="menu-toggle"><i class="fa fa-bars"></i></button>
                    <ul class="menu">
                        <li class="menu-item"><a href="h.php">Dashboard</a></li>
                        <li class="menu-item"><a href="h_bookings.php">Pending Bookings</a></li>
                        <li class="menu-item"><a href="healthreport.php">Completed Reports</a></li>
                        <li class="menu-item"><a href="hlogout.php">Logout</a></li>
                    </ul>
                </nav>
                <div class="mobile-navigation"></div>
            </div>
        </div>
    </header>
<?php } ?>