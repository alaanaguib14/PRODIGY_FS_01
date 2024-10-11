<?php 
include "connection.php";
$error = "";
if(isset($_POST['login'])){
    $email= mysqli_real_escape_string($connect,$_POST['email']);
    $password = mysqli_real_escape_string($connect,$_POST['password']);

    if(empty($email)){
        $error = "email can't be empty";
    }
    if(empty($password)){
        $error = "password can't be empty";
    }else{
        $select_email = "SELECT * FROM `user` WHERE `email` = '$email'";
        $run_email = mysqli_query($connect,$select_email);
        if($run_email){
            if(mysqli_num_rows($run_email) > 0){
                $data = mysqli_fetch_assoc($run_email);
                $hashedPass = $data['password'];
                if (password_verify($password, $hashedPass)){
                    $_SESSION['user_id'] = $data['user_id'];
                    $_SESSION['user_name'] = $data['user_name'];
                    header("Location:index.php");
                }
                else{
                    $error ="Incorrect Password"; 
                }
            }else{
                $error ="Email isn't registered"; 
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
    <title>Login Form</title>
    <link rel="stylesheet" href="./css/login.css">

</head>
<body>
    <div class="login-container">
        <form class="login-form" id="loginForm" method="POST">
            <h2>Login</h2>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <?php if(!empty($error)) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $error ?>
                </div>
            <?php } ?>

            <button type="submit" name="login" class="btn">Login</button>
            <a href="./emailverify.php" class="forgot-password">Forgot Password?</a>
            <a href="./signup.php" class="signup">Don't have an account?</a>
        </form>
    </div>

    <script src="./js/login.js"></script>
</body>
</html>
