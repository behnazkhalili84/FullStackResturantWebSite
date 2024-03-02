<?php
session_start();

require_once "config.php";
$userID = $_SESSION["userID"];
$role=$_SESSION["role"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $address2 = $_POST["address2"];
    $city = $_POST["city"];
    $province = $_POST["province"];
    $postalCode = $_POST["postalCode"];
    
    // Validate input data (you can add more validation if needed)
    if (empty($firstName) || empty($lastName) || empty($phone) || empty($address) || empty($city) || empty($province) || empty($postalCode)) {
        $error = "All fields are required.";
    } else {
        // Update user information in the database
        $userID = $_SESSION["userID"];
        $sql = "UPDATE users SET firstName=?, lastName=?, phone=?, address=?, address2=?, city=?, province=?, postalCode=? WHERE userID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $phone, $address, $address2, $city, $province, $postalCode, $userID);
            if (mysqli_stmt_execute($stmt)) {
                // Information updated successfully
                $success = "User information updated successfully.";
        
                // Redirect to Account.php with success message
                header("location: Account.php?success=" . urlencode($success));
                exit();
            } else {
                // Display an error message if something went wrong
                $error = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        } else {
            // Display an error message if something went wrong
            $error = "Oops! Something went wrong. Please try again later.";
        }
    }
}

// Fetch the user's current information
$userID = $_SESSION["userID"];
$sql = "SELECT * FROM users WHERE userID=?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $userID);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
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
                               }

        /* >>>>>>   Body    <<<<<<<*/

        body {
            font-family: "NeueMontreal-Regular", sans-serif;
            background-color: rgba(255, 255, 240, 0.3);
      
        }
        .container {
            width: 350px;
            margin: 0 auto;
            margin-bottom:80px;
            padding: 5px 45px 45px 25px;
            background-color: rgba(255, 255, 240, 0.1);
            border-radius: 15px;
            box-shadow: 0 0 5px #B85042;
            font-family:Arial, Helvetica, sans-serif;
            
            }
        

        .container h2 {
            text-align: center;
            padding: 15px;
            font-size: 20px; 
            font-family: "Arial"; 
            font-weight: bold; 
            color: #333; 
        }
      
        .formContainer {
            display: grid;
            grid-template-columns: 138px auto; 
            gap: 20px; 
            
        }
        .formContainer p {
            grid-column: span 2; 
            text-align: center; 
        }

        .formContainer label {
            
            font-weight: bold;
            font-size: 13px;
            white-space: nowrap;
            text-align: right;
            align-self: center;
            justify-self: end;
            
        }

        .formContainer input {
            padding-left:5px;
            width: 200px;
            height: 25px;
            border-radius:5px;
            justify-self: start;
            border: 1px solid rgba(0, 0, 0, 0.2);
            outline: none;
        }

        .formContainer button {
            grid-column: 2;
            width: 208px;
            height: 25px;
            border-radius:5px;
            border: none;
            justify-self: start;
            background-color: rgba(184, 80, 66, 0.5);
      
        }

        
        .formContainer button:hover {
            color: black;
            background-color: rgba(184, 80, 66, 0.9);
            box-shadow: 0 0 1px black;
            font-weight: bold;
            cursor:pointer;
            
        }

        .input-wrapper {
        position: relative;
        outline: none;
        }

        .input-wrapper select {
            width: 100%;
            height: 30px;
            border-radius: 5px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            font-size: 12px;
            padding-left:2px;
            outline: none; 
         
        }

        .errors {
            position: absolute;
            top: 105%;
            left: 3px;
            color: red;
            font-size: 8px;
          
        }

        ::placeholder {
            font-size: 12px;
           
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
            <a href="index.php"> <!-- Replace "index.html" with the path to your home page -->
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>
  
<body>
   
    <?php if(isset($error)): ?>
        <div><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if(isset($success)): ?>
        <div><?php echo $success; ?></div>
    <?php endif; ?>
    <div class="container">
    <h2>Update User Information</h2>
        <form method="post" class="formContainer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        
            <label for="firstName" class="form-label">First Name:</label>
        <div class="input-wrapper">
            <input type="text" id="firstName" name="firstName" class="form-control <?php echo (!empty($firstNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['firstName']; ?>">
            <span class="errors" id="firstName-error"><?php echo $firstNameErr; ?></span>
        </div>
        
            <label for="lastName" class="form-label">Last Name:</label>
        <div class="input-wrapper">
            <input type="text" id="lastName" name="lastName" class="form-control <?php echo (!empty($lastNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['lastName']; ?>">
            <span class="errors" id="lastName-error"><?php echo $lastNameErr; ?></span>
        </div>
        
            <label for="phone" class="form-label">Phone:</label>
        <div class="input-wrapper">
            <input type="text" id="phone" name="phone" class="form-control <?php echo (!empty($phoneErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['phone']; ?>">
            <span class="errors" id="phone-error"><?php echo $phoneErr; ?></span>
        </div>
        
            <label for="address" class="form-label">Address:</label>
        <div class="input-wrapper">
            <input type="text" id="address" name="address" class="form-control <?php echo (!empty($addressErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['address']; ?>">
            <span class="errors" id="address-error"><?php echo $addressErr; ?></span>
        </div>
        
            <label for="address2" class="form-label">Address 2:</label>
        <div class="input-wrapper">
            <input type="text" id="address2" name="address2" class="form-control" value="<?php echo $user['address2']; ?>">
        </div>
        
            <label for="city" class="form-label">City:</label>
        <div class="input-wrapper">
            <input type="text" id="city" name="city" class="form-control <?php echo (!empty($cityErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['city']; ?>">
            <span class="errors" id="city-error"><?php echo $cityErr; ?></span>
        </div>
        
            <label for="province" class="form-label">Province:</label>
        <div class="input-wrapper">
            <input type="text" id="province" name="province" class="form-control" value="<?php echo $user['province']; ?>">
        </div>
       
            <label for="postalCode" class="form-label">Postal Code:</label>
        <div class="input-wrapper">
            <input type="text" id="postalCode" name="postalCode" class="form-control <?php echo (!empty($postalCodeErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $user['postalCode']; ?>">
            <span class="errors" id="postalCode-error"><?php echo $postalCodeErr; ?></span>
        </div>
       
            <button type="submit" class="btn btn-primary">Update Information</button>
       
            <div style="grid-column: 2; color:blue;">
                <a href="<?php echo ($role === 'user') ? 'user.php' : 'admin.php'; ?>">Return to user page</a>
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
