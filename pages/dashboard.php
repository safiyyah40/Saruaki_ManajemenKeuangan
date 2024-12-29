<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php");
    exit();
}

// Ambil data pengguna
$userId = $_SESSION['user_id'];
$query = "SELECT fullName FROM users WHERE id = '$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Query error: " . mysqli_error($conn);
    exit();
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found!";
    exit();
}

$fullName = $user['fullName'];

// Mengambil pemasukan hari ini
$queryIncomeToday = "
    SELECT SUM(amount) AS total_income_today
    FROM income
    WHERE user_id = '$userId'
    AND DATE(date) = CURDATE()
    ";
$resultIncomeToday = mysqli_query($conn, $queryIncomeToday);
$totalIncomeToday = 0;
if ($row = mysqli_fetch_assoc($resultIncomeToday)) {
    $totalIncomeToday = $row['total_income_today'];
}

// Mengambil pemasukan bulan ini
$queryIncomeThisMonth = "
    SELECT SUM(amount) AS total_income_this_month
    FROM income
    WHERE user_id = '$userId'
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
    ";
$resultIncomeThisMonth = mysqli_query($conn, $queryIncomeThisMonth);
$totalIncomeThisMonth = 0;
if ($row = mysqli_fetch_assoc($resultIncomeThisMonth)) {
    $totalIncomeThisMonth = $row['total_income_this_month'];
}

// Mengambil seluruh total pemasukan 
$queryTotalIncome = "SELECT SUM(amount) AS total_income
    FROM income WHERE user_id = '$userId'";
$resultTotalIncome = mysqli_query($conn, $queryTotalIncome);
$totalIncome = 0;
if ($row = mysqli_fetch_assoc($resultTotalIncome)) {
    $totalIncome = $row['total_income'];
}

// Mengambil pengeluaran hari ini
$querySpendingToday = "
    SELECT SUM(amount) AS total_spending_today
    FROM spending
    WHERE user_id = '$userId'
    AND DATE(date) = CURDATE()
    ";
$resultSpendingToday = mysqli_query($conn, $querySpendingToday);
$totalSpendingToday = 0;
if ($row = mysqli_fetch_assoc($resultSpendingToday)) {
    $totalSpendingToday = $row['total_spending_today'];
}

// Mengambil pengeluaran bulan ini
$querySpendingThisMonth = "
    SELECT SUM(amount) AS total_spending_this_month
    FROM spending
    WHERE user_id = '$userId'
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
    ";
$resultSpendingThisMonth = mysqli_query($conn, $querySpendingThisMonth);
$totalSpendingThisMonth = 0;
if ($row = mysqli_fetch_assoc($resultSpendingThisMonth)) {
    $totalSpendingThisMonth = $row['total_spending_this_month'];
}

// Mengambil seluruh total pengeluaran 
$queryTotalSpending = "
    SELECT SUM(amount) AS total_spending
    FROM spending
    WHERE user_id = '$userId'
    ";
$resultTotalSpending = mysqli_query($conn, $queryTotalSpending);
$totalSpending = 0;
if ($row = mysqli_fetch_assoc($resultTotalSpending)) {
    $totalSpending = $row['total_spending'];
}

// Mengambil debt bulan ini
$queryDebtThisMonth = "
    SELECT SUM(amount) AS total_debt_this_month
    FROM debt
    WHERE user_id = '$userId'
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
    ";
$resultDebtThisMonth = mysqli_query($conn, $queryDebtThisMonth);
$totalDebtThisMonth = 0;
if ($row = mysqli_fetch_assoc($resultDebtThisMonth)) {
    $totalDebtThisMonth = $row['total_debt_this_month'];
}

// Mengambil receivable bulan ini
$queryReceivableThisMonth = "
    SELECT SUM(amount) AS total_receivable_this_month
    FROM receivable
    WHERE user_id = '$userId'
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
    ";
$resultReceivableThisMonth = mysqli_query($conn, $queryReceivableThisMonth);
$totalReceivableThisMonth = 0;
if ($row = mysqli_fetch_assoc($resultReceivableThisMonth)) {
    $totalReceivableThisMonth = $row['total_receivable_this_month'];
}

// Menghitung jumlah transaksi hari ini
$queryTodayTransaction = "
    SELECT 
        (SELECT COUNT(*) FROM income WHERE user_id = '$userId' AND DATE(date) = CURDATE())
        +
        (SELECT COUNT(*) FROM spending WHERE user_id = '$userId' AND DATE(date) = CURDATE())
        +
        (SELECT COUNT(*) FROM debt WHERE user_id = '$userId' AND DATE(date) = CURDATE())
        +
        (SELECT COUNT(*) FROM receivable WHERE user_id = '$userId' AND DATE(date) = CURDATE())
        AS total_transaction_today;
";
$resultTodayTransaction = mysqli_query($conn, $queryTodayTransaction);
$totalTransactionToday = 0;
if ($row = mysqli_fetch_assoc($resultTodayTransaction)) {
    $totalTransactionToday = $row['total_transaction_today'];
}

// Menghitung jumlah transaksi bulan ini
$queryThisMonthTransaction = "
    SELECT 
        (SELECT COUNT(*) FROM income WHERE user_id = '$userId' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()))
        +
        (SELECT COUNT(*) FROM spending WHERE user_id = '$userId' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()))
        +
        (SELECT COUNT(*) FROM debt WHERE user_id = '$userId' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()))
        +
        (SELECT COUNT(*) FROM receivable WHERE user_id = '$userId' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()))
        AS total_transaction_this_month;
";
$resultThisMonthTransaction = mysqli_query($conn, $queryThisMonthTransaction);
$totalTransactionThisMonth = 0;
if ($row = mysqli_fetch_assoc($resultThisMonthTransaction)) {
    $totalTransactionThisMonth = $row['total_transaction_this_month'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboardstylee.css">
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
                <a href="home.php" class="nav-link">
                    <img src="../images/icon_home.svg" alt="Home Icon" class="nav-icon"> Home
                </a>
                <a href="dashboard.php" class="nav-link active">
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
                <h6>Pages / <span class="text-warning">Dashboard</span></h6>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle custom-btn" type="button" data-bs-toggle="dropdown">
                        <img src="../images/icon_user.svg" alt="User Icon">
                        <span class="username-text"><?php echo $fullName; ?></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="myprofile.php">
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

            <!-- Cards Section -->
            <div class="row g-5">
                <!-- Card 1 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Today's Income</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalIncomeToday, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Today's Spending</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalSpendingToday, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Today's Transaction</h6>
                            <h4 class="fw-bold"><?php echo number_format($totalTransactionToday); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">This Month's Income</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalIncomeThisMonth, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Spending this Month</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalSpendingThisMonth, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="col-md-4">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Transaction this Month</h6>
                            <h4 class="fw-bold"><?php echo number_format($totalTransactionThisMonth); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="col-md-6">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Debt this Month</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalDebtThisMonth, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="col-md-6">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Receivable this Month</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalReceivableThisMonth, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Total Income and Spending -->
                <div class="col-md-6">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Total Income</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalIncome, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card custom-card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Total Spending</h6>
                            <h4 class="fw-bold">Rp <?php echo number_format($totalSpending, 0, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>