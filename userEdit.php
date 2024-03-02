<?php
session_start();
 
// Check if the user is logged in and has the admin role, if not redirect to login page
if (!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "admin")) {
    header("location: login.php");
    exit;
}

require_once "config.php";
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
        <a class="nav-link" href="admin.php">Manage Menu</a>
        </li>
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="userEdit.php">Manage Users</a>
        </li>
        </li>
        <a class="nav-link" href="userAdd.php"><button class="btn btn-success btn-sm" type="button">Add New Users</button></a>
        </li>
    </ul>
    <a class="nav-link link-primary" href="logout.php">Log Out</a>
    </div>
</div>
</nav>

    <div class="container">
    <table id="menu" class="table table-hover caption-top" style="width:100%">
    <caption>List of users</caption>
        <thead>
            <tr class="table-active table-secondary">
                <th>UserID</th>
                <th>Email</th>
                <th>First</th>
                <th>Last</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Address2</th>
                <th>City</th>
                <th>Province</th>
                <th>PostalCode</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sqlMenu = "SELECT * FROM users";
        if($resultMenu = mysqli_query($link, $sqlMenu)){
            if(mysqli_num_rows($resultMenu) > 0){
                while($row = mysqli_fetch_array($resultMenu)){
                    ?>
                    <tr>
                        <td><?php echo $row['userID']?></td>
                        <td><?php echo $row['email']?></td>
                        <td><?php echo $row['firstName']?></td>
                        <td><?php echo $row['lastName']?></td>
                        <td><?php echo $row['phone']?></td>
                        <td><?php echo $row['address']?></td>
                        <td><?php echo $row['address2']?></td>
                        <td><?php echo $row['city']?></td>
                        <td><?php echo $row['province']?></td>
                        <td><?php echo $row['postalCode']?></td>
                        <td><?php echo $row['role']?></td>
                        <td>
                        <a class="btn btn-info btn-sm" href="userUpdate.php?userID=<?php echo $row['userID']; ?>" role="button"><span>Update</span></a>
                        <a class="btn btn-danger btn-sm" href="userDelete.php?userID=<?php echo $row['userID']; ?>" role="button"><span>Delete</span></a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="5">No menu records were found.</td></tr>';
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        ?>
        </tbody>
    </table>
    </div>
</body>
</html>