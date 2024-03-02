<?php
session_start();

// Check if the user is logged in and has the user role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "user")) {
    header("location: login.php");
    exit;
}

require_once "config.php";

// Check if success parameter exists in the URL
if(isset($_GET['success'])) {
    $success = $_GET['success'];
    // Output the success message
    
}
// Fetch user details for the current user
$users = [];
$userID = $_SESSION["userID"];
$role = $_SESSION["role"];

$sql = "SELECT email, firstName, lastName, phone, address, address2, city, province, postalCode 
        FROM users
        WHERE userID = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $userID);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    } else {
        echo "Error: Unable to execute SQL statement.";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: Unable to prepare SQL statement.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="icon" href="assets/Favicon.ico" type="image/x-icon">

    <style>
        /* Header Styles */
        .logo-container {
            text-align: center; 
            padding-right: 15px;
            margin: 0;
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

        .user-details table {
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-details th, .user-details td {
            padding: 10px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-details th {
            background-color: rgba(128, 128, 128, 0.8);
            color: white;
        }

        .user-details tbody tr:nth-child(even) {
            background-color: rgba(100, 149, 237, 0.1);
        }

        .user-details tbody tr:hover {
            background-color: rgba(100, 149, 237, 0.3);
        }

        .user-details tbody td {
            color: black;
        }

        .return-link {
            margin-top: 20px;
            text-align: center;
            
        }
        .return-link2 {
            margin-top: 20px;
            text-align: center;
        }
        .return-link2 a {
            text-decoration: none;}
        .return-link a {
            text-decoration: none;
            color: #333;
            padding: 5px 15px;
            background-color: rgba(128, 128, 128, 0.4);
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .return-link a:hover {
            background-color: rgba(128, 128, 128, 0.4);
            color: rgba(184, 80, 66, 0.9);
        }

        .return-link2 a:hover {
            color: rgba(184, 80, 66, 0.9);
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
        <div class="welcome-message"><?php echo htmlspecialchars($_SESSION["first_name"]); ?>, here are your details:</div><br>
        <?php echo '<div class="alert alert-success" style="color:green; text-align:center;">' . htmlspecialchars($success) . '</div>'; ?>
        <div class="user-details">
            <?php if (!empty($users)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Postal Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['firstName']; ?></td>
                                <td><?php echo $user['lastName']; ?></td>
                                <td><?php echo $user['phone']; ?></td>
                                <td><?php echo $user['address']; ?></td>
                                <td><?php echo $user['city']; ?></td>
                                <td><?php echo $user['province']; ?></td>
                                <td><?php echo $user['postalCode']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Error: No user details found.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="return-link">
            <a href="updateUser.php">Update Information</a>
        </div>
    <div class="return-link2">
        <a href="<?php echo ($role === 'user') ? 'user.php' : 'admin.php'; ?>">Return to user page</a>
    </div>
</body>
</html>
