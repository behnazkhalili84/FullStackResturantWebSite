<?php

session_start();
 
// Check if the user is logged in and has the admin role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "admin")) {
    header("location: login.php");
    exit;
}


// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = $retype_password = $first_name = $last_name = $phone = $address = $address2 = $city = $province = $postalcode = "";
$emailErr = $passwordErr = $retype_passwordErr = $first_nameErr = $last_nameErr = $phoneErr = $addressErr = $address2Err = $cityErr = $provinceErr = $postalcodeErr = "";
$userRole = "admin";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    /// Validate email
    if(empty($_POST["email"])){
        $emailErr = "Please enter your email.";
    } elseif(!preg_match("/^[^@\s]+@[^@\s]+\.[^@\s]+$/",trim($_POST["email"]))){
        $emailErr = "Invalid email format.";
    } else {
        $email = test_input($_POST["email"]);
    }

   // Validate password
   if(empty($_POST["password"])){
    $passwordErr = "Please enter a password.";
   } elseif(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?!.*\s).{10,}$/',trim($_POST["password"]))){
    $passwordErr = "Password should contain one digit, one uppercase letter, and one special character.";
   } else {
    $password = test_input($_POST["password"]);
   }

   // Validate retype password
   if(empty($_POST["retype_password"])){
       $retype_passwordErr = "Please confirm password.";     
   } else{
       $retype_password = trim($_POST["retype_password"]);
       if(empty($passwordErr) && ($password != $retype_password)){
           $retype_passwordErr = "Password did not match.";
       }
   }

    // Validate first name
    if(empty($_POST["first_name"])){
        $first_nameErr = "Please enter your first name.";
    } elseif(!preg_match("/^[A-Za-z]{1,20}$/", trim($_POST["first_name"]))){
        $first_nameErr = "First name must be up to 20 letters long.";
    } else {
        $first_name = test_input($_POST["first_name"]);
    }

    // Validate last name
    if(empty($_POST["last_name"])){
        $last_nameErr = "Please enter your last name.";
    } elseif(!preg_match("/^(?=.{1,20}$)[A-Za-z]+(?: [A-Za-z]+)?$/",trim($_POST["last_name"]))){
        $last_nameErr = "Last name must be up to 20 letters long.";
    } else {
        $last_name = test_input($_POST["last_name"]);
    }
    
    // Validate phone
    if(empty($_POST["phone"])){
        $phoneErr = "Please enter your phone number.";
    } elseif(!preg_match("/^\d{10}$/",trim($_POST["phone"]))){
        $phoneErr = "Phone number must contain exactly 10 digits.";
    } else {
        $phone = test_input($_POST["phone"]);
    }

    // Validate address
    if(empty($_POST["address"])){
        $addressErr = "Please enter your address.";
    } elseif(!preg_match("/^\d+\s+\w+/",$_POST["address"])){
        $addressErr = "Enter street number and street name separated by a space.";
    } else {
        $address = test_input($_POST["address"]);
    }

    // Validate address 2
    $address2 = test_input($_POST["address2"]);

    // Validate city
    if(empty($_POST["city"])){
        $cityErr = "Please enter your city.";
    } elseif(strlen(trim($_POST["city"])) > 50){
        $cityErr = "City name should not exceed 50 characters.";
    } elseif(!preg_match("/^[\p{L} '-]+$/u",trim($_POST["city"]))){
        $cityErr = "City should have only letters and special characters such as: - '";
    } else {
        $city = test_input($_POST["city"]);
    }

    // Validate province
    if(empty($_POST["province"])){
        $provinceErr = "Please enter your province.";
    } else{
        $province = trim($_POST["province"]);
    }

    // Validate postal code
    if(empty($_POST["postalCode"])){
        $postalcodeErr = "Please enter your postal code.";
    } elseif(!preg_match("/^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$/",trim($_POST["postalCode"]))){
        $postalcodeErr = "Postal code should follow the Canadian format (A1A 1A1)";
    } else {
        $postalcode = test_input($_POST["postalCode"]);
    }

    // Check input errors before inserting in database
    if(empty($emailErr) && empty($passwordErr) && empty($retype_passwordErr) && empty($first_nameErr) && empty($last_nameErr) && empty($phoneErr) && empty($addressErr) && empty($cityErr) && empty($provinceErr) && empty($postalcodeErr) && empty($userRoleErr)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, firstName, lastName, phone, address, address2, city, province, postalCode,role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssss", $param_email, $param_password, $param_first_name, $param_last_name, $param_phone, $param_address, $param_address2, $param_city, $param_province, $param_postalcode,$param_userRole);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_phone = $phone;
            $param_address = $address;
            $param_address2 = $address2;
            $param_city = $city;
            $param_province = $province;
            $param_postalcode = $postalcode;
            $param_userRole = $userRole;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: userEdit.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         // Close statement
         mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css"
  rel="stylesheet"
/>
<!-- Custom css -->
<link href="styles/adminAdd.css" rel="stylesheet"/>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Welcome <?php echo htmlspecialchars($_SESSION["first_name"]); ?>!</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="admin.php">Manage Menu</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="userEdit.php">Manage Users</a>
        </li>
    </ul>
    <a class="nav-link link-primary" href="logout.php">Log Out</a>
    </div>
</div>
</nav>

<!--Main container -->
<div class="container d-flex justify-content-center align-items-center min-vh-100">
<!--Form container -->
<div class = "row border rounded-5 p-3 bg-white shadow-lg box-area">
<!--Left Box-->
     <div class = "col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box" style="background:#a0c4ff;">
     <p class = "text-white fs-2">Add New Menu Item</p>
     <p class = "text-white text-wrap text-center" tyle = "width: 17 rem;">Please note all inputs are required!</p>
     <div class = "featured-image mb-3">
        <img src="assets/logo.png" class = "img-fluid" style = "width:400px"alt="duck">
     </div>
     </div>
<!--Right Box -->
<div class="col-md-6 right-box">
<div class = "row align-items-center">
        
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <div class="form-outline">
        <label class="form-label" style="font-size: 1.1em; font-weight:600;">Email</label>
        <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="email"value="<?php echo $email;?>">
        <span><?php echo $emailErr;?></span>
        </div>
        
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Password</label>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="password" name="password" value="<?php echo $password;?>">
            <span><?php echo $passwordErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Retype Password</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="password" name="retype_password" value="<?php echo $retype_password;?>">
            <span><?php echo $retype_passwordErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">First Name</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="first_name" value="<?php echo $first_name;?>">
            <span><?php echo $first_nameErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Last Name</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="last_name" value="<?php echo $last_name;?>">
            <span><?php echo $last_nameErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Phone Number</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="phone" value="<?php echo $phone;?>">
            <span><?php echo $phoneErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Address</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="address" value="<?php echo $address;?>">
            <span><?php echo $addressErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label"></label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="address2" value="<?php echo $address2;?>">
            <span><?php echo $address2Err;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">City</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="city" value="<?php echo $city;?>">
            <span><?php echo $cityErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Province</label><br>
            <select id="province" name="province" class="<?php echo (!empty($provinceErr)) ? 'is-invalid' : ''; ?>">
                    <option value="">Select Province</option>
                    <option value="AB" <?php echo ($province === 'AB') ? 'selected' : ''; ?>>Alberta</option>
                    <option value="BC" <?php echo ($province === 'BC') ? 'selected' : ''; ?>>British Columbia</option>
                    <option value="MB" <?php echo ($province === 'MB') ? 'selected' : ''; ?>>Manitoba</option>
                    <option value="NB" <?php echo ($province === 'NB') ? 'selected' : ''; ?>>New Brunswick</option>
                    <option value="NL" <?php echo ($province === 'NL') ? 'selected' : ''; ?>>Newfoundland and Labrador</option>
                    <option value="NS" <?php echo ($province === 'NS') ? 'selected' : ''; ?>>Nova Scotia</option>
                    <option value="NT" <?php echo ($province === 'NT') ? 'selected' : ''; ?>>Northwest Territories</option>
                    <option value="NU" <?php echo ($province === 'NU') ? 'selected' : ''; ?>>Nunavut</option>
                    <option value="ON" <?php echo ($province === 'ON') ? 'selected' : ''; ?>>Ontario</option>
                    <option value="PE" <?php echo ($province === 'PE') ? 'selected' : ''; ?>>Prince Edward Island</option>
                    <option value="QC" <?php echo ($province === 'QC') ? 'selected' : ''; ?>>Quebec</option>
                    <option value="SK" <?php echo ($province === 'SK') ? 'selected' : ''; ?>>Saskatchewan</option>
                    <option value="YT" <?php echo ($province === 'YT') ? 'selected' : ''; ?>>Yukon</option>
            </select>
            <span class="error"><?php echo $provinceErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Postal Code/ZIP</label><br>
            <input class = "form-control form-control-lg bg-light fs-8 shadow" type="text" name="postalcode" value="<?php echo $postalcode;?>">
            <span><?php echo $postalcodeErr;?></span>
        </div>
        <br>
        <div class = "input-group mb-3">
            <input class="btn btn-primary w-100 fs-6" type="submit" value="Submit">
        </div>
    </form>
</div>
</div>
</div>
</div>
</body>
</html>