<?php

include 'config.php';

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = md5($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
    
    $user_type = $_POST['user_type'];
    $user_type = filter_var($user_type, FILTER_SANITIZE_STRING);
    $admin_code_input = isset($_POST['admin_code']) ? filter_var($_POST['admin_code'], FILTER_SANITIZE_STRING) : '';
    
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
    
    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);
    
    if($select->rowCount() > 0){
        $message[] = 'User email already exists!';
    }else{
        if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
        }else{
            if($user_type === 'admin'){
                // Fetch the actual admin code from the database
                $select_admin_code = $conn->prepare("SELECT admin_code FROM `admin_code` LIMIT 1");
                $select_admin_code->execute();
                $admin_code_row = $select_admin_code->fetch(PDO::FETCH_ASSOC);
                $actual_admin_code = $admin_code_row['admin_code'];
                
                // Verify admin code
                if($admin_code_input !== $actual_admin_code){
                    $message[] = 'Admin code is incorrect, but you can register as a normal user.';
                    $user_type = 'user'; // Revert user type to 'user' if admin code is wrong
                }
            }
            
            $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image, user_type) VALUES(?,?,?,?,?)");
            $insert->execute([$name, $email, $pass, $image, $user_type]);
    
            if($insert){
                if($image_size > 2000000){
                    $message[] = 'Image size is too large!';
                }else{
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'Registered successfully!';
                    
                    header('location:login.php');
                }
            }
        }
    }
    
}



?>




<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="components.css">

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   
<section class="form-container">

   <form action=""  enctype="multipart/form-data" method="POST">
      <h3>register now</h3>
      <input type="text" name="name" class="box" placeholder="enter your name" required>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
      <!-- Add this section below the confirm password input -->
<select name="user_type" class="box" id="userType" onchange="toggleAdminCode(this.value)">
<option value="" selected disabled>User Type</option>
    <option value="user">User</option>
    <option value="admin">Admin</option>
    <option value="seller">Seller</option>
</select>
<div id="adminCodeContainer" style="display: none;">
    <input type="text" name="admin_code" class="box" placeholder="Enter admin code">
</div>

      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>

<script>
function toggleAdminCode(value) {
    const adminCodeContainer = document.getElementById('adminCodeContainer');
    adminCodeContainer.style.display = value === 'admin' ? 'block' : 'none';
}
</script>

</body>
</html>