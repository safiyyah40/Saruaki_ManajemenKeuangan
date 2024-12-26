<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="incomestyle.css">
</head>
<body>
    <div class="container bg-light">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar py-4">
            <div class="text-center mb-4">
                <img src="../images/logo_saruaki.svg" alt="Logo" class="logo">
                <h5>SARUAKI</h5>
                <div class="horizontal-line"></div>
            </div>
            <nav class="nav flex-column">
                <a href="home.php" class="nav-link">
                    <img src="../images/icon_home.svg" alt="Home Icon" class="nav-icon"> Home
                </a>
                <a href="dashboard.php" class="nav-link">
                    <img src="../images/icon_dashboard.svg" alt="Dashboard Icon" class="nav-icon"> Dashboard
                    </a>
                    <div class="horizontal-line"></div>
                    <p class="text-warning fw-bold">Transaction</p>
                    <a href="income.php" class="nav-link active">
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
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Pages /<span class="text-warning">Income</span></h6>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle custom-btn" type="button" data-bs-toggle="dropdown">
                        <img src="../images/icon_user.svg" alt="User Icon"> 
                        <span class="username-text">Username</span>
                        </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#">
                                <img src="../images/icon_myprofile.svg" alt="My Profile Icon" class="dropdown-icon"> 
                                My Profile
                            </a>
                        </li>
                        <div class="horizontal-line" style="margin-top: 5px;"></div>
                        <li>
                            <a class="dropdown-item" href="../Login.php">
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
                    <h2>Income</h2>
                </div>
                <div class="popup" id="popup-1">
                    <div class="overlay"></div>
                    <div class="content">
                        <div class="header">
                            <h2>Add Transaction</h2>
                        </div>
                        <div class="form">
                            <div class="mb-3">
                                <h6>Date</h6>
                                <input type="date" class="form-control" placeholder="Select Date">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Notes</label>
                                <textarea class="form-control" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Amount of Income</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Enter Amount">
                            </div>
                            <div class="button-group">
                                <button type="button" class="button-close" onclick="togglePopup()">Cancel</button>
                                <button type="button" class="button-save">Save</button>
                            </div>
                        </div>
                    </div>

                    <button class="add-button" onclick="togglePopup()">+ Add Transaction</button>
                    <div class="search">
                        <label for="search">Search:</label>
                        <input type="text" id="search" placeholder="Search for transaction..">
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Notes</th>
                                <th>Amount of Income</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="footer">
                        Showing 0 to 0
                    </div>
                </div>

            </div>
        </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePopup() {
        const popup = document.getElementById("popup-1");
        popup.classList.toggle("active");
    }
    </script>
</body>
</html>