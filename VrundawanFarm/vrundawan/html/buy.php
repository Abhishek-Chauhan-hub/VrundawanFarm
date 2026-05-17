<?php 
include('header.php'); 
include('../database/db.php'); 
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../style/buy.css">
</head>
<body>

<main class="product-page">
    <h1 class="page-title">અમારા તાજા ઉત્પાદનો</h1>

    <!-- GHEE -->
    <h2 class="category-title">શુદ્ધ ગાયનું ઘી (A2 Cow Ghee)</h2>
    <div class="product-grid">
        <?php
        $ghee = mysqli_query($con, "SELECT * FROM product WHERE category='ghee'");
        while($row = mysqli_fetch_array($ghee)){
        ?>
        <div class="product-card">
            <img src="../image/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p class="price">₹ <?php echo $row['price']; ?></p>
            
            <button onclick="addToCart(<?php echo $row['id']; ?>)" class="cart-btn">
                કાર્ટમાં ઉમેરો
            </button>
        </div>
        <?php } ?>
    </div>

    <!-- MANGO -->
    <h2 class="category-title">તાજી કેસર કેરી (Fresh Kesar Mango)</h2>
    <div class="product-grid">
        <?php
        $mango = mysqli_query($con, "SELECT * FROM product WHERE category='mango'");
        while($row = mysqli_fetch_array($mango)){
        ?>
        <div class="product-card">
            <img src="../image/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p class="price">₹ <?php echo $row['price']; ?></p>
            
            <button onclick="addToCart(<?php echo $row['id']; ?>)" class="cart-btn">
                કાર્ટમાં ઉમેરો
            </button>
        </div>
        <?php } ?>
    </div>
</main>

<script>
function addToCart(productId) {
    <?php if(!isset($_SESSION['customer_id'])): ?>
        alert("કાર્ટમાં ઉમેરવા માટે પહેલા લોગિન કરો");
        window.location.href = "login.php";
    <?php else: ?>
        // User is logged in - we can add to cart
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'product_id=' + productId
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        });
    <?php endif; ?>
}
</script>

</body>
</html>

<?php include('footer.php'); ?>