<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    if (isset($_FILES["reviewImage"]) && $_FILES["reviewImage"]["error"] == 0) {
        $targetDir = "uploaded/";
        $targetFile = $targetDir . basename($_FILES["reviewImage"]["name"]);
        if (move_uploaded_file($_FILES["reviewImage"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO feedback (name, email, message, image) VALUES ('$name', '$email', '$message', '$targetFile')";
            if ($conn->query($sql) === TRUE) {
                echo "Review submitted successfully.";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No image uploaded or there was an upload error.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @font-face {
            font-family: 'Lemonmilk';
            src: url('media/fonts/lemonmilk.otf') format('opentype');
        }

        body {
            font-family: 'Lemonmilk', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background-color: #eef2f3;
            overflow-x: hidden;
        }

        .navbar {
            position: sticky;
            top: 0;
            color: white;
            background: black;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 100%;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .logo {
            font-size: 48px;
            font-weight: bold;
            margin-right: 40px;
        }

        .logo span {
            color: yellow;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            font-size: 18px;
            position: relative;
            padding-bottom: 5px;
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background-color: yellow;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 25px;
        }

        .review-box {
            background-color: #fff;
            text-align: left;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #007bff;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 20px;
        }

        .review-box h4 {
            margin: 0;
            font-size: 20px;
            color: #007bff;
        }

        .review-box small {
            color: #777;
        }

        .review-box p {
            margin-top: 10px;
            color: #333;
            line-height: 1.6;
        }

        .review-content {
            display: flex;
            align-items: center;
        }

        .review-image {
            max-width: 100px;
            margin-right: 20px;
            border-radius: 8px;
        }

        .footer {
            background-color: black;
            color: white;
            padding: 15px 20px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .footer-text {
            font-size: 1rem;
        }

        .footer-nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
            margin: 0;
        }

        .footer-nav ul li {
            display: inline;
        }

        .footer-nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        .footer-nav ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="home.html" class="logo">The Flavorist<span>.</span></a>
        <ul class="nav-links">
            <li><a href="allrecipes.html">Recipes</a></li>
            <li><a href="health.html">Health & Diet</a></li>
            <li><a href="cuisines.html">Cuisines</a></li>
            <li><a href="aboutus.html">About Us</a></li>
            <li><a href="customerFeedback.html">Feedback</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

   
    <div class="container">
        <h1>What Our Customers Say</h1>

        <?php
        $sql = "SELECT name, email, message, image FROM feedback ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review-box'>";
                echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                echo "<small>" . htmlspecialchars($row['email']) . "</small>";
                echo "<div class='review-content'>";
                if ($row['image']) {
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Review Image' class='review-image'>";
                }
                echo "<p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No feedback available yet. Be the first to submit yours!</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">Credit: Manasi Patil, Manasvi Naik & Mehek Abhyankar</p>
            <nav class="footer-nav">
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="allrecipes.html">Recipes</a></li>
                    <li><a href="aboutus.html">About</a></li>
                    <li><a href="contactUs.html">Contact</a></li>
                </ul>
            </nav>
        </div>
    </footer>

</body>
</html>
