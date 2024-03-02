<?php
// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logout_message = isset($_GET['logout']) && $_GET['logout'] === 'success' ? "You are now logged out." : "";

// Check if there's a password reset success parameter
$password_reset_success = isset($_GET['password_reset']) && $_GET['password_reset'] === 'success';

// Check if there's a password reset failure parameter
$password_reset_failure = isset($_GET['password_reset']) && $_GET['password_reset'] === 'failure';

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: user.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password =  "";
$email_err = $password_err = $login_err = "";
$registration_message = "";
// Check if the registration parameter is present and has the value 'success'
if(isset($_GET['registration']) && $_GET['registration'] === 'success') {
    $registration_message = "User registration successful.";
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT userID, firstName, lastName, phone, email, password, role FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $userID, $firstName, $lastName, $phone, $email, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userID"] = $userID;
                            $_SESSION["first_name"] = $firstName;
                            $_SESSION["phone"] = $phone;
                            $_SESSION["last_name"] = $lastName;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;

                            if ($role === 'admin') {
                                header("location: admin.php");
                            } else {
                                header("location: user.php");
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    // Email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else {
                // Display error message in case of failure in SQL execution
                $login_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
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
    </style>
</head>

    <!-- Header -->
    <header>
        <div class="logo-container">
            <a href="index.php"> 
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>

<body>
    <div class="wrapper">
        <h2>Sign in</h2>

        <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger"><span style="color: red;">' . $login_err . '</span></div>';
            }
        ?>
            <div class="registration-message"><?php echo $registration_message; ?></div>
            
            <?php if(!empty($logout_message)): ?>
                <div class="alert alert-success" style="color:red;"><?php echo $logout_message; ?></div><br>
            <?php endif; ?>

            <!-- display the password reset success message -->
            <?php if($password_reset_success): ?>
                <div class="registration-message" style="color: green;">Password's been reset successfully.</div><br>
            <?php endif; ?>

            <!-- display the password reset failure message -->
            <?php if($password_reset_failure): ?>
                <div class="alert alert-danger" style="color:red;">Oops! Something went wrong. Please try again later.</div>
            <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div><br/>
            <p>New to Foodi Foodi? <a href="register.php">Sign up now</a>.</p>
        </form>
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

