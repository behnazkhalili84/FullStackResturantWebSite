<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>FoodiFoodi's About us</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="assets/Favicon.ico" type="image/x-icon">
    <script src="src/script.js"></script>

</head>

<body>

  <!-- Header -->
 <!-- Header -->
 <header>
    <img src="assets/logo.png" alt="Restaurant Logo" class="logo">
    <nav>
        <ul>
            <li><a href="index.php" style="color:#dd3b30">Home</a></li>
            <li><a href="Order.php">Order</a></li>
            <li><a href="Reservation.php">Reservation</a></li>
            <li><a href="Aboutus.html">About Us</a></li>
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
            <a href="user.php"> Account</a>
            <a href="logout.php">Logout</a>
        </div>
      </div>';
} else {
// If user is not logged in, display the login button
echo '<li><a href="login.php" class="btn-login">Login</a></li>';
}
?>

        </ul>
    </div>
</header>


<hr>
<style>
    hr {
        border: 2px solid #333;
        margin-bottom: 10px;
    }
</style>

	<br><br>
	<!-- Hero Section -->
	<section class="hero">
	<h1 style="text-align:center; color:#dd3b30";>About Us</h1>
	<p>Welcome to <pr style="color:#dd3b30">FOODI FOODI</pr>, where culinary excellence meets timeless tradition. Established over 35 years ago, 
	our restaurant has been a cherished part of the community, weaving a rich tapestry of culinary artistry, warm ambiance, 
	and unparalleled taste.<br><br> From the moment you step through our doors, we embark on a journey to transport you to a different world,
	one where passion for food meets extraordinary craftsmanship. Our team of skilled chefs, each a master in their craft, pour their
	creativity and expertise into every dish. It's not just a meal; it's an experience crafted to elevate your senses. Join us at 
	FOODI FOODI and let the legacy of our three and a half decades of culinary mastery enchant your palate.</p>
	
	<img src="assets/resturantview.jpg" alt="Resturant view" class="center-image "><!-- free photo by https://www.istockphoto.com/photo -->
	
    <i><h2 style="text-align:center">Our Team</h2></i>

<div class="team-staff">	 
<i><h3 style="color:#dd3b30">Shadan Farahbakhsh</h3></i>	
<div class="container">
	<h4>Chef</h4><br>
	    <p style="margin-right:100px;">Embarking on a culinary journey at 18 in a small Iranian restaurant, she immersed herself in the rich tapestry of Persian flavors.
	       With a career spanning prestigious kitchens in Montreal, she perfected the art of crafting delectable dishes and irresistible desserts.
	       A culinary storyteller, she weaves emotions into each plate, inviting you to savor a world of flavors shaped by passion and experience.<br>
	    
		   <a href="mailto:sh.farahbakhsh@gmail.com" style="color:#dd3b30">Contact by email</a>
   	    </p>
  
    <div class="image">
         <img src="assets/IMG_shadan.JPG" alt="staff photo" class="circle" >
     </div>		 
</div>

<i><h3 style="color:#dd3b30; margin-left:400px;">Behnaz Khalili</h3></i>
	<h4 style="margin-left:400px">Sommelier, Win Specialise</h4>
<div class="container" >
    <div class="image">
         <img src="assets/behnaz.jpg" alt="staff photo" class="circle" style="margin-right: 100px;" >
     </div>	
	 <p>
	    Behnaz, a seasoned sommelier at FOODI FOODI, seamlessly intertwines the worlds of wine and customer service with unparalleled expertise. Rooted in years of restaurant industry
		experience and studies in Paris, she is driven by an insatiable thirst for knowledge, regularly exploring the nuances of natural agriculture. Characterized by sensitivity,
		warmth, and acute environmental awareness, Behnaz shapes the FOODI FOODI experience, crafting wine lists and pairings to ensure every moment is a symphony of flavors and 
		attentive service. <br>
		   <a href="mailto:b.kalili@gmail.com" style="color:#dd3b30">Contact by email</a>
   	    </p> 
</div><br><br>
<i><h3 style="color:#dd3b30">Ehsan Ghambari</h3></i>
<div class="container">
	<h4>Resturant Owner</h4><br>
       <p>As the visionary owner of a culinary haven, he artfully orchestrates flavors that enchant patrons upon entry. His deep-rooted passion for gastronomy drives a menu marrying innovation and tradition.
         Beyond the dining space's warm embrace and meticulously plated dishes, each element reflects his commitment to an unforgettable experience. Leading with a familial touch, this restaurant is more 
        than a business—it's a tradition woven into his family's heritage. He envisions a vibrant tapestry where community, artistry, and exceptional cuisine converge, inviting
        guests on a uniquely familial culinary journey.<br>
	    
		   <a href="mailto:eghambari@gmail.com" style="color:#dd3b30">Contact by email</a>
   	    </p>
  
    <div class="image">
         <img src="assets/eh.jpeg" alt="staff photo" class="circle" >
     </div>		 
</div>

<i><h3 style="color:#dd3b30; margin-left:400px;">Yan Deng</h3></i>
	<h4 style="margin-left:400px">Pastry chef</h4>
<div class="container" >
    <div class="image">
         <img src="assets/yan.JPG" alt="staff photo" class="circle" style="margin-right: 100px;" >
     </div>	
	 <p>
	     With a passion that extends beyond the kitchen, Yan crafts each dessert with a blend of creativity, skill, and a touch of whimsy. Trained in the fine art of pastry and dessert making,
         she brings a fresh perspective to classic favorites and innovative creations alike.Yan's desserts are more than just a course; they are an experience. From the velvety smoothness of her signature chocolate 
         creations to the vibrant bursts of flavor in her fruit-based delights, each dish is designed to leave a lasting impression. Her dedication to excellence and innovation ensures that every dessert from
          FoodiFoodi's kitchen is a journey of delightful discovery. <br>
		   <a href="mailto:Yan.deng@gmail.com" style="color:#dd3b30">Contact by email</a>
   	    </p> 
</div><br><br>
	

	</section>
	
	<div class="contact-info">
        <div class="address">
            <p><strong>Contact Us</strong></p>
            <p>21 275 Rue Lakeshore Road,<br>
            Sainte-Anne-de-Bellevue,<br>
            QC H9X 3L9, Canada</p>
            <p>Phone: +1 514-457-5036</p>
        </div>
        <div class="hours-of-operation">
            <p><strong><u>Hours of Operation:</u></strong></p>
            <ul>
                <li><strong>Monday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Tuesday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Wednesday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Thursday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Friday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Saturday</strong> 5:00 AM – 9:30 PM</li>
                <li><strong>Sunday</strong> 5:00 AM – 9:30 PM</li>
            </ul>
        </div>
    </div>
    
	  <!-- Separator Line -->
    <div class="separator-line"></div>

   <!-- Footer -->
        <footer>
            <div id="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2801.1453819145295!2d-73.94430312372181!3d45.40640783729286!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc938500caad3a7%3A0xd807d74a59dcffcf!2sJohn%20Abbott%20College!5e0!3m2!1sen!2smx!4v1706733076708!5m2!1sen!2smx" 
                referrerpolicy="no-referrer-when-downgrade"></iframe>
           
                <img src="assets/Logo.svg" alt="Footer Logo" >
            </div>
            <div class="footer-right">
                
               
                </div>
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