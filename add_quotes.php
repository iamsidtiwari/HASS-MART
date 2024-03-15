<?php
// Initialize variables
$quote = $author = '';
$quoteErr = $authorErr = '';
$submittedSuccessfully = false;
require_once 'config.php';
// Include your database connection file
session_start();

// Check if the user is logged in and is an admin
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    // Redirect to the login page or another suitable page
    header('Location: login.php');
    exit();
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to fetch quotes from the database
function getQuotes($conn) {
    try {
        $sql = "SELECT id, myquotes, author FROM quotes";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Function to delete a quote by ID
function deleteQuote($conn, $quoteId) {
    try {
        $sql = "DELETE FROM quotes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$quoteId]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["quote"])) {
        $quoteErr = "Quote is required";
    } else {
        $quote = test_input($_POST["quote"]);
    }

    if (empty($_POST["author"])) {
        $authorErr = "Author is required";
    } else {
        $author = test_input($_POST["author"]);
    }

    // If all inputs are valid, insert into database
    if (empty($quoteErr) && empty($authorErr)) {
        try {
            $sql = "INSERT INTO quotes (myquotes, author) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            // Bind parameters and execute
            $stmt->execute([$quote, $author]);

            // Set a flag or session variable to indicate successful submission
            $submittedSuccessfully = true;

            // Redirect to the same page to prevent form resubmission
            header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]));
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}



// Check if delete request is present
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    deleteQuote($conn, $deleteId);
}

$quotes = getQuotes($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quote</title>
    <style>
        

        form {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-right:15px;
        }

        h2 {
            margin-top: 50px; /* Add space between the top of the page and the heading */
        }

        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .error {
            color: #ff3860;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #43a047;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #1e1e1e;
        }

        tr:hover {
            background-color: #333;
        }

        .delete-btn-table {
            background-color: #e74c3c;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn-table:hover {
            background-color: #c0392b;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
     <?php include 'admin_header.php'; ?>
    <section class="add-quotes">
    <div class="quotes">
    <h2>Add Quotes</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="clearForm()">
        <label for="quote">Quote:</label><br>
        <textarea id="quote" name="quote" rows="4" cols="50"><?php echo $quote; ?></textarea>
        <span class="error">* <?php echo $quoteErr; ?></span>
        <br><br>
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" value="<?php echo $author; ?>">
        <span class="error">* <?php echo $authorErr; ?></span>
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
    </div>
    </section>
    <section class="stored-quotes">
    <div class="stquotes">
    <h2>Stored Quotes</h2>

    <?php if ($quotes && is_array($quotes) && !empty($quotes)) : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Quote</th>
                <th>Author</th>
                <th>Action</th>
            </tr>
            <?php foreach ($quotes as $quote) : ?>
                <tr>
                    <td><?php echo $quote['id']; ?></td>
                    <td><?php echo $quote['myquotes']; ?></td>
                    <td><?php echo $quote['author']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $quote['id']; ?>" class="delete-btn-table">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No quotes available.</p>
    <?php endif; ?>
    </div>
    </section>
    <script>
        function clearForm() {
            // Assuming you have a function to clear the form, as mentioned in your original code
        }
    </script>
</body>
</html>
