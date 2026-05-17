<?php include('../database/db.php'); ?>

<section class="featured-products">

<?php
$get = mysqli_query($con, "SELECT * FROM products ORDER BY id ASC");
while($row = mysqli_fetch_array($get)){
?>
<head>
    <link rel="stylesheet" href="../style/product.css">
</head>
<div class="product-card">

    <img src="../image/<?php echo $row['image']; ?>">

    <div class="product-info">
        <h3><?php echo $row['name']; ?></h3>
        <p class="price"><?php echo $row['price']; ?></p>

        <button class="buy-btn">
            <a href="buy.php">હમણાં જ ખરીદો</a>
        </button>
    </div>

</div>

<?php } ?>

</section>