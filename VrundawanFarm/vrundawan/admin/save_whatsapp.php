<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// ⏱ 30 MIN TIMEOUT
if(time() - $_SESSION['last_login'] > 1800){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// update time
$_SESSION['last_login'] = time();
?><?php
include('../database/db.php');

if(isset($_POST['name'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    mysqli_query($con, "INSERT INTO inquiries(name,email,message,type)
    VALUES('$name','$email','$message','whatsapp')");

    echo "saved";
}
?>