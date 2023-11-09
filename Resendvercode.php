<?php
Session_start();
include ('dbcon.php');
if(isset($_POST['resendverification'])){

    if(!empty(trim($_POST['email'])))
    {
       $email = mysqli_real_escape_string($con,$_POST['email']);
       $checkemail_query = "SELECT * FROM admins WHERE EMAIL='$email' LIMIT 1";
       $checkemail_query_run = mysqli_query($con,$checkemail_query);

       if(mysqli_num_rows($checkemail_query_run)>0)
       {
         $row = mysqli_fetch_array($checkemail_query_run);
         if($row['VERIFICATION_STATUS'] =="0")
         {
            $fullname =$row['fullname'];
            $email = $row['email'];
            $verification_code=$row['verification_code'];

           function resend_email_verification($fullname,$email,$verification_code){
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
            $mail->Subject = "RESEND - Email verification from Guest House And Spa";
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
            } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            
          }
          

           
            $_SESSION['status']=" Verification link has been sent to your email you provided, please verify...";
        header("Location: Adminsignin.php");
        exit(0);
         }
         else
         {
            $_SESSION['status']="Email already verified. Please try to reset your login details.";
        header("Location: adminresendvercode.php");
        exit(0);
         }

       }
       else
       {
        $_SESSION['status']="Email is not registered";
        header("Location: Admin_sign-up.php");
        exit(0);
       }
      
    }
    else 
    {
        $_SESSION['status']="Please enter the email field";
        header("Location: adminresendvercode.php");
        exit(0);
    }
}
?>