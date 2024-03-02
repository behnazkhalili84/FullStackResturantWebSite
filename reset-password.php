<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 $role=$_SESSION["role"];
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
if(empty(trim($_POST["new_password"]))){
    $new_password_err = "Please enter the new password.";     
} elseif(strlen(trim($_POST["new_password"])) < 10){
    $new_password_err = "Password must have at least 10 characters.";
} elseif(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?!.*\s).{10,}$/', trim($_POST["new_password"]))){
    $new_password_err = "Password should contain one digit, one uppercase letter, and one special character.";
} else{
    $new_password = trim($_POST["new_password"]);
}

// Validate confirm password
if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Please confirm the password.";
} else{
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($new_password_err) && ($new_password != $confirm_password)){
        $confirm_password_err = "Password did not match.";
    }
}
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE userID = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["userID"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php?password_reset=success");
                exit();
            } else{
                // Display an error message if something went wrong
                header("location: login.php?password_reset=failure");
                exit();
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
    <title>Reset Password</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <style>
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
        }        body {
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
            width: 250px;
            height: 25px;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            outline: none;
            padding-left: 5px;
        }

        .invalid-feedback {
            color: red;
            font-size: 11px;
        }

        .btn-primary {
            width: 258px;
            height: 28px;
            border-radius: 5px;
            border: none;
            background-color: rgba(184, 80, 66, 0.5);
            cursor: pointer;
            margin-bottom: 10px;
        }

        .btn-secondary {
            width: 100%;
            height: 35px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            color: black;
            background-color: rgba(184, 80, 66, 0.9);
            box-shadow: 0 0 1px black;
            font-weight: bold;
        }

        .btn-secondary:hover {
            background-color: rgba(0, 0, 0, 0.3);
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
            <a href="index.html"> <!-- Replace "index.html" with the path to your home page -->
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <div class="form-group">
             <p style="display: inline; margin-right: 10px;">Return to user page:</p><a href="<?php echo ($role === 'user') ? 'user.php' : 'admin.php'; ?>" class="btn btn-secondary" style="display: inline;">Cancel</a>
            </div>
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
