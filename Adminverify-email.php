<?php
session_start();
include('dbcon.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT VERIFICATION_CODE, VERIFICATION_STATUS FROM admins WHERE VERIFICATION_CODE='$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if (mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_array($verify_query_run);
        if ($row['VERIFICATION_STATUS'] == "0") 
        {
            $clicked_token = $row['VERIFICATION_CODE'];
            $update_query = "UPDATE admins SET VERIFICATION_STATUS='1' WHERE VERIFICATION_CODE='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con, $update_query);

            if ($update_query_run) {
                $_SESSION['status'] = "Your account has been verified successfully...";
                header("Location: Adminsignin.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Verification Failed...";
                header("Location: Adminsignin.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Email already verified...";
            header("Location: Adminsignin.php");
        }
    } else {
        $_SESSION['status'] = "This token does not exist";
        header("Location: Adminsignin.php");
    }
} else {
    $_SESSION['status'] = "Not allowed";
    header("Location: Adminsignin.php");
}
?>
