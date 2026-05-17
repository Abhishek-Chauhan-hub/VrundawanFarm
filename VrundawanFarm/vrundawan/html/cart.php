<?php 
include('header.php'); 
include('../database/db.php'); 

if(!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Handle quantity update or remove
if(isset($_POST['update'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    if($quantity > 0) {
        mysqli_query($con, "UPDATE cart SET quantity = $quantity 
                           WHERE customer_id = $customer_id AND product_id = $product_id");
    } else {
        mysqli_query($con, "DELETE FROM cart 
                           WHERE customer_id = $customer_id AND product_id = $product_id");
    }
    echo "<script>alert('Cart updated successfully!');</script>";
}

// Handle remove item
if(isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    mysqli_query($con, "DELETE FROM cart 
                       WHERE customer_id = $customer_id AND product_id = $product_id");
    echo "<script>alert('Item removed from cart!');</script>";
    header("Location: cart.php"); // Refresh page
    exit();
}

// Fetch cart items
$cart_query = mysqli_query($con, "
    SELECT c.*, p.name, p.price, p.image, p.category 
    FROM cart c 
    JOIN product p ON c.product_id = p.id 
    WHERE c.customer_id = $customer_id
    ORDER BY c.added_at DESC
");

$total = 0;
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - વૃંદાવન ફાર્મ</title>
    <link rel="stylesheet" href="../style/buy.css">
    <style>
        .cart-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .cart-table th, .cart-table td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        .cart-table th { background: #2d5a27; color: white; }
        .cart-total { 
            font-size: 1.5em; 
            font-weight: bold; 
            text-align: right; 
            margin: 30px 0; 
            color: #2d5a27;
        }
        .action-btn { 
            padding: 8px 14px; 
            margin: 0 5px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        img { border-radius: 5px; }
    </style>
</head>
<body>

<main class="product-page">
    <h1 class="page-title">🛒 તમારી કાર્ટ</h1>

    <?php if(mysqli_num_rows($cart_query) > 0): ?>
        <form method="post">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>ઉત્પાદન</th>
                        <th>છબી</th>
                        <th>કિંમત (₹)</th>
                        <th>જથ્થો</th>
                        <th>સબટોટલ (₹)</th>
                        <th>ક્રિયા</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($cart_query, 0); // Reset pointer
                    while($item = mysqli_fetch_assoc($cart_query)): 
                        $price = (float)$item['price'];      // Convert to number
                        $qty   = (int)$item['quantity'];     // Convert to integer
                        $subtotal = $price * $qty;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                        <td>
                            <img src="../image/<?php echo htmlspecialchars($item['image']); ?>" 
                                 width="80" height="80" alt="<?php echo $item['name']; ?>">
                        </td>
                        <td>₹ <?php echo number_format($price, 2); ?></td>
                        <td>
                            <input type="number" name="quantity" value="<?php echo $qty; ?>" 
                                   min="1" style="width:80px; padding:8px;">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        </td>
                        <td><strong>₹ <?php echo number_format($subtotal, 2); ?></strong></td>
                        <td>
                            <button type="submit" name="update" class="action-btn" 
                                    style="background:#2d5a27;color:white;">Update</button>
                            <a href="cart.php?remove=<?php echo $item['product_id']; ?>" 
                               onclick="return confirm('આ આઈટમ કાર્ટમાંથી દૂર કરવી છે?')" 
                               class="action-btn" style="background:#d32f2f;color:white;">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>

        <div class="cart-total">
            કુલ રકમ: ₹ <?php echo number_format($total, 2); ?>
        </div>

        <div style="text-align:center; margin:40px 0;">
            <a href="buy.php" class="cart-btn" 
               style="background:#2d5a27;color:white;padding:14px 30px;text-decoration:none;border-radius:8px;font-size:1.1em;">
                🛍️ Continue Shopping
            </a>
            <a href="checkout.php" 
   class="cart-btn" 
   style="background:#d4a017;color:white;padding:14px 30px;border:none;border-radius:8px;font-size:1.1em;margin-left:20px;">
    Proceed to Checkout →
</a>        </div>

    <?php else: ?>
        <div style="text-align:center; padding:80px 20px;">
            <h2>તમારી કાર્ટ ખાલી છે 😊</h2>
            <p style="font-size:1.2em;">કેટલાક સ્વાદિષ્ટ ઉત્પાદનો ઉમેરીને શરૂઆત કરો!</p>
            <br>
            <a href="buy.php" style="background:#2d5a27;color:white;padding:15px 40px;border-radius:8px;text-decoration:none;">
                Browse Products
            </a>
        </div>
    <?php endif; ?>
</main>

<?php include('footer.php'); ?>
</body>
</html>