<?php
// Include your database connection file
header('Content-Type: application/json');
include('config.php');

// Get JSON data from the request body
$json_data = file_get_contents('php://input');

// Check if JSON data is not empty
if (!empty($json_data)) {
    // Decode JSON data
    $requestData = json_decode($json_data, true);
    // error_log('$_POST: ' . print_r($_POST, true));

    // Check if the required data is set
    if (isset($requestData['cartItems']) && isset($requestData['totalPrice'])) {
        $cartItems = $requestData['cartItems'];
        $totalPrice = $requestData['totalPrice'];
        $userID = $requestData['userID'];
        // $customerEmail = $requestData['email'];

        // error_log('cartItems: ' . print_r($cartItems, true));
        // error_log('totalPrice: ' . $totalPrice);

        // Insert into orders table
        $sql = "INSERT INTO orders (userID, totalPrice, date) VALUES ($userID, $totalPrice, NOW())";
        if ($link->query($sql) === TRUE) {
            $orderID = $link->insert_id; // Get the ID of the inserted order

            // Insert into orderitems table
            foreach ($cartItems as $item) {
                $foodID = $item['foodID'];
                $quantity = $item['quantity'];
                $sql = "INSERT INTO orderitems (orderID, foodID, quantity) VALUES ($orderID, $foodID, $quantity)";
                $link->query($sql);
            }

            // Close the database connection
            $link->close();
           
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Handle database insertion error
            echo json_encode(['success' => false, 'error' => 'Failed to insert order into database.']);
        }
    } else {
        // Handle missing or null data
        echo json_encode(['success' => false, 'error' => 'Missing or null data received.']);
    }
} else {
    // Handle empty JSON data
    echo json_encode(['success' => false, 'error' => 'Empty JSON data received.']);
}
?>