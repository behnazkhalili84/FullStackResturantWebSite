<?php

 session_start();
 require_once "config.php";

// if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
//     // User is logged in
//      echo 'Welcome, ' . $_SESSION['first_name'] . '!';
// } else {
//     // Redirect to Login page 
//       header('Location: login.php');
//     exit();
//        }

// Modify the SQL query to select the last inserted reservation

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
   
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

        footer {
        position: relative; /* Position relative to contain pseudo-element */
        background: linear-gradient(to bottom, #f8f9fa, rgba(248, 249, 250, 0.3));
        padding: 25px;
        text-align: center;
        bottom: 0;
        width:100%;
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

        .msg-container{
            width: 40%;
            border: 1px solid #ddd;
            margin : 0 auto;
            border-radius: 5px;
            display : flex;
            justify-content : center;
            align-items : center;
            flex-direction : column;
            padding: 20px; 
            background-color: #f9f9f9; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            
        }
        .form-title{
            text-align:center;
            margin-bottom: 20px;


        }

        
.reservation-detail {
    margin-bottom: 5px; /* Add spacing between details */
    font-size: 16px; /* Increase font size for better readability */
}

.reservation-detail strong {
    font-weight: bold; /* Make detail labels bold */
}

.underline {
    border: none; /* Remove the default border */
    border-bottom: 1px solid #ddd; /* Add a bottom border */
    width: 100%; /* Set the width to 60% */
    margin: 20px auto; /* Center the line and add margin */
}

.buttons {
    margin-top: 20px;
    display: flex;
    flex-direction : row;
    margin-top: 20px;
    margin-left: 20px;
}

.cancel-btn, .back-btn {
    padding: 10px 20px;
    margin-right: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.cancel-btn {
    background-color: #ff0000; /* Red color for cancel button */
    color: #fff; /* White text color */
}

.back-btn {
    background-color: #333; /* Dark color for back button */
    color: #fff; /* White text color */
}
 
    </style>
</head>


  <!-- Header -->
  </head>

<!-- Header -->
<header>
   <img src="assets/logo.png" alt="Restaurant Logo" class="logo">
   <nav>
       <ul>
           <li><a href="index.php" style="color:#dd3b30">Home</a></li>
           <li><a href="Order.php">Order</a></li>
           <li><a href="Reservation.php">Reservation</a></li>
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
    <hr>

   
        <h2 class ="form-title"> You table reserved successfully</h2>
       
        <?php

$reservationID ="";
        
        if (isset($_SESSION['additionalEmail'])) {
            $additionalEmail = $_SESSION['additionalEmail'];
         
        }else {
            // Handle the case where $_SESSION['email'] is not set
           
            echo "additional Email is not set in the session.";
        }

        
      

        $sql = "SELECT * FROM reservations WHERE additionalEmail = '".$_SESSION['additionalEmail']."' ORDER BY reservationID DESC LIMIT 1 ";
         $result = $link->query($sql);

if ($result->num_rows > 0) {
   
    // Fetch the last inserted reservation
    $row = $result->fetch_assoc();
    
    echo "<div class='msg-container'>";
    // Display the reservation details
 echo"<h4 style='color:red;margin:5px 0;'>Reservation detail</h4>";
 echo" <hr class='underline'>";

    echo "<p class='reservation-detail'>Reservation ID: " . $row["reservationID"] . "</p><br>";
    echo "<p class='reservation-detail'>Email: " . $row["additionalEmail"] . "</p><br>";
    echo "<p class='reservation-detail'>Baby Seat is needed: " . $row["babySeat"] . "</p><br>";
    echo "<p class='reservation-detail'>Reservation Date: " . $row["date"] . "</p><br>";
    echo "<p class='reservation-detail'>Reservation Time: " . $row["time"] . "</p><br>";
    echo "<p class='reservation-detail'>Emergency Contact: " . $row["emergencyContact"] . "</p><br>";
    echo "<p class='reservation-detail'>Total Guests: " . $row["totalGuest"] . "</p><br>";
    echo "<p class='reservation-detail'>Occasion: " . $row["occaision"] . "</p><br>";
    echo "<p class='reservation-detail'>Special Requests: " . $row["specialRequests"] . "</p><br>";
    
    echo"<div class='buttons'>";
    echo '<button onclick="window.location.href=\'edit.php?reservationID=' . $row["reservationID"] . '\';" class="back-btn">Edit Reservation</button>';

    echo '<form action="cancel_reservation.php" method="post">';
    echo '<input type="hidden" name="reservationID" value="<?php echo $row["reservationID"]; ?>';
    echo '<button type="submit" onclick = "confirmCancel()" class="cancel-btn">Cancel Reservation</button>';

    echo '</form>';
    echo '<button onclick="window.location.href=\'Reservation.php\';" class="back-btn">Back to Reservation</button>';
    echo"</div>";

    $_SESSION['reservationID'] = $row["reservationID"];




    echo "</div>";
    }
   
    
 else {
    echo "No reservations found.";
}

?>
<script>
 function confirmCancel() {
            var result = confirm("Are you sure you want to cancel this reservation?");
            if (result) {
                console.log("resault");
                // If user clicks "Yes", submit the form
                document.getElementById("cancelForm").submit();
            } else {
                // If user clicks "No", redirect to confirmation.php
                window.location.href = "confirmation.php";
            }
        }
    </script>
  

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
