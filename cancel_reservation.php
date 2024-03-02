<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reservationID']) ) {
        $reservationID = $_SESSION['reservationID']; 
        // Prepare a delete statement
        $sql = "DELETE FROM reservations WHERE reservationID = ?";
        
        if ($stmt = $link->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $reservationID);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                 echo "<script>alert('Your reservation with ID " . $reservationID . " has been successfully canceled.'); window.location.href = 'Reservation.php';</script>";
               

            } else {
                // Error occurred while canceling reservation
              echo "<script>alert('Reservation ID not provided.'); window.location.href = 'Cancel_reservation.php';</script>";
            }
            
            // Close statement
            $stmt->close();
        }
    } else {
        // Reservation ID not provided
        echo "Reservation ID not provided.";
    }
} else {
    // Redirect back to the reservation page if accessed directly
    header("location: Reservation.php");
    exit;
}

