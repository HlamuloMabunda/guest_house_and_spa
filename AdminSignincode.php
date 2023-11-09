<?php
session_start();
include('dbcon.php');

if (isset($_POST['adminsignin'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Select user by email and password
        $login_query = "SELECT * FROM admins WHERE EMAIL='$email' AND PASSWORD='$password' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);

            if ($row['VERIFICATION_STATUS'] == "1") {
                // User is verified, update the session and redirect
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_user'] = [
                    'username' => $row['FULLNAME'],
                    'email' => $row['EMAIL'],
                ];

                $_SESSION['status'] = "You are logged in...";

                // Redirect to the home page
                header("Location: index.html");
                exit(0);
            } else {
                // User is not verified, handle accordingly
                $_SESSION['status'] = "Please verify your email to login...";
                header("Location: Adminsignin.php");
                exit(0);
            }
        } else {
            // Invalid email or password
            $_SESSION['status'] = "Invalid Email or Password...";
            header("Location: Adminsignin.php");
            exit(0);
        }
    } else {
        // Incomplete form submission
        $_SESSION['status'] = "Please fill in all fields in order to login...";
        header("Location: Adminsignin.php");
        exit(0);
    }
}
?>
