<?php
require 'connection.php';

$message = "";

// Mengecek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password_user = $_POST['password_user'];
    $confirm_password = $_POST['confirm_password'];

    // Memeriksa apakah password dan konfirmasi password sama
    if ($password_user !== $confirm_password) {
        $message = "Password and Confirm Password don't match.";
    } else {
        // Hash password menggunakan bcrypt
        $hashed_password = password_hash($password_user, PASSWORD_DEFAULT);

        // Query untuk mengecek apakah username atau email sudah terdaftar
        $sql_check = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result_check = mysqli_query($conn, $sql_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $message = "This Username or Email already exists.";
        } else {
            // Query untuk memasukkan data user baru ke database
            $sql_insert = "INSERT INTO users (fullName, gender, email, username, password_user) VALUES ('$fullName', '$gender', '$email', '$username', '$hashed_password')";
            if (mysqli_query($conn, $sql_insert)) {
                $message = "Registration Successful!";

                header("Location: Login.php");
                exit();
            } else {
                $message = "An error occurred, please try again later.";
            }
        }
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SARUAKI | Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="registerstyle.css">    
  </head>
  <body>
  <div class="container">
        <div class="left-section">
            <h2 class="fw-bold">Create an Account</h2>
            <p>Already Have an Account? <a href="Login.php">Sign In</a></p>
            <img src="" alt="">
        </div>
        <div class="form-container">
            <div class="card-body">
                <!-- Menampilkan pesan jika ada error atau sukses -->
                <?php if ($message): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="fullName" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="gender" required>
                            <option value="" disabled selected>Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3 password-container">
                        <input type="password" class="form-control" name="password_user" placeholder="Password" required>
                        <span class="show-hide">
                            <img src="images/eye-off.svg" alt="Show Icon" class="show-icon"> 
                        </span>
                    </div>

                    <div class="mb-3 password-container">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                        <span class="show-hide">
                            <img src="images/eye-off.svg" alt="Show Icon" class="show-icon"> 
                        </span>
                    </div>

                    <div class="d-grid">

                        <button type="submit" class="btn btn-custom">SIGN UP</button>
                    </div>
                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>