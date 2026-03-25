<?php
session_start();

// Ensure data exists
if(!isset($_SESSION['email_parent'])) {
    header("Location: viewchilds.php");
    exit;
}

// 1. Get Data from Session
$parent_email = $_SESSION['email_parent'];
$doctor_emails = $_SESSION['email_doctors']; // This is an array
$body_parent = $_SESSION['email_body_parent'];
$body_doctor = $_SESSION['email_body_doctor'];

// 2. Email Headers
$headers = "From: no-reply@childhealth.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// 3. Send Email to Parent
$subject_parent = "Vaccination Booking Confirmed";
@mail($parent_email, $subject_parent, $body_parent, $headers);

// 4. Send Emails to Doctors/Health Workers
if(!empty($doctor_emails) && is_array($doctor_emails)) {
    $subject_doctor = "New Vaccination Appointment Scheduled";
    foreach($doctor_emails as $doc_email) {
        @mail($doc_email, $subject_doctor, $body_doctor, $headers);
    }
}

// 5. Clear Session Data
unset($_SESSION['email_parent']);
unset($_SESSION['email_doctors']);
unset($_SESSION['email_body_parent']);
unset($_SESSION['email_body_doctor']);

// 6. Alert User & Redirect
echo "<script>
        alert('Booking confirmed! Notifications have been sent to your email and the Health Center.');
        location.replace('viewbooking.php');
      </script>";
?>