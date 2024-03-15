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
<!-- home.php -->
<!-- home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
</head>

<body>
   
<?php include 'header.php'; ?>

<div id="banner">
  <?php
  $query = "SELECT * FROM banners";
  $result = $conn->query($query);

  if ($result->rowCount() > 0) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          echo '<a href="'.htmlspecialchars($row["webpage_url"]).'"><img class="banner-image" src="'.htmlspecialchars($row["image_path"]).'" alt="'.htmlspecialchars($row["banner_name"]).'" style="display:none;"></a>';
      }
  } else {
      echo "<p>No banners found.</p>";
  }
  ?>
<script>
    const bannerImages = document.querySelectorAll('.banner-image');
    let currentImageIndex = 0;
    bannerImages[currentImageIndex].style.opacity = '1'; // Initially show the first image.

    function changeImage() {
        const currentImage = bannerImages[currentImageIndex];
        const nextImageIndex = (currentImageIndex + 1) % bannerImages.length;
        const nextImage = bannerImages[nextImageIndex];

        // Fade out the current image
        currentImage.style.opacity = '0';

        // Wait for the fade out to finish before fading in the next image
        setTimeout(() => {
            // Hide the current image (to prevent it from blocking clicks) and show the next one
            currentImage.style.display = 'none';
            nextImage.style.display = 'block';
            nextImage.style.opacity = '1';
            
            // Update the current image index
            currentImageIndex = nextImageIndex;
        }, 2000); // This timeout should match the transition time in your CSS
    }

    // Set an interval to change the image
    setInterval(changeImage, 3000); // Change image every 5 seconds
</script>

</div>


<!---
<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>KOI BKL HI HOGA JO HASS MART SE SHOPPING NA KARE</span>
         <h3>Yes you are at the right place</h3>
         <p>Ab aaye hai to kuch kharid dari kar ke hi jaiye</p>
         <a href="about.php" class="btn">about us</a>
      </div>

   </section>

</div>

---->
<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="cbox-container">

      <div class="cbox">
         <img src="project images/mobile.png" alt="">
         <a href="category.php?category=fruits" class="btn">Mobiles</a>
      </div>

      <div class="cbox">
         <img src="project images/tcltv.webp" alt="">
         <a href="category.php?category=electronic" class="btn">Electronic</a>
      </div>
     <!--- 
      <div class="cbox">
         <img src="project images/electric.png" alt="">
         <a href="category.php?category=vegitables" class="btn">Electric</a>
      </div>
---->
      <div class="cbox">
         <img src="project images/hplaptop.webp" alt="">
         <a href="category.php?category=fish" class="btn">Computers</a>
      </div>
      <div class="cbox">
         <img src="project images/clothing.png" alt="">
         <a href="category.php?category=vegitables" class="btn">Fashion</a>
      </div>
      
      <div class="cbox">
         <img src="project images/grocer.png" alt="">
         <a href="category.php?category=fruits" class="btn">Grocery</a>
      </div>
      
      <div class="cbox">
         <img src="project images/grocer.png" alt="">
         <a href="category.php?category=fruits" class="btn">Grocery</a>
      </div>

   </div>
   <div class="cbox-container">
   <div class="cbox">
         <img src="project images/electric.png" alt="">
         <a href="category.php?category=vegitables" class="btn">Electric</a>
      </div>
      <div class="cbox">
         <img src="project images/grocer.png" alt="">
         <a href="category.php?category=fruits" class="btn">Grocery</a>
      </div>

      <div class="cbox">
         <img src="project images/shoess.png" alt="">
         <a href="category.php?category=electronic" class="btn">Footwears</a>
      </div>
      
      <div class="cbox">
         <img src="project images/bookss.png" alt="">
         <a href="category.php?category=vegitables" class="btn">Books</a>
      </div>

      <div class="cbox">
         <img src="project images/SG-PROkit.png" alt="">
         <a href="category.php?category=fish" class="btn">Toys</a>
      </div>

      <div class="cbox">
         <img src="project images/furniture.png" alt="">
         <a href="category.php?category=vegitables" class="btn">Furnitures</a>
      </div>

   </div>

</section>

<!--- temporary commented because we want new things
<section class="products">

   <h1 class="title">latest products</h1>

   <div class="pbox-container">

 /*  <?php/*  <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="pbox" method="POST" >
      <div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden"  name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
            
   </form>
   
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';  
   } ?>  */
   ?>*/

   </div>

</section>
--->
<!--- electronic section-->
<section class="our-products">

    <h1 class="title">Electronic Products</h1>

    <div class="pbox-container">

        <?php
        $select_electronic_products = $conn->prepare("SELECT * FROM `products` WHERE `category` = 'electronic' LIMIT 5");
        $select_electronic_products->execute();

        if($select_electronic_products->rowCount() > 0){
            while($fetch_electronic_products = $select_electronic_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>
        
        <form action="" class="pbox" method="POST" >
            <div class="price">₹<span><?= $fetch_electronic_products['price']; ?></span>/-</div>
           <!--- <a href="view_page.php?pid=<?= $fetch_electronic_products['id']; ?>" class="fas fa-eye"></a>
            <a href="#" class="fas fa-cart-plus" title="Add to Cart"></a>
            <a href="#" class="fas fa-heart" title="Add to Wishlist"></a>    --->
            <img src="uploaded_img/<?= $fetch_electronic_products['image']; ?>" alt="" onclick="window.location='view_page.php?pid=<?= $fetch_electronic_products['id']; ?>'">
            <div class="name"><?= $fetch_electronic_products['name']; ?></div>
            <input type="hidden" name="pid" value="<?= $fetch_electronic_products['id']; ?>">
            <input type="hidden" name="p_name" value="<?= $fetch_electronic_products['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $fetch_electronic_products['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $fetch_electronic_products['image']; ?>" alt="image not available">
            
           <div class="btn-container">
           <button type="submit" class="option-btn" name="add_to_wishlist"><i class="fa fa-heart"></i> </button>
           <input type="number" min="1" value="1" name="p_qty" class="qty">
                              <button type="submit" class="btn" name="add_to_cart"><i class="fa fa-shopping-cart"></i></button>
</div>

        </form>
        
        <?php
            }
        } else {
            echo '<p class="empty">No electronic products added yet!</p>';
        }
        ?>

    </div>

</section>
<!--- clothes section-->
<section class="our-products">

    <h1 class="title">clothes Products</h1>

    <div class="pbox-container">

        <?php
        $select_clothes_products = $conn->prepare("SELECT * FROM `products` WHERE `category` = 'clothes' LIMIT 5");
        $select_clothes_products->execute();

        if($select_clothes_products->rowCount() > 0){
            while($fetch_clothes_products = $select_clothes_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>
        
        <form action="" class="pbox" method="POST" >
            <div class="price">₹<span><?= $fetch_clothes_products['price']; ?></span>/-</div>
           <!--- <a href="view_page.php?pid=<?= $fetch_clothes_products['id']; ?>" class="fas fa-eye"></a>
            <a href="#" class="fas fa-cart-plus" title="Add to Cart"></a>
            <a href="#" class="fas fa-heart" title="Add to Wishlist"></a>    --->
            <img src="uploaded_img/<?= $fetch_clothes_products['image']; ?>" alt="" onclick="window.location='view_page.php?pid=<?= $fetch_clothes_products['id']; ?>'">
            <div class="name"><?= $fetch_clothes_products['name']; ?></div>
            <input type="hidden" name="pid" value="<?= $fetch_clothes_products['id']; ?>">
            <input type="hidden" name="p_name" value="<?= $fetch_clothes_products['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $fetch_clothes_products['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $fetch_clothes_products['image']; ?>" alt="image not available">
           <!--- <input type="number" min="1" value="1" name="p_qty" class="qty">  --->
           <div class="btn-container">
           <button type="submit" class="option-btn" name="add_to_wishlist"><i class="fa fa-heart"></i> </button>
           <input type="number" min="1" value="1" name="p_qty" class="qty">
                              <button type="submit" class="btn" name="add_to_cart"><i class="fa fa-shopping-cart"></i></button>
</div>


        </form>
        
        <?php
            }
        } else {
            echo '<p class="empty">No clothes products added yet!</p>';
        }
        ?>

    </div>

</section>
<section class="our-products">

    <h1 class="title">Grocery Products</h1>

    <div class="pbox-container">

        <?php
        $select_grocery_products = $conn->prepare("SELECT * FROM `products` WHERE `category` = 'grocery' LIMIT 5");
        $select_grocery_products->execute();

        if($select_grocery_products->rowCount() > 0){
            while($fetch_grocery_products = $select_grocery_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>
        
        <form action="" class="pbox" method="POST" >
            <div class="price">₹<span><?= $fetch_grocery_products['price']; ?></span>/-</div>
           <!--- <a href="view_page.php?pid=<?= $fetch_grocery_products['id']; ?>" class="fas fa-eye"></a>
            <a href="#" class="fas fa-cart-plus" title="Add to Cart"></a>
            <a href="#" class="fas fa-heart" title="Add to Wishlist"></a>    --->
            <img src="uploaded_img/<?= $fetch_grocery_products['image']; ?>" alt="" onclick="window.location='view_page.php?pid=<?= $fetch_grocery_products['id']; ?>'">
            <div class="name"><?= $fetch_grocery_products['name']; ?></div>
            <input type="hidden" name="pid" value="<?= $fetch_grocery_products['id']; ?>">
            <input type="hidden" name="p_name" value="<?= $fetch_grocery_products['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $fetch_grocery_products['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $fetch_grocery_products['image']; ?>" alt="image not available">
           <!--- <input type="number" min="1" value="1" name="p_qty" class="qty">  --->
           <div class="btn-container">
           <button type="submit" class="option-btn" name="add_to_wishlist"><i class="fa fa-heart"></i> </button>
           <input type="number" min="1" value="1" name="p_qty" class="qty">
                              <button type="submit" class="btn" name="add_to_cart"><i class="fa fa-shopping-cart"></i></button>
</div>


        </form>
        
        <?php
            }
        } else {
            echo '<p class="empty">No grocery products added yet!</p>';
        }
        ?>

    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    let currentIndex = 0;

    function showSlide(index) {
        const carousel = document.getElementById('carousel');
        const totalSlides = document.querySelectorAll('.carousel-item').length;
        index = (index + totalSlides) % totalSlides;
        currentIndex = index;
        const translateValue = -index * 100 + '%';
        carousel.style.transform = 'translateX(' + translateValue + ')';
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function prevSlide() {
        showSlide(currentIndex - 1);
    }

    // Automatically transition to the next slide every 3 seconds (adjust as needed)
    setInterval(nextSlide, 1000);
</script>

</body>
</html>
