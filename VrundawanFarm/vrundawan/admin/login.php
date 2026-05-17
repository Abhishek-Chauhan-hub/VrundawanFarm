<?php
session_start();
include('../database/db.php');

$error = "";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = $_POST['password'];

    $res = mysqli_query($con, "SELECT * FROM admin WHERE username='$username'");

    if(mysqli_num_rows($res) > 0){
        $admin = mysqli_fetch_assoc($res);
        
        // Check hashed password first, fallback to old plain password
        if(password_verify($password, $admin['password']) || 
           $password === $admin['password']){   // temporary fallback
            
            $_SESSION['admin'] = $admin['username'];
            $_SESSION['last_login'] = time();
            
            // Optional: Remove old plain password later
            header("Location: index.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login - Vrundavan Farm</title>
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
        width: 340px;
    }
    input{
        width:100%;
        padding:12px;
        margin:10px 0;
        border:1px solid #ddd;
        border-radius:6px;
    }
    button{
        width:100%;
        padding:12px;
        background:#2d5a27;
        color:white;
        border:none;
        border-radius:6px;
        font-size:16px;
        cursor:pointer;
    }
    .error { color:red; text-align:center; margin:10px 0; }
</style>
</head>
<body>

<div class="box">
    <h2 style="text-align:center; margin-bottom:25px;">Admin Login</h2>
    
    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login" type="submit">Login to Admin Panel</button>
    </form>
    
    <p style="text-align:center; margin-top:20px; font-size:0.9em; color:#666;">
        Vrundavan Farm Admin
    </p>
</div>

</body>
</html>