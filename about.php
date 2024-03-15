<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
  

</head>
<body>
   

<?php include 'header.php'; ?>

<section class="about">
<div class="animated-text">
    <h1>Welcome to Hassmart Shopping</h1>
  </div>
  
  <div id="banner">
    <div class="banner-item">
        <a href="category.php"><img class="banner-image" src="images/home-bg.jpg" alt="Banner Image 1"></a>
    </div>
    <div class="banner-item">
        <a href="category.php"><img class="banner-image" src="images/home-bg3.jpg" alt="Banner Image 2"></a>
    </div>
    <div class="banner-item">
        <a href="category.php"><img class="banner-image" src="images/home-bg4.jpg" alt="Banner Image 3"></a>
        <div class="banner-content">
            <h3>Your Banner Heading Here</h3>
            <button>Button</button>
        </div>
    </div>
    <!-- Add more images as needed -->
    <script>
        const bannerImages = document.querySelectorAll('.banner-image');
        let currentImageIndex = 0;

        function changeImage() {
            bannerImages[currentImageIndex].style.display = 'none';
            currentImageIndex = (currentImageIndex + 1) % bannerImages.length;
            bannerImages[currentImageIndex].style.display = 'block';
        }

        setInterval(changeImage, 5000); // Change image every 5 seconds (adjust as needed)
    </script>
</div>

  <section class="row-container">
   <div class="row">

      <div class="box">
         <img src="images/sid-img01.jpg" alt="">
         <h3>Our Exellent Team</h3>
         <p>"Choose Hassmart for your shopping needs and experience the epitome of convenience. With our seamless interface, extensive product range, and lightning-fast delivery, we redefine the art of hassle-free shopping. Whether it's groceries, electronics, or fashion, we've got you covered every step of the way.".</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>
      

      <div class="box">
         <img src="images/about-img-2.png" alt="">
         <h3>WHAT WE PROVIDE?</h3>
         <p>
"At Hassmart, we provide a curated selection of top-quality products, ensuring you find exactly what you need. From everyday essentials to specialty items, our diverse range caters to every taste and requirement. With our user-friendly platform and secure payment options, shopping with us is not just convenient, it's a delightful experience.".</p>
         <a href="shop.php" class="btn">our shop</a>
      </div>

   </div>
   </section>
</section>

<section class="reviews">

   <h1 class="title">CLIENTS REIVEWS</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Devid Beckham</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Seren Williams</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Pollard Gayle</h3>
      </div>

      <div class="box">
         <img src="images/pic-4.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Srebdh</h3>
      </div>

      <div class="box">
         <img src="images/pic-5.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Sid wat</h3>
      </div>

      <div class="box">
         <img src="images/pic-6.png" alt="">
         <p>Wow such a great experience having . One of the best shopping site in the world and the customer service is very good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Rohit Sharma</h3>
      </div>

   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>