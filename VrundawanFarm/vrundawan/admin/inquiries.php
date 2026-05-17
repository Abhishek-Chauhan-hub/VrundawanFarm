<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// ⏱ 30 MIN TIMEOUT
if(time() - $_SESSION['last_login'] > 1800){
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// update time
$_SESSION['last_login'] = time();
?>
<?php
include('../database/db.php');

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM inquiries WHERE id='$id'");
    echo "<script>alert('Deleted'); window.location='inquiries.php';</script>";
}

$data = mysqli_query($con, "SELECT * FROM inquiries ORDER BY id DESC");
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Vrundavan Admin</title>

<link rel="stylesheet" href="../style/admin_index.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://unpkg.com/phosphor-icons"></script>

</head>

<body>

<div class="app-container">

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="logo-box">V</div>
        <div>
            <span class="main-title">Vrundavan</span>
            <span class="sub-title">Admin Panel</span>
        </div>
    </div>

    <nav class="nav-list">
        <a href="index.php" class="nav-link"><i class="ph-monitor"></i> Slider</a>
        <a href="products.php" class="nav-link"><i class="ph-shopping-cart"></i> Products</a>
        <a href="contact.php" class="nav-link"><i class="ph-envelope-simple"></i> Contact</a>
        <a href="inquiries.php" class="nav-link active">
    <i class="ph-envelope"></i> Inquiries
</a>
<a href="orders.php" class="nav-link">
    <i class="ph-package"></i> Orders
</a>
<a href="change_password.php" class="nav-link "><i class="ph-key"></i> Change Password</a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="btn-logout"><i class="ph-power"></i> Logout</a>
    </div>
</aside>

<!-- MAIN -->
<main class="main-panel">

<header class="panel-header">
    <div>
        <h1>Dashboard</h1>
        <p>Inquiries</p>
    </div><div style="position:fixed; top:10px; left:300px; background:#fff; padding:8px 15px; border-radius:10px; font-size:12px;">
Session Active
</div>
    <!-- <div class="admin-badge">Admin Mode</div> -->
</header>

<section class="banner-section">

<?php while($row = mysqli_fetch_array($data)){ ?>

<div class="glass-card">

    <p><b>Name:</b> <?php echo $row['name']; ?></p>
    <p><b>Email:</b> <?php echo $row['email']; ?></p>
    <p><b>Message:</b> <?php echo $row['message']; ?></p>
    <p><b>Type:</b> <?php echo $row['type']; ?></p>

    <a href="?delete=<?php echo $row['id']; ?>" 
       onclick="return confirm('Delete this?')" 
       class="btn-outline">
       Delete
    </a>

</div>

<?php } ?>

</section>

</main>
</div>

</body>
</html>
<script>
function sendWhatsApp(){

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let message = document.getElementById("message").value;

    if(name === "" || email === "" || message === ""){
        alert("Fill all fields first");
        return;
    }

    // SEND TO DATABASE
    fetch('../admin/save_whatsapp.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "name=" + encodeURIComponent(name) + 
              "&email=" + encodeURIComponent(email) + 
              "&message=" + encodeURIComponent(message)
    })
    .then(res => res.text())
    .then(data => console.log(data));

    // OPEN WHATSAPP
    let text = "Name: " + name + "%0AEmail: " + email + "%0AMessage: " + message;

    let phone = "917041546469"; // replace

    window.open("https://wa.me/" + phone + "?text=" + text, "_blank");
}
</script>