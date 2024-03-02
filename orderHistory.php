<?php
session_start();

// Check if the user is logged in and has the user role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "user")) {
    header("location: login.php");
    exit;
}

require_once "config.php";

// Fetch order history for the current user
$order_history = [];
$userID = $_SESSION["userID"];
$role=$_SESSION["role"];

$sql = "SELECT o.orderID, o.totalPrice, o.date, m.name AS productName, m.price AS productPrice, oi.quantity 
        FROM orders o
        JOIN orderitems oi ON o.orderID = oi.orderID
        JOIN menu m ON oi.foodID = m.foodID
        WHERE o.userID = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $userID);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $order_history[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">

    <style>
        /* Header Styles */
        .logo-container {
            text-align: center; 
            padding-right: 15px;
            margin:0;
        }

        .logo {
            width: 150px; 
            cursor: pointer; 
        }

        body {
            font-family: "NeueMontreal-Regular", sans-serif;
            background-color: rgba(255, 255, 240, 0.3);
        }

        /* Custom styles */
        .welcome-message {
            font-size: 24px;
            text-align: center;
            color: black;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .order-history table {
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-history th, .order-history td {
            padding: 10px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .order-history th {
            background-color: rgba(128, 128, 128, 0.8);
            color: white;
        }

        .order-history tbody tr:nth-child(even) {
            background-color: rgba(100, 149, 237, 0.1);
        }

        .order-history tbody tr:hover {
            background-color: rgba(100, 149, 237, 0.3);
        }

        .order-history tbody td {
            color: black;
        }
        .return-link {
            margin-top: 20px;
            text-align: center;
        }

        .return-link a {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            background-color: rgba(128, 128, 128, 0.4);
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .return-link a:hover {
            background-color: rgba(128, 128, 128, 0.4);
            color:rgba(184, 80, 66, 0.9);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <a href="index.php">
                <img src="assets/logo.png" alt="Logo" class="logo">
            </a>
        </div>   
    </header>

    <div class="container">
        <div class="welcome-message"><?php echo htmlspecialchars($_SESSION["first_name"]); ?>, Here is your order history:</div>
        <div class="order-history">
            <?php if (!empty($order_history)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_history as $order): ?>
                            <tr>
                                <td><?php echo $order['orderID']; ?></td>
                                <td><?php echo $order['productName']; ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td><?php echo $order['productPrice'] * $order['quantity']; ?></td>
                                <td><?php echo $order['date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="return-link">
            <a href="<?php echo ($role === 'user') ? 'user.php' : 'admin.php'; ?>">Return to user page</a>
        </div>
</body>
</html>
