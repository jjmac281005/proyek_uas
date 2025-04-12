<?php
$host = "localhost";
$user = "root";  // Ganti sesuai database kamu
$pass = "";      // Ganti jika ada password
$dbname = "cafe_reservation";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>
