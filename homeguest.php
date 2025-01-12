<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$sql = "SELECT o.category, o.notes, o.profesi, o.date, u.fullName AS username
        FROM opinion o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC
        LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <link rel="icon" href="./images/logo_saruaki.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./pages/homestyle.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar py-4">
            <div class="text-center mb-4">
                <img src="./images/logo_saruaki.svg" alt="Logo" class="logo">
                <h5>SARUAKI FINANCE</h5>
                <hr>
            </div>
            <nav class="nav flex-column px-3">
                <a href="home.php" class="nav-link active">
                    <img src="./images/icon_home.svg" alt="Home Icon" class="nav-icon"> Home
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Pages / <span class="text-warning">Home</span></h6>
            </div>

            <!-- Content -->
            <div class="highlight">
                <h1>Track Your Finances, Achieve Your Goals.</h1>
                <p>Stay in control of your financial journey with SARUAKI FINANCE. Easily record and monitor your daily and monthly income and expenses, keep track of debts and receivables, and gain a clear overview of your financial health. Our app empowers you to make informed decisions and stay on top of your finances, helping you achieve both short-term and long-term financial goals.</p>
                <a href="Login.php">
                    <button class="start-now">
                        <h4>
                            START NOW
                        </h4>
                    </button>
                </a>
            </div>
            <div class="highlight-1">
                <p>See what people think of this app</p>
            </div>
            <div class="card-group">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($row['category']) . '</h5>
                                <p class="card-text">"' . htmlspecialchars($row['notes']) . '"</p>
                                <h6 class="card-subtitle">– ' . htmlspecialchars($row['username']) . ', ' . htmlspecialchars($row['profesi']) . '</h6>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="alert alert-warning no-opinions">
                            <i class="fa fa-exclamation-circle"></i> No opinions found.
                          </div>';
                }
                ?>
            </div>
            <div class="opinions hidden">
            </div>

            <!-- Footer -->
            <footer id="page-footer" class="footer-popover ">
                <div class="footer">
                    <center>
                        <h1>SARUAKI FINANCE</h1>
                        <hr class="line">
                        <h4>Email : saruakifinance@gmail.com</h4>
                        <p>&copy; 2025 Saruaki Finance | All Right Reserved</p>
                    </center>
                </div>
            </footer>
        </div>   
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>