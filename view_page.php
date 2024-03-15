<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="sponsered-products">
    <div class="pbox-container sponsored-container">

        <?php
        $suggested_products_query = $conn->prepare("SELECT * FROM `products` ORDER BY RAND() LIMIT 1");
        $suggested_products_query->execute();

        if ($suggested_products_query->rowCount() > 0) {
            while ($fetch_suggested_product = $suggested_products_query->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" class="pbox sponsored-product" method="POST">
                    <div class="price">₹<span><?= $fetch_suggested_product['price']; ?></span>/-</div>
                    <img src="uploaded_img/<?= $fetch_suggested_product['image']; ?>" alt="<?= $fetch_suggested_product['name']; ?>" onclick="window.location='view_page.php?pid=<?= $fetch_suggested_product['id']; ?>'">
                    <div class="name"><?= $fetch_suggested_product['name']; ?></div>
                    <input type="hidden" name="pid" value="<?= $fetch_suggested_product['id']; ?>">
                    <input type="hidden" name="p_name" value="<?= $fetch_suggested_product['name']; ?>">
                    <input type="hidden" name="p_price" value="<?= $fetch_suggested_product['price']; ?>">
                    <input type="hidden" name="p_image" value="<?= $fetch_suggested_product['image']; ?>" alt="image not available">
                    
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">No suggested products available!</p>';
        }
        ?>

    </div>

</section>


<section class="quick-view">
   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Assuming images are non-empty, filter out empty values
            $images = array_filter([$fetch_products['image'], $fetch_products['image2'], $fetch_products['image3'], $fetch_products['image4']], function($value) { return !is_null($value) && $value !== ''; });
   ?>
   <form action="" class="vbox" method="POST">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="image-container slider">
            <?php foreach ($images as $index => $image) : ?>
                <img src="uploaded_img/<?= $image; ?>" alt="" class="slide" style="<?= $index === 0 ? '' : 'display: none;'; ?>">
            <?php endforeach; ?>
            <button type="button" class="prev" onclick="changeSlide(-1)" style="position: absolute; top: 50px; left: 10px;">&#10094;</button>
         <button type="button" class="next" onclick="changeSlide(1)" style="position: absolute; top: 50px; right: 10px;">&#10095;</button>
      </div>
      </div>
      <div class="product-details"> <!-- Container for details on the right side -->
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="price">Price MRP ₹<?= $fetch_products['price']; ?></div>
         <input type="number" min="1" value="1" name="p_qty" class="qty">
         <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist">
         <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
         <div class="details">
            <h3>DESCRIPTION</h3><?= $fetch_products['details']; ?>
         </div>
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>
</section>




<!--- sugestion section-->

<section class="our-products">

    <h1 class="title">Suggested Products</h1>

    <div class="pbox-container">

        <?php
        $suggested_products_query = $conn->prepare("SELECT * FROM `products` ORDER BY RAND() LIMIT 5");
        $suggested_products_query->execute();

        if ($suggested_products_query->rowCount() > 0) {
            while ($fetch_suggested_product = $suggested_products_query->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" class="pbox" method="POST">
                    <div class="price">₹<span><?= $fetch_suggested_product['price']; ?></span>/-</div>
                    <img src="uploaded_img/<?= $fetch_suggested_product['image']; ?>" alt="<?= $fetch_suggested_product['name']; ?>" onclick="window.location='view_page.php?pid=<?= $fetch_suggested_product['id']; ?>'">
                    <div class="name"><?= $fetch_suggested_product['name']; ?></div>
                    <input type="hidden" name="pid" value="<?= $fetch_suggested_product['id']; ?>">
                    <input type="hidden" name="p_name" value="<?= $fetch_suggested_product['name']; ?>">
                    <input type="hidden" name="p_price" value="<?= $fetch_suggested_product['price']; ?>">
                    <input type="hidden" name="p_image" value="<?= $fetch_suggested_product['image']; ?>" alt="image not available">
                    
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">No suggested products available!</p>';
        }
        ?>

    </div>

</section>


<script>
    let slideIndex = 0; // Start with the first image
    const slides = document.querySelectorAll(".slide");

    function showSlide(n) {
        slides.forEach((slide, index) => {
            slide.style.display = "none"; // Hide all slides
        });
        slides[n].style.display = "block"; // Show the desired slide
    }

    function changeSlide(n) {
        slideIndex += n;
        if (slideIndex >= slides.length) { slideIndex = 0; } // Go to the first slide if we've reached the end
        else if (slideIndex < 0) { slideIndex = slides.length - 1; } // Go to the last slide if we're moving back from the first

        showSlide(slideIndex);
    }

    // Initialize the first slide to be visible
    if (slides.length > 0) {
        showSlide(slideIndex);
    }
</script>


<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>