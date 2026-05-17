<?php 
include('header.php'); 
include('../database/db.php'); 

if(!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch all orders of the customer
$orders_query = mysqli_query($con, "
    SELECT * FROM orders 
    WHERE customer_id = $customer_id 
    ORDER BY order_date DESC
");
?>

<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>My Orders - વૃંદાવન ફાર્મ</title>
    <link rel="stylesheet" href="../style/buy.css">
    <style>
        .order-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .order-header {
            background: #2d5a27;
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .status-pending { color: #f0ad4e; font-weight: bold; }
        .status-confirmed { color: #5cb85c; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>

<main class="product-page">
    <h1 class="page-title">📦 મારા ઓર્ડર્સ</h1>

    <?php if(mysqli_num_rows($orders_query) > 0): ?>
        <?php while($order = mysqli_fetch_assoc($orders_query)): 
            $order_id = $order['id'];
            $items_query = mysqli_query($con, "
                SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                JOIN product p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id
            ");
        ?>
        <div class="order-card">
            <div class="order-header">
                <strong>Order #<?php echo $order_id; ?></strong> | 
                Date: <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?> | 
                Total: ₹ <?php echo number_format($order['total_amount'], 2); ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = mysqli_fetch_assoc($items_query)): ?>
                    <tr>
                        <td>
                            <img src="../image/<?php echo htmlspecialchars($item['image']); ?>" 
                                 width="50" style="vertical-align:middle; margin-right:10px;">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td>₹ <?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <p style="margin-top:15px;">
                Status: 
                <span class="<?php echo $order['status'] == 'confirmed' ? 'status-confirmed' : 'status-pending'; ?>">
                    <?php echo strtoupper($order['status']); ?>
                </span>
            </p>
        </div>
        <?php endwhile; ?>

    <?php else: ?>
        <div style="text-align:center; padding:80px 20px;">
            <h2>You haven't placed any orders yet 😊</h2>
            <p style="font-size:1.2em;">Start shopping now!</p>
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