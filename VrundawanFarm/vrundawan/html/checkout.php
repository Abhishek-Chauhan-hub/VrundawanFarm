<?php 
include('header.php'); 
include('../database/db.php'); 

if(!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch cart items for checkout
$cart_query = mysqli_query($con, "
    SELECT c.*, p.name, p.price, p.image 
    FROM cart c 
    JOIN product p ON c.product_id = p.id 
    WHERE c.customer_id = $customer_id
");

$total = 0;
$items = [];

while($item = mysqli_fetch_assoc($cart_query)) {
    $price = (float)$item['price'];
    $qty = (int)$item['quantity'];
    $subtotal = $price * $qty;
    $total += $subtotal;
    
    $items[] = [
        'product_id' => $item['product_id'],
        'name' => $item['name'],
        'price' => $price,
        'quantity' => $qty,
        'subtotal' => $subtotal
    ];
}

if(empty($items)) {
    header("Location: cart.php");
    exit();
}

// Place Order
if(isset($_POST['place_order'])) {
    // Insert into orders table
    $insert_order = mysqli_query($con, "INSERT INTO orders (customer_id, total_amount) 
                                       VALUES ($customer_id, $total)");
    
    if($insert_order) {
        $order_id = mysqli_insert_id($con);
        
        // Insert order items
        foreach($items as $item) {
            mysqli_query($con, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                               VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price']})");
        }
        
        // Clear the cart
        mysqli_query($con, "DELETE FROM cart WHERE customer_id = $customer_id");
        
        echo "<script>
                alert('🎉 Order Placed Successfully! Your Order ID is #$order_id');
                window.location.href = 'myorders.php';
              </script>";
        exit();
    } else {
        $error = "Failed to place order. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Checkout - વૃંદાવન ફાર્મ</title>
    <link rel="stylesheet" href="../style/buy.css">
    <style>
        .checkout-container { max-width: 900px; margin: 30px auto; padding: 20px; }
        .order-summary { background: #f9f9f9; padding: 20px; border-radius: 10px; }
        .total-row { font-size: 1.4em; font-weight: bold; color: #2d5a27; }
    </style>
</head>
<body>

<div class="checkout-container">
    <h1 class="page-title">🛒 Checkout</h1>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>₹ <?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹ <?php echo number_format($item['subtotal'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-row">
            Total Amount: ₹ <?php echo number_format($total, 2); ?>
        </div>
    </div>

    <br><br>

    <form method="post" style="text-align:center;">
        <button type="submit" name="place_order" 
                class="btn btn-success btn-lg" 
                style="padding:15px 50px; font-size:1.2em;">
            ✅ Place Order Now
        </button>
    </form>

    <br>
    <a href="cart.php" style="color:#2d5a27;">← Back to Cart</a>
</div>

<?php include('footer.php'); ?>
</body>
</html>