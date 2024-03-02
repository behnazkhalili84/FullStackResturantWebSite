<?php

 session_start();
 require_once "config.php";

 // Fetch user details for the current user
$users = [];
$role = $_SESSION["role"];

 

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is logged in
    
} else {
    // Redirect to Login page 
      header('Location: login.php');
    exit();
       }


if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    // Proceed with your code that uses $email
} else {
    // Handle the case where $_SESSION['email'] is not set
    echo "Email is not set in the session.";
}


if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

  

    $firstname = $_SESSION["first_name"];
    $lastname = $_SESSION["last_name"];
    $email = $_SESSION["email"];
    $userID = $_SESSION["userID"];



 function test_input($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data); 
  return $data;
  }

//definevariablesandsettoemptyvalues
$emergencyContactErr=$dateErr=$timeErr=$totalGuestErr=$specialRequestsErr=$babySeatErr=$occaisionErr=$additionalEmailErr="";
$reservationID=$emergencyContact=$date=$time=$totalGuest=$preferedSeating=$specialRequests=$babySeat=$occaision=$additionalEmail="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    // emergency Contact number verification
  $emergencyContact = test_input($_POST["emergencyContact"]);
  if(!preg_match("/^\d{3}\s\d{7}$/", $emergencyContact)){
    $emergencyContactErr = "Please enter a valid phone number in the format (e.g., 514 1234567).";
     }

// Date verification
if(empty($_POST["date"])){
  $dateErr = "date  is required";
}
else {
    $date = $_POST["date"];
    $time = $_POST["time"];
    
    // Combine date and time into a single datetime object
    $datetime_str = $date . ' ' . $time;
    $datetime = new DateTime($datetime_str);
    
    // Get the current datetime
    $current_datetime = new DateTime();
    
    if ($datetime <= $current_datetime) {
        // Reservation datetime is in the past or present
        $dateErr = "Please select a future date and time for your reservation.";
    }
    
} 

// time verification
if(empty($_POST["time"])){
  $timeErr = "time  is required";
}
{
    $time = date("H:i", strtotime($_POST["time"])); // Convert time to 24-hour format
    $start_time = "12:00";
    $end_time = "23:00";

    if ($time < $start_time || $time > $end_time) {
        $timeErr = "Please select a reservation time between 12:00 PM and 11:00 PM.";
    }
}

//Total Guest verification 
if(empty($_POST["totalGuest"])){
    $totalGuestErr = "Total guest field is required";
  }
  else { 
    $totalGuest = $_POST["totalGuest"];
    if($totalGuest <= 0 || $totalGuest> 15){
        $totalGuestErr = "Sorry, we can only accommodate reservations for up to 15 guests.";

    }
  }

  //Addditional email verification
  if(empty($_POST["additionalEmail"])){

    $additionalEmailErr = "Email field is required";
  }else{

$additionalEmail = test_input($_POST["additionalEmail"]);

if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $additionalEmail)){
    $additionalEmailErr = "Invalid email address.";
}
    }


// baby seat verificatin
if(empty($_POST["babySeat"])){
    $babySeatErr = "Please confirm if you need a baybe sit or not";
   }
  else{
    $babySeat = $_POST["babySeat"];
  }

  //occaision verification
  if(empty($_POST["occaision"])){
    $occaisionErr = "Please select the occaision";
   }
  else{
    $occaision = $_POST["occaision"];
  }

  //special_requests verification needed for  special_requests field
  $specialRequests = $_POST["specialRequests"] ?? ''; 

  // No verification need for prefered Seating field
  $preferedSeating = $_POST["preferedSeating"] ?? '';



  $_SESSION['additionalEmail'] = $additionalEmail; 
    
    // Check if there are any errors
   if (empty($emergencyContactErr) && empty($dateErr) && empty($timeErr) && empty($totalGuestErr) && empty($specialRequestsErr)
   && empty($babySeatErr) && empty($occaisionErr) && empty($additionalEmailErr) ) {

             // Prepare and execute the statement
          $sql = "INSERT INTO reservations (additionalEmail,babySeat,date,emergencyContact,occaision,preferedSeating,
              specialrequests,time,totalGuest,userID) VALUES (?,?,?,?,?,?,?,?,?,?)";

       if($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt, "ssssssssii",$pars_additionalEmail, $pars_babySeat, $pars_date,$pars_emergencyContact,$pars_occaision,$pars_preferedSeating,
        $pars_specialRequests,$pars_time,$pars_totalGuest ,$pars_userID);
       
    
        $pars_emergencyContact = $emergencyContact;
        $pars_date = $date;
        $pars_time = $time;
        $pars_totalGuest = $totalGuest;
        $pars_preferedSeating = $preferedSeating;
        $pars_specialRequests = $specialRequests;
        $pars_babySeat = $babySeat;
        $pars_occaision = $occaision;
        $pars_additionalEmail = $additionalEmail;
        $pars_userID = $userID ;



       if(mysqli_stmt_execute($stmt)) {
        header("location: Confirmation.php");
        exit();
     } else {
         echo "Oops! Something went wrong. Please try again later.";
     }
     mysqli_stmt_close($stmt);


    }


mysqli_close($link); // Close the database connection


 }

 }
    ?>

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/reservationstyles.css">

    <!-- <link rel="stylesheet" href="styles/reservation.css"> -->
    <!-- <script src="src/script.js" defer></script> -->
    <title>FoodiFoodi's Reservation</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">


       
</head>

    <!-- Header -->
    <header>
        <img src="assets/logo.png" alt="Restaurant Logo" class="logo">
        <nav>
            <ul>
                <li><a href="index.php" >Home</a></li>
                <li><a href="Order.php">Order</a></li>
                <li><a href="Reservation.php" style="color:#dd3b30">Reservation</a></li>
                <li><a href="Aboutus.php">About Us</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <ul class="action-list">
                <li class="nav-item cart-item">
                    <a href="Cart.php" class="cart-icon" style="text-decoration:none;">
                        <img src="assets/cart.png" alt="Cart">
                        <span id="cartItemCount" style="color: black; font-size: 20px; text-decoration:none; position: relative; top: -25px; right: 35px;">0</span>
                    </a>                
                </li>                
                <?php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    // If user is logged in, display their name with an arrow
    echo '<div class="dropdown">
            <span id="dropdown-toggle" class="dropdown-toggle" style="color:#dd3b30; font-weight: bold;">Hi, ' . $_SESSION["first_name"] . ' ▼</span>
            <div id="dropdown-menu" class="dropdown-menu">
                <a href="' . ($_SESSION["role"] === "admin" ? "admin.php" : "user.php") . '"> Account</a>
                <a href="orderHistory.php">Order History</a>
                <a href="logout.php">Logout</a>
            </div>
          </div>';
} else {
    // If user is not logged in, display the login button
    echo '<li style="margin-top:18px;"><a href="login.php" class="btn-login">Login</a></li>';
}
?>

            </ul>
        </div>
    </header>
<p id="myElementId"></p>
    <hr>
 <br>

    <body>
        <!-- Hero Section -->
        <div class="container">
        <h2 style="font-family: Lucida Calligraphy; color: #dd3b30">Reservation Form</h2>

        <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="formContainer">

                      
                        <label for="first_name">First Name <span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <input type="text" id="firstname" name="firstname" value="<?php echo $firstname ?>"  readonly >
                        </div>

                        <label for="Last_name">Last Name <span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <input type="text" id="lastname" name="lastname" value="<?php echo $lastname ?>" readonly  >
                         </div>

                        <label for="additionalEmail"> Email <span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <input type="email" id="additionalEmail" name="additionalEmail" value ="<?php echo $email; ?>" >
                        <span class="errors" id="email-error"><?php echo $additionalEmailErr; ?></span>
                        </div>
                
                        <label for="emergencyContact">Emergency Contact Number </label>
                        <div class="input-wrapper">
                        <input type="tel" id="emergencyContact" name="emergencyContact" placeholder="### #######"  value ="<?php echo $emergencyContact; ?>" >
                        <span class="errors" id="emergencyContact-error"><?php echo $emergencyContactErr; ?></span>
                        </div>

                        <label for="date">Date <span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <input type="date" id="date" name="date" placeholder="Booking date here" value ="<?php echo $date; ?>" >
                        <span class="errors" id="date-error"><?php echo $dateErr; ?></span>
                         </div>
                  
                        <label for="time">Time<span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <input type="time" id="time" name="time" placeholder="Starting time here" min="12:00 PM" max="11:00 PM" " value ="<?php echo $time; ?>"  >
                        <span class="errors" id="time-error"><?php echo $timeErr; ?></span>
                        </div>

                        <label for="totalGuest">Total Guest*</label>
                        <div class="input-wrapper">
                        <input type="number" id="totalGuest" name="totalGuest" placeholder="4" min="1" max="15" value ="<?php echo $totalGuest; ?>" >
                        <span class="errors" id="totalGuest-error"><?php echo $totalGuestErr; ?></span>
                        </div>


                        <label for="preferedSeating">Preferred Seating:<span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                       <input  type="radio" id="inside" name="preferedSeating" value="inside" <?php echo ($preferedSeating === 'inside') ? 'checked' : ''; ?>>
                        <label for="inside">Inside</label>    
                        <input  type="radio" id="outside" name="preferedSeating" value="outside"  <?php echo ($preferedSeating === 'outside') ? 'checked' : ''; ?>>
                         <label for="outside">Outside</label>
                         </div>



       <label for="babySeat">Do you need a baby seat?<span style="color: #dd3b30">*</span></label>
    <div class="input-wrapper">
        <input  type="radio" id="yes" name="babySeat" value="Yes" <?php echo ($babySeat === 'Yes') ? 'checked' : ''; ?>>
        <label  for="yes">Yes</label>
        <input  type="radio" id="no" name="babySeat" value="No" <?php echo ($babySeat === 'No') ? 'checked' : ''; ?>>
        <label  for="no">No</label>
        <span class="errors" id="babySeat-error"><?php echo $babySeatErr; ?></span>

    </div>

                    <label for="occaision" >Occaision:<span style="color: #dd3b30;">*</span></label>
                    <div class="input-wrapper">
                         <select id="occaision" name="occaision" style= "width: 256px;"> 
                              <option value="Birthday" <?php echo ($occaision === 'Birthday') ? 'selected' : ''; ?>>Birthday</option>
                              <option value="Anniversary" <?php echo ($occaision === 'Anniversary') ? 'selected' : ''; ?>>Anniversary</option>
                              <option value="Date Night" <?php echo ($occaision === 'Date Night') ? 'selected' : ''; ?>>Date Night</option>
                              <option value="Business Meeting" <?php echo ($occaision === 'Business Meeting') ? 'selected' : ''; ?>>Business Meeting</option>
                              <option value="Family Gathering" <?php echo ($occaision === 'Family Gathering') ? 'selected' : ''; ?>>Family Gathering</option>
                              <option value="Celebration" <?php echo ($occaision === 'Celebration') ? 'selected' : ''; ?>>Celebration</option>
                              <option value="Casual Dining" <?php echo ($occaision === 'Casual Dining') ? 'selected' : ''; ?>>Casual Dining</option>
                              <option value="Formal Dining" <?php echo ($occaision === 'Formal Dining') ? 'selected' : ''; ?>>Formal Dining</option>
                              <option value="Special Event" <?php echo ($occaision === 'Special Event') ? 'selected' : ''; ?>>Special Event</option>
                             <option value="Other" <?php echo ($occaision === 'Other') ? 'selected' : ''; ?>>Other</option>
                         </select>
                         <span class="errors" id="occaision-error"><?php echo $occaisionErr; ?></span>
                    </div>
            
  
                        <label for="special_requests">Special Information<span style="color: #dd3b30">*</span></label>
                        <div class="input-wrapper">
                        <textarea id="special_requests" name="specialRequests" style= "width: 250px; height: 50px; border-radius: 5px; "  placeholder="Enter your special request here"><?php echo $specialRequests; ?></textarea>
                       </div>

                    
                        <button type="submit" id="bookNowButton"  style="margin-right: 10px;" class="book-button">Book a Table</button>
                        <button type="reset" class="reset-button">Reset</button>
                    

                     </form>
        </div>
        <br><br>

    <!-- Separator Line -->
    <div class="separator-line"></div>
    
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
    <script>


document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("dropdown-toggle").addEventListener("click", function() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            if (dropdownMenu.style.display === "none" || dropdownMenu.style.display === "") {
                dropdownMenu.style.display = "block";
            } else {
                dropdownMenu.style.display = "none";
            }
        });
    });

    </script>
 
</body>

</html>