<?php
// Ensure that error reporting is enabled for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the configuration file and establish a database connection
include 'config.php';

// Start the session
session_start();

// Check if the admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location: login.php');
    exit();
}


// Handle form submission for adding a new banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_banner'])) {
    $banner_name = $_POST['banner_name'];
    $webpage_url = $_POST['webpage_url'];

    // Handle image upload
    $image_path = 'uploads/'; // Specify your upload directory
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Ensure the target directory exists
    if (!file_exists($image_path)) {
        mkdir($image_path, 0777, true);
    }

    move_uploaded_file($image_tmp, $image_path . $image_name);

    // Insert the new banner into the database
    $insert_banner = $conn->prepare("INSERT INTO banners (banner_name, webpage_url, image_path) VALUES (?, ?, ?)");
    $insert_banner->execute([$banner_name, $webpage_url, $image_path . $image_name]);
}

// Handle deletion of a banner
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_banner = $conn->prepare("DELETE FROM banners WHERE id = ?");
    $delete_banner->execute([$delete_id]);
}

// Fetch all banners from the database
$select_banners = $conn->query("SELECT * FROM banners")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banners Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #1a1a1a;
    color: #fff;
    margin: 0;
    padding: 0;
}

.banners {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.title {
    font-size: 2rem;
    color: #fff;
    margin-bottom: 20px;
}

form {
    background-color: #262626;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 8px;
    color: #fff;
}

form input[type="text"],
form input[type="file"],
form input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #333;
    border-radius: 4px;
    background-color: #333;
    color: #fff;
}

form input[type="submit"] {
    background-color: #00cc66;
    color: #fff;
    cursor: pointer;
}

form input[type="submit"]:hover {
    background-color: #00994d;
}

.banner-container {
    display: flex;
    flex-wrap: wrap;
}

.banner-box {
    width: calc(33.33% - 20px);
    height: 200px;
    margin: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    background-color: #333;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.banner-box img {
    width: 100%;
    height: auto;
    object-fit: cover;
    margin-bottom: 10px;
}

.banner-box p {
    margin: 0;
    font-size: 1rem;
    color: #fff;
}

.delete-btn {
    position: absolute;
    bottom: 10px;
    right: 20px;
    background-color: #e74c3c;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.delete-btn:hover {
    background-color: #c0392b;
}


    </style>
</head>
<body>

    <?php include 'admin_header.php'; ?>

    <section class="banners">
        <h1 class="title">Banners Management</h1>

        <form method="post" action="" enctype="multipart/form-data">
            <label for="banner_name">Banner Name:</label>
            <input type="text" name="banner_name" required>
            <br>

            <label for="webpage_url">Webpage URL:</label>
            <input type="text" name="webpage_url" required>
            <br>

            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*" required>
            <br>

            <input type="submit" name="add_banner" value="Add Banner">
        </form>

        <div class="banner-container">
        <h1 class="title">Uploaded Banners</h1>
            <?php foreach ($select_banners as $banner) : ?>
                <div class="banner-box">
                    <img src="<?php echo $banner['image_path']; ?>" alt="Banner Image">
                    <div class="banner-info">
                        <p>Banner Name: <?php echo $banner['banner_name']; ?></p>
                        <p>Webpage URL: <?php echo $banner['webpage_url']; ?></p>
                    </div>
                    <div class="delete-btn" onclick="deleteBanner(<?php echo $banner['id']; ?>)">Delete Banner</div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
        function deleteBanner(bannerId) {
            if (confirm('Are you sure you want to delete this banner?')) {
                window.location.href = 'admin_banners.php?delete=' + bannerId;
            }
        }
    </script>

</body>
</html>
