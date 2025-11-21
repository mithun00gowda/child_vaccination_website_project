<?php
require('../config/autoload.php'); 
include("header.php"); 
?>

<style>
    .success-container {
        text-align: center;
        padding: 50px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        margin-top: 50px;
        margin-bottom: 50px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .success-icon {
        color: #28a745;
        font-size: 80px;
        margin-bottom: 20px;
    }
    .btn-home {
        background-color: lightskyblue;
        color: black;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
    }
    .btn-home:hover {
        background-color: deepskyblue;
        color: white;
        text-decoration: none;
    }
</style>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Booking Status</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="success-container">
                    <div class="success-icon">
                        <i class="fa fa-check-circle">✔</i>
                    </div>

                    <h1 style="color: #28a745;">Booking Confirmed!</h1>
                    
                    <hr>
                    
                    <h3 style="color: black;">Your vaccination slot has been successfully reserved.</h3>
                    <p>Please arrive 15 minutes early at the health center with your child's ID.</p>
                    
                    <br>
                    
                    <a href="index2.php" class="btn-home">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>