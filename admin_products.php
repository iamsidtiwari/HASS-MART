<?php


@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

// Check if a search term was submitted
$search_term = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

// Prepare your SQL based on whether a search term was provided
if ($search_term) {
    $show_products = $conn->prepare("SELECT * FROM `products` WHERE `name` LIKE :search_term OR `category` LIKE :search_term");
    $show_products->bindParam(':search_term', $search_term);
} else {
    $show_products = $conn->prepare("SELECT * FROM `products`");
}
$show_products->execute();

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_product'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // Optional images
    $image2 = $_FILES['image2']['name'] ? $_FILES['image2']['name'] : '';
    $image3 = $_FILES['image3']['name'] ? $_FILES['image3']['name'] : '';
    $image4 = $_FILES['image4']['name'] ? $_FILES['image4']['name'] : '';

    $image2_tmp_name = $_FILES['image2']['tmp_name'];
    $image3_tmp_name = $_FILES['image3']['tmp_name'];
    $image4_tmp_name = $_FILES['image4']['tmp_name'];

    $image2_folder = $image2 ? 'uploaded_img/' . $image2 : '';
    $image3_folder = $image3 ? 'uploaded_img/' . $image3 : '';
    $image4_folder = $image4 ? 'uploaded_img/' . $image4 : '';

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        if ($_FILES['image']['size'] > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image, image2, image3, image4) VALUES(?,?,?,?,?,?,?,?)");
            $insert_products->execute([$name, $category, $details, $price, $image, $image2, $image3, $image4]);

            if ($insert_products) {
                move_uploaded_file($image_tmp_name, $image_folder);
                if ($image2) move_uploaded_file($image2_tmp_name, $image2_folder);
                if ($image3) move_uploaded_file($image3_tmp_name, $image3_folder);
                if ($image4) move_uploaded_file($image4_tmp_name, $image4_folder);

                $message[] = 'New product added successfully!';
            } else {
                $message[] = 'There was an error adding the product.';
            }
        }
    }
}


if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">ADD NEW PRODUCTS</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="enter product name">
         <select name="category" class="box" required>
            <option value="" selected disabled>select category</option>
            <option value="Electronic">Electronic</option>
               <option value="Clothes">Clothes</option>
               <option value="Cosmatics">Cosmatics</option>
               <option value="Books">Books</option>
               <option value="vegitables">vegitables</option>
               <option value="fruits">fruits</option>
               <option value="meat">meat</option>
               <option value="fish">fish</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="enter product price">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="file" name="image2" class="box" accept="image/jpg, image/jpeg, image/png">
   <input type="file" name="image3" class="box" accept="image/jpg, image/jpeg, image/png">
   <input type="file" name="image4" class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add product" name="add_product">
   </form>

</section>

<section class="show-products">

    <h1 class="title">Products Added</h1>
    <form action="" method="GET">
         <input type="text" name="search" placeholder="Search by name or category" required>
         <button type="submit">Search</button>
         
    </form>
    <div class="product-container">
        <?php
        if ($show_products->rowCount() > 0) {
            while ($product = $show_products->fetch(PDO::FETCH_ASSOC)) {
                // Display each product
                echo "<div class='product'>";
                echo "<h2>" . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . "</h2>";
                echo "<p>Category: " . htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') . "</p>";
                // Add more details as needed
                echo "</div>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        ?>
    </div>


    <div class="box-container">

        <?php
        $show_products = $conn->prepare("SELECT * FROM `products`");
        $show_products->execute();
        if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class="box">
                    <div class="product-info">
                        <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image'], ENT_QUOTES); ?>" alt="">
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="price">₹.<?= $fetch_products['price']; ?>/-</div>
                    </div>
                    <div class="flex-btn">
                        <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                        <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>

    </div>

</section>











<script src="js/script.js"></script>

</body>
</html>