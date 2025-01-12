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

$viewMode = isset($_GET['view_mode']) ? $_GET['view_mode'] : '10';
$dataPerPage = $viewMode === 'all' ? $totalData : 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$startIndex = ($page - 1) * $dataPerPage;
$nomor = $startIndex + 1;

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$searchCondition = '';
if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $searchCondition = "
        WHERE (
            notes LIKE '%$searchEscaped%' 
            OR amount LIKE '%$searchEscaped%' 
            OR date LIKE '%$searchEscaped%' 
            OR activity_transaction LIKE '%$searchEscaped%'
        )
    ";
}

$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM (
        SELECT id, date, notes, amount, 'Income' AS activity_transaction FROM income WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Spending' AS activity_transaction FROM spending WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Debt' AS activity_transaction FROM debt WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Receivable' AS activity_transaction FROM receivable WHERE user_id = '$userId'
    ) AS all_transactions
    $searchCondition
";

$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

$queryLimit = $dataPerPage > 0 ? "LIMIT $startIndex, $dataPerPage" : '';

$query = "
    SELECT * 
    FROM (
        SELECT id, date, notes, amount, 'Income' AS activity_transaction FROM income WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Spending' AS activity_transaction FROM spending WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Debt' AS activity_transaction FROM debt WHERE user_id = '$userId'
        UNION ALL
        SELECT id, date, notes, amount, 'Receivable' AS activity_transaction FROM receivable WHERE user_id = '$userId'
    ) AS all_transactions
    $searchCondition
    ORDER BY date DESC
    $queryLimit
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
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
    <link rel="stylesheet" href="contentstyle.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar py-4">
            <div class="text-center mb-4">
                <img src="../images/Saruaki_2.png" alt="Logo" class="logo">
                <h5>SARUAKI FINANCE</h5>
                <hr>
            </div>
            <nav class="nav flex-column px-3">
                <a href="home.php" class="nav-link">
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
                <a href="activities.php" class="nav-link active">
                    <img src="../images/icon_activities.png" alt="Activities Icon" class="nav-icon"> Activities
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content p-4 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="navbar">
                    <h6>Pages / <span class="text-warning">Activities</span></h6>
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
            <!-- Income Table -->
            <div class="container-table">
                <div class="header">
                    <h2 style="font-weight: bold;">Activities</h2>
                </div>
                <div class="search" style="margin-top: 90px">
                    <form method="GET" action="">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            placeholder="Search Activities...">
                        <button type="submit">
                            <img src="../images/icon_search.svg" alt="icon search">
                        </button>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Notes</th>
                            <th>Amount of Transaction</th>
                            <th>Transaction Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $nomor++; ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                                <td><?php echo 'Rp ' . number_format(floatval($row['amount']), 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['activity_transaction']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <!-- Tombol Previous -->
                        <li class="page-item <?php echo $page === 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo max($page - 1, 1); ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>

                        <!-- Tombol Angka Halaman -->
                        <?php for ($i = 1; $i <= ceil($totalData / $dataPerPage); $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?php echo $page === ceil($totalData / $dataPerPage) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo min($page + 1, ceil($totalData / $dataPerPage)); ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <div class="footernya">
                    Showing
                    <?php echo $viewMode === 'all' ? "1 to $totalData" : ($startIndex + 1) . " to " . min($startIndex + $dataPerPage, $totalData); ?>
                    of <?php echo $totalData; ?>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>