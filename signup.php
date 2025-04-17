<?php
include 'db.php';
session_start();

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) 
    {
        $message = "Email already exists. Try another one.";
    } 
    else 
    {
        $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);

        if ($stmt->execute()) 
        {
            $message = "Account created successfully. <a href='login.php'>Login here</a>";
        } 
        else 
        {
            $message = "Error: Could not create account.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateSignupForm() 
        {
            var fullname = document.getElementById("fullname").value.trim();
            var email = document.getElementById("email").value.trim();
            var password = document.getElementById("password").value.trim();
            var errorMessage = document.getElementById("error-message");

            errorMessage.innerHTML = "";

            if (fullname === "" || email === "" || password === "") 
            {
                errorMessage.innerHTML = "All fields are required.";
                return false;
            }

            var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (!email.match(emailPattern)) 
            {
                errorMessage.innerHTML = "Please enter a valid email.";
                return false;
            }

            if (password.length < 6) 
            {
                errorMessage.innerHTML = "Password must be at least 6 characters long.";
                return false;
            }

            return true;
        }
    </script>
</head>
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

    .nav-links a:hover {
        text-decoration: none;
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

    .nav-links a:hover::after {
        transform: scaleX(1);
    }

    .container {
            width: 390px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            margin-bottom:60px;
            margin-top :60px;

        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            margin-bottom
        }
        button {
            width: 100%;
            padding: 12px;
            background: black;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-top:10px;
            margin-bottom:10px;
        }
       
        button:hover {
            background: yellow;
            color: black;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #ff6b81;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
   
    /* Footer Styling */
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
    }

    .footer-text {
        font-size: 1rem;
    }

    .footer-nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
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
<body>
<nav class="navbar">
        <a href="#" class="logo">The Flavorist<span>.</span></a>
        <ul class="nav-links">
            <li><a href="#">Recipes</a></li>
            <li><a href="#">Health & Diet</a></li>
            <li><a href="#">Cuisines</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Feedback</a></li>
            
        </ul>
    </nav>

    <div class="container">
        <h2>Create an Account</h2>
        <p id="error-message" class="error-message"><?php echo $message; ?></p>
        <form action="signup.php" method="POST" onsubmit="return validateSignupForm()">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    
     <!-- Footer -->
     <footer class="footer">
        <div class="footer-content">
            <p class="footer-text">Credit: Manasi Patil, Manasvi Naik & Mehek Abhyankar</p>
            <nav class="footer-nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Recipes</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </footer>
</body>
</html>
