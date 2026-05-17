<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(time() - $_SESSION['last_login'] > 1800){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_login'] = time();

include('../database/db.php');

$message = "";

if(isset($_POST['change_password'])){
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm  = $_POST['confirm_password'];

    if($new_pass !== $confirm){
        $message = "❌ New passwords do not match!";
    }
    elseif(strlen($new_pass) < 6){
        $message = "❌ Password must be at least 6 characters!";
    }
    else {

        $username = $_SESSION['admin'];

        $query = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");
        $admin = mysqli_fetch_assoc($query);

        if(!$admin){
            $message = "❌ Admin not found!";
        }
        elseif($old_pass == $admin['password']){

            $update = mysqli_query($con, "UPDATE admin SET password='$new_pass' WHERE username='$username'");

            if($update){
                $message = "✅ Password changed successfully!";
            } else {
                $message = "❌ Error: " . mysqli_error($con);
            }

        } else {
            $message = "❌ Old password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password - Vrundavan Admin</title>
<link rel="stylesheet" href="../style/admin_index.css">
<script src="https://unpkg.com/phosphor-icons"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="app-container">

<aside class="sidebar">
    <div class="brand">
        <div class="logo-box">V</div>
        <div class="brand-text">
            <span class="main-title">Vrundavan</span>
            <span class="sub-title">Admin Panel</span>
        </div>
    </div>

    <nav class="nav-list">
        <a href="index.php" class="nav-link"><i class="ph-monitor"></i> Dashboard</a>
        <a href="products.php" class="nav-link"><i class="ph-shopping-cart"></i> Products</a>
        <a href="contact.php" class="nav-link"><i class="ph-envelope-simple"></i> Contact</a>
        <a href="inquiries.php" class="nav-link"><i class="ph-envelope"></i> Inquiries</a>
        <a href="orders.php" class="nav-link"><i class="ph-package"></i> Orders</a>
        <a href="change_password.php" class="nav-link active"><i class="ph-key"></i> Change Password</a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"><i class="ph-power"></i> Logout</a>
    </div>
</aside>

<main class="main-panel">

<header class="panel-header">
    <div class="title-group">
        <h1>🔑 Change Password</h1>
        <p>Keep your account secure</p>
    </div>
</header>

<div class="glass-card" style="max-width:500px; margin:40px auto;">
    <?php if($message): ?>
        <p style="text-align:center; padding:15px; background:#d4edda; color:#155724; border-radius:8px;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="password" name="old_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password (min 6 characters)" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        
        <button type="submit" name="change_password" class="btn-save" style="margin-top:20px;">
            Update Password
        </button>
    </form>
</div>

</main>
</div>

</body>
</html>