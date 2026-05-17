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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vrundavan Admin</title>

<link rel="stylesheet" href="../style/admin_index.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/phosphor-icons"></script>

</head>

<body>

<div class="app-container">

<!-- ✅ SIDEBAR -->
<aside class="sidebar">
    
    <div class="brand">
        <div class="logo-box">V</div>
        <div class="brand-text">
            <span class="main-title">Vrundavan</span>
            <span class="sub-title">Admin Panel</span>
        </div>
    </div>

    <nav class="nav-list">

        <a href="index.php" class="nav-link active">
            <i class="ph-monitor"></i> Slider
        </a>

        <a href="products.php" class="nav-link">
            <i class="ph-shopping-cart"></i> Products
        </a>

        <a href="contact.php" class="nav-link">
            <i class="ph-envelope-simple"></i> Contact
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout">
            <i class="ph-power"></i> Logout
        </a>
    </div>

</aside>

