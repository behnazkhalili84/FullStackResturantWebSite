<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config.php";

// Fetch user details for the current user
$userID = isset($_SESSION["userID"]) ? $_SESSION["userID"] : null;
$role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>FoodiFoodi's Home</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">

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
<p id="myElementId"></p>
    <hr>
    <style>
        hr {
            border: 2px solid #333;
            margin-bottom: 5px;
        }
    </style>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-right">
            <div class="video-container">
                <video src="assets/video.mp4" autoplay loop muted></video>
                <a href="Order.php" class="btn-order-now-overlay">Order Now</a>
            </div>
        </div>
        <hr>
  
        <div class="hero-left">
            <div class="hero-content">
                <h2 class="hero-heading">Indulge in Culinary Delights! Taste the Magic...</h2>
                <h4 class="hero-subheading">Embrace the Joy of  <i style="color:#dd3b30">FOODI FOODI!</i></h4>
                <a href="Reservation.php" target="_blank" class="btn-reservation">Reservation</a>    
                <a href="menu.html" target="_blank" class="btn-order-now">Menu</a>     
                <div class="additional-content">
                <h3>DISCOVER MORE</h3>
                <p>Sip and savor our expertly crafted cocktails. Elevate your experience and indulge in the art of mixology."</p>
                </div>
                <div class="image-container">
                    <img src="assets/mixology.jpg" alt="Random Cocktail Image">
                </div>
                <div id="random-cocktail"></div>
                <div class="additional-content">
                    <h3>Even More</h3>
                    <p>Welcome to Foodi Foodi, where every dish tells a story, and every bite is an adventure. Nestled in the heart of the city, our restaurant offers a culinary experience like no other. With a menu curated by our talented chefs, each dish is a masterpiece crafted with passion and precision.</p>
                    <p>Step into our cozy dining space and embark on a journey of flavors that will tantalize your taste buds and leave you craving for more. From savory appetizers to decadent desserts, we have something to satisfy every craving and palate.</p>
                    <p>At Foodi Foodi, we believe in using only the finest and freshest ingredients to create dishes that are not only delicious but also nourishing for the body and soul. Our commitment to quality and excellence shines through in every dish we serve, ensuring a dining experience that is truly unforgettable.</p>
                    <p>Come and join us at Foodi Foodi and let us take you on a gastronomic adventure filled with love, laughter, and of course, incredible food. Whether you're dining with family, friends, or that special someone, we promise to make every moment with us a culinary delight.</p>
                </div>
            </div>
        </div>
    </section>

 

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

    <script src="src/login.js"></script>
    <script>
        // Function to fetch random cocktail and update content
        function fetchRandomCocktail() {
            fetch('https://www.thecocktaildb.com/api/json/v1/1/random.php')
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    // Extract cocktail details from the response
                    const cocktail = data.drinks[0];
                    const cocktailName = cocktail.strDrink;
                    const cocktailIngredients = [];
                    for (let i = 1; i <= 15; i++) {
                        const ingredient = cocktail[`strIngredient${i}`];
                        const measure = cocktail[`strMeasure${i}`];
                        if (ingredient && measure) {
                            cocktailIngredients.push(`${measure} ${ingredient}`);
                        }
                    }
                    const cocktailInstructions = cocktail.strInstructions;

                    // Construct HTML content for the cocktail
                    const cocktailHTML = `
                        <h3>Random Cocktail:</h3>
                        <p><strong>Name:</strong> ${cocktailName}</p>
                        <p><strong>Ingredients:</strong> ${cocktailIngredients.join(', ')}</p>
                        <p><strong>Instructions:</strong> ${cocktailInstructions}</p>
                    `;

                    // Update the content of the random-cocktail div
                    document.getElementById('random-cocktail').innerHTML = cocktailHTML;
                })
                .catch(error => {
                    console.error('Error fetching random cocktail:', error);
                    document.getElementById('random-cocktail').innerHTML = '<p>Error fetching random cocktail. Please try again later.</p>';
                });
        }

        // Automatically fetch random cocktail when the page loads
        fetchRandomCocktail();


        document.addEventListener('DOMContentLoaded', function() {
        // localStorage.removeItem('cartItems');
        // localStorage.removeItem('itemCount');
        // localStorage.removeItem('sessionItemCount');

        var totalItemCount = parseInt(localStorage.getItem('itemCount')) || 0;
        
        // Update the displayed count
        var cartItemCount = document.getElementById("cartItemCount");
        cartItemCount.innerText = totalItemCount;
    });


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
