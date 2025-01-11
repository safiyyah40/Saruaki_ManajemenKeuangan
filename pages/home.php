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
    <link rel="stylesheet" href="homestyle.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar py-4">
            <div class="text-center mb-4">
                <img src="../images/logo_saruaki.svg" alt="Logo" class="logo">
                <h5>SARUAKI</h5>
                <hr>
            </div>
            <nav class="nav flex-column px-3">
                <a href="home.php" class="nav-link active">
                    <img src="../images/icon_home.svg" alt="Home Icon" class="nav-icon"> Home
                </a>
                <a href="dashboard.php" class="nav-link">
                    <img src="../images/icon_dashboard.svg" alt="Dashboard Icon" class="nav-icon"> Dashboard
                </a>
                <hr>
                <p class="text-warning fw-bold">Transaction</p>
                <a href="income.php" class="nav-link">
                    <img src="../images/icon_income.svg" alt="Income Icon" class="nav-icon"> Income
                </a>
                <a href="spending.php" class="nav-link">
                    <img src="../images/icon_spending.svg" alt="Spending Icon" class="nav-icon"> Spending
                </a>
                <a href="debt.php" class="nav-link">
                    <img src="../images/icon_debt.png" alt="Debt Icon" class="nav-icon"> Debt
                </a>
                <a href="receivable.php" class="nav-link">
                    <img src="../images/icon_receivable.svg" alt="Receivable Icon" class="nav-icon"> Receivable
                </a>
                <p class="text-warning fw-bold mt-4">Report</p>
                <a href="printreport.php" class="nav-link">
                    <img src="../images/icon_print_report.svg" alt="Print Icon" class="nav-icon"> Print Report
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Pages / <span class="text-warning">Home</span></h6>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle custom-btn" type="button" data-bs-toggle="dropdown">
                        <img src="../images/icon_user.svg" alt="User Icon">
                        <span class="username-text"><?php echo $fullName; ?></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="profile.php">
                                <img src="../images/icon_myprofile.svg" alt="My Profile Icon" class="dropdown-icon">
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../Login.php">
                                <img src="../images/icon_logout.svg" alt="Logout Icon" class="dropdown-icon">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="highlight">
                <h1>Track Your Finances, Achieve Your Goals.</h1>
                <p>Stay in control of your financial journey with SARUAKI. Easily record and monitor your daily and monthly income and expenses, keep track of debts and receivables, and gain a clear overview of your financial health. Our app empowers you to make informed decisions and stay on top of your finances, helping you achieve both short-term and long-term financial goals.</p>
            </div>
            <div class="highlight-1">
                <p>See what people think of this app</p>
            </div>
            <div class="card-group">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Simple and User-Friendly</h5>
                        <p class="card-text">"This app is incredibly easy to use! As someone who’s just starting to manage my finances, I found the design very intuitive. Everything is clearly laid out, and I didn’t have to spend much time learning how to use it."</p>
                        <h6 class="card-subtitle">–Ume, First-Time User</h6>
                    </div>
                </div>
            <div class="card">
                 <div class="card-body">
                    <h5 class="card-title">Access Anywhere, Anytime</h5>
                    <p class="card-text">"I love the flexibility of being able to access my financial data no matter where I am. Whether I’m at home on my computer or on the go with my phone, I can always check my finances with ease."</p>
                    <h6 class="card-subtitle">–Kazehaya, Professional</h6>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Saves Time and Energy</h5>
                    <p class="card-text">"I used to spend hours manually calculating my spending, but this app’s automation has made my life so much easier. It saves me time and lets me focus on other important tasks."</p>
                    <h6 class="card-subtitle">Sawako, Collage Student</h6>
                    </div>
                </div>
            </div>

            <div class="opinion">
                <input type="text" placeholder="Tell us your opinion!">
            </div>

            <!-- Footer -->
            <footer id="page-footer" class="footer-popover ">
                <div class="footer">
                    <center>
                        <h1>SARUAKI</h1>
                        <hr class="line">
                        <h4>Email : saruakifinance@gmail.com</h4>
                        <p>&copy; 2025 Saruaki Finance | All Rights Reserved</p>
                    </center>
                </div>
            </footer>
            
        </div>   
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
         

</body>
</html>