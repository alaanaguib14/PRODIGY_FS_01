<?php
include "mail.php";
$error = "";
if(isset($_SESSION['email']) && !isset($_SESSION['user_id'])){
    $email = $_SESSION['email'];
}else{
    header("location:login.php");
}
if(isset($_POST['submit'])){
    $select = "SELECT * FROM `user` WHERE `email`='$email'";
    $runSelect = mysqli_query($connect, $select);
    $fetch = mysqli_fetch_assoc($runSelect);
    $user_name = $fetch['user_name'];
    $new_pass = mysqli_real_escape_string($connect, $_POST['password']);
    $confirm_pass = mysqli_real_escape_string($connect, $_POST['confirm_pass']);

    $uppercase = preg_match('@[A-Z]@', $new_pass);
    $lowercase = preg_match('@[a-z]@', $new_pass);
    $number = preg_match('@[0-9]@', $new_pass);
    $character = preg_match('@[^/w]@', $new_pass);

    if (empty($new_pass) || empty($confirm_pass)){
        $error= "You must enter a new password";
    }else if ($uppercase < 1 || $lowercase < 1 || $number < 1 || $character < 1){
        $error="Password must contain uppercase, lowercase, numbers, characters";
    }else{
        if ($new_pass == $confirm_pass){
            $newHashPass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = "UPDATE `user` SET `password`='$newHashPass' WHERE `email`='$email'";
            $run_update = mysqli_query($connect, $update);

            if ($run_update){
                $email_content = "
                <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fffffa; color: #00000a; line-height: 1.6;'>
                    <div style='background-color: #080a74; padding: 20px; text-align: center; color: #fffffa;'>
                        <h1 style='color: #fffffa;'>Password Reset Successful</h1>
                    </div>
                    <div style='padding: 20px; background-color: #f7faffd3; color: #00000a; border-radius: 25px; box-shadow: -2px 13px 32px 0px rgba(0, 0, 0, 0.378); transition: all 0.5s; margin-top: 5%; margin-bottom: 5%;'>
                        <p style='color: #00000a;'>Dear $user_name,</p>
                        <p style='color: #00000a;'>Your password has been reset successfully. You can now log in with your new password.</p>
                        <p style='color: #00000a;'>If you did not request this change, please contact our support team immediately.</p>
                        <p style='color: #00000a;'>Thank you for using Prodigy!</p>
                        <p style='color: #00000a;'>Best regards,<br>The Prodigy Team</p>
                    </div>
                    <div style='background-color: #f6d673; color: #080a74; padding: 20px; text-align: center; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;'>
                        <p style='color: #080a74;'>For support and updates, please visit our website or contact us via email.</p>
                        <p style='color: #080a74;'>Email: <a href='mailto:MiDlancerTeam@gmail.com' style='color: #080a74;'>MiDlancerTeam@gmail.com</a></p>
                    </div>
                </body>
                ";
                $mail->setFrom('MiDlancerTeam@gmail.com', 'MiDlancer');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Successfully';
                $mail->Body = ($email_content);
                $mail->send();
                unset($_SESSION['otp'], $_SESSION['email']);

                header("Location:login.php");
            }else{
                $error= "New password doesn't match confirm password";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="./css/login.css">

</head>
<body>
    <div class="login-container">
        <form class="login-form" id="loginForm" method="POST">
            <h2>Reset Password</h2>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-group">
                <label for="confirm_pass">Confirm Password</label>
                <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm your password" required>
            </div>

            <?php if(!empty($error)) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $error ?>
                </div>
            <?php } ?>

            <button type="submit" name="submit" class="btn">Reset</button>

        </form>
    </div>

    <script src="./js/forgot.js"></script>
</body>
</html>