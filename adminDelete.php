<?php
session_start();
 
// Check if the user is logged in and has the admin role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "admin")) {
    header("location: login.php");
    exit;
}

if(isset($_POST["foodID"]) && !empty($_POST["foodID"])){

require_once "config.php";

$sql = "DELETE FROM menu WHERE foodID = ?";
    
if($stmt = mysqli_prepare($link, $sql)){

mysqli_stmt_bind_param($stmt, "i", $param_foodID);
    
$param_foodID = trim($_POST["foodID"]);
        
if(mysqli_stmt_execute($stmt)){
// if list was deleted successfully. Redirect to admin page;
header("location: admin.php");
exit();
} else{
echo "Oops! Something went wrong. Please try again later.";
}
}
mysqli_stmt_close($stmt);
mysqli_close($link);
} else{
// Check if ISBN exist
if(empty(trim($_GET["foodID"]))){
// If doesn't contain foodID parameter. show error message.
echo "something went wrong, doesn't contain foodID parameter";
exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">
    <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>
    <script defer src="src/admin.js"></script>
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
    <a class="nav-link link-primary" href="#">Log Out</a>
    </div>
</div>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
<div class = "row border rounded-5 p-3 bg-white shadow-lg box-area">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="foodID" value="<?php echo trim($_GET["foodID"]); ?>"/>
        <p class = "text">Are you sure you want to delete this item?</p>
        <input type="submit" value="Yes" class="btn btn-primary">
        <a href="admin.php" class="btn btn-secondary">No</a>
    </form>
</div>
</div>

</body>
</html>