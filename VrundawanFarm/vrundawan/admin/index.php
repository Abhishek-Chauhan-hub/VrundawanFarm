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

/* ================= SLIDER UPLOAD ================= */
if(isset($_POST['upload'])){
    $count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM sliders"));
    if($count >= 3){
        echo "<script>alert('Maximum 3 slider images allowed!');</script>";
    } else {
        $image = $_FILES['slider_image']['name'];
        $tmp = $_FILES['slider_image']['tmp_name'];

        if(move_uploaded_file($tmp, "../image/".$image)){
            mysqli_query($con, "INSERT INTO sliders(image) VALUES('$image')");
            echo "<script>alert('Slider Uploaded Successfully!');</script>";
            header("Location: index.php");
            exit();
        }
    }
}

/* ================= SLIDER UPDATE ================= */
if(isset($_POST['update_slider'])){
    $id = intval($_POST['slider_id']);
    $image = $_FILES['new_image']['name'];
    $tmp = $_FILES['new_image']['tmp_name'];

    if($image != "" && move_uploaded_file($tmp, "../image/".$image)){
        mysqli_query($con, "UPDATE sliders SET image='$image' WHERE id=$id");
        echo "<script>alert('Slider Image Updated!');</script>";
        header("Location: index.php");
        exit();
    }
}

/* ================= QUICK STATISTICS ================= */
$total_orders     = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as cnt FROM orders"))['cnt'];
$total_pending    = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as cnt FROM orders WHERE status='pending'"))['cnt'];
$total_products   = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as cnt FROM product"))['cnt'];
$total_inquiries  = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as cnt FROM inquiries"))['cnt'];

// Total Sales from Confirmed Orders
$total_sales = mysqli_fetch_array(mysqli_query($con, 
    "SELECT COALESCE(SUM(total_amount), 0) as sales FROM orders WHERE status='confirmed'"))['sales'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - Vrundavan Admin</title>
<link rel="stylesheet" href="../style/admin_index.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/phosphor-icons"></script>
<style>
    .stat-card {
        background: white;
        padding: 25px 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .sales-card { border-left: 6px solid #2d5a27; }
</style>
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
        <a href="index.php" class="nav-link active"><i class="ph-monitor"></i> Dashboard</a>
        <a href="products.php" class="nav-link"><i class="ph-shopping-cart"></i> Products</a>
        <a href="orders.php" class="nav-link"><i class="ph-package"></i> Orders</a>
        <a href="contact.php" class="nav-link"><i class="ph-envelope-simple"></i> Contact</a>
        <a href="inquiries.php" class="nav-link"><i class="ph-envelope"></i> Inquiries</a>
    <a href="change_password.php" class="nav-link"><i class="ph-key"></i> Change Password</a>
    </nav>


    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"><i class="ph-power"></i> Logout</a>
    </div>
</aside>

<main class="main-panel">

<header class="panel-header">
    <div class="title-group">
        <h1>Dashboard</h1>
        <p>Overview of your farm business</p>
    </div>
</header>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 40px;">
    
    <div class="stat-card sales-card">
        <h2 style="color:#2d5a27; margin:0;">₹ <?php echo number_format($total_sales, 2); ?></h2>
        <p style="margin:8px 0 0;">Total Sales (Confirmed)</p>
    </div>

    <div class="stat-card">
        <h2 style="color:#2d5a27; margin:0;"><?php echo $total_orders; ?></h2>
        <p style="margin:8px 0 0;">Total Orders</p>
    </div>

    <div class="stat-card">
        <h2 style="color:#f0ad4e; margin:0;"><?php echo $total_pending; ?></h2>
        <p style="margin:8px 0 0;">Pending Orders</p>
    </div>

    <div class="stat-card">
        <h2 style="color:#2d5a27; margin:0;"><?php echo $total_products; ?></h2>
        <p style="margin:8px 0 0;">Active Products</p>
    </div>

    <div class="stat-card">
        <h2 style="color:#2d5a27; margin:0;"><?php echo $total_inquiries; ?></h2>
        <p style="margin:8px 0 0;">Customer Inquiries</p>
    </div>
</div>
<!-- Slider Management -->
<h2 style="margin-bottom:20px;">Manage Slider Images (Max 3)</h2>

<div class="glass-card" style="margin-bottom:30px;">
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="slider_image" required accept="image/*">
        <button name="upload" class="btn-save">Upload New Slider Image</button>
    </form>
</div>

<section class="banner-section">
    <?php 
    $sliderData = mysqli_query($con, "SELECT * FROM sliders");
    while($row = mysqli_fetch_array($sliderData)): 
    ?>
    <div class="glass-card">
        <div class="img-preview">
            <img src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="Slider">
        </div>
        
        <form method="post" enctype="multipart/form-data" style="margin-top:15px;">
            <input type="hidden" name="slider_id" value="<?php echo $row['id']; ?>">
            <input type="file" name="new_image" required accept="image/*">
            <button name="update_slider" class="btn-save">Change Image</button>
        </form>

        <!-- Delete Button -->
        <a href="delete.php?id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Delete this slider image permanently?')" 
           style="color:#d32f2f; margin-top:12px; display:block; text-align:center; font-weight:500;">
           🗑️ Delete Slider
        </a>
    </div>
    <?php endwhile; ?>
</section>

</main>
</div>

</body>
</html>