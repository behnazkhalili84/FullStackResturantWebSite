<?php
session_start();
 
// Check if the user is logged in and has the admin role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "admin")) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";
// Define variables and set to empty values
$foodID = $imageURL = $name = $description = $price = $categories = "";
$imageURLErr = $nameErr = $descriptionErr = $priceErr = $categoriesErr = "";

if(isset($_POST["foodID"]) && !empty($_POST["foodID"])){
    $foodID = $_POST["foodID"];

    //Validate imageURL
    if (empty($_POST["imageURL"])){
        $imageURLErr = "imageURL cannot be empty";
    } else {
        $imageURL = test_input($_POST["imageURL"]);
    }

    //Validate name
    if (empty($_POST["name"])){
        $nameErr = "name cannot be empty";
    } else {
        $name = test_input($_POST["name"]);
    }

    //Validate description
    if (empty($_POST["description"])) {
        $descriptionErr = "Description cannot be empty";
    } else if (strlen($_POST["description"]) > 200) {
        // Check if the description exceeds 200 characters
        $descriptionErr = "Description cannot exceed 200 characters";
    } else {
        $description = test_input($_POST["description"]);
    }

    //Validate price
    $price = test_input($_POST["price"]);
    if (!is_numeric($price)) {
    $priceErr = "Price must be a number";
    } else if (!preg_match("/^\d{1,4}(\.\d{1,2})?$/", $price)) {
    $priceErr = "Price must be in decimal format (1,000.00)";
    }

     //Validate categories
     if (empty($_POST["categories"])|| $_POST["categories"] == "invalid"){
        $categoriesErr = "categories cannot be empty";
    } else {
        $categories = test_input($_POST["categories"]);
    }

    // If there is no error
    if(empty($imageURLErr) && empty($nameErr) && empty($descriptionErr) && empty($priceErr) && empty($categoriesErr)){
        $sql = "UPDATE menu SET imageURL=?, name=?, description=?, price=?, categories=? WHERE foodID = ?";
             
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssssi", $param_imageURL, $param_name, $param_description, $param_price, $param_categories, $param_foodID);
                
                $param_imageURL = $imageURL;
                $param_name = $name;
                $param_description = $description;
                $param_price = $price;
                $param_categories = $categories;
                $param_foodID = $foodID;
                
                
                if(mysqli_stmt_execute($stmt)){
                    // Records updated successfully. Redirect to admin page;
                    header("location: admin.php");
                    exit();
                } else{
                    echo "Error updating record: " . mysqli_error($link);
                }
            }
             
      
            mysqli_stmt_close($stmt);
        }
        
        mysqli_close($link);
} else{
    
    if(isset($_GET["foodID"]) && !empty(trim($_GET["foodID"]))){
        $foodID =  trim($_GET["foodID"]);
        
        $sql = "SELECT * FROM menu WHERE foodID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_foodID);
            
            
            $param_foodID = $foodID;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $imageURL= $row["imageURL"];
                    $name = $row["name"];
                    $description = $row["description"];
                    $price = $row["price"];
                    $categories = $row["categories"];
                } else{
                    echo "Unable to update". mysqli_error($link);
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }  else{
        echo "Unable to update". mysqli_error($link);
        exit();
    }
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
        </li>
        <a class="nav-link" href="adminAdd.php"><button class="btn btn-success btn-sm" type="button">Add New Items</button></a>
        </li>
    </ul>
    <a class="nav-link link-primary" href="logout.php">Log Out</a>
    </div>
</div>
</nav>

<!--Main container -->
<div class="container d-flex justify-content-center align-items-center min-vh-100">
<!--Form container -->
<div class = "row border rounded-5 p-3 bg-light shadow-lg box-area">
<!--Left Box-->
<div class = "col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box" style="background:#a0c4ff;">
    <p class = "text-white fs-2">Update Menu Item</p>
    <p class = "text-white text-wrap text-center" tyle = "width: 17 rem;">Please note all inputs are required!</p>
    <div class = "featured-image mb-3">
        <img src="assets/logo.png" class = "img-fluid" style = "width:350px"alt="logo">
    </div>
   
</div>
<!--Right Box -->
    <div class="col-md-6 right-box">
        <div class = "row align-items-center">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <div class="form-outline">
        <input type="hidden" name="foodID" value="<?php echo $foodID; ?>"/>
        </div>
        
        <div class="form-outline">
        <label class="form-label" style="font-size: 1.1em; font-weight:600;">Image URL</label>
        <input class = "form-control form-control-lg bg-light fs-10 shadow" type="text" name="imageURL" value="<?php echo $imageURL; ?>">
        <span><?php echo $imageURLErr;?></span>
        </div>
        
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Name</label>
            <input class = "form-control form-control-lg bg-light fs-10 shadow" type="text" name="name" value="<?php echo $name; ?>">
            <span><?php echo $nameErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Description</label><br>
            <input class = "form-control form-control-lg bg-light fs-10 shadow" type="text" name="description" value="<?php echo $description; ?>">
            <span><?php echo $descriptionErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Price</label><br>
            <input class = "form-control form-control-lg bg-light fs-10 shadow" type="text" name="price" value="<?php echo $price; ?>">
            <span><?php echo $priceErr;?></span>
        </div>
        <div class="form-outline">
            <label class="form-label" style="font-size: 1.1em; font-weight:600;">Categories</label><br>
            <select class = "form-control form-control-lg bg-light fs-10 shadow" name="categories" id="categories">
            <option value="invalid">Please select</option>
            <option value="Appetizer">Appetizer</option>
            <option value="Main">Main</option>
            <option value="Dessert">Dessert</option>
            </select>
            <span class="error"><?php echo $categoriesErr;?></span>
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