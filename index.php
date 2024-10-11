<?php
include "connection.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Style Link -->
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <a href="./index.php" class="logo">MyWebsite</a>
        <ul class="nav-links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./index.php">Services</a></li>
            <?php if(isset($_SESSION['user_id'])) {?>
                <li><a href="#">Profile</a></li>
                <form method="post">
                    <li><button type="submit" name="logout">Logout</button></li>
                </form>
            <?php }else{ ?>
                <li><a href="./login.php">Login</a></li>
            <?php } ?>

        </ul>
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <h1>Welcome to <span>My Website</span></h1>
        <p>Your success is our priority. Explore our services to know more.</p>
        <?php if(!isset($_SESSION['user_id'])) {?>
            <a href="./signup.php" class="btn">Get Started</a>
        <?php } ?>
    </div>
</header>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>About Us</h3>
                <p>My website provide you an excellent services.</p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: info@mywebsite.com</p>
                <p>Phone: +123 456 7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 MyWebsite. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="./js/index.js"></script>
</script>
</body>
</html>