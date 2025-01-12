<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../homeguest.php");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $profession = $_POST['profession'];
    $category = $_POST['category'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO opinion (user_id, date, profesi, category, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $userId, $date, $profession, $category, $notes);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
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
    <title>SARUAKI FINANCE | Home</title>
    <link rel="icon" href="../images/Saruaki.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="homestyle.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar py-4">
            <div class="text-center mb-4">
                <a href="home.php">
                    <img src="../images/Saruaki_2.png" alt="Logo" class="logo">
                </a>
                <h5>SARUAKI FINANCE</h5>
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
                <p class="text-warning fw-bold mt-4">History</p>
                <a href="activities.php" class="nav-link">
                    <img src="../images/icon_activities.png" alt="Activities Icon" class="nav-icon"> Activities
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="navbar">
                    <h6>Pages / <span class="text-warning">Home</span></h6>
                </div>
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
                            <a class="dropdown-item" href="../homeguest.php">
                                <img src="../images/icon_logout.svg" alt="Logout Icon" class="dropdown-icon">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="highlight">
                <img src="../images/homepage.jpg" alt="">
                <h1>Track Your Finances, Achieve Your Goals.</h1>
                <p>Stay in control of your financial journey with SARUAKI FINANCE. Easily record and monitor your daily and<br>monthly income and expenses, keep track of debts and receivables, and gain a clear overview of your<br>financial health. Our app empowers you to make informed decisions and stay on top of your finances,<br>helping you achieve both short-term and long-term financial goals.</p>
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
            <div class="opinion">
                <a onclick="togglePopup()" class="icon-button">
                    <img src="../images/icon_opinion.svg" alt="icon opinion">Tell us your opinion!
                </a>
            </div>
            <div class="popup" id="popup-1">
                <div class="overlay" onclick="togglePopup()"></div>
                <div class="content">
                    <div class="header">
                        <h2 style="font-weight: bold;">Tell us your opinion</h2>
                    </div>
                    <div class="form">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="profession" class="form-label">Profession</label>
                                <input type="text" class="form-control" name="profession" placeholder="Your Profession" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category" placeholder="What is your first impression?" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Can you share your experience while using this app?" required></textarea>
                            </div>
                            <div class="button-group">
                                <button type="button" class="button-close" onclick="togglePopup()">Cancel</button>
                                <button type="submit" class="button-save">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
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
    <script>
        function togglePopup() {
            const popup = document.getElementById("popup-1");
            popup.classList.toggle("active");
        }
    </script>
</body>
</html>