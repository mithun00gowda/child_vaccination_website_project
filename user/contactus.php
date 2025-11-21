<?php 
require('../config/autoload.php'); 
include("header2.php");

$dao = new DataAccess();
$elements = array(
    "name"=>"", "email"=>"", "subject"=>"", "message"=>""
);

$form = new FormAssist($elements, $_POST);

$labels = array(
    'name'=>"Name", 
    'email'=>"Email", 
    'subject'=>"Subject", 
    'message'=>"Message"
);

$rules = array(
    "name" => array("required"=>true),
    "email" => array("required"=>true),
    "subject" => array("required"=>true),
    "message" => array("required"=>true),
);
    
$validator = new FormValidator($rules, $labels);

if(isset($_POST["send"]))
{
    if($validator->validate($_POST))
    {
        $data = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'subject' => $_POST['subject'],
            'message' => $_POST['message'],
            'date' => date('Y-m-d')
        );
    
        if($dao->insert($data, "contact"))
        {
            echo "<script>alert('Message sent successfully! We will contact you soon.');</script>";
        }
        else
        {
            $msg = "Failed to send message"; 
            echo "<script>alert('$msg');</script>";
        }
    }   
}
?>

<style>
    .contact-info {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 5px;
        height: 100%;
    }
    .contact-icon {
        color: lightskyblue;
        font-size: 20px;
        margin-right: 10px;
    }
    .form-control {
        border-radius: 0;
        height: 45px;
    }
    textarea.form-control {
        height: auto;
    }
</style>

<div class="page-head" data-bg-image="images/abstract.jpg">
    <div class="container">
        <h2 class="page-title" style="color: white">Contact Us</h2>
    </div>
</div>

<div class="fullwidth-block">
    <div class="container">
        <div class="row">
            
            <div class="col-md-5">
                <div class="contact-info">
                    <h3 style="color:black;">Get in Touch</h3>
                    <p>Have questions about vaccinations or schedules? Feel free to contact us.</p>
                    <br>
                    <p><i class="contact-icon">📍</i> 123 Health Avenue, Kerala, India</p>
                    <p><i class="contact-icon">📞</i> +91 98765 43210</p>
                    <p><i class="contact-icon">✉️</i> support@vaccination.com</p>
                </div>
            </div>

            <div class="col-md-7">
                <div class="booking-form">
                    <h3 style="color:black; margin-bottom: 20px;">Send us a Message</h3>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Name</span>
                                    <input class="form-control" name="name" type="text" placeholder="Your Name" required>
                                    <?= $validator->error('name'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Email</span>
                                    <input class="form-control" name="email" type="email" placeholder="Your Email" required>
                                    <?= $validator->error('email'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="form-label">Subject</span>
                                    <input class="form-control" name="subject" type="text" placeholder="Subject" required>
                                    <?= $validator->error('subject'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="form-label">Message</span>
                                    <textarea class="form-control" name="message" rows="5" placeholder="Write your message here..." required></textarea>
                                    <?= $validator->error('message'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-btn">
                            <button class="submit-btn" name="send" style="background-color: lightskyblue; color: black; border: none; padding: 10px 30px; font-weight: bold;">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include("footer.php"); ?>