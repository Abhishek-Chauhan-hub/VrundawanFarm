<?php
session_start();
include('../database/db.php');

if(!isset($_SESSION['customer_id'])) {
    echo "Please login first!";
    exit();
}

if(isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $customer_id = $_SESSION['customer_id'];
    $quantity = 1; // default quantity

    // Check if product already exists in cart
    $check = mysqli_query($con, "SELECT * FROM cart WHERE customer_id = $customer_id AND product_id = $product_id");
    
    if(mysqli_num_rows($check) > 0) {
        // Update quantity if already in cart
        mysqli_query($con, "UPDATE cart SET quantity = quantity + 1 
                           WHERE customer_id = $customer_id AND product_id = $product_id");
        echo "Quantity updated in cart!";
    } else {
        // Insert new item
        mysqli_query($con, "INSERT INTO cart (customer_id, product_id, quantity) 
                           VALUES ($customer_id, $product_id, $quantity)");
        echo "Product added to cart successfully!";
    }
} else {
    echo "Invalid request!";
}
?>