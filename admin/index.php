<?php 
require('../config/autoload.php'); 
$dao = new DataAccess();

if(isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    
    // Check credentials
    $q = "SELECT * FROM admin WHERE username='$u' AND password='$p'";
    $info = $dao->query($q);
    
    if(!empty($info)) {
        session_start();
        $_SESSION['admin_user'] = $u;
        echo "<script>location.replace('home.php');</script>";
    } else {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - ChildHealthKarnataka</title>
    <!-- ONLINE BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { 
            background-color: #eef2f3; 
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h3 {
            color: #333;
            font-weight: bold;
            margin-top: 10px;
        }
        .login-header img {
            width: 80px;
        }
        .btn-custom { 
            background-color: lightskyblue; 
            color: white; 
            width: 100%; 
            font-weight: bold;
            padding: 10px;
            border: none;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: deepskyblue;
        }
        .form-control {
            height: 45px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-header">
            <!-- You can use the logo here if you want -->
            <i class="fa fa-user-md" style="font-size: 50px; color: lightskyblue;"></i>
            <h3>Admin Panel</h3>
            <p>ChildHealthKarnataka</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label><i class="fa fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            </div>
            <br>
            <button name="login" class="btn btn-custom">LOGIN</button>
        </form>
    </div>

</body>
</html>