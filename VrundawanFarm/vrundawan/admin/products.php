<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// 30 MIN TIMEOUT
if(time() - $_SESSION['last_login'] > 1800){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$_SESSION['last_login'] = time();

include('../database/db.php');

/* ================= ADD PRODUCT ================= */
if(isset($_POST['add'])){
    $name     = mysqli_real_escape_string($con, $_POST['name']);
    $price    = mysqli_real_escape_string($con, $_POST['price']);
    $category = mysqli_real_escape_string($con, $_POST['category']);

    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    if(move_uploaded_file($tmp, "../image/".$image)){
        mysqli_query($con, "INSERT INTO product(name, price, image, category) 
                           VALUES('$name', '$price', '$image', '$category')");
        echo "<script>alert('Product Added Successfully!');</script>";
        header("Location: products.php");
        exit();
    }
}

/* ================= DELETE PRODUCT ================= */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $res = mysqli_query($con, "SELECT image FROM product WHERE id=$id");
    $row = mysqli_fetch_array($res);
    
    if($row && file_exists("../image/".$row['image'])){
        unlink("../image/".$row['image']);
    }

    mysqli_query($con, "DELETE FROM product WHERE id=$id");
    echo "<script>alert('Product Deleted');</script>";
    header("Location: products.php");
    exit();
}

/* FETCH PRODUCTS */
$data = mysqli_query($con, "SELECT * FROM product ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products - Vrundavan Admin</title>
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
        <a href="index.php" class="nav-link"><i class="ph-monitor"></i> Slider</a>
        <a href="products.php" class="nav-link active"><i class="ph-shopping-cart"></i> Products</a>
        <a href="contact.php" class="nav-link"><i class="ph-envelope-simple"></i> Contact</a>
        <a href="inquiries.php" class="nav-link"><i class="ph-envelope"></i> Inquiries</a>
        <a href="orders.php" class="nav-link">
    <i class="ph-package"></i> Orders
</a>
<a href="change_password.php" class="nav-link"><i class="ph-key"></i> Change Password</a>
        <!-- We'll add Orders later -->
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"><i class="ph-power"></i> Logout</a>
    </div>
</aside>

<!-- MAIN PANEL -->
<main class="main-panel">

<header class="panel-header">
    <div class="title-group">
        <h1>Manage Products</h1>
        <p>Add, view and delete products</p>
    </div>
</header>

<!-- ADD PRODUCT FORM -->
<div class="glass-card" style="margin-bottom:30px;">
    <h3>Add New Product</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="price" placeholder="Price (₹)" required>
        
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="ghee">Ghee (A2 Cow Ghee)</option>
            <option value="mango">Mango (Kesar Mango)</option>
        </select>

        <input type="file" name="image" required>
        <button name="add" class="btn-save">Add Product</button>
    </form>
</div>

<!-- LIST OF PRODUCTS -->
<section class="banner-section">
    <?php while($row = mysqli_fetch_array($data)): ?>
    <div class="glass-card">
        <div class="img-preview">
            <img src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="">
        </div>
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><strong>₹ <?php echo $row['price']; ?></strong></p>
        <small>Category: <?php echo strtoupper($row['category']); ?></small><br><br>
        
        <a href="?delete=<?php echo $row['id']; ?>" 
           onclick="return confirm('Are you sure you want to delete this product?')" 
           class="btn-outline" style="color:red;">
           Delete
        </a>
    </div>
    <?php endwhile; ?>
</section>

</main>
</div>

</body>
</html>