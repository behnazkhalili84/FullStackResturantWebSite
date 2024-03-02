<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = $retype_password = $first_name = $last_name = $phone = $address = $address2 = $city = $province = $postalcode = "";
$emailErr = $passwordErr = $retype_passwordErr = $first_nameErr = $last_nameErr = $phoneErr = $addressErr = $address2Err = $cityErr = $provinceErr = $postalcodeErr = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $emailErr = "Please enter your email.";
    } elseif(!preg_match("/^[^@\s]+@[^@\s]+\.[^@\s]+$/", trim($_POST["email"]))){
        $emailErr = "Invalid email format.";
    } else {
            // Prepare a select statement
            $sql = "SELECT email From users Where email= ?";
            
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = trim($_POST["email"]);
            
                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $emailErr = "This email is already registered.";
                    } else {
                            $email = trim($_POST["email"]);
                        }
                } else {
                        echo "Oops! Something went wrong. Please try again later. Error: " . mysqli_error($link);
                    }
            
                    // Close statement
                    mysqli_stmt_close($stmt);
                    } else {
                        echo "Error preparing statement: " . mysqli_error($link);
                    }
        }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $passwordErr = "Please enter a password.";
    } elseif(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?!.*\s).{10,}$/', trim($_POST["password"]))){
        $passwordErr = "Password should contain one digit, one uppercase letter, and one special character.";
    } else {
        $password = test_input($_POST["password"]);
    }
    
    // Validate retype password
    if(empty(trim($_POST["retype_password"]))){
        $retype_passwordErr = "Please confirm password.";     
    } else{
        $retype_password = trim($_POST["retype_password"]);
        if(empty($passwordErr) && ($password != $retype_password)){
            $retype_passwordErr = "Password did not match.";
        }
    }

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_nameErr = "Please enter your first name.";
    } elseif(!preg_match("/^[A-Za-z]{1,20}$/", trim($_POST["first_name"]))){
        $first_nameErr = "First name must be up to 20 letters long.";
    } else {
        $first_name = test_input($_POST["first_name"]);
    }
    

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_nameErr = "Please enter your last name.";
    } elseif(!preg_match("/^(?=.{1,20}$)[A-Za-z]+(?: [A-Za-z]+)?$/", trim($_POST["last_name"]))){
        $last_nameErr = "Last name must be up to 20 letters long.";
    } else {
        $last_name = test_input($_POST["last_name"]);
    }
    

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phoneErr = "Please enter your phone number.";
    } elseif(!preg_match("/^\d{10}$/", trim($_POST["phone"]))){
        $phoneErr = "Phone number must contain exactly 10 digits.";
    } else {
        $phone = test_input($_POST["phone"]);
    }
    
    // Validate address
    if(empty(trim($_POST["address"]))){
        $addressErr = "Please enter your address.";
    } elseif(!preg_match("/^\d+\s+\w+/", trim($_POST["address"]))){
        $addressErr = "Enter street number and street name separated by a space.";
    } else {
        $address = test_input($_POST["address"]);
    }

    // Validate address 2
    $address2 = test_input($_POST["address2"]);

    // Validate city
    if(empty(trim($_POST["city"]))){
        $cityErr = "Please enter your city.";
    } elseif(strlen(trim($_POST["city"])) > 50){
        $cityErr = "City name should not exceed 50 characters.";
    } elseif(!preg_match("/^[\p{L} '-]+$/u", trim($_POST["city"]))){
        $cityErr = "City should have only letters and special characters such as: - '";
    } else {
        $city = test_input($_POST["city"]);
    }

    // Validate province
    if(empty(trim($_POST["province"]))){
        $provinceErr = "Please enter your province.";
    } else{
        $province = trim($_POST["province"]);
    }

    // Validate postal code
    if(empty(trim($_POST["postalcode"]))){
        $postalcodeErr = "Please enter your postal code.";
    } elseif(!preg_match("/^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$/", trim($_POST["postalcode"]))){
        $postalcodeErr = "Postal code should follow the Canadian format (A1A 1A1)";
    } else {
        $postalcode = test_input($_POST["postalcode"]);
    }
    
    // Check input errors before inserting in database
    if(empty($emailErr) && empty($passwordErr) && empty($retype_passwordErr) && empty($first_nameErr) && empty($last_nameErr) && empty($phoneErr) && empty($addressErr) && empty($cityErr) && empty($provinceErr) && empty($postalcodeErr)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, firstName, lastName, phone, address, address2, city, province, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $param_email, $param_password, $param_first_name, $param_last_name, $param_phone, $param_address, $param_address2, $param_city, $param_province, $param_postalcode);
            
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
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page with success message
                header("location: login.php?registration=success");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>FoodiFoodi's Sign Up</title>
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
            <a href="index.html"> 
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>
  
<body>
    <div class="container">
        <h2>Create account</h2>

        <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="formContainer">

            <label for="email">Email<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
                <span class="errors" id="email-error"><?php echo $emailErr; ?></span>
            </div>

            <label for="password">Password<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" placeholder="At least 10 characters" value="<?php echo isset($password) ? $password : ''; ?>">
                <span class="errors" id="password-error"><?php echo $passwordErr; ?></span>
            </div>

            <label for="retype-password">Retype Password<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="password" id="retype-password" name="retype_password" value="<?php echo isset($retype_password) ? $retype_password : ''; ?>">
                <span class="errors" id="retype-password-error"><?php echo $retype_passwordErr; ?></span>
            </div>

            <label for="first-name">First Name<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="text" id="first-name" name="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>">
                <span class="errors" id="first-name-error"><?php echo $first_nameErr; ?></span>
            </div>

            <label for="last-name">Last Name<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="text" id="last-name" name="last_name" value="<?php echo isset($last_name) ? $last_name : ''; ?>">
                <span class="errors" id="last-name-error"><?php echo $last_nameErr; ?></span>
            </div>

            <label for="phone">Phone Number<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>">
                <span class="errors" id="phone-error"><?php echo $phoneErr; ?></span>
            </div>

            <label for="address">Address<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="text" id="address" name="address" value="<?php echo isset($address) ? $address : ''; ?>">
                <span class="errors" id="address-error"><?php echo $addressErr; ?></span>
            </div>

            <label for="address2"></label>
            <div class="input-wrapper">
                <input type="text" id="address2" name="address2" placeholder="Office/Apt Number" value="<?php echo isset($address2) ? $address2 : ''; ?>">
                <span class="errors" id="address2-error"><?php echo $address2Err; ?></span>
            </div>

            <label for="city">City<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="text" id="city" name="city" value="<?php echo isset($city) ? $city : ''; ?>">
                <span class="errors" id="city-error"><?php echo $cityErr; ?></span>
            </div>


            <label for="province">Province<span style="color:red"> * </span></label>
            <div class="input-wrapper">
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
                <span class="errors"><?php echo $provinceErr; ?></span>
            </div>

            <label for="postalcode">Postal Code/ZIP<span style="color:red"> * </span></label>
            <div class="input-wrapper">
                <input type="text" id="postalcode" name="postalcode" value="<?php echo isset($postalcode) ? $postalcode : ''; ?>">
                <span class="errors" id="postalcode-error"><?php echo $postalcodeErr; ?></span>
            </div>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
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

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registration-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const retypePasswordInput = document.getElementById('retype-password');
                const firstNameInput = document.getElementById('first-name');
                const lastNameInput = document.getElementById('last-name');
                const phoneInput = document.getElementById('phone');
                const addressInput = document.getElementById('address');
                const address2Input = document.getElementById('address2');
                const cityInput = document.getElementById('city');
                const provinceInput = document.getElementById('province');
                const postalCodeInput = document.getElementById('postalcode');

                let isValid = true;

                if (!validateEmail(emailInput.value)) {
                    document.getElementById('email-error').innerText = 'JS: Email should have at least 8 characters.';
                    isValid = false;
                }

                if (!validatePassword(passwordInput.value)) {
                    document.getElementById('password-error').innerText = 'JS: Password should have at least 10 characters without any spaces.';
                    isValid = false;
                }

                if (!validateRetypePassword(passwordInput.value, retypePasswordInput.value)) {
                    document.getElementById('retype-password-error').innerText = 'JS: Passwords do not match.';
                    isValid = false;
                }

                if (!validateFirstName(firstNameInput.value)) {
                    document.getElementById('first-name-error').innerText = 'JS: First name is required, and can not contain these characters: @ # $ % &.';
                    isValid = false;
                }

                if (!validateLastName(lastNameInput.value)) {
                    document.getElementById('last-name-error').innerText = 'JS: Last name is required, and can not contain these characters: @ # $ % &.';
                    isValid = false;
                }

                if (!validatePhone(phoneInput.value)) {
                    document.getElementById('phone-error').innerText = 'JS: Phone number should have 10 digits.';
                    isValid = false;
                }

                if (!validateAddress(addressInput.value)) {
                    document.getElementById('address-error').innerText = 'JS: Address is required.';
                    isValid = false;
                }

                if (!validateCity(cityInput.value)) {
                    document.getElementById('city-error').innerText = 'JS: City is required.';
                    isValid = false;
                }

                if (!validateProvince(provinceInput.value)) {
                    document.getElementById('province-error').innerText = 'JS: Please select a province.';
                    isValid = false;
                }

                if (!validatePostalCode(postalCodeInput.value)) {
                    document.getElementById('postalcode-error').innerText = 'JS: Postal code is required.';
                    isValid = false;
                }

                if (isValid) {
                    form.submit();
                }
            });

            document.getElementById('email').addEventListener('input', function() {
                document.getElementById('email-error').innerText = '';
            });

            document.getElementById('password').addEventListener('input', function() {
                document.getElementById('password-error').innerText = '';
            });

            document.getElementById('retype-password').addEventListener('input', function() {
                document.getElementById('retype-password-error').innerText = '';
            });

            document.getElementById('first-name').addEventListener('input', function() {
                document.getElementById('first-name-error').innerText = '';
            });

            document.getElementById('last-name').addEventListener('input', function() {
                document.getElementById('last-name-error').innerText = '';
            });

            document.getElementById('phone').addEventListener('input', function() {
                document.getElementById('phone-error').innerText = '';
            });

            document.getElementById('address').addEventListener('input', function() {
                document.getElementById('address-error').innerText = '';
            });

            document.getElementById('address2').addEventListener('input', function() {
                document.getElementById('address2-error').innerText = '';
            });

            document.getElementById('city').addEventListener('input', function() {
                document.getElementById('city-error').innerText = '';
            });

            document.getElementById('province').addEventListener('change', function() {
                document.getElementById('province-error').innerText = '';
            });

            document.getElementById('postalcode').addEventListener('input', function() {
                document.getElementById('postalcode-error').innerText = '';
            });

        });

        function validateEmail(email) {
            return email.trim().length > 0 && /.{8,}/.test(email);
        }

        function validatePassword(password) {
            return password.trim().length > 0 && /^(?!\s).{10,}$/.test(password);
        }

        function validateRetypePassword(password, retypePassword) {
            return password === retypePassword;
        }

        function validateFirstName(firstName) {
            return firstName.trim().length > 0 && /^[^@#$%&]+$/.test(firstName);
        }

        function validateLastName(lastName) {
            return lastName.trim().length > 0 && /^[^@#$%&]+$/.test(lastName);
        }

        function validatePhone(phone) {
            return /\d{10}/.test(phone);
        }

        function validateAddress(address) {
            return address.trim().length > 0;
        }

        function validateCity(city) {
            return city.trim().length > 0;
        }

        function validateProvince(province) {
            return province.trim().length > 0;
        }

        function validatePostalCode(postalCode) {
            return postalCode.trim().length > 0;
        }

    </script>
</body>
</html>


