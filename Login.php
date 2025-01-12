<?php
require 'connection.php';

$message = "";

// Mengecek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data input dari form
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password_user = isset($_POST['password_user']) ? $_POST['password_user'] : null;

    // Validasi apakah input sudah diisi
    if ($username && $password_user) {
        // Query untuk mencari user berdasarkan nama pengguna
        $query = "SELECT * FROM users WHERE username = '$username'";
        $hasil = mysqli_query($conn, $query);

        // Periksa apakah data user ditemukan
        if ($hasil && mysqli_num_rows($hasil) > 0) {
            $pengguna = mysqli_fetch_assoc($hasil);

            // Verifikasi kata sandi menggunakan password_verify
            if (password_verify($password_user, $pengguna['password_user'])) {
                // Mulai sesi untuk menyimpan data login
                session_start();
                $_SESSION['user_id'] = $pengguna['id'];
                $_SESSION['username'] = $pengguna['username'];

                // Redirect ke halaman main menu
                header("Location: pages/dashboard.php");
                
                exit();
            } else {
                $message = "Username or Password Incorrect.";
            }
        } else {
            $message = "Username is not found.";
        }
    } else {
        $message = "Please fill in Username and Password.";
    }
}
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SARUAKI FINANCE| Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="loginstyle.css">
  </head>
  <body>
  <div class="container">
        <div class="card mx-auto">
        <div class="monkey-container">
        <img src="images/Saruaki_3.png" alt="Monkey Icon" class="monkey-icon">
    </div>
            <h3 class="fw-bold mt-4 mb-3">Sign In to SARUAKI</h3>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-danger" role="alert" style="text-align: left;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?> 
                
                <!-- Formulir Login -->
                <form action="" method="POST">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3 password-container">
                        <input type="password" class="form-control" name="password_user" placeholder="Password" required>
                        <span class="show-hide">
                            <img src="images/eye-off.svg" alt="Hide Icon" class="show-icon"> 
                        </span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-custom">SIGN IN</button>
                    </div>
                </form>
            </div>
            <div class="links mt-3">
                <p>Don't Have an Account? <a href="Register.php">Sign Up</a></p>
            </div>
        </div>
    </div>
    <script>
        const showHideIcons = document.querySelectorAll(".show-hide");
        showHideIcons.forEach((icon) => {
            const input = icon.previousElementSibling;
            const img = icon.querySelector("img");
            icon.addEventListener("click", function () {
                if (input.type === "password") {
                    input.type = "text"; 
                    img.src = "images/eye.svg"; 
                } else {
                    input.type = "password"; 
                    img.src = "images/eye-off.svg";
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
</body>
</html>