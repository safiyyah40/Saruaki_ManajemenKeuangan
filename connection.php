<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "manajemen_keuangan"; 

$conn = mysqli_connect($servername, $username, $password, $database);

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>