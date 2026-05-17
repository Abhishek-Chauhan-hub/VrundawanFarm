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
?>
<?php
include('../database/db.php');

$id = $_GET['id'];

$get = mysqli_query($con, "SELECT * FROM sliders WHERE id='$id'");
$row = mysqli_fetch_array($get);

$image = $row['image'];

// DELETE IMAGE FROM FOLDER
unlink("../image/".$image);

// DELETE FROM DB
mysqli_query($con, "DELETE FROM sliders WHERE id='$id'");

header("Location: index.php");
?>