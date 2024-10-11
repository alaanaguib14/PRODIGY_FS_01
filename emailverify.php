<?php
include "mail.php";
if(isset($_SESSION['user_id'])){
    header("index.php");
}
if(isset($_POST['verify'])){
    $_SESSION['email'] = htmlspecialchars(strip_tags($_POST['email']));
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid Email format";
    }else{
        $old_time=time(); // TIME AS IT IS
        $_SESSION['time']=$old_time;
        $select="SELECT *FROM `user` WHERE `email`='$email'";
        $runselect=mysqli_query($connect,$select);
        if(mysqli_num_rows($runselect) > 0){
            $fetch=mysqli_fetch_assoc($runselect);
            $user_name=$fetch['user_name'];
            $rand=rand(10000,99999);
            $email_content = "
            <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fffffa; color: #00000a; line-height: 1.6;'>
                <div style='background-color: #080a74; padding: 20px; text-align: center; color: #fffffa;'>
                    <h1 style='color: #fffffa;'>Password Reset Request</h1>
                </div>
                <div style='padding: 20px; background-color: #f7faffd3; color: #00000a; border-radius: 25px; box-shadow: -2px 13px 32px 0px rgba(0, 0, 0, 0.378); transition: all 0.5s; margin-top: 5%; margin-bottom: 5%;'>
                    <p style='color: #00000a;'>Dear $user_name,</p>
                    <p style='color: #00000a;'>We received a request to reset your password. Your verification code is:</p>
                    <div style='text-align: center;'>
                        <h2 style='color: #080a74; background-color: #f6d673; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center; display: inline-block;'>$rand</h2>
                    </div>
                    <p style='color: #00000a;'>Please enter this code on the password reset page to proceed.</p>
                    <p style='color: #00000a;'>If you did not request a password reset, please ignore this email. Your account remains secure.</p>
                    <p style='color: #00000a;'>Thank you for using Prodigy!</p>
                    <p style='color: #00000a;'>Best regards,<br>The Prodigy Team</p>
                </div>
                <div style='background-color: #f6d673; color: #080a74; padding: 20px; text-align: center; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;'>
                    <p style='color: #080a74;'>For support and updates, please visit our website or contact us via email.</p>
                    <p style='color: #080a74;'>Email: <a href='mailto:MiDlancerTeam@gmail.com' style='color: #080a74;'>MiDlancerTeam@gmail.com</a></p>
                </div>
            </body>
            ";
            $_SESSION["otp"]=$rand;

            global $mail;
            $mail->setFrom('MiDlancerTeam@gmail.com', 'MiDlancer');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body=($email_content);
            $mail->send();

            header("location:forgotPassword.php");
        }else{
            $error = "Email is Incorrect";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <!-- Font Awesome -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="login-container">

        <a href="login.php" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
        </a>

        <form class="login-form" id="loginForm" method="POST">

            <h2>Email Verification</h2>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <?php if(!empty($error)) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $error ?>
                </div>
            <?php } ?>

            <button type="submit" name="verify" class="btn">Verify</button>

        </form>
    </div>

    <script src="./js/emailverify.js"></script>
</body>
</html>