<?php
    // Start the session
    session_start();

    // Check if the session variable for item count is set
    if (!isset($_SESSION['itemCount'])) {
        // If not set, initialize it to 0
        $_SESSION['itemCount'] = 0;
    }

    // Include your database connection file
    include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodiFoodi's Order</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
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
<header>
        <img src="assets/logo.png" alt="Restaurant Logo" class="logo">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="Order.php" style="color:#dd3b30">Order</a></li>
                <li><a href="Reservation.php">Reservation</a></li>
                <li><a href="Aboutus.php">About Us</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <ul class="action-list">
                <li class="nav-item cart-item">
                    <a href="Cart.php" class="cart-icon" style="text-decoration:none;">
                        <img src="assets/cart.png" alt="Cart">
                        <span id="cartItemCount" style="color: black; font-size: 20px; text-decoration:none; position: relative; top: -11px; right: 35px;">0</span>
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
    <section>
    <div class="container">
        <h2 class = "text-dark fw-bold text-center py-5">Explore our featured foods</h2>
    </div>
    
    <div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // Include your database connection file
        include('config.php');

        // Check if the connection is successful
        if ($link) {
            // Query to select food items from the database
            $sql = "SELECT imageURL, name, description, price, foodID FROM menu";
            // Execute the query
            $result = $link->query($sql);
            // Check if the query execution was successful
            if ($result !== false && $result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col">
                        <div class="food-item">
                            <img src="<?php echo $row['imageURL']; ?>" class="img-fluid" alt="<?php echo $row['name']; ?>" style="width: 400px; height: 300px;">
                            <div class="food-description">
                                <h4><?php echo $row['name']?></h4>
                                <p><?php echo $row['description']?></p>
                                <p><?php echo "$".$row['price']?></p>
                                <br>
                                <!-- Pass item details and count to JavaScript function -->
                                <button onclick="addToCart(<?php echo $row['foodID']; ?>, '<?php echo $row['name']; ?>', <?php echo $row['price']; ?>, '<?php echo $row['imageURL']; ?>', 1)" class="btn btn-danger">Add to cart</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "0 results";
            }
            // Close the database connection
            $link->close();
        } else {
            // Handle connection error
            echo "Failed to connect to the database.";
        }
        ?>
    </div>
</div>
    </section>
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
            <div id="cartItemsContainer"></div>
            <script>
    // Initialize cart item count when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // localStorage.removeItem('cartItems');
        // localStorage.removeItem('itemCount');
        // localStorage.removeItem('sessionItemCount');

        var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
        
        // Update the displayed count
        var cartItemCount = document.getElementById("cartItemCount");
        cartItemCount.innerText = totalItemCount;
    });


    // Add to cart function
    // Add to cart function
    function addToCart(foodID, itemName, itemPrice, imageUrl,quantity) {
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        // cartItems.push({ foodID: foodID, name: itemName, price: itemPrice, image: imageUrl });
        var existingItem = cartItems.find(item => item.foodID === foodID);
            if (existingItem) {
                // If the item already exists, update its quantity
                existingItem.quantity += quantity;
            } else {
                // If the item does not exist, add it to the cart
                cartItems.push({ foodID: foodID, name: itemName, price: itemPrice, image: imageUrl, quantity: quantity });
            }

        localStorage.setItem('cartItems', JSON.stringify(cartItems));

      

        // Increment the total item count
        var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
        totalItemCount++;
        localStorage.setItem('itemCount', totalItemCount);

        // Update the displayed count
        var cartItemCount = document.getElementById("cartItemCount");
        cartItemCount.innerText = totalItemCount;

        console.log('Item added to cart:', { foodID: foodID, name: itemName, price: itemPrice, image: imageUrl,quantity: quantity });
    }


    
    // Modify the goToCartPage function to store the item count in localStorage
    function goToCartPage() {
        window.location.href = "Cart.php";
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

</body>

</html>