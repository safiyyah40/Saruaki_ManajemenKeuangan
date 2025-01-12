<?php 
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$updateQuery = "UPDATE users SET last_access = NOW() WHERE id = '$userId'";
if (!mysqli_query($conn, $updateQuery)) {
    die("Update failed: " . mysqli_error($conn));
}

$query = "SELECT * FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found!");
}

$fullName = $user['fullName'];
$username = $user['username'];
$gender = $user['gender'];
$email = $user['email'];

if (!isset($user['last_access']) || empty($user['last_access'])) {
    $lastAccess = "Never Accessed";
} else {
    $lastAccess = date('l, d F Y, h:i A', strtotime($user['last_access']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <link rel="icon" href="../images/logo_saruaki.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="profilestyle.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>My Profile</h1>
            <div class="button-back">
                <a href="dashboard.php"><button>Back</button></a>
            </div>
        </div>
        <div class="content">
            <img src="../images/icon_user.svg" alt="User Icon">
            <div class="text">
                <h1><?php echo $username; ?></h1>
                <p>Last Access: <?php echo $lastAccess; ?></p>
            </div>
        </div>
    </div>

    <div class="container-1">
        <div class="header">
            <h1>About Me</h1>
        </div>
        <div class="content-1">
            <div class="left">
                <div class="name">
                    <h5>Name</h5>
                    <p><?php echo $fullName; ?></p>
                </div>
                <div class="username">
                    <h5>Username</h5>
                    <p><?php echo $username; ?></p>
                </div>
            </div>
            <div class="right">
                <div class="Gender">
                    <h5>Gender</h5>
                    <p><?php echo $gender; ?></p>
                </div>
                <div class="email">
                    <h5>Email</h5>
                    <p><?php echo $email; ?></p>
                </div>
                <a href="editprofile.php"><button>Edit</button></a>
            </div>
            
        </div>
    </div>

    <!-- Footer -->
     <div class="bottom" >
       <footer id="page-footer" class="footer-popover ">
                <div class="footer">
                    <center>
                        <h1>SARUAKI FINANCE</h1>
                        <hr class="line">
                        <h4>Email : saruakifinance@gmail.com</h4>
                        <p>&copy; 2025 Saruaki Finance | All Rights Reserved</p>
                    </center>
                </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>