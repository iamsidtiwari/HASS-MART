<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'order placed already!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'order placed successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <style>
      .display-orders, .checkout-orders {
    background-color: #000;
    color: #fff;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 0 20px #0f0;
}

.display-orders {
    margin: 0 auto;
    margin-left: 20px;
    margin-right: 20px;
    width: 80%; /* Adjust as needed */
}

.display-orders .product {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.display-orders .product img {
    width: 100px; /* Adjust image width as needed */
    height: auto; /* Maintain aspect ratio */
    margin-right: 20px;
    cursor: pointer;
}

.display-orders .product-details p {
    color: #fff;
    margin: 0;
}

.display-orders .product-details p span {
    color: #0f0;
    font-weight: bold;
}

.display-orders .empty {
    color: #fff;
}

.display-orders .grand-total {
    color: #0f0;
    font-weight: bold;
}

.checkout-orders {
    width: 95%; /* Adjust as needed */
    margin-left:40px;
    margin-right:10px;
    border-radius:10px
}

.checkout-orders form {
    background-color: #000;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px #0f0;
}

.checkout-orders h3 {
    color: #fff;
    margin-bottom: 20px;
    font-weight:bold;
    text-align:center;
}

.flex {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.inputBox {
    width: 49%;
    margin-bottom: 10px;
}

.inputBox span {
    color: #0f0;
    font-weight: bold;
}

.box {
    width: calc(100% - 20px);
    padding: 10px;
    border: 2px solid #0f0;
    border-radius: 5px;
    background-color: transparent;
    color: #fff;
}

.box:focus {
    outline: none;
    box-shadow: 0 0 10px #0f0;
}




@media (max-width: 768px) {
    .flex {
        flex-direction: column;
    }
}

      </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">
    <?php
        $cart_grand_total = 0;
        $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart_items->execute([$user_id]);
        if($select_cart_items->rowCount() > 0){
            while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
                $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
                $cart_grand_total += $cart_total_price;
    ?>
    <div class="product">
        <img src="uploaded_img/<?= $fetch_cart_items['image']; ?>" alt="Product Image" onclick="window.location='view_page.php?pid=<?= $fetch_cart_items['id']; ?>'">
        <div class="product-details">
            <p><?= $fetch_cart_items['name']; ?><span>(<?= '₹'.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span></p>
        </div>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">Your cart is empty!</p>';
    }
    ?>
    <div class="grand-total">Grand Total : <span>₹<?= $cart_grand_total; ?>/-</span></div>
</section>


<section class="checkout-orders">

   <form action="http://localhost/om/paymentGatway.php" method="POST">

      <h3>PLACE YOUR ORDER</h3>

      <div class="flex">
         <div class="inputBox">
            <span>YOUR NAME :</span>
            <input type="text" name="name" placeholder="Enter Your Name" class="box" required>
            

            <!-- // for amount -->
            <input hidden type="text" name="amount" value="<?php echo $cart_grand_total ?>"  class="box" >
         </div>
         <div class="inputBox">
            <span>YOUR NUMBER :</span>
            <input type="number" name="number" placeholder="Enter Your Number" class="box" required>
         </div>
         <div class="inputBox">
            <span>YOUR EMAIL :</span>
            <input type="email" name="email" placeholder="Enter Your Email" class="box" required>
         </div>
         <div class="inputBox">
            <span>PAYMENT METHOD :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash on delivery</option>
               <option value="credit card">Credit card</option>
               <option value="paytm">Online payment</option>
               <option value="paypal">Paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>ADDRESS LINE 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
         </div>
         <div class="inputBox">
            <span>ADDRESS LINE 02 :</span>
            <input type="text" name="street" placeholder="e.g. Street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>CITY :</span>
            <input type="text" name="city" placeholder="e.g. Surat" class="box" required>
         </div>
         <div class="inputBox">
            <span>STATE :</span>
            <input type="text" name="state" placeholder="e.g. Gujarat" class="box" required>
         </div>
         <div class="inputBox">
            <span>COUNTRY :</span>
            <input type="text" name="country" placeholder="e.g. India" class="box" required>
         </div>
         <div class="inputBox">
            <span>PIN CODE :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <button style="background-color: #4CAF50; /* green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 5px;">Place Order</button>


   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>