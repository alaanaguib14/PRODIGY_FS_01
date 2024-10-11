<?php
include 'mail.php';

$error = "";
if(isset($_SESSION['rand'], )) // prevent hopping on the page
{
    $rand=$_SESSION['rand'];
    $name=$_SESSION['user_name'];
    $email=$_SESSION['email'];
    $phone=$_SESSION['phone_number'];
    $passwordhashing=$_SESSION['password'];
  

    if(isset($_POST['submit']))
    {
        if (!isset($_POST['otp1'], $_POST['otp2'], $_POST['otp3'], $_POST['otp4'], $_POST['otp5']))
            $error = "Please fill all OTP fields";
        else
        {
            $otp= $_POST['otp1'].$_POST['otp2'].$_POST['otp3'].$_POST['otp4'].$_POST['otp5'];
            $current_time=time();

            if ($otp != $rand)
                $error= "Incorrect OTP";
            else if (($otp == $rand) && ($current_time - $_SESSION['time'] > 60)) // ASSUMING 60, COULD BE LESS - BACK DECIDE
                $error= "OTP expired";
            else
            {
                $email_content = "
                <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fffffa; color: #00000a; line-height: 1.6;'>
                    <div style='background-color: #080a74; padding: 20px; text-align: center; color: #fffffa;'>
                        <h1 style='color: #fffffa;'>Welcome to Prodigy, <span style='color: #f6d673;'>$name</span>!</h1>
                    </div>
                    <div style='padding: 20px; background-color: #f7faffd3; color: #00000a; border-radius: 25px; box-shadow: -2px 13px 32px 0px rgba(0, 0, 0, 0.378); transition: all 0.5s; margin-top: 5%; margin-bottom: 5%;'>
                        <p style='color: #00000a;'>Dear <span style='color: #080a74; background-color: #f6d673; padding: 2px 4px; border-radius: 3px;'>$name</span>,</p>
                        <p style='color: #00000a;'>Thank you for registering on Prodigy as a client! We are thrilled to have you on board.</p>
                        <p style='color: #00000a;'>Here are some things you can do to get started:</p>
                        <ul>
                            <li style='color: #00000a;'>Check out the <a style='color: #080a74; background-color: #f6d673; padding: 2px 4px; border-radius: 3px;'>Career</a> and <a style='color: #080a74; background-color: #f6d673; padding: 2px 4px; border-radius: 3px;'>Freelancers</a> pages.</li>
                            <li style='color: #00000a;'>Hire freelancers to help you with your projects.</li>
                        </ul>
                        <p style='color: #00000a;'>If you have any questions or need assistance, feel free to reach out to our support team at any time.</p>
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
                $mail->Subject = 'Welcome Aboard';
                $mail->Body=($email_content);
                $mail->send();
                $insert="INSERT INTO `user` VALUES(NULL,'$name','$email','$passwordhashing','$phone')";
                $run_insert=mysqli_query($connect,$insert);
                header("location:login.php");
            }
        }

    }
    if (isset($_POST['resend']))
    {
        $email=$_SESSION['email'];

        $rand=rand(10000,99999);

        $email_content = "
        <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fffffa; color: #00000a; line-height: 1.6;'>
            <div style='background-color: #080a74; padding: 20px; text-align: center; color: #fffffa;'>
                <h1 style='color: #fffffa;'>Complete Your Registration - Code Resent</h1>
            </div>
            <div style='padding: 20px; background-color: #f7faffd3; color: #00000a; border-radius: 25px; box-shadow: -2px 13px 32px 0px rgba(0, 0, 0, 0.378); transition: all 0.5s; margin-top: 5%; margin-bottom: 5%;'>
                <p style='color: #00000a;'>Dear <span style='color: #080a74;'>$name</span>,</p>
                <p style='color: #00000a;'>Thank you for registering with Prodigy! Please use the OTP we've resent you the OTP, use it to verify your email address and complete your registration:</p>
                <div style='text-align: center''>
                    <p style='text-align: center; font-size: 24px; font-weight: bold; color: #080a74; background-color: #f6d673; padding: 10px; border-radius: 5px; display: inline-block;'>$rand</p>
                </div>
                <p style='color: #00000a;'>If you did not request this registration, please ignore this email.</p>
                <p style='color: #00000a;'>Best regards,<br>The Prodigy Team</p>
            </div>
            <div style='background-color: #f6d673; padding: 20px; text-align: center; color: #080a74; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;'>
                <p style='color: #080a74;'>For support and updates, please visit our website or contact us via email.</p>
                <p style='color: #080a74;'>Email: <a href='mailto:MiDlancerTeam@gmail.com' style='color: #080a74;'>MiDlancerTeam@gmail.com</a></p>
            </div>
        </body>";

        $_SESSION['rand'] = $rand;
        $old_time=time(); // new start point
        $_SESSION['time']=$old_time;

        $mail->setFrom('MiDlancerTeam@gmail.com', 'MiDlancer');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Account Activation Code';
        $mail->Body=($email_content);
        $mail->send();
    }
}
else{
    $error = "NOT AUTHORIZED";
    // header("Location: index.php"); // possible: login >redirect> home (if already logged in)
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/otp_.css">
</head>
<body>
    <div class="otp-container">
        <h2>OTP Verification</h2>
        <div class="otp-field">
            <input type="text" id="otp1" maxlength="1" autofocus name="otp1">
            <input type="text" id="otp2" maxlength="1" disabled name="otp2">
            <input type="text" id="otp3" maxlength="1" disabled name="otp3">
            <input type="text" id="otp4" maxlength="1" disabled name="otp4">
            <input type="text" id="otp5" maxlength="1" disabled name="otp5">
        </div>

        <?php if(!empty($error)) { ?>
            <div class="alert alert-warning" role="alert">
                <?php echo $error ?>
            </div>
        <?php } ?>

        <button type="submit" name="submit" class="btn" disabled>Submit</button>

        <div class="resend-container">
            <span>Didn't receive code?</span>
            <button type="submit" name="resend" class="resend-btn">Resend</button>
        </div>
    </div>

    <script src="./js/otp.js"></script>
</body>
</html>
