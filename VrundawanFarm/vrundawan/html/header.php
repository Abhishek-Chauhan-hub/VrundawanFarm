<?php 
session_start(); 
include("../database/db.php");
?>
<!DOCTYPE html>
<html lang="gu">
<head>
    <link rel="stylesheet" href="../style/indexstyle.css">
</head>
<body>

    <header>
        <img src="../image/logo.png" class="logo-image" alt="Vrundawan Farm">
        
        <nav>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="aboutus.php">About Us</a></li>
        <li><a href="buy.php">Products</a></li>
        <li><a href="cart.php">Cart 
            <?php 
            if(isset($_SESSION['customer_id'])) {
                include('../database/db.php');
                $count = mysqli_fetch_array(mysqli_query($con, 
                    "SELECT COUNT(*) as cnt FROM cart WHERE customer_id = ".$_SESSION['customer_id']));
                if($count['cnt'] > 0) echo "(".$count['cnt'].")";
            }
            ?>
        </a></li>
        <?php if(isset($_SESSION['customer_id'])): ?>
            <li><a href="myorders.php">My Orders</a></li>
        <?php endif; ?>
        <li><a href="contactus.php">Contact Us</a></li>
        
        <?php if(isset($_SESSION['customer_id'])): ?>
            <li><a href="logout.php">Logout (<?php echo $_SESSION['customer_name']; ?>)</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
    </header>
</body>
</html>