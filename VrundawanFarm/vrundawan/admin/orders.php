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

/* ================= UPDATE ORDER STATUS ================= */
if(isset($_POST['update_status'])){
    $order_id = intval($_POST['order_id']);
    $status   = mysqli_real_escape_string($con, $_POST['status']);
    
    mysqli_query($con, "UPDATE orders SET status='$status' WHERE id=$order_id");
    echo "<script>alert('Order status updated!');</script>";
}

/* ================= FETCH ALL ORDERS ================= */
$orders = mysqli_query($con, "
    SELECT o.*, c.username, c.mobile 
    FROM orders o 
    JOIN customer c ON o.customer_id = c.id 
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Orders Management - Vrundavan Admin</title>
<link rel="stylesheet" href="../style/admin_index.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/phosphor-icons"></script>
<style>
    .status-pending { color: #f0ad4e; font-weight: bold; }
    .status-confirmed { color: #5cb85c; font-weight: bold; }
    .order-card { margin-bottom: 25px; }
</style>
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
        <a href="products.php" class="nav-link"><i class="ph-shopping-cart"></i> Products</a>
        <a href="contact.php" class="nav-link"><i class="ph-envelope-simple"></i> Contact</a>
        <a href="inquiries.php" class="nav-link"><i class="ph-envelope"></i> Inquiries</a>
                <a href="orders.php" class="nav-link active"><i class="ph-package"></i> Orders</a>
    <a href="change_password.php" class="nav-link"><i class="ph-key"></i> Change Password</a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"><i class="ph-power"></i> Logout</a>
    </div>
</aside>

<!-- MAIN PANEL -->
<main class="main-panel">

<header class="panel-header">
    <div class="title-group">
        <h1>📦 Customer Orders</h1>
        <p>View and manage all customer orders</p>
    </div>
</header>

<?php if(mysqli_num_rows($orders) > 0): ?>
    <?php while($order = mysqli_fetch_assoc($orders)): 
        $order_id = $order['id'];
        $items = mysqli_query($con, "
            SELECT oi.*, p.name 
            FROM order_items oi 
            JOIN product p ON oi.product_id = p.id 
            WHERE oi.order_id = $order_id
        ");
    ?>
    <div class="glass-card order-card">
        <div style="background:#2d5a27; color:white; padding:12px; border-radius:8px; margin-bottom:15px;">
            <strong>Order #<?php echo $order_id; ?></strong> | 
            Date: <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?> | 
            Customer: <?php echo htmlspecialchars($order['username']); ?> 
            (<?php echo $order['mobile']; ?>)
        </div>

        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th style="padding:10px; text-align:left;">Product</th>
                    <th style="padding:10px;">Qty</th>
                    <th style="padding:10px;">Price</th>
                    <th style="padding:10px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td style="padding:10px;"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td style="padding:10px; text-align:center;"><?php echo $item['quantity']; ?></td>
                    <td style="padding:10px; text-align:right;">₹ <?php echo number_format($item['price'], 2); ?></td>
                    <td style="padding:10px; text-align:right;">₹ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="margin-top:15px; font-size:1.2em; font-weight:bold; color:#2d5a27;">
            Total Amount: ₹ <?php echo number_format($order['total_amount'], 2); ?>
        </div>

        <!-- Update Status -->
        <form method="post" style="margin-top:15px;">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <select name="status" style="padding:8px; margin-right:10px;">
                <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
                <option value="confirmed" <?php if($order['status']=='confirmed') echo 'selected'; ?>>Confirmed (Ready)</option>
                <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>
            <button type="submit" name="update_status" class="btn-save">Update Status</button>
        </form>
    </div>
    <?php endwhile; ?>

<?php else: ?>
    <div class="glass-card" style="text-align:center; padding:60px;">
        <h2>No orders yet</h2>
        <p>When customers place orders, they will appear here.</p>
    </div>
<?php endif; ?>

</main>
</div>

</body>
</html>