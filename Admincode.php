<?php
Session_start();
include('dbcon.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['adminsubmit'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email']; 
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $verificationCode=md5(rand());

    function sendemmail_verify($fullname,$email,$verificationCode){

      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'hlamu01mabunda@gmail.com';
      $mail->Password = 'hjeuyvayoiwrcjiv';
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;
      $mail->setFrom('hlamu01mabunda@gmail.com', 'Guess House and Spa');
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = "Email verification from Guest House And Spa";
      $email_template ="
     <h2>You have registered to our Guest House & Spa...</h2>
     <h5>Please verify your email address to login with the below given link</h5>
     <br><br>
     <a href='http://localhost/GUESTHOUSEANDSPAPROJECT/Adminverify-email.php?token=$verificationCode'>click here</a>
    ";    
      $mail->Body = $email_template;
      try {
        $mail->send();
        echo 'Message has been sent';
      } catch (Exception $e) 
      {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }      
    }
    $check_email_query = "SELECT EMAIL FROM admins WHERE EMAIL='$email'LIMIT 1"; 
    $check_email_query_run = mysqli_query($con,$check_email_query);

    if(mysqli_num_rows($check_email_query_run)>0)
    {
    $_SESSION['status'] ="Email already exists";
    header("Location:Admin_sign-up.php");
    exit(0);
    }
    else{
      $query ="INSERT INTO admins (FULLNAME,EMAIL,GENDER,PASSWORD,VERIFICATION_CODE)VALUES('$fullname','$email','$gender','$password','$verificationCode')";
      $query_run = mysqli_query($con,$query);

      if ($query_run) {
        sendemmail_verify($fullname, $email, $verificationCode); // Removed double quotes here
        $_SESSION['status'] = "Registration Successfull. Please verify your email address.";
        header("Location: Admin_sign-up.php");
        exit(0);
    } else {
        $_SESSION['status'] = "Registration Failed";
        header("Location: Admin_sign-up.php");
        exit(0);
    }
    }
  }
    

?>