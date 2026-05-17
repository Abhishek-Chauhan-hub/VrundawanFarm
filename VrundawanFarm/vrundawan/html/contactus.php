<?php include('header.php'); ?>
<?php include('../database/db.php'); ?>

<?php
$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM contact_info WHERE id=1"));

if(isset($_POST['send'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

   mysqli_query($con, "INSERT INTO inquiries(name,email,message,type)
VALUES('$name','$email','$message','form')");
    echo "<script>alert('Message Sent Successfully');</script>";
}
?>

<!DOCTYPE html>
<html lang="gu">
<head>
<meta charset="UTF-8">
<title>Contact</title>
<link rel="stylesheet" href="../style/contactus.css">
</head>

<body>

<section class="contact-section">

<div class="contact-header">
    <h1>અમારો સંપર્ક કરો</h1>
</div>

<div class="contact-container">

<!-- INFO -->
<div class="contact-info">

    <div class="info-item">
        <span class="icon">📍</span>
        <p><?php echo $data['address']; ?></p>
    </div>

    <div class="info-item">
        <span class="icon">📞</span>
        <p><?php echo $data['phone']; ?></p>
    </div>

    <div class="info-item">
        <span class="icon">✉️</span>
        <p><?php echo $data['email']; ?></p>
    </div>

    <img src="../image/farm-mailbox.png" class="contact-image">

</div>

<!-- FORM -->
<div class="contact-form-container">

<form method="post" class="contact-form">

    <input type="text" id="name" name="name" placeholder="તમારું નામ" required>

    <input type="email" id="email" name="email" placeholder="ઈમેઈલ" required>

    <textarea id="message" name="message" rows="5" placeholder="તમારો સંદેશ" required></textarea>

    <button name="send" class="submit-btn">સંદેશ મોકલો</button>

    <!-- WhatsApp Button -->
    <button type="button" onclick="sendWhatsApp()" class="whatsapp-btn">
        WhatsApp પર સંપર્ક કરો
    </button>

</form>
</div>

</div>

</section>

</body>
</html>

<?php include('footer.php'); ?>
<script>
function sendWhatsApp(){

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let message = document.getElementById("message").value;

    if(name === "" || email === "" || message === ""){
        alert("Fill all fields first");
        return;
    }

    // SAVE TO DATABASE USING AJAX
    fetch('save_whatsapp.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `name=${name}&email=${email}&message=${message}`
    });

    let text = "Name: " + name + "%0AEmail: " + email + "%0AMessage: " + message;

    let phone = "917041546469";
    window.open("https://wa.me/" + phone + "?text=" + text, "_blank");
}
</script>