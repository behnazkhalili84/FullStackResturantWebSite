<?php
session_start();

// Check if the user is logged in and has the user role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "user")) {
    header("location: login.php");
    exit;
}

require_once "config.php";


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodiFoodi's Login</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <style>

        .registration-message {
            color: green;
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
        }
/* >>>>>>   HEADER    <<<<<<<*/

        /* Header Styles */
      
        
        .logo-container {
            text-align: center; 
            padding-right: 15px;
            margin:0;
        }

        .logo {
            width: 150px; 
            cursor: pointer; 
        }

        body {
            font-family: "NeueMontreal-Regular", sans-serif;
            background-color: rgba(255, 255, 240, 0.3);
        }

        .wrapper {
            width: 250px;
            margin: 0 auto;
            margin-bottom: 80px;
            padding: 20px 45px 45px 25px;
            background-color: rgba(255, 255, 240, 0.1);
            border-radius: 15px;
            box-shadow: 0 0 5px #B85042;
            font-family: Arial, Helvetica, sans-serif;
        }

        .wrapper h2 {
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-family: "Arial";
            font-weight: bold;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            
        }

        .form-control {
            width: calc(100% - 2px);
            height: 25px;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            outline: none;
            padding-left: 5px;
            padding-bottom: 5px;

        }

        .invalid-feedback {
            color: red;
            font-size: 12px;
        }

        .btn-primary {
            width: 102%;
            height: 30px;
            border-radius: 5px;
            border: none;
            background-color: rgba(184, 80, 66, 0.5);
            cursor: pointer;
        }

        .btn-primary:hover {
            color: black;
            background-color: rgba(184, 80, 66, 0.9);
            box-shadow: 0 0 1px black;
            font-weight: bold;
        }

        p {
            text-align: center;
        }
        footer {
        position: relative; /* Position relative to contain pseudo-element */
        background: linear-gradient(to bottom, #f8f9fa, rgba(248, 249, 250, 0.3));
        padding: 22px;
        text-align: center;
        }

        footer::before,
        footer::after {
            content: '';
            position: absolute;
            top: 0;
            width: 50%;
            height: 1px; /* Thickness of the gradient border */
        }

        footer::before {
            right: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(248, 249, 250, 0.3)); /* Gradient border */
        }

        footer::after {
            left: 0;
            background: linear-gradient(to left, rgba(0, 0, 0, 0.5), rgba(248, 249, 250, 0.3)); /* Gradient border */
        }

        .footer-content {
            max-width: 800px; /* Adjusted max-width */
            margin: 0 auto;
            display: flex;
            flex-direction: column; /* Changed flex-direction to column */
            align-items: center; /* Center align items */
        }

        .footer-content p {
            margin: 0;
        }

        .footer-content ul {
            list-style-type: none;
            padding: 0;
        }

        .footer-content ul li {
            display: inline;
            margin-right: 10px;
        }

        .footer-content ul li:last-child {
            margin-right: 0;
        }


        .options-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: 25px;
           
        }

        .option {
            width: 150px;
            padding: 20px;
            background-color: rgba(255, 255, 240, 0.1);
            border-radius: 15px;
            box-shadow: 0 0 5px #B85042;
            font-family: Arial, Helvetica, sans-serif;
            text-align:center;
            font-size:20px;
           
        }
        .option2 {
            width: 150px;
            padding: 20px;
            background-color: rgba(255, 255, 240, 0.1);
            border-radius: 15px;
            box-shadow: 0 0 5px #B85042;
            font-family: Arial, Helvetica, sans-serif;
            text-align:center;
            font-size:20px;
            color: rgba(221, 59, 30, 0.8);
           
        }

        .option:hover {
            color: darkblue;
        }
        .option2:hover {
            color: darkblue;
        }
        

        .welcome-message {
            font-size: 24px;
            text-align: center;
        }


    </style>
</head>

    <!-- Header -->
    <header>
        <div class="logo-container">
            <a href="index.php"> <!-- Replace "index.html" with the path to your home page -->
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>

<body>

    <h2 style="color:black;">
        <div class="welcome-message">Hi, <?php echo htmlspecialchars($_SESSION["first_name"]); ?></div><br>
        <div class="welcome-message" style="font-size: 15px">What would you like to do today?</div>
    </h2>
    <div class="options-container">
            <a href="order.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option2">Order Now</div>
            </a>
            <a href="Reservation.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option2">Reservation</div>
            </a>
            <a href="Account.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option">Account Info</div>
            </a>
            <a href="orderHistory.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option">Order History</div>
            </a>
            <a href="reset-password.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option">Reset Password</div>
            </a>
            <a href="logout.php" style="text-decoration: none; color: rgba(100, 149, 237, 1);">
                <div class="option">Sign Out</div>
            </a>
    </div>



    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 FoodiFoodi's. All rights reserved.</p>
            <ul>
                <li><a href="termOfUse.html">Terms of Use</a></li>
                <li><a href="privacyPolicy.html">Privacy Policy</a></li>
            </ul>
        </div>
    </footer>

</body>

</html>

