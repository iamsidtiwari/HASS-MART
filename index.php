<?php
// Start the session
session_start();
require_once 'config.php';

// PHP login check logic
$loggedIn = isset($_SESSION['user_id']); // Replace 'user_id' with the actual session key you use

$sql = "SELECT myquotes, author FROM quotes ORDER BY RAND() LIMIT 1";
$stmt = $conn->query($sql);
$quoteData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a quote is retrieved
if ($quoteData) {
    $quote = $quoteData['myquotes'];
    $author = $quoteData['author'];
} else {
    $quote = "No quotes available.";
    $author = "Unknown";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hass Mart</title>
    <style>
        

        @keyframes fadeInOut {
            0% { opacity: 0.2; }
            50% { opacity: 1; }
            100% { opacity: 0.3; visibility: hidden; }
        }
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Ensure vertical stacking */
            background-color: #000; /* Or any color you prefer */
            font-family: Arial, sans-serif;
            color: #fff; /* Adjust based on your preference */
        }
        #splash-screen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .splash-screen img {
            max-width: 200px; /* Adjust based on your logo size */
            margin-bottom: 50px; /* Space between logo and quote */
            box-shadow: 0 0 20px #0c0;
            
        }
        .glowing-quote {
            color: #fff; /* Initial text color */
            font-size: 20px; /* Adjust font size as needed */
            text-align: center;
            animation: glow 1s ease-in-out infinite alternate;
        }
        @keyframes glow {
            from {
                text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
            }
            to {
                text-shadow: 0 0 20px #fff, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
            }
        }
        #logo {
            width: 400px; /* Adjust based on your logo's size */
            animation: fadeInOut 5s forwards; /* Adjust time as needed */
        }
    </style>
</head>
<body>

<div id="splash-screen" class="splash-screen">
        <img src="project images/hassmartglow.png" alt="Logo" id="logo"> <!-- Your logo here -->
        <div class="glowing-quote">
            "<?php echo htmlspecialchars($quote); ?>" - <?php echo htmlspecialchars($author); ?>
        </div>
    </div>

<script>
document.getElementById('logo').addEventListener('animationend', function() {
    // After the animation, check if the user is logged in and redirect
    var loggedIn = <?php echo json_encode($loggedIn); ?>;
    if (loggedIn) {
        window.location.href = 'home.php';
    } else {
        window.location.href = 'login.php';
    }
});
</script>

</body>
</html>
