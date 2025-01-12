<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../homeguest.php");
    exit();
}

$userId = $_SESSION['user_id'];
$query = "SELECT fullName, username, gender, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found!");
}

$user = $result->fetch_assoc();

// Jika form disubmit, update data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];

    $updateQuery = "UPDATE users SET fullName = ?, username = ?, gender = ?, email = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $fullName, $username, $gender, $email, $userId);

    if ($updateStmt->execute()) {
        // Refresh data pengguna setelah update
        $user['fullName'] = $fullName;
        $user['username'] = $username;
        $user['gender'] = $gender;
        $user['email'] = $email;

        $successMessage = "Profile updated successfully!";
        header("Location: profile.php");
    } else {
        $errorMessage = "Failed to update profile. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE | Edit Profile</title>
    <link rel="icon" href="../images/logo_saruaki.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="editstyle.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Profile</h1>
            <div class="button-back">
                <a href="profile.php"><button>Back</button></a>
            </div>
        </div>
        <div class="content">
            <img src="../images/icon_user.svg" alt="User Icon">
            <div class="text">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p>Last Access: <?php echo date('l, d F Y, h:i A'); ?></p>
            </div>
        </div>
    </div>

    <div class="container-1">
        <div class="header">
            <h1>About Me</h1>
        </div>
        <div class="content-1">
            <form method="POST" action="">
                <div class="left">
                    <div class="name">
                        <h5>Name</h5>
                        <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" required>
                    </div>
                    <div class="username">
                        <h5>Username</h5>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                </div>
                <div class="right">
                    <div class="gender">
                        <h5>Gender</h5>
                        <input type="text" name="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                    </div>
                    <div class="email">
                        <h5>Email</h5>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <a href="profile.php"><button type="submit">Save</button></a>
                </div>
            </form>
        </div>
    </div>
    <!-- Footer -->
    <div class="bottom">
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