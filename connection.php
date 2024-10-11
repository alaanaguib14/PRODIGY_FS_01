<?php
$localhost = "localhost";
$username = "root";
$password = "";
$database = "prodigy1";

$connect = mysqli_connect($localhost,$username,$password,$database);
session_start();
ob_start();

if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location:index.php");
}
?>