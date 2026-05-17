<body>
    <head>
        <link rel="stylesheet" href="../style/slider.css">
    </head>
<?php include('../database/db.php'); ?>

<main class="slider-section">

<div class="slider-container">

<?php
$get = mysqli_query($con, "SELECT * FROM sliders");
while($row = mysqli_fetch_array($get)){
?>

<div class="mySlides fade">
    <img src="../image/<?php echo $row['image']; ?>" style="width:100%">
</div>

<?php } ?>

<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>

<br>

<div style="text-align:center">
<?php
$get2 = mysqli_query($con, "SELECT * FROM sliders");
$i=1;
while($row = mysqli_fetch_array($get2)){
?>
<span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
<?php $i++; } ?>
</div>

</main>
<script>
let slideIndex = 0;
let slideTimer;

// Initialize the slider
showSlides();

// Next/previous controls
function plusSlides(n) {
  clearTimeout(slideTimer); // Reset timer when user clicks manually
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  clearTimeout(slideTimer); // Reset timer when user clicks a dot
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  
  // Handle manual navigation bounds
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}

  // Handle auto-increment logic
  if (n === undefined) {
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
  }

  // Hide all slides
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  // Remove active class from dots
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // Display the current slide
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";

  // Set the timer for the next auto-scroll (4 seconds)
  slideTimer = setTimeout(showSlides, 4000); 
}
</script></body>