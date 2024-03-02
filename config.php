<?php
$host = "localhost";
$dbname = "WebWizDB"; 
$port = "3306";

// Create connection
$link = mysqli_connect("localhost","root","","webwizdb") ;


// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

?>