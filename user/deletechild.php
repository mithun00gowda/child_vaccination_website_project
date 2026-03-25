<?php 
require('../config/autoload.php'); // Ensure sessions are initialized
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("header2.php");

// 1. Broken Access Control Patch: Ensure parent is actually logged in
if(!isset($_SESSION['pid'])) {
    echo "<script> alert('Unauthorized access. Please login.'); location.replace('login.php'); </script>";
    exit;
}

// 2. Input Validation: Ensure ID is present and is a number
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script> alert('Invalid request.'); location.replace('viewchilds.php'); </script>";
    exit;
}

// 3. Sanitization
$cid = intval($_GET['id']);
$pid = intval($_SESSION['pid']); // Get the currently logged-in parent ID

// 4. Secure Database Connection
// NOTE: Ideally, use the connection from your DataAccess class, but keeping mysqli as requested
$conn = new mysqli("localhost", "root", "", "vaccine"); // Make sure dbname matches your setup
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 5. SQL Injection & IDOR Patch: Use Prepared Statements
// Only delete if the child ID matches AND it belongs to the logged-in parent
$stmt = $conn->prepare("DELETE FROM child WHERE cid = ? AND pid = ?");
$stmt->bind_param("ii", $cid, $pid);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "<script> alert('Child details dropped successfully.'); location.replace('viewchilds.php'); </script>";
    } else {
        // If 0 rows affected, either the child doesn't exist OR it belongs to another parent
        echo "<script> alert('Action failed. Record not found or you do not have permission.'); location.replace('viewchilds.php'); </script>";
    }
} else {
    echo "<script> alert('Database error occurred.'); location.replace('viewchilds.php'); </script>";
}

$stmt->close();
$conn->close();
?>