<?php 
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$query = "SELECT fullName FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found!");
}

$fullName = $user['fullName'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="profilestyle.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>My Profile</h1>
            <button><a href="home.php">Back</a></button>
        </div>
        <div class="content">
            <img src="../images/icon_user.svg" alt="User Icon">
            <div class="text">
                <h1>USERNAME</h1>
                <p>Last Acces Sunday, 29 December 2024, 7:00 PM</p>
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
                    <input type="text" placeholder="Fullname">
                </div>
                <div class="username">
                    <h5>Username</h5>
                    <input type="text" placeholder="Username">
                </div>
            </div>
            <div class="right">
                <div class="Gender">
                    <h5>Gender</h5>
                    <input type="text" placeholder="Male">
                </div>
                <div class="email">
                    <h5>Email</h5>
                    <input type="text" placeholder="dummy@gmail.com">
                </div>
                <button><a href="profile.php">Save</a></button>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>