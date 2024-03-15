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
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
  <!----     
    <style>

        
/* General styling */
.sbox {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    position: relative;
}

.product-container {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
    width: 100%;
    position: relative;
}

.img-container {
    text-align: center;
}

.img-container img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.details-container {
    text-align: center;
    padding: 15px;
}

.name {
    font-size: 1.2em;
    margin: 10px 0;
}

.price {
    font-size: 1.1em;
    color: #3498db;
}

.buttons-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    bottom: 10px;
    right: 10px;
}

.heart-icon {
    font-size: 24px;
    color: #e74c3c;
    cursor: pointer;
    margin-right: 10px;
}

/* Adjustments for small screens */
@media only screen and (max-width: 768px) {
    .product-container {
        width: 100%;
    }

    .buttons-container {
        justify-content: center;
        align-items: center;
        position: relative;
        top: auto;
        right: auto;
        margin-top: 10px;
    }

    .buttons-container input[type="submit"] {
        margin-top: 10px;
    }
}

.heart-icon.active{
    color:red;
}

/* Adjustments for small screens */




    </style>  ----->
    <!-----
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const heartIcon = document.querySelector('.heart-icon');

            // Add a click event listener to the heart icon
            heartIcon.addEventListener('click', function (event) {
                event.preventDefault();
                // Toggle the 'active' class to change the color
                this.classList.toggle('active');
            });
        });
    </script>  ----->

</head>
<body>
   
<?php include 'header.php'; ?>
<section class="search-form">
    <form action="" method="POST" class="form-container">
       

        <!-- Sorting Dropdown -->
        <select name="sort_by" class="box sort-dropdown">
            <option value="default">Sort By</option>
            <option value="asc">Price Low to High</option>
            <option value="desc">Price High to Low</option>
        </select>
        <div class="search-container">
            <input type="text" class="box" name="search_box" placeholder="Search products...">
            <button type="submit" name="search_btn" class="search-btn"><i class="fas fa-search"></i></button>
        </div>
        <!-- Filtering Dropdown -->
        <select name="filter_category" class="box filter-dropdown">
            <option value="">All Categories</option>
            <option value="electronics">Electronics</option>
            <option value="grocery">Grocery</option>
            <!-- Add more categories as needed -->
        </select>
    </form>
</section>

<section class="our-products">
    <div class="pbox-container">
        <?php
        if(isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
            
            $sort_by = $_POST['sort_by'] ?? 'default';
            $filter_category = $_POST['filter_category'] ?? '';
            
            // Base query
            $query = "SELECT * FROM `products` WHERE name LIKE :search OR category LIKE :search";
            
            // Filter by category if selected
            if (!empty($filter_category)) {
                $query .= " AND category = '{$filter_category}'";
            }
            
            // Sort by price
            if ($sort_by === 'asc') {
                $query .= " ORDER BY price ASC";
            } elseif ($sort_by === 'desc') {
                $query .= " ORDER BY price DESC";
            }
            
            $select_products = $conn->prepare($query);
            $select_products->execute(['search' => '%'.$search_box.'%']);
            if($select_products->rowCount() > 0){
                while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
        ?>
        
        <form action="" class="pbox" method="POST">
      <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" onclick="window.location='view_page.php?pid=<?= $fetch_products['id']; ?>'">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <div class="btn-container">
           <button type="submit" class="option-btn" name="add_to_wishlist"><i class="fa fa-heart"></i> </button>
           <input type="number" min="1" value="1" name="p_qty" class="qty">
                              <button type="submit" class="btn" name="add_to_cart"><i class="fa fa-shopping-cart"></i></button>
</div>

   </form>
        <?php
                }
            } else {
                echo '<p class="empty">no result found!</p>';
            }
        }
        ?>
    </div>
</section>





<script src="js/script.js"></script>

</body>
</html>