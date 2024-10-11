<?php 
include 'mail.php';
$error = "";

if(isset($_POST['signup'])){
    $name = htmlspecialchars(strip_tags(mysqli_real_escape_string($connect, $_POST['user_name'])));
    $phone = htmlspecialchars(strip_tags(mysqli_real_escape_string($connect, $_POST['phone'])));
    $email = htmlspecialchars(strip_tags(mysqli_real_escape_string($connect, $_POST['email'])));
    $password = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['password']));
    $confirm_pass= htmlspecialchars($_POST['confirm_pass']);
    $passwordhashing = password_hash($password, PASSWORD_DEFAULT);
    $lowercase = preg_match('@[a-z]@',$password);
    $uppercase = preg_match('@[A-Z]@',$password);
    $numbers = preg_match('@[0-9]@',$password);
    $select = "SELECT * FROM `user` WHERE `email` ='$email' ";
    $run_select = mysqli_query($connect,$select);
    $rows = mysqli_num_rows($run_select);
    
    if($rows>0)
        $error= "This email is already taken";
    elseif ($lowercase <1 || $uppercase <1 ||   $numbers<1)
        $error= "Password must contain at least 1 uppercase , 1 lowercase and number";
    elseif ($password !=$confirm_pass)
        $error= "Password doesn't match confirmed password";
    elseif (strlen($phone)!=11) // >>> 11 In Egypt <<<
        $error= "Please enter a valid phone number";
    else
    {
        $rand=rand(10000,99999);
        $_SESSION['rand']=$rand;
        $_SESSION['user_name']=$name;
        $_SESSION['email']=$email;
        $_SESSION['phone_number']=$phone;
        $_SESSION['password'] = $passwordhashing;
        $_SESSION['time'] = time(); // we start calc'ing from this point
        $massage="
        <body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fffffa; color: #00000a; line-height: 1.6;'>
            <div style='background-color: #080a74; padding: 20px; text-align: center; color: #fffffa;'>
                <h1 style='color: #fffffa;'>Complete Your Registration</h1>
            </div>
            <div style='padding: 20px; background-color: #f7faffd3; color: #00000a; border-radius: 25px; box-shadow: -2px 13px 32px 0px rgba(0, 0, 0, 0.378); transition: all 0.5s; margin-top: 5%; margin-bottom: 5%;'>
                <p style='color: #00000a;'>Dear <span style='color: #080a74;'>$name</span>,</p>
                <p style='color: #00000a;'>Thank you for registering with Prodigy! Please use the OTP below to verify your email address and complete your registration:</p>
                <div style='text-align: center;'>
                    <p style='text-align: center; font-size: 24px; font-weight: bold; color: #080a74; background-color: #f6d673; padding: 10px; border-radius: 5px; display: inline-block;'>$rand</p>
                </div>
                <p style='color: #00000a;'>If you did not request this registration, please ignore this email.</p>
                <p style='color: #00000a;'>Best regards,<br>The Prodigy Team</p>
            </div>
            <div style='background-color: #f6d673; padding: 20px; text-align: center; color: #080a74; border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;'>
                <p style='color: #080a74;'>For support and updates, please visit our website or contact us via email.</p>
                <p style='color: #080a74;'>Email: <a href='mailto:MiDlancerTeam@gmail.com' style='color: #080a74;'>MiDlancerTeam@gmail.com</a></p>
            </div>
        </body>
        ";
        $mail->setFrom('MiDlancerTeam@gmail.com', 'MiDlancer');
        $mail->addAddress($email);      
        $mail->isHTML(true);                               
        $mail->Subject = 'Account Activation code';
        $mail->Body=($massage);                 
        $mail->send(); 
        if($mail->send()) {
          header("Location: otp_signup.php"); 
        } else {
            $error = "Failed to send email. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link rel="stylesheet" href="./css/sign.css">
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form id="signupForm" method="POST">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="user_name" placeholder="Enter your name" required>
            </div>

            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

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

            <button type="submit" name="signup" class="btn">Sign Up</button>

            <div class="already-account">
                <a href="./login.php">Already have an account?</a>
            </div>
        </form>
    </div>
    <script src="./js/signup.js"></script>
</body>
</html>
