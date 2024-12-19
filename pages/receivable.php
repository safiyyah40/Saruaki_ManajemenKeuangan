<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SARUAKI FINANCE</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dashboardstyle.css">
</head>
<body>
    <div class="container bg-light">
        <div class="row vh-100">
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
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Pages / <span class="text-warning">Receivable</span></h6>
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

                <!-- Cards -->
                <div class="row g-5">
                    <!-- Card 1 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Today's Income</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Today's Spending</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Today's Transaction</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Income this Month</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Spending this Month</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 7 -->
                    <div class="col-md-4">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Transaction this Month</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 9 -->
                    <div class="col-md-6">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Debt this Month</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card 10 -->
                    <div class="col-md-6">
                        <div class="card custom-card shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Receivable this Month</h6>
                                <h4 class="fw-bold">Rp.0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
