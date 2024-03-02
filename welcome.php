<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 800px; padding: 20px; }
        table { width: 100%; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .edit-btn { margin-right: 5px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["userID"]); ?>!</h2>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["first_name"]); ?>!</h2>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["last_name"]); ?>!</h2>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>!</h2>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["role"]); ?>!</h2>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["phone"]); ?>!</h2>
        <p>
            
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </p>
</body>
</html>
