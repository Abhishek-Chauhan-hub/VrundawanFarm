<?php 
session_start();
include('../database/db.php');

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
$query = mysqli_query($con, "SELECT * FROM customer 
         WHERE (username='$username' OR email='$username')");
         
if(mysqli_num_rows($query) == 1){
    $row = mysqli_fetch_array($query);
    
    // Verify hashed password
    if(password_verify($_POST['password'], $row['password'])){
        $_SESSION['customer_id'] = $row['id'];
        $_SESSION['customer_name'] = $row['name'] ?? $row['username'];
        
        header("Location: buy.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
} else {
    $error = "Invalid Username or Password!";
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Login - Vrundawan Farm</title>
    <style>
        body{
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            background:#f5f7f6;
            font-family:Arial, sans-serif;
        }
        .box{
            background:white;
            padding:40px;
            border-radius:12px;
            box-shadow:0 10px 30px rgba(0,0,0,0.15);
            width: 320px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2 class="text-center">Customer Login</h2>
    
    <?php if(isset($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

    <form method="post">
        <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
    
    <p class="text-center mt-3">
        Don't have an account? <a href="register.php">Register Here</a>
    </p>
</div>

</body>
</html>