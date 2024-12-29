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

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $deleteQuery = "DELETE FROM receivable WHERE id = '$id' AND user_id = '$userId'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: receivable.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $debitur = mysqli_real_escape_string($conn, $_POST['debitur']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $query = "UPDATE receivable SET date = '$date', debitur = '$debitur', notes = '$notes', amount = '$amount' WHERE id = '$id' AND user_id = '$userId'";
    } else {
        $query = "INSERT INTO receivable (user_id, date, debitur, notes, amount) VALUES ('$userId', '$date', '$debitur', '$notes', '$amount')";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: receivable.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}

$viewMode = isset($_GET['view_mode']) ? $_GET['view_mode'] : '10';
$dataPerPage = $viewMode === 'all' ? $totalData : 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$startIndex = ($page - 1) * $dataPerPage;
$nomor = $startIndex + 1;

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$searchCondition = '';
if (!empty($search)) {
    $searchCondition = " AND (receivable.notes LIKE '%$search%' OR receivable.amount LIKE '%$search%' OR receivable.date LIKE '%$search%' OR receivable.debitur LIKE '%$search%')";
}

$totalQuery = "SELECT COUNT(*) AS total FROM receivable WHERE user_id = '$userId' $searchCondition";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalData = $totalRow['total'];

$queryLimit = $viewMode === 'all' ? "" : "LIMIT " . max($startIndex, 0) . ", $dataPerPage";
$query = "
    SELECT
        receivable.id,
        receivable.date, 
        receivable.debitur, 
        receivable.notes, 
        receivable.amount
    FROM 
        receivable 
    WHERE 
        user_id = '$userId'
        $searchCondition
    ORDER BY 
        receivable.date DESC
    $queryLimit
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

if (isset($_GET['get_transaction'])) {
    $id = mysqli_real_escape_string($conn, $_GET['get_transaction']);
    $query = "SELECT * FROM receivable WHERE id = '$id' AND user_id = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Transaction not found']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="contentstyle.css">
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
                <a href="receivable.php" class="nav-link active">
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
                <h6>Pages / <span class="text-warning">Receivable</span></h6>
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

            <!-- Receivable Table -->
            <div class="container-table">
                <div class="header">
                    <h2 style="font-weight: bold;">Receivable</h2>
                </div>
                <div class="popup" id="popup-1">
                    <div class="overlay"></div>
                    <div class="content">
                        <div class="header">
                            <h2 style="font-weight: bold;">Receivable</h2>
                        </div>
                        <div class="form">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="debitur" class="form-label">debitur</label>
                                    <input type="text" class="form-control" name="debitur" required>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="1" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount of Receivable</label>
                                    <input type="number" class="form-control" name="amount" required>
                                </div>
                                <div class="button-group">
                                    <button type="button" class="button-close" onclick="togglePopup()">Cancel</button>
                                    <button type="submit" class="button-save">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <button class="add-button" onclick="togglePopup()">+ Add Transaction</button>
                    <div class="search">
                        <form method="GET" action="">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                placeholder="Search transactions...">
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
                                <th>Debitur</th>
                                <th>Notes</th>
                                <th>Amount of Receivable</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $nomor++; ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['debitur']); ?></td>
                                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                                    <td><?php echo 'Rp ' . number_format(floatval($row['amount']), 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="receivable.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this transaction?');">
                                            <img src="../images/icon_delete.svg" alt="delete">
                                        </a>
                                        <a href="#" onclick="event.preventDefault(); toggleEditPopup('<?php echo $row['id']; ?>');">
                                            <img src="../images/icon_edit.svg" alt="edit">
                                        </a>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <li class="page-item <?php echo $page === 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo max($page - 1, 1); ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                            </li>

                            <?php for ($i = 1; $i <= ceil($totalData / $dataPerPage); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page === ceil($totalData / $dataPerPage) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo min($page + 1, ceil($totalData / $dataPerPage)); ?>&search=<?php echo urlencode($search); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="footer">
                        Showing
                        <?php echo $viewMode === 'all' ? "1 to $totalData" : ($startIndex + 1) . " to " . min($startIndex + $dataPerPage, $totalData); ?>
                        of <?php echo $totalData; ?>
                    </div>
                </div>

                <div class="popup" id="popup-edit">
                    <div class="overlay"></div>
                    <div class="content">
                        <div class="header">
                            <h2 style="font-weight: bold;">Edit Transaction</h2>
                        </div>
                        <div class="form">
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="edit-date" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-debitur" class="form-label">Debitur</label>
                                    <input type="text" class="form-control" id="edit-debitur" name="debitur" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="edit-notes" name="notes" rows="1" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-amount" class="form-label">Amount of Receivable</label>
                                    <input type="number" class="form-control" id="edit-amount" name="amount" required>
                                </div>
                                <div class="button-group">
                                    <button type="button" class="button-close" onclick="closeEditPopup()">Cancel</button>
                                    <button type="submit" class="button-save">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function togglePopup() {
                    const popup = document.getElementById("popup-1");
                    popup.classList.toggle("active");
                }

                function toggleEditPopup(id) {
                    const popup = document.getElementById("popup-edit");
                    popup.classList.toggle("active");
                    if (popup.classList.contains("active")) {
                        // Ambil data transaksi
                        fetch(`receivable.php?get_transaction=${id}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('edit-id').value = id;
                                document.getElementById('edit-date').value = data.date;
                                document.getElementById('edit-debitur').value = data.debitur;
                                document.getElementById('edit-notes').value = data.notes;
                                document.getElementById('edit-amount').value = data.amount;
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }

                function closeEditPopup() {
                    const popup = document.getElementById("popup-edit");
                    popup.classList.remove("active");
                }
            </script>
</body>
</html>