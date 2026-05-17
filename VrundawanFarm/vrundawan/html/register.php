<?php
include('../database/db.php');

$error = "";
$success = "";

if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $email    = mysqli_real_escape_string($con, trim($_POST['email']));
    $mobile   = mysqli_real_escape_string($con, trim($_POST['mobile']));
    $password = $_POST['password'];

    if(empty($username) || empty($email) || empty($mobile) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    elseif(strlen($mobile) != 10 || !is_numeric($mobile)) {
        $error = "Mobile number must be exactly 10 digits!";
    }
    elseif(strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    }
    else {
        // Check if username or email already exists
        $check = mysqli_query($con, "SELECT id FROM customer WHERE username='$username' OR email='$email'");
        
        if(mysqli_num_rows($check) > 0) {
            $error = "Username or Email already exists!";
        }
        else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO customer (username, email, mobile, password, name) 
                      VALUES ('$username', '$email', '$mobile', '$hashed_password', '$username')";

            if(mysqli_query($con, $query)) {
                $success = "✅ Registration successful! You can now login.";
            } else {
                $error = "❌ Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>Register - Vrundawan Farm</title>
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
            width: 400px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2 class="text-center mb-4">Customer Register</h2>
    
    <?php if($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>
        <div class="mb-3">
            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number (10 digits)" maxlength="10" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Create Password" required>
        </div>
        
        <button type="submit" name="register" class="btn btn-primary w-100">Register Now</button>
    </form>
    
    <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login Here</a>
    </p>
</div>

</body>
</html>