<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['admin_user'])) { echo "<script>location.replace('index.php');</script>"; exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    
    <!-- ONLINE BOOTSTRAP CSS (The Fix) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Roboto', sans-serif;
        }
        /* Navbar Styling */
        .navbar-custom { 
            background-color: #fff; 
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 10px 0;
        }
        .navbar-custom .navbar-brand { 
            color: lightskyblue !important; 
            font-weight: bold; 
            font-size: 24px;
        }
        .navbar-custom .navbar-nav > li > a { 
            color: #555 !important; 
            font-weight: 500;
            font-size: 16px;
        }
        .navbar-custom .navbar-nav > li > a:hover { 
            color: lightskyblue !important; 
        }
        
        /* Main Content Styling */
        .content-area { 
            padding: 30px; 
            margin-top: 20px;
        }
        
        /* Card Boxes (Used in Home, etc) */
        .card-box { 
            background: white; 
            border: none; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
            margin-bottom: 20px;
        }
        .card-box h4 {
            margin-top: 0;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid lightskyblue;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        /* Form Inputs */
        .form-control {
            border-radius: 4px;
            height: 40px;
            box-shadow: none;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: lightskyblue;
        }
        
        /* Buttons */
        .btn-primary, .btn-success {
            border: none;
            padding: 10px 20px;
            font-weight: bold;
        }
        
        /* Tables */
        .table {
            background: white;
        }
        .table thead th {
            background-color: lightskyblue;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-default navbar-custom">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php"><i class="fa fa-heartbeat"></i> ChildHealthKarnataka</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
    <li><a href="home.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="hospital.php"><i class="fa fa-hospital-o"></i> Hospitals</a></li>
    <li><a href="staff.php"><i class="fa fa-user-md"></i> Hospital Staff</a></li> <!-- NEW LINK -->
    <li><a href="vaccine.php"><i class="fa fa-medkit"></i> Vaccines</a></li> <!-- NEW LINK -->
    <li><a href="schedule.php"><i class="fa fa-calendar"></i> Schedules</a></li>
  </ul>
  <ul class="nav navbar-nav navbar-right">
    <li><a href="logout.php" style="color: red !important;"><i class="fa fa-sign-out"></i> Logout</a></li>
  </ul>
</div>
  </div>
</nav>

<div class="container content-area">