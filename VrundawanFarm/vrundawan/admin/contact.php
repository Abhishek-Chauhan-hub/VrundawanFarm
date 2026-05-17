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

if(isset($_POST['update'])){
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    mysqli_query($con, "UPDATE contact_info SET 
        address='$address',
        phone='$phone',
        email='$email'
        WHERE id=1
    ");

    echo "<script>alert('Updated');</script>";

    // ✅ REDIRECT (IMPORTANT)
    header("Location: contact.php");
    exit();
}

$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM contact_info WHERE id=1"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Contact</title>

<link rel="stylesheet" href="../style/admin_index.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/phosphor-icons"></script>

</head>

<body>

<div class="app-container">

<!-- SIDEBAR -->
<aside class="sidebar">
    
    <div class="brand">
        <div class="logo-box">V</div>
        <div class="brand-text">
            <span class="main-title">Vrundavan</span>
            <span class="sub-title">Admin Panel</span>
            
        </div>
    </div>

    <nav class="nav-list">

        <a href="index.php" class="nav-link">
            <i class="ph-monitor"></i> Slider
        </a>

        <a href="products.php" class="nav-link">
            <i class="ph-shopping-cart"></i> Products
        </a>

        <a href="contact.php" class="nav-link active">
            <i class="ph-envelope-simple"></i> Contact
        </a>
        <a href="inquiries.php" class="nav-link">
    <i class="ph-envelope"></i> Inquiries
</a>
<a href="orders.php" class="nav-link">
    <i class="ph-package"></i> Orders
</a>
<a href="change_password.php" class="nav-link"><i class="ph-key"></i> Change Password</a>

    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout">
            <i class="ph-power"></i> Logout
        </a>
        
    </div>

</aside>

<!-- MAIN PANEL -->
<main class="main-panel">

<header class="panel-header">
    <div class="title-group">
        <h1>Manage Contact</h1>
        <p>Update contact details</p>
    </div>
<div style="position:fixed; top:10px; left:300px; background:#fff; padding:8px 15px; border-radius:10px; font-size:12px;">
Session Active
</div>
    <!-- <div class="admin-badge">Admin Mode</div> -->
</header>

<div class="glass-card">

<form method="post">

<textarea name="address" placeholder="Address"><?php echo $data['address']; ?></textarea>

<input type="text" name="phone" value="<?php echo $data['phone']; ?>" placeholder="Phone">

<input type="text" name="email" value="<?php echo $data['email']; ?>" placeholder="Email">

<button name="update" class="btn-save">Update</button>

</form>

</div>

</main>
</div>

</body>
</html>