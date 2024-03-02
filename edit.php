<?php

 session_start();
 require_once "config.php";

 

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is logged in
     echo 'Welcome, ' . $_SESSION['first_name'] . '!';
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

    $reservationID =  $_SESSION['reservationID'] ;

    function test_input($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data); 
        return $data;
        }

//definevariablesandsettoemptyvalues
$emergencyContactErr=$dateErr=$timeErr=$totalGuestErr=$specialRequestsErr=$babySeatErr=$occaisionErr=$additionalEmailErr="";
$emergencyContact=$date=$time=$totalGuest=$preferedSeating=$specialRequests=$babySeat=$occaision=$additionalEmail="";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $reservationID = $_POST["reservationID"];

    // emergency Contact number verification
    if(empty($_POST["emergencyContact"])){
        $emergencyContactErr = "Emergancy contact field is required.";
      }else{
  $emergencyContact = test_input($_POST["emergencyContact"]);
  if(!preg_match("/^\d{3}\s\d{7}$/", $emergencyContact)){
    $emergencyContactErr = "Please enter a valid phone number in the format (e.g., 514 1234567).";
     }
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
else{
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



    // Check if there are any errors
   if (empty($emergencyContactErr) && empty($dateErr) && empty($timeErr) && empty($totalGuestErr) && empty($specialRequestsErr)
   && empty($babySeatErr) && empty($occaisionErr) && empty($additionalEmailErr) ) {


$sql= "UPDATE reservations  SET `date`=?,`time`=?,`additionalEmail`=?,
`emergencyContact`=?,`totalGuest`=?,`preferedSeating`=?,`occaision`=?,`babySeat`=?,
`specialRequests`=? WHERE `reservationID`= ?";

       if($stmt = mysqli_prepare($link, $sql)) {

        mysqli_stmt_bind_param($stmt,'ssssissssi', $pars_date, $pars_time,$pars_additionalEmail,$pars_emergencyContact,$pars_totalGuest,
        $pars_preferedSeating,$pars_occaision,$pars_babySeat,$pars_specialRequests,$pars_reservationID);

        $pars_reservationID = $reservationID;
        $pars_emergencyContact = $emergencyContact;
        $pars_date = $date;
        $pars_time = $time;
        $pars_totalGuest = $totalGuest;
        $pars_preferedSeating = $preferedSeating;
        $pars_specialRequests = $specialRequests;
        $pars_babySeat = $babySeat;
        $pars_occaision = $occaision;
        $pars_additionalEmail = $additionalEmail;
       
       

        if(mysqli_stmt_execute($stmt)) {
        header('location: Confirmation.php');
        echo'You successfully updated your reservation.change';
        exit();
     } else {
         echo 'Oops! Something went wrong. Please try again later.';
     }
    }
     mysqli_stmt_close($stmt);

    }

   mysqli_close($link); // Close the database connection


 }else{
   
    
 
    if(isset($_GET["reservationID"]) && !empty(trim($_GET["reservationID"]))){
     
        $reservationID=  trim($_GET["reservationID"]);
      

        $sql = "SELECT * FROM reservations WHERE reservationID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $pars_reservationID);
            
            $pars_reservationID = $reservationID;

        if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
    
         if(mysqli_num_rows($result) == 1){
                    
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

       $emergencyContact = $row["emergencyContact"];
        $date = $row["date"];
        $time= $row["time"];
        $totalGuest = $row["totalGuest"];;
        $preferedSeating = $row["preferedSeating"];
        $specialRequests = $row["specialRequests"];
        $babySeat = $row["babySeat"];
        $occaision = $row["occaision"];
        $additionalEmail = $row["additionalEmail"];
        $userID = $row["userID"];

        }else{
            echo "Unable to update1". mysqli_error($link);
            exit();
        }
        
    } else{
        echo "Unable to update2". mysqli_error($link);
    }
}

mysqli_stmt_close($stmt);
mysqli_close($link);
}  else{
echo "Unable to update3". mysqli_error($link);
exit();
}
        
 }
 
    ?>

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles/reservation.css"> -->
    <!-- <script src="src/script.js" defer></script> -->
    <title>FoodiFoodi's Reservation</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">


        <style>
                body {
            font-family: "NeueMontreal-Regular", sans-serif;
            background-color: rgba(255, 255, 240, 0.3);
      
        }
        /* >>>>>>   HEADER    <<<<<<<*/
 
        /* Header Styles */
        header {
            background-color: rgb(var(--color-base-background-1));
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 0px;
            margin-bottom: 0px;
        }
 
        header .logo {
            max-width: 100px;
            margin-left: 35px;
            @media screen and (max-width: 800px) {/* Logo being responsive for smaller screens */
                max-width: 80px;
                margin-left: 20px;
            }
        }
 
        /*Navigation List*/
 
        nav ul {
            list-style: none;
            display: flex;
            gap: 50px;
            margin: 0;
        }
 
        /* texts for navigations */
        nav a {
            display: flex;
            text-decoration: none;
            color: rgb(var(--color-base-text));
            font-weight: bold;
            font-size: 18px;
            transition: font-size 0.3s ease;
            /*responsive for smaller screens */
            @media screen and (max-width: 800px) {
                font-size: 13px;
            }
        }
 
        nav a:hover {
            font-size: 20px;
        }
 
        /* cart and login*/
        .user-actions .action-list {
            list-style: none;
            display: flex;
            flex-direction: column;
        }
 
        /*cart*/
        .user-actions a img {
            max-height: 30px;
            margin-left: 0;
            padding-left: 30px;
        }
 
        /*login*/
        .btn-login{
            padding: 4px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            background-color: rgba(221, 59, 48, 0.8);
            color: rgba(255, 255, 240, 0.8);
            text-decoration: none;
 
            /*responsive for smaller screens */
            @media screen and (max-width: 800px) {
                font-size: 12px;
                padding: 4px 10px;
            }
            
            /*transition for hover*/
            transition: background-color 0.3s ease, color 0.3s ease;
        }
 
        .btn-login:hover {
            background-color: #dd3b30;;
            color: black;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 5px 45px 45px 25px;
            background-color: rgba(255, 255, 240, 0.6);
            border-radius: 15px;
            box-shadow: 0 0 5px #B85042;
            font-family:Arial, Helvetica, sans-serif;
            @media screen and (max-width: 700px) {
            width: 80%;
            padding-left: 0;
            padding-right: 35px;
            }
        }
 
        .container h2 {
            text-align: center;
            padding: 15px;
            @media screen and (max-width: 800px) {
            font-size: 95%;
            text-align: center;
            padding: 25px;
            }
        }
       
        .formContainer {
            display: grid;
            grid-template-columns: 138px auto;
            gap: 22px;
            margin-left: 80px;
            
        }
 
        .formContainer label {
            font-weight: bold;
            white-space: nowrap;
            text-align: right;
            align-self: center;
            justify-self: end;
           
            @media screen and (max-width: 800px) {
            font-size: 80%;
            }
            
        }
 
        .formContainer input {
            width: 350px;
            height: 25px;
            border-radius:5px;
            justify-self: start;
            border: 1px solid rgba(0, 0, 0, 0.2);
            outline: none;
            @media screen and (max-width: 800px) {
            width: 160px;
            height: 20px;
            }
        }
 
        .formContainer button {
            grid-column: 2;
            width: 348px;
            height: 25px;
            border-radius:5px;
            border: none;
            justify-self: start;
            background-color: rgba(184, 80, 66, 0.85);
            @media screen and (max-width: 800px) {
            width: 166px;
            height: 20px;
            }
        }
 
        
        .formContainer button:hover {
            color: black;
            background-color: rgba(184, 80, 66, 1);
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
            padding: 5px;
            font-size: 14px;
            outline: none; /* Remove default outline */
            @media screen and (max-width: 800px) {
            width: 166px;
            height: 25px;
            font-size: 12px;
            padding:0 0 0 3px;
            }
        }
 
        .errors {
            position: absolute;
            top: 105%;
            left: 5px;
            color: red;
            font-size: 0.6em;
            @media screen and (max-width: 800px) {
            font-size: 50%;
            }
        }
 
        ::placeholder {
            font-size: 14px;
            padding-left: 3px;
        }
 
        footer {
        position: relative; /* Position relative to contain pseudo-element */
        background: linear-gradient(to bottom, #f8f9fa, rgba(248, 249, 250, 0.3));
        padding: 25px;
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
        <img src="assets/logo.svg" alt="Restaurant Logo" class="logo">
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="assets/Menu.pdf" target="_blank">Menu</a></li>
                <li><a href="Reservation.html" style="color:#dd3b30">Reservation</a></li>
                <li><a href="Aboutus.html">About Us</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <ul class="action-list">
                <li><a href="#" class="cart-icon"><img src="assets/cart.png" alt="Cart"></a></li>
                <li><?php
                        if(isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
                            echo '<li><p>Hello ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</p></li>';
                        } else {
                            echo '<li><button class="btn-login" style="background-color: #dd3b30;"><a href="login.html" style="text-decoration: none; color: white;">Login</a></button></li>';
                        }
                        ?>
                </li>
            </ul>
        </div>
    </header>

    <hr>
 <br>


    <body>
        <!-- Hero Section -->
        <div class="container">
        <h2 style="font-family: Lucida Calligraphy; color: #dd3b30">Reservation Form</h2>

        <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="formContainer">

        <input type="hidden" id="reservationID" name="reservationID" value="<?php echo $reservationID ?>"  >

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

                    <label for="occaision">Occaision:<span style="color: #dd3b30">*</span></label>
                    <div class="input-wrapper">
                         <select id="occaision" name="occaision"> 
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
                        <textarea id="special_requests" name="specialRequests" placeholder="Enter your special request here"><?php echo $specialRequests; ?></textarea>
                       </div>

                    
                        <button type="submit" id="bookNowButton"  style="margin-right: 10px;" class="book-button">Update</button>
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
 
</body>

</html>