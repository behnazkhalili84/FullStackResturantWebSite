<?php
    session_start();
    require_once "config.php";
   
   
   if (isset($_SESSION['userID'])) {
        $userID = $_SESSION["userID"];
       // Proceed with your code that uses $email
   } else {
       // Handle the case where $_SESSION['email'] is not set
       header('Location: login.php');
   }


   $sql = "SELECT userID,email,firstName,lastName,phone,address FROM users WHERE userID  = ?";

   if ($stmt = $link->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param("i", $param_id);
    $param_id = $_SESSION["userID"];
    // Execute the statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($id, $username, $firstName, $lastName, $phoneNumber, $address);

            // Fetch the result
            if ($stmt->fetch()) {
                $disabled = 'disabled';

                // Now populate the form fields with user information
                $firstNameValue = htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8');
                $lastNameValue = htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8');
                $phoneNumberValue = htmlspecialchars($phoneNumber, ENT_QUOTES, 'UTF-8');
                $addressValue = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
            }
        } else {
            echo "No user found with the given ID.";
        }
    } else {
        echo "Error executing the statement.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Error preparing the statement.";
}

// Close connection
$link->close();

    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="styles/cart.css">-->
    <title>FoodiFoodi's Cart</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>

/* Main container */
.box-area{
    width:4000px;
}

.dropdown {
    position: relative;
    top: 20px;
}
 
.dropdown-menu {
    display: none;
    position: absolute;
    background-color: rgba(255, 255, 240, 1);
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 10px;
    box-shadow: 0 0 5px #B85042;
    z-index: 1000;
    top: 25px;
    left: 5px;
}
 
.dropdown:hover {
    cursor: pointer;
}
 
.dropdown-menu a {
    display: block;
    padding: 8px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
}
 
.dropdown-menu a:hover {
    cursor: pointer;
    color: rgba(221, 59, 48, 1);
}
 
body {
    background: rgba(255, 255, 240, 0.3);
    font-family: "NeueMontreal-Regular", sans-serif;
    color: rgb(var(--color-base-text));
    margin: 0;
    padding: 0;
}

header {
    background-color: rgba(255, 255, 240, 0.6);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0px;
}
 
header .logo {
    max-width: 120px;
    margin-left: 25px;
    bottom:0;
    margin-bottom: 0;
    padding-bottom: 0;
    @media screen and (max-width: 800px) {
        max-width: 80px;
        margin-left: 20px;
    }
}
 
.logo-link {
    display: inline-block;
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
    font-size: 20px; /* Adjusted font size */
    transition: font-size 0.3s ease;
    /*responsive for smaller screens */
    @media screen and (max-width: 800px) {
        font-size: 13px;
    }
    padding-top: 25px;
}
 
nav a:hover {
    color: #dd3b30; /* Change text color on hover */
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5); /* Add text shadow on hover */
}
 
/* cart and login */
.user-actions .action-list {
    list-style: none;
    display: flex;
    padding-right: 35px;
}
 
/*cart*/
.user-actions a img {
    max-height: 50px;
}
 
/*login*/
.btn-login {
    border: none;
    border-radius: 9px;
    cursor: pointer;
    font-size: 16px; /* Adjusted font size */
    color: white;
    text-decoration: none;
    background-color: rgba(221, 59, 48, 1);
    /*responsive for smaller screens */
    @media screen and (max-width: 800px) {
        font-size: 12px;
        padding: 4px 10px;
    }
    /*transition for hover*/
    transition: background-color 0.3s ease, color 0.3s ease;
}
 
.btn-login:hover {
    background-color: rgba(221, 59, 48, 0.5);
    cursor: pointer;
}

footer {
    position: static; /* Position relative to contain pseudo-element */
    background: linear-gradient(to bottom, #f8f9fa, rgba(248, 249, 250, 0.3));
    padding: 22px;
    text-align: center;
}
 
footer::before,
footer::after {
    content: "";
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


<body>
    <!-- Header -->
    <header>
        <img src="assets/logo.png" alt="Restaurant Logo" class="logo">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
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
            <span id="dropdown-toggle" class="dropdown-toggle" style="color:#dd3b30;">Hi, ' . $_SESSION["first_name"] . ' ▼</span>
            <div id="dropdown-menu" class="dropdown-menu">
                <a href="' . ($_SESSION["role"] === "admin" ? "admin.php" : "user.php") . '"> Account</a>
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
<div class="container d-flex justify-content-center align-items-center">
<h1 style="font-size: 50px;">Shopping Cart</h1>
</div>
<!-- Main Section -->
<div class="container d-flex justify-content-center align-items-center"> 
<!--Content container -->
<div class = "row border rounded-5 p-3 bg-white shadow-lg box-area">
<!--Left Box-->
<div class = "col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box">
<h2 style="text-align: center;">Your Order Details</h2>
    <div class="cart-items-container">
        <!-- Cart items will be dynamically added here using JavaScript -->
    </div>
    
    <div class="cart-total">
        <p style="font-size: 1.1em; font-weight:600;">Total Price: <span id="totalPrice">$0.00</span></p>
    </div>
    
    <button ype="button" class="checkout-button btn btn-danger" onclick="goToOrderPage()">Back to Order </button>
</div>

<!--Right Box -->
<div class="col-md-6 right-box">
<div class = "row align-items-center">
        
        <h2 style="text-align: center;">Payment Information</h2>
        <div class="select-payment">
            
            <div class="radio_button">
                <input type="radio" class="payment" name="payment" value="Visa" ><img src="assets/visa (1).png" alt="Visa" width="5%;" height="5%;">
                <input type="radio" class="payment" name="payment" value="MasterCard"><img src="assets/card.png" alt="MasterCard" width="5%;" height="5%;" >
                <input type="radio" class="payment" name="payment" value="PayPal"><img src="assets/paypal (1).png" alt="PayPal" width="5%;" height="5%;">
            </div>
           
        </div>
        
        <div class="payment-info form-outline row align-items-center">
            <label class="form-label" for="cardNumber" style="font-size: 1.1em; font-weight:600;">Card Number:</label>
            <input class="form-control form-control bg-light fs-8 shadow" type="text" id="cardNumber" name="cardNumber" required>
            <br><br>
            <label class="form-label" for="cvv" style="font-size: 1.1em; font-weight:600;">CVV:</label>
            <input class="form-control form-control bg-light fs-8 shadow" type="text" id="cvv" name="cvv" required>
            <br><br>
            <label class="form-label" for="expirationDate" style="font-size: 1.1em; font-weight:600;">Expiration Date:</label>
            <input class="form-control form-control bg-light fs-8 shadow" type="date" id="expirationDate" name="expirationDate" required><br><br>
        </div> 
    </div>
    <br><br>
    <div class="customer-container form-outline row align-items-center">
        <div class="customer-info">
            <h2 style="text-align: center;">Customer Information</h2>
            <div class = "row">
            <div class = "col">
            <label class="form-label" for="firstName" style="font-size: 1.1em; font-weight:600;">First Name</label><br>
            <input class="form-control form-control bg-light fs-8 shadow" type="text" id="firstName" name="firstName" value="<?php echo $firstNameValue ?? ''; ?>" <?php echo $disabled ?? ''; ?>>
            </div>
            <div class = "col">
            <label class="form-label" for="lastName" style="font-size: 1.1em; font-weight:600;">Last Name</label><br>
            <input class="form-control form-control bg-light fs-8 shadow" type="text" id="lastName" name="lastName"  value="<?php echo $lastNameValue ?? ''; ?>" <?php echo $disabled ?? ''; ?>>
            </div>
            </div>
            <br>
            <label class="form-label" for="phoneNumber" style="font-size: 1.1em; font-weight:600;">Phone Number</label><br>
            <input class="form-control form-control bg-light fs-8 shadow" type="text" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumberValue ?? ''; ?>" <?php echo $disabled ?? ''; ?>>
            <br>
            <label class="form-label" for="address" style="font-size: 1.1em; font-weight:600;">Address:</label><br>
            <input class="form-control form-control bg-light fs-8 shadow" type="address" id="address" name="address"  value="<?php echo $addressValue ?? ''; ?>" <?php echo $disabled ?? ''; ?>>
            <br>
        <div class = "input-group mb-3">
            <input class=" checkout-button btn btn-primary w-100 fs-6" onclick="placeOrder()" type="submit" value="Place Your Order">
        </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

   
    
    <!-- Payment Methods Container -->
    <!-- <div id="paymentMethodsContainer" class="payment-methods-container" style="display: none;"></div> -->
        <!-- Payment methods will be dynamically added here using JavaScript -->

<br>
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
        document.addEventListener('DOMContentLoaded', function () {
        //     localStorage.removeItem('cartItems');
        // localStorage.removeItem('itemCount');
        // localStorage.removeItem('sessionItemCount');
        // localStorage.removeItem('itemCountFromOrder');
        

        // // Update the displayed count
        var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
        
        // Update the displayed count
        var cartItemCount = document.getElementById("cartItemCount");
        cartItemCount.innerText = totalItemCount;
        displayCart(); // Display cart items
    });

 
        function displayCart() {
            var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            var totalPrice = 0;
            var cartItemsContainer = document.querySelector('.cart-items-container');
            cartItemsContainer.innerHTML = ''; 
            cartItems.forEach(function (item) {
                var itemTotalPrice = item.price * item.quantity;
                var itemElement = document.createElement('div');
                itemElement.classList.add('cart-item');

                itemElement.innerHTML = `
                    <div class="food-item">
                    <img src="${item.image}" class = "img-fluid" style = "width:450px; height:300px;" alt="${item.name}">
                    <div class="food-description">
                        <p style="font-size: 1.1em; font-weight:600;">${item.name} &nbsp &nbsp $${item.price} <br> Quantity: <span id="quantity_${item.name}">${item.quantity}</span> &nbsp &nbsp
                            <span class="plus-description" >
                                <button type="button" class="btn btn-primary btn-sm" onclick="decreaseQuantity('${item.name}')">-</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="increaseQuantity('${item.name}')">+</button>
                            </span>
                            <button type="button" class="button-description btn btn-danger btn-sm" onclick="removeItem('${item.name}')">Remove</button>
                        </p>
                    </div>
                 </div>
                `;
                cartItemsContainer.appendChild(itemElement);
                totalPrice += item.price * item.quantity;
            });
            document.getElementById('totalPrice').textContent = '$' + totalPrice.toFixed(2);
        }

        function decreaseQuantity(itemName) {
            var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
            var cartItemCount = document.getElementById("cartItemCount");
            var item = cartItems.find(item => item.name === itemName);
            if (item && item.quantity > 1) {
                item.quantity -= 1;
                totalItemCount -=1;
                cartItemCount.innerText = totalItemCount;
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                localStorage.setItem('itemCount', totalItemCount);
                displayCart(); // Update cart display
            }
        }



        function increaseQuantity(itemName) {
            var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
            var cartItemCount = document.getElementById("cartItemCount");
            var item = cartItems.find(item => item.name === itemName);
            if (item) {
                item.quantity += 1;
                totalItemCount +=1;
                cartItemCount.innerText = totalItemCount;
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                localStorage.setItem('itemCount', totalItemCount);
                displayCart(); // Update cart display
            }
        }

                


        // Function to remove an item from the cart
        function removeItem(itemName) {
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
        // Filter out the item with the specified name
        var item = cartItems.find(item => item.name === itemName);
        var cartItemCount = document.getElementById("cartItemCount");
            if (item) {
                totalItemCount = totalItemCount -item.quantity ;
                // Update the displayed count
                cartItemCount.innerText = totalItemCount;
            }
        cartItems = cartItems.filter(function(item) {
            return item.name !== itemName;
        });

        // Decrement the total item count
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        localStorage.setItem('itemCount', totalItemCount);

        displayCart(); // Update cart display
    }


    function goToOrderPage() {
    window.location.href = "Order.php"; // Replace "order.html" with the URL of your order page
    }


    function placeOrder() {
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    var totalPrice = parseFloat(document.getElementById('totalPrice').textContent.substring(1));
    var userID = "<?php echo $userID; ?>"; // Get the userID from PHP
    var cardNumber = document.getElementById('cardNumber').value;
        var cvv = document.getElementById('cvv').value;
        var expirationDate = document.getElementById('expirationDate').value;

        // Perform validation
        if (cardNumber.trim() === '') {
            alert('Please enter your card number.');
            return;
        }
        if (cvv.trim() === '') {
            alert('Please enter your CVV.');
            return;
        }
        if (expirationDate.trim() === '') {
            alert('Please enter your expiration date.');
            return;
        }
    // console.log('Cart Items:', cartItems);
    // console.log('Total Price:', totalPrice);
    // console.log('Total Price:', userID);
    // var firstName = document.getElementById('firstName').value;
    // var lastName = document.getElementById('lastName').value;
    // var phoneNumber = document.getElementById('phoneNumber').value;
    
    // Check if all required fields are filled
    if (!cartItems || cartItems.length === 0 || !totalPrice|| !userID ) {
        alert('Please fill all required fields and ensure there are items in the cart.');
        return;
    }
    
    // Prepare data to send
    var data = {
        cartItems: cartItems,
        totalPrice: totalPrice,
        userID : userID
        // firstName: firstName,
        // lastName: lastName,
        // phoneNumber: phoneNumber,
        // email: email
    };
    
    // Send data to the PHP script using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'place_order.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // localStorage.removeItem('cartItems');
                    // localStorage.removeItem('itemCount');
                    localStorage.clear();
                    // Redirect to a success page or perform any other action
                    window.location.href = 'OrderConfirmation.php';
                } else {
                    // Error occurred, handle accordingly
                    alert('Failed to place order: ' + response.error);
                }
            } else {
                // Error occurred, handle accordingly
                alert('Failed to place order: ' + xhr.status);
            }
        }
    };
    xhr.send(JSON.stringify(data));
}

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>
